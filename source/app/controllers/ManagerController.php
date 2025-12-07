<?php

namespace app\controllers;

use Flight;
use app\models\AnnonceModel;
use app\models\PosteModel;
use app\models\ReferenceModel;
use app\models\CandidatureModel;
use app\models\EntretienModel;
use app\models\DocumentCandidatureModel;

class ManagerController {
    
    private $annonceModel;
    private $posteModel;
    private $referenceModel;
    private $candidatureModel;
    private $entretienModel;
    private $documentModel;

    public function __construct() {
        // ✅ PAS de vérification ici (déjà faite dans routes.php)
        $this->annonceModel = new AnnonceModel(Flight::db());
        $this->posteModel = new PosteModel(Flight::db());
        $this->referenceModel = new ReferenceModel(Flight::db());
        $this->candidatureModel = new CandidatureModel(Flight::db());
        $this->entretienModel = new EntretienModel(Flight::db());
        $this->documentModel = new DocumentCandidatureModel(Flight::db());
    }

    public function dashboard() {
        $annonces = $this->annonceModel->getAllAnnonces();
        Flight::render('manager/dashboard', [
            'annonces' => $annonces,
            'page_title' => 'Tableau de bord Manager'
        ]);
    }

    public function createAnnonceForm() {
        $postes = $this->posteModel->getAllPostes();
        $typeContrats = $this->referenceModel->getAllTypeContrats();
        $categories = $this->referenceModel->getAllCategories();
        
        Flight::render('manager/create_annonce', [
            'postes' => $postes,
            'typeContrats' => $typeContrats,
            'categories' => $categories,
            'page_title' => 'Créer une annonce'
        ]);
    }

    public function createAnnonce() {
        try {
            // Récupère la connexion DB
            $db = Flight::db();

            // 1) Essayer d'utiliser directement l'id_employe dans la session (si présent)
            $manager_employe_id = $_SESSION['user']['id_employe'] ?? null;

            // 2) Si pas présent, essayer de retrouver l'employe par email (fallback)
            if (empty($manager_employe_id) && !empty($_SESSION['user']['email'])) {
                $stmt = $db->prepare("SELECT id FROM Employe WHERE email = ? LIMIT 1");
                $stmt->execute([$_SESSION['user']['email']]);
                $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($row && !empty($row['id'])) {
                    $manager_employe_id = $row['id'];
                }
            }

            // 3) Validation des données du formulaire
            $requiredFields = ['titre', 'description', 'competences_requises', 'diplomes_requis', 
                             'experience_min', 'niveau_responsabilite', 'autonomie_requise', 
                             'date_limite', 'id_poste', 'id_type_contrat'];
            
            foreach ($requiredFields as $field) {
                if (empty(Flight::request()->data->$field)) {
                    Flight::redirect('/manager/create-annonce?error=missing_field&field=' . $field);
                    return;
                }
            }

            // 4) Validation de la date limite (doit être future)
            $dateLimite = Flight::request()->data->date_limite;
            if (strtotime($dateLimite) < strtotime(date('Y-m-d'))) {
                Flight::redirect('/manager/create-annonce?error=invalid_date');
                return;
            }

            // 5) VÉRIFICATION POSTE LIBRE - CRITIQUE
            $id_poste = (int)Flight::request()->data->id_poste;
            $stmt = $db->prepare("SELECT postes_disponibles FROM v_postes_libres WHERE id = ?");
            $stmt->execute([$id_poste]);
            $posteInfo = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$posteInfo || $posteInfo['postes_disponibles'] <= 0) {
                Flight::redirect('/manager/create-annonce?error=poste_complet&id_poste=' . $id_poste);
                return;
            }

            // 6) Préparation des données
            $data = [
                'titre' => trim(Flight::request()->data->titre),
                'description' => trim(Flight::request()->data->description),
                'competences_requises' => trim(Flight::request()->data->competences_requises),
                'diplomes_requis' => trim(Flight::request()->data->diplomes_requis),
                'experience_min' => (int)Flight::request()->data->experience_min,
                'niveau_responsabilite' => trim(Flight::request()->data->niveau_responsabilite),
                'autonomie_requise' => trim(Flight::request()->data->autonomie_requise),
                'date_publication' => date('Y-m-d'),
                'date_limite' => $dateLimite,
                'id_poste' => $id_poste,
                'id_type_contrat' => (int)Flight::request()->data->id_type_contrat,
                'id_manager' => $manager_employe_id
            ];

            // 7) Création de l'annonce
            if ($this->annonceModel->createAnnonce($data)) {
                Flight::redirect('/manager/dashboard?success=1&poste_libre=1');
            } else {
                Flight::redirect('/manager/create-annonce?error=creation_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur création annonce: " . $e->getMessage());
            Flight::redirect('/manager/create-annonce?error=exception');
        }
    }

    public function viewAnnonce($id) {
        $annonce = $this->annonceModel->getAnnonceById($id);
        
        if (!$annonce) {
            Flight::redirect('/manager/dashboard?error=not_found');
            return;
        }
        
        Flight::render('manager/view_annonce', [
            'annonce' => $annonce,
            'page_title' => 'Détails de l\'annonce'
        ]);
    }

    public function updateStatut($id) {
        $statut = Flight::request()->data->statut;
        
        // Validation du statut
        $statutsValides = ['active', 'inactive', 'cloture'];
        if (!in_array($statut, $statutsValides)) {
            Flight::json(['success' => false, 'error' => 'Statut invalide'], 400);
            return;
        }
        
        if ($this->annonceModel->updateStatut($id, $statut)) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['success' => false], 500);
        }
    }

    public function deleteAnnonce($id) {
        if ($this->annonceModel->deleteAnnonce($id)) {
            Flight::redirect('/manager/dashboard?deleted=1');
        } else {
            Flight::redirect('/manager/dashboard?error=delete_failed');
        }
    }

    public function updateAnnonceForm($id) {
        $annonce = $this->annonceModel->getAnnonceById($id);
        
        if (!$annonce) {
            Flight::redirect('/manager/dashboard?error=not_found');
            return;
        }
        
        $postes = $this->posteModel->getAllPostes();
        $typeContrats = $this->referenceModel->getAllTypeContrats();
        
        Flight::render('manager/update_annonce', [
            'annonce' => $annonce,
            'postes' => $postes,
            'typeContrats' => $typeContrats,
            'page_title' => 'Modifier l\'annonce'
        ]);
    }

    public function updateAnnonce($id) {
        try {
            // Validation des données
            $requiredFields = ['titre', 'description', 'competences_requises', 'diplomes_requis', 
                             'experience_min', 'niveau_responsabilite', 'autonomie_requise', 
                             'date_limite', 'id_poste', 'id_type_contrat'];
            
            foreach ($requiredFields as $field) {
                if (empty(Flight::request()->data->$field)) {
                    Flight::json(['success' => false, 'error' => 'Champ requis: ' . $field], 400);
                    return;
                }
            }

            $data = [
                'titre' => trim(Flight::request()->data->titre),
                'description' => trim(Flight::request()->data->description),
                'competences_requises' => trim(Flight::request()->data->competences_requises),
                'diplomes_requis' => trim(Flight::request()->data->diplomes_requis),
                'experience_min' => (int)Flight::request()->data->experience_min,
                'niveau_responsabilite' => trim(Flight::request()->data->niveau_responsabilite),
                'autonomie_requise' => trim(Flight::request()->data->autonomie_requise),
                'date_limite' => Flight::request()->data->date_limite,
                'id_poste' => (int)Flight::request()->data->id_poste,
                'id_type_contrat' => (int)Flight::request()->data->id_type_contrat
            ];

            if ($this->annonceModel->updateAnnonce($id, $data)) {
                Flight::json(['success' => true]);
            } else {
                Flight::json(['success' => false, 'error' => 'Échec de la mise à jour'], 500);
            }
        } catch (\Exception $e) {
            error_log("Erreur update annonce: " . $e->getMessage());
            Flight::json(['success' => false, 'error' => 'Exception'], 500);
        }
    }

    // ========== GESTION DES CANDIDATURES ==========
    
    public function viewCandidatures() {
        $candidatures = $this->candidatureModel->getCandidaturesManager($_SESSION['user']['id']);
        
        Flight::render('manager/candidatures_list', [
            'candidatures' => $candidatures,
            'page_title' => 'Candidatures reçues'
        ]);
    }

    // ========== GESTION DES ENTRETIENS ==========
    
    public function creerEntretien($candidature_id) {
        $candidature = $this->candidatureModel->getCandidatureById($candidature_id);
        
        if (!$candidature) {
            Flight::redirect('/manager/candidatures?error=not_found');
            return;
        }
        
        Flight::render('manager/creer_entretien', [
            'candidature' => $candidature,
            'page_title' => 'Planifier l\'entretien'
        ]);
    }

    public function submitCreerEntretien($candidature_id) {
        $data = [
            'date_entretien' => Flight::request()->data->date_entretien,
            'lieu' => Flight::request()->data->lieu,
            'id_candidature' => $candidature_id,
            'id_manager' => $_SESSION['user']['id']
        ];

        if ($this->entretienModel->create($data)) {
            Flight::redirect('/manager/candidatures?success=entretien_cree');
        } else {
            Flight::redirect('/manager/candidatures?error=1');
        }
    }

    public function publierEntretien($entretien_id) {
        if ($this->entretienModel->publierEntretien($entretien_id)) {
            Flight::json(['success' => true, 'message' => 'Entretien publié']);
        } else {
            Flight::json(['success' => false], 500);
        }
    }

    public function viewEntretiens() {
        $entretiens = $this->entretienModel->getEntretiensEnAttente();
        
        Flight::render('manager/liste_entretiens', [
            'entretiens' => $entretiens,
            'page_title' => 'Entretiens planifiés'
        ]);
    }

    public function faireEntretien($id) {
        $entretien = $this->entretienModel->getEntretienById($id);
        
        if (!$entretien) {
            Flight::redirect('/manager/candidatures?error=not_found');
            return;
        }
        
        Flight::render('manager/faire_entretien', [
            'entretien' => $entretien,
            'page_title' => 'Entretien - ' . $entretien['candidat_nom']
        ]);
    }

    public function submitNoteEntretien($id) {
        $data = [
            'note_manager' => Flight::request()->data->note_manager,
            'qualites' => Flight::request()->data->qualites,
            'defauts' => Flight::request()->data->defauts,
            'id_manager' => $_SESSION['user']['id']
        ];

        if ($this->entretienModel->updateNoteManager($id, $data)) {
            // Mettre à jour le statut de la candidature
            $entretien = $this->entretienModel->getEntretienById($id);
            $this->candidatureModel->updateStatut($entretien['id_candidature'], 'entretien_termine');
            
            Flight::redirect('/manager/candidatures?success=note_envoyee');
        } else {
            Flight::redirect('/manager/candidatures?error=1');
        }
    }

    public function confirmEntretien($id) {
        $date = Flight::request()->data->date_entretien;
        $lieu = Flight::request()->data->lieu;
        
        if ($this->entretienModel->updateDateEntretien($id, $date, $lieu)) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['success' => false], 500);
        }
    }
}