<?php

namespace app\controllers;

use Flight;
use app\models\ContratModel;
use app\models\CandidatureModel;
use app\models\ReferenceModel;
use app\models\AnnonceModel;

class ContratController {
    
    private $contratModel;
    private $candidatureModel;
    private $referenceModel;
    private $annonceModel;

    public function __construct() {
        $this->contratModel = new ContratModel(Flight::db());
        $this->candidatureModel = new CandidatureModel(Flight::db());
        $this->referenceModel = new ReferenceModel(Flight::db());
        $this->annonceModel = new AnnonceModel(Flight::db());
    }

    /**
     * Liste tous les contrats
     */
    public function index() {
        $contrats = $this->contratModel->getAllContrats();
        
        Flight::render('rh/contrats/index', [
            'contrats' => $contrats,
            'page_title' => 'Génération des Contrats'
        ]);
    }

    /**
     * Formulaire de génération de contrat pour un candidat
     */
    public function createForm($candidature_id) {
        $candidature = $this->candidatureModel->getCandidatureById($candidature_id);
        
        if (!$candidature) {
            Flight::redirect('/rh/contrats?error=candidature_not_found');
            return;
        }
        
        // Vérifier si accepté
        if ($candidature['decision_finale'] !== 'accepte') {
            Flight::redirect('/rh/contrats?error=candidat_not_accepted');
            return;
        }
        
        // Vérifier si contrat déjà existant
        if ($this->contratModel->contratExistsPourCandidature($candidature_id)) {
            Flight::redirect('/rh/contrats?error=contrat_exists');
            return;
        }
        
        $typeContrats = $this->referenceModel->getAllTypeContrats();
        
        Flight::render('rh/contrats/create', [
            'candidature' => $candidature,
            'typeContrats' => $typeContrats,
            'page_title' => 'Générer un Contrat'
        ]);
    }

    /**
     * Génère un contrat pour un candidat
     */
    public function create($candidature_id) {
        try {
            $candidature = $this->candidatureModel->getCandidatureById($candidature_id);
            
            if (!$candidature) {
                Flight::redirect('/rh/contrats?error=candidature_not_found');
                return;
            }
            
            // 1. Créer l'employé
            $employe_id = $this->contratModel->createEmployeFromCandidature($candidature_id);
            
            if (!$employe_id) {
                Flight::redirect('/rh/contrats?error=employe_creation_failed');
                return;
            }
            
            // 2. Récupérer l'annonce pour avoir le poste
            $annonce = $this->annonceModel->getAnnonceById($candidature['id_annonce']);
            
            // 3. Créer le contrat
            $data = [
                'date_debut' => Flight::request()->data->date_debut,
                'date_fin' => Flight::request()->data->date_fin ?? null,
                'duree' => Flight::request()->data->duree ?? null,
                'salaire' => Flight::request()->data->salaire,
                'id_poste' => $annonce['id_poste'],
                'id_employe' => $employe_id,
                'id_statut_contrat' => 1, // Actif
                'id_type_contrat' => Flight::request()->data->id_type_contrat
            ];
            
            $contrat_id = $this->contratModel->createContrat($data);
            
            if ($contrat_id) {
                Flight::redirect('/rh/contrats?success=contrat_created');
            } else {
                Flight::redirect('/rh/contrats?error=contrat_creation_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur création contrat: " . $e->getMessage());
            Flight::redirect('/rh/contrats?error=exception');
        }
    }

    /**
     * Affiche un contrat
     */
    public function view($id) {
        $contrat = $this->contratModel->getContratById($id);
        
        if (!$contrat) {
            Flight::redirect('/rh/contrats?error=not_found');
            return;
        }
        
        Flight::render('rh/contrats/view', [
            'contrat' => $contrat,
            'page_title' => 'Contrat de ' . $contrat['employe_prenom'] . ' ' . $contrat['employe_nom']
        ]);
    }

    /**
     * Télécharge un contrat en PDF (placeholder pour l'instant)
     */
    public function downloadPDF($id) {
        $contrat = $this->contratModel->getContratById($id);
        
        if (!$contrat) {
            Flight::redirect('/rh/contrats?error=not_found');
            return;
        }
        
        // TODO: Implémenter la génération PDF
        // Pour l'instant, rediriger vers la vue
        Flight::redirect('/rh/contrat/' . $id . '?info=pdf_not_yet_implemented');
    }

    /**
     * Liste des employés
     */
    public function employes() {
        $employes = $this->contratModel->getAllEmployes();
        
        Flight::render('rh/employes/index', [
            'employes' => $employes,
            'page_title' => 'Liste des Employés'
        ]);
    }

    /**
     * Détails d'un employé
     */
    public function viewEmploye($id) {
        $db = Flight::db();
        
        // Récupérer l'employé
        $sql = "SELECT * FROM Employe WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $employe = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$employe) {
            Flight::redirect('/rh/employes?error=not_found');
            return;
        }
        
        // Récupérer le contrat
        $contrat = $this->contratModel->getContratByEmployeId($id);
        
        Flight::render('rh/employes/view', [
            'employe' => $employe,
            'contrat' => $contrat,
            'page_title' => 'Employé: ' . $employe['prenom'] . ' ' . $employe['nom']
        ]);
    }
}