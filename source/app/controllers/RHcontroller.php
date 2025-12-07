<?php

namespace app\controllers;

use Flight;
use app\models\AnnonceModel;
use app\models\CandidatureModel;
use app\models\EntretienModel;
use app\models\DocumentCandidatureModel;
use app\models\ContratModel;

class RHController {
    
    private $annonceModel;
    private $candidatureModel;
    private $entretienModel;
    private $documentModel;
    private $contratModel;

    public function __construct() {
        $this->annonceModel = new AnnonceModel(Flight::db());
        $this->candidatureModel = new CandidatureModel(Flight::db());
        $this->entretienModel = new EntretienModel(Flight::db());
        $this->documentModel = new DocumentCandidatureModel(Flight::db());
        $this->contratModel = new ContratModel(Flight::db());
    }

    public function dashboard() {
        $db = Flight::db();
        
        // Récupérer les annonces
        $annonces = $this->annonceModel->getAllAnnonces();
        
        // Stats postes libres
        $sql = "SELECT * FROM v_stats_postes";
        $stats_postes = $db->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        // Compter candidats en attente d'évaluation
        $sql = "SELECT COUNT(*) as count FROM v_candidats_en_evaluation 
                WHERE en_attente_evaluation_rh = 1 OR en_attente_entretien_manager = 1";
        $candidats_attente = $db->query($sql)->fetch(\PDO::FETCH_ASSOC)['count'];
        
        // Compter entretiens planifiés
        $sql = "SELECT COUNT(*) as count FROM entretien 
                WHERE statut IN ('planifie', 'termine')";
        $entretiens_count = $db->query($sql)->fetch(\PDO::FETCH_ASSOC)['count'];
        
        // Compter candidats acceptés
        $sql = "SELECT COUNT(*) as count FROM candidature 
                WHERE decision_finale = 'accepte'";
        $candidats_acceptes = $db->query($sql)->fetch(\PDO::FETCH_ASSOC)['count'];
        
        // Compter candidats rejetés
        $sql = "SELECT COUNT(*) as count FROM candidature 
                WHERE decision_finale = 'rejete'";
        $candidats_rejetes = $db->query($sql)->fetch(\PDO::FETCH_ASSOC)['count'];
        
        Flight::render('rh/dashboard', [
            'annonces' => $annonces,
            'stats_postes' => $stats_postes,
            'candidats_attente' => $candidats_attente,
            'entretiens_count' => $entretiens_count,
            'candidats_acceptes' => $candidats_acceptes,
            'candidats_rejetes' => $candidats_rejetes,
            'page_title' => 'Tableau de bord RH'
        ]);
    }

    public function viewAnnonces() {
        $search = Flight::request()->query->search ?? '';
        $statut = Flight::request()->query->statut ?? '';
        $contrat = Flight::request()->query->contrat ?? '';

        $annonces = $this->annonceModel->getAllAnnonces();
        
        if ($search || $statut || $contrat) {
            $annonces = array_filter($annonces, function($annonce) use ($search, $statut, $contrat) {
                $match = true;
                
                if ($search && stripos($annonce['titre'], $search) === false) {
                    $match = false;
                }
                
                if ($statut && $annonce['statut'] !== $statut) {
                    $match = false;
                }
                
                if ($contrat && $annonce['id_type_contrat'] != $contrat) {
                    $match = false;
                }
                
                return $match;
            });
        }
        
        Flight::render('rh/liste_annonces', [
            'annonces' => $annonces,
            'page_title' => 'Liste des Annonces d\'Emploi'
        ]);
    }

    public function viewAnnonce($id) {
        $annonce = $this->annonceModel->getAnnonceById($id);
        
        if (!$annonce) {
            Flight::redirect('/rh/annonces?error=not_found');
            return;
        }
        
        Flight::render('rh/view_annonce', [
            'annonce' => $annonce,
            'page_title' => 'Détails de l\'annonce'
        ]);
    }

    public function viewCandidatures() {
        $search = Flight::request()->query->search ?? '';
        $poste = Flight::request()->query->poste ?? '';
        $statut = Flight::request()->query->statut ?? '';
        $annonce = Flight::request()->query->annonce ?? '';
        $experience = Flight::request()->query->experience ?? '';

        if ($search || $poste || $statut || $annonce || $experience) {
            $candidatures = $this->candidatureModel->searchCandidatures([
                'nom' => $search,
                'poste' => $poste,
                'statut' => $statut,
                'annonce' => $annonce,
                'experience' => $experience
            ]);
        } else {
            $candidatures = $this->candidatureModel->getAllCandidatures();
        }
        
        Flight::render('rh/candidatures_list', [
            'candidatures' => $candidatures,
            'page_title' => 'Tri et Classification des Candidatures'
        ]);
    }

    public function viewCandidature($id) {
        $candidature = $this->candidatureModel->getCandidatureById($id);
        $documents = $this->documentModel->getDocumentsByCandidature($id);
        $managers = Flight::authModel()->getAllManagers();
        
        Flight::render('rh/view_candidature', [
            'candidature' => $candidature,
            'documents' => $documents,
            'managers' => $managers,
            'page_title' => 'Détails candidature'
        ]);
    }

    public function envoyerVersManager() {
        $candidature_id = Flight::request()->data->candidature_id;
        $id_manager = Flight::request()->data->id_manager;

        if ($this->candidatureModel->envoyerVersManager($candidature_id, $id_manager)) {
            Flight::json(['success' => true, 'message' => 'Candidature envoyée au manager']);
        } else {
            Flight::json(['success' => false], 500);
        }
    }

    public function planifierEntretien() {
        $candidature_id = Flight::request()->data->candidature_id;
        $date = Flight::request()->data->date_entretien;
        $lieu = Flight::request()->data->lieu;

        $data = [
            'date_entretien' => $date,
            'lieu' => $lieu,
            'id_candidature' => $candidature_id,
            'id_rh' => $_SESSION['user']['id']
        ];

        if ($this->entretienModel->create($data)) {
            $this->candidatureModel->updateStatut($candidature_id, 'entretien_planifie');
            Flight::json(['success' => true]);
        } else {
            Flight::json(['success' => false], 500);
        }
    }

    public function viewResultatsEntretiens() {
        $entretiens = $this->entretienModel->getAllEntretiens();
        $entretiens = array_filter($entretiens, fn($e) => $e['statut'] === 'termine');
        
        Flight::render('rh/resultats_entretiens', [
            'entretiens' => $entretiens,
            'page_title' => 'Résultats des entretiens'
        ]);
    }

    public function evaluerEntretien($id) {
        $entretien = $this->entretienModel->getEntretienById($id);
        
        Flight::render('rh/evaluer_entretien', [
            'entretien' => $entretien,
            'page_title' => 'Évaluation entretien'
        ]);
    }

    public function submitEvaluationEntretien($id) {
    // Récupération DB (optionnelle pour transaction)
    $db = method_exists('Flight', 'db') ? Flight::db() : null;

    try {
        // Récupère l'entretien
        $entretien = $this->entretienModel->getEntretienById($id);
        if (!$entretien) {
            Flight::redirect('/rh/resultats-entretiens?error=entretien_not_found');
            return;
        }

        // Récupère les données de la requête (casting sécurisé)
        $note_rh = isset(Flight::request()->data->note_rh) ? (float) Flight::request()->data->note_rh : 0.0;
        // Si note_competence fournie on l'utilise, sinon fallback sur note_rh (comportement précédent)
        $note_competence = isset(Flight::request()->data->note_competence) 
            ? (float) Flight::request()->data->note_competence 
            : $note_rh;

        $observation = isset(Flight::request()->data->observation) ? Flight::request()->data->observation : null;
        $decision = isset(Flight::request()->data->decision) ? Flight::request()->data->decision : null;
        $type_contrat_post = isset(Flight::request()->data->type_contrat) ? Flight::request()->data->type_contrat : null;
        $date_debut_post = isset(Flight::request()->data->date_debut) ? Flight::request()->data->date_debut : null;

        // Préparer le payload pour updateNoteRH
        $dataToUpdate = [
            'note_rh' => $note_rh,
            'observation' => $observation,
            // si tu veux sauver note_competence séparément, ajoute la colonne dans la table et ici
        ];

        // Calcul note finale (prendre la note manager si existe, sinon considérer 0 ou note_rh)
        $note_manager = isset($entretien['note_manager']) ? (float) $entretien['note_manager'] : 0.0;
        // Si manager absent, on peut décider de la moyenne entre note_rh et note_competence ou simplement note_rh.
        // Ici on suit ton code précédent : moyenne manager+rh (manager peut être 0)
        $note_finale = ($note_manager + $note_rh) / 2;

        // Démarrer transaction si possible (PDO)
        $useTransaction = ($db && method_exists($db, 'beginTransaction'));
        if ($useTransaction) {
            $db->beginTransaction();
        }

        // Sauvegarder la note RH en base (exécute la méthode existante)
        $updated = $this->entretienModel->updateNoteRH($id, $dataToUpdate);

        if (!$updated) {
            if ($useTransaction) $db->rollBack();
            Flight::redirect('/rh/resultats-entretiens?error=update_failed');
            return;
        }

        // -----------------------
        // 1) Logique mise en formation automatique
        // Condition : note_rh >= 10 ET note_competence < 10
        // -----------------------
        if ($note_rh >= 10 && $note_competence < 10) {
            // Création mise en formation pour les compétences déficitaires
            $competenceModel = new \app\models\CompetenceModel(Flight::db());

            // Récupère les compétences déficitaires pour la candidature de cet entretien
            // (ta méthode getCompetencesDeficitaires doit renvoyer : id_competence, niveau_requis, niveau_candidat)
            $deficitaires = $competenceModel->getCompetencesDeficitaires($entretien['id_candidature']);

            foreach ($deficitaires as $comp) {
                // Préparer payload (ajuste les clés selon ton model)
                $payload = [
                    'id_candidature' => $entretien['id_candidature'],
                    'id_competence_deficitaire' => $comp['id_competence'] ?? null,
                    'niveau_requis' => $comp['niveau_requis'] ?? null,
                    'niveau_candidat' => $comp['niveau_candidat'] ?? null,
                    'statut' => 'proposee',
                    'raison' => 'Compétence insuffisante détectée lors entretien RH'
                ];

                // Créer la mise en formation (méthode déjà utilisée dans ton code)
                $competenceModel->creerMiseEnFormation($payload);
            }

            // Mettre à jour le statut de la candidature
            $this->candidatureModel->updateStatut($entretien['id_candidature'], 'mise_en_formation');

            if ($useTransaction) $db->commit();

            // Redirection spécifique
            Flight::redirect('/rh/candidats-en-attente?success=mise_formation');
            return;
        }

        // -----------------------
        // 2) Suite normale : décision & type de contrat
        // -----------------------
        // Préparer décision_data
        $decision_data = [];
        if ($decision !== null) {
            $decision_data['decision_finale'] = $decision;
        }

        if ($decision === 'accepte') {
            // récupérer candidature pour vérifier demande_contrat_direct si nécessaire
            $candidature = $this->candidatureModel->getCandidatureById($entretien['id_candidature']);

            // Priorité : type_contrat fourni dans la requête
            if (!empty($type_contrat_post)) {
                $decision_data['type_contrat_accorde'] = $type_contrat_post;
            } else {
                // Logique par défaut basée sur note_finale et demande_contrat_direct
                if ($note_finale >= 18 && !empty($candidature['demande_contrat_direct'])) {
                    $decision_data['type_contrat_accorde'] = 'Contrat de Travail';
                } else {
                    $decision_data['type_contrat_accorde'] = 'Contrat d\'Essai (2-6 mois)';
                }
            }

            // date_debut_travail facultative
            $decision_data['date_debut_travail'] = $date_debut_post ?? null;
        }

        // Mettre à jour la décision dans la candidature (si des données existent)
        if (!empty($decision_data)) {
            $this->candidatureModel->updateDecision($entretien['id_candidature'], $decision_data);
        }

        // Commit transaction si tout est ok
        if ($useTransaction) $db->commit();

        // Redirection réussite
        Flight::redirect('/rh/resultats-entretiens?success=1');
        return;
    } catch (\Exception $e) {
        // Rollback transaction si active
        if (isset($useTransaction) && $useTransaction && $db && $db->inTransaction()) {
            $db->rollBack();
        }

        // Optionnel : logger l'erreur si tu as un logger
        // error_log($e->getMessage());

        // Redirection avec erreur
        Flight::redirect('/rh/resultats-entretiens?error=exception');
        return;
    }
}

    public function viewCandidaturesAcceptees() {
        $db = Flight::db();
        
        // Récupérer les candidats acceptés avec leurs contrats
        $sql = "SELECT c.*, 
                a.titre as annonce_titre,
                p.label as poste_nom,
                ce.id as contrat_id,
                e.id as employe_id
                FROM candidature c
                LEFT JOIN annonce_emploi a ON c.id_annonce = a.id
                LEFT JOIN Poste p ON a.id_poste = p.id
                LEFT JOIN Employe e ON c.email = e.email
                LEFT JOIN contrat_employe ce ON e.id = ce.id_employe
                WHERE c.decision_finale = 'accepte'
                ORDER BY c.date_decision DESC";
        
        $candidatures = $db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
        Flight::render('rh/candidatures_acceptees', [
            'candidatures' => $candidatures,
            'page_title' => 'Candidats acceptés'
        ]);
    }

    public function viewCandidaturesRejetees() {
        $candidatures = $this->candidatureModel->getCandidaturesRejetees();
        
        Flight::render('rh/candidatures_rejetees', [
            'candidatures' => $candidatures,
            'page_title' => 'Candidats rejetés'
        ]);
    }

   public function viewCandidatsEnAttente() {
    $db = Flight::db();
    
    // UTILISER LA VUE CORRECTE : v_candidats_mise_en_formation
    $sql = "SELECT * FROM v_candidats_mise_en_formation 
            WHERE statut IN ('proposee', 'acceptee', 'en_formation', 'terminee')
            ORDER BY 
                CASE 
                    WHEN statut = 'proposee' THEN 1
                    WHEN statut = 'acceptee' THEN 2
                    WHEN statut = 'en_formation' THEN 3
                    ELSE 4
                END,
                date_mise_en_formation DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $candidats = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    Flight::render('rh/candidats_en_attente', [
        'candidats' => $candidats,
        'page_title' => 'Candidats en Attente de Formation'
    ]);
}
}