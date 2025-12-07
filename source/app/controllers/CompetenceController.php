<?php

namespace app\controllers;

use Flight;
use PDO;
use app\models\CompetenceModel;
use app\models\CandidatureModel;
use app\models\EntretienModel;
use app\models\FormationModel;

class CompetenceController {
    
    private $competenceModel;
    private $candidatureModel;
    private $entretienModel;
    private $formationModel;

    public function __construct() {
        $this->competenceModel = new CompetenceModel(Flight::db());
        $this->candidatureModel = new CandidatureModel(Flight::db());
        $this->entretienModel = new EntretienModel(Flight::db());
        $this->formationModel = new FormationModel(Flight::db());
    }

    // ==================== CANDIDATS EN ATTENTE ====================

    public function candidatsEnAttente() {
        $candidats = $this->competenceModel->getCandidatsMiseEnFormation();
        Flight::render('rh/candidats_en_attente', [
            'candidats' => $candidats,
            'page_title' => 'Candidats en Attente de Formation'
        ]);
    }

    // ==================== MATCHING AUTOMATIQUE ====================

    public function matchingAutomatique() {
        try {
            $db = Flight::db();
            
            $query = "
                SELECT 
                    cand.id as id_candidature, cand.nom as candidat_nom, cand.prenom as candidat_prenom,
                    cand.email as candidat_email, a.id as annonce_id, a.id_poste, p.label as poste_nom,
                    c.libelle as categorie_libelle, e.id as entretien_id, cand.decision_finale,
                    COUNT(DISTINCT pc.id_competence) as nb_competences_requises,
                    COUNT(DISTINCT CASE WHEN cc.niveau_evalue >= pc.niveau_requis THEN pc.id_competence END) as nb_competences_conformes,
                    COALESCE(SUM(CASE WHEN cc.niveau_evalue IS NOT NULL THEN 
                        (LEAST(cc.niveau_evalue, pc.niveau_requis) / pc.niveau_requis) * pc.importance * 100
                    ELSE 0 END) / NULLIF(SUM(pc.importance), 0), 0) as score_pondere_pct,
                    CASE WHEN cmf.statut IS NOT NULL THEN cmf.statut
                         WHEN cand.decision_finale IS NOT NULL THEN cand.decision_finale
                         ELSE 'candidat' END as statut_evaluation
                FROM candidature cand
                JOIN annonce_emploi a ON cand.id_annonce = a.id
                JOIN Poste p ON a.id_poste = p.id
                JOIN categorie c ON p.id_categorie = c.id
                LEFT JOIN entretien e ON cand.id = e.id_candidature
                LEFT JOIN Poste_Competence pc ON p.id = pc.id_poste
                LEFT JOIN Candidat_Competence cc ON cand.id = cc.id_candidature AND pc.id_competence = cc.id_competence
                LEFT JOIN Candidat_Mise_Formation cmf ON cand.id = cmf.id_candidature
                WHERE e.id IS NOT NULL AND e.statut_publication = 'publie' AND cc.niveau_evalue IS NOT NULL
                GROUP BY cand.id, a.id, p.id, c.id, e.id, cmf.statut, cand.decision_finale
                ORDER BY score_pondere_pct DESC, cand.nom, cand.prenom
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute();
            $matchings = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($matchings as &$matching) {
                $detailQuery = "
                    SELECT comp.libelle as competence_nom, pc.niveau_requis,
                           COALESCE(cc.niveau_evalue, 0) as niveau_candidat, pc.importance,
                           CASE WHEN cc.niveau_evalue >= pc.niveau_requis THEN 100
                                WHEN cc.niveau_evalue IS NOT NULL THEN (cc.niveau_evalue / pc.niveau_requis) * 100
                                ELSE 0 END as score_pct,
                           (pc.niveau_requis - COALESCE(cc.niveau_evalue, 0)) as ecart_niveau
                    FROM Poste_Competence pc
                    JOIN Competence comp ON pc.id_competence = comp.id
                    LEFT JOIN Candidat_Competence cc ON cc.id_candidature = ? AND cc.id_competence = pc.id_competence
                    WHERE pc.id_poste = ?
                    ORDER BY pc.importance DESC
                ";
                
                $detailStmt = $db->prepare($detailQuery);
                $detailStmt->execute([$matching['id_candidature'], $matching['id_poste']]);
                $matching['detail_matching'] = json_encode($detailStmt->fetchAll(\PDO::FETCH_ASSOC));
            }
            
            Flight::render('rh/matching_automatique', [
                'matchings' => $matchings,
                'page_title' => 'Matching Automatique - Compétences'
            ]);
            
        } catch (\Exception $e) {
            error_log("Erreur matching automatique: " . $e->getMessage());
            Flight::redirect('/rh/dashboard?error=matching_failed');
        }
    }

    // ==================== GESTION MISE EN FORMATION ====================

    private function updateFormationStatut($id, $statut, $redirect) {
        try {
            if ($this->competenceModel->updateMiseEnFormation($id, $statut)) {
                Flight::redirect($redirect . '?success=' . $statut);
            } else {
                Flight::redirect($redirect . '?error=update_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur update formation: " . $e->getMessage());
            Flight::redirect($redirect . '?error=exception');
        }
    }

    /**
     * Le candidat accepte de suivre la formation
     */
    public function candidatAccepteFormation($id_candidature) {
        try {
            $db = Flight::db();
            
            // Vérifier qu'une formation est proposée/acceptée
            $query = "SELECT id FROM Candidat_Mise_Formation 
                     WHERE id_candidature = ? AND statut = 'acceptee' LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute([$id_candidature]);
            $miseFormation = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$miseFormation) {
                Flight::redirect('/entretiens?error=no_formation');
                return;
            }
            
            // Changer le statut vers "en_formation"
            $updateQuery = "UPDATE Candidat_Mise_Formation 
                           SET statut = 'en_formation', date_debut_formation = NOW() 
                           WHERE id = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->execute([$miseFormation['id']]);
            
            // Rediriger vers le formulaire de formation
            Flight::redirect('/candidat/formation/' . $id_candidature . '?success=formation_demarree');
            
        } catch (\Exception $e) {
            error_log("Erreur acceptation formation: " . $e->getMessage());
            Flight::redirect('/entretiens?error=exception');
        }
    }

    public function accepterMiseEnFormation($id) {
        $this->updateFormationStatut($id, 'acceptee', '/rh/candidats-en-attente');
    }

    public function rejeterMiseEnFormation($id) {
        $this->updateFormationStatut($id, 'echouee', '/rh/candidats-en-attente');
    }

    public function demarrerFormation($id) {
        $this->updateFormationStatut($id, 'en_formation', '/rh/candidats-en-attente');
    }

    // ==================== FORMULAIRE DE FORMATION ====================

    public function formationFormulaire($id_candidature) {
        $candidature = $this->candidatureModel->getCandidatureById($id_candidature);
        
        if (!$candidature) {
            Flight::redirect('/rh/candidats-en-attente?error=not_found');
            return;
        }

        $db = Flight::db();
        $query = "SELECT * FROM Candidat_Mise_Formation WHERE id_candidature = ? 
                  AND statut IN ('en_formation','acceptee') ORDER BY date_debut_formation DESC LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_candidature]);
        $mise_formation = $stmt->fetch(\PDO::FETCH_ASSOC);

        Flight::render('candidat/formulaire_formation', [
            'candidature' => $candidature,
            'mise_formation' => $mise_formation,
            'page_title' => 'Formulaire de Formation'
        ]);
    }

    // ==================== SOUMISSION DU FORMULAIRE (CORRECTION) ====================
    
    public function submitFormationFormulaire() {
        try {
            $req = Flight::request();
            
            $id_candidature = $req->data->id_candidature ?? null;
            
            error_log("=== SUBMIT FORMATION DEBUG ===");
            error_log("id_candidature from form: " . ($id_candidature ?? 'NULL'));
            
            if (!$id_candidature) {
                error_log("ERROR: No id_candidature in form!");
                Flight::redirect('/entretiens?error=no_candidature');
                return;
            }
            
            $db = Flight::db();
            
            // Vérifier s'il y a une mise en formation
            $check = $db->prepare("SELECT id, statut FROM Candidat_Mise_Formation WHERE id_candidature = ? ORDER BY id DESC LIMIT 1");
            $check->execute([$id_candidature]);
            $mise_formation = $check->fetch(\PDO::FETCH_ASSOC);
            
            $id_mise = $mise_formation['id'] ?? null;
            
            if ($id_mise && in_array($mise_formation['statut'], ['en_formation', 'terminee', 'echouee'])) {
                if ($mise_formation['statut'] !== 'en_formation') {
                    Flight::redirect('/entretiens?error=formation_already_done');
                    return;
                }
            }
            
            // Traiter les fichiers uploadés
            $uploaded_files = [];
            if (!empty($_FILES['documents_formation']['name'][0])) {
                $upload_dir = realpath(__DIR__ . '/../../public') . '/uploads/formations/' . $id_candidature;
                if (!is_dir($upload_dir)) {
                    @mkdir($upload_dir, 0755, true);
                }
                
                foreach ($_FILES['documents_formation']['tmp_name'] as $idx => $tmp) {
                    if (!empty($tmp) && is_uploaded_file($tmp)) {
                        $filename = basename($_FILES['documents_formation']['name'][$idx]);
                        $filename = preg_replace('/[^a-zA-Z0-9._\-]/', '_', $filename);
                        $dest = $upload_dir . '/' . time() . '_' . $filename;
                        
                        if (move_uploaded_file($tmp, $dest)) {
                            $uploaded_files[] = $filename;
                        }
                    }
                }
            }
            
            // Stocker en SESSION
            $_SESSION['formation_data'] = [
                'id_candidat_mise_formation' => $id_mise,
                'id_candidature' => $id_candidature,
                'contenu_formation' => trim($req->data->contenu_formation ?? ''),
                'duree_heures' => $req->data->duree_heures ?? null,
                'methodologie' => $req->data->methodologie ?? null,
                'formateur' => trim($req->data->formateur ?? ''),
                'date_debut_formation' => $req->data->date_debut_formation ?? null,
                'date_fin_formation' => $req->data->date_fin_formation ?? null,
                'resultats' => trim($req->data->resultats ?? ''),
                'observations' => trim($req->data->observations ?? ''),
                'certificat' => $req->data->certificat ?? null,
                'documents' => $uploaded_files
            ];
            
            error_log("Formation data stored in session with id_candidature: " . $id_candidature);
            
            // Mettre à jour le statut en BD
            if ($id_mise && $mise_formation['statut'] === 'acceptee') {
                $update = $db->prepare("UPDATE Candidat_Mise_Formation SET statut = 'en_formation' WHERE id = ?");
                $update->execute([$id_mise]);
            } elseif (!$id_mise) {
                $insert = $db->prepare("INSERT INTO Candidat_Mise_Formation (id_candidature, statut, date_mise_en_formation) VALUES (?, 'en_formation', NOW())");
                $insert->execute([$id_candidature]);
                $id_mise = $db->lastInsertId();
                $_SESSION['formation_data']['id_candidat_mise_formation'] = $id_mise;
            }
            
            // Créer l'enregistrement Formulaire_Formation en BD
            if ($id_mise) {
                try {
                    $insertForm = $db->prepare("INSERT INTO Formulaire_Formation 
                        (id_candidat_mise_formation, contenu_formation, duree_heures, methodologie, 
                         formateur, date_debut_formation, date_fin_formation, resultats, observations, certificat)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $insertForm->execute([
                        $id_mise,
                        $req->data->contenu_formation ?? null,
                        $req->data->duree_heures ?? null,
                        $req->data->methodologie ?? null,
                        $req->data->formateur ?? null,
                        $req->data->date_debut_formation ?? null,
                        $req->data->date_fin_formation ?? null,
                        $req->data->resultats ?? null,
                        $req->data->observations ?? null,
                        $req->data->certificat ?? null
                    ]);
                    $lastFormId = $db->lastInsertId();
                    // store form id in session so candidate can continue to questionnaire
                    $_SESSION['formation_data']['id_formulaire_formation'] = $lastFormId;
                    error_log("Formulaire_Formation created successfully for id_mise: " . $id_mise . " (form_id=" . $lastFormId . ")");
                } catch (\Exception $e) {
                    error_log("Warning: Could not create Formulaire_Formation: " . $e->getMessage());
                    // Continue anyway, the session data is still saved
                }
            }
            
            error_log("=== END DEBUG ===");
            
            // ✅ CORRECTION : Rediriger vers /entretiens au lieu de /candidat/confirmer-formation
            Flight::redirect('/entretiens?success=formation_submitted&id_candidature=' . $id_candidature);
            
        } catch (\Exception $e) {
            error_log("Erreur envoi formulaire: " . $e->getMessage());
            Flight::redirect('/entretiens?error=exception');
        }
    }

    // ==================== CONFIRMATION FORMULAIRE ====================

    public function showFormationReview() {
        $data = $_SESSION['formation_data'] ?? null;
        if (!$data) {
            Flight::redirect('/entretiens?error=no_formation_data');
            return;
        }
        
        error_log("Confirmation page - id_candidature: " . ($data['id_candidature'] ?? 'MISSING'));

        Flight::render('candidat/confirmer_formation', [
            'data' => $data,
            'page_title' => 'Confirmation du Formulaire'
        ]);
    }

    // ==================== QUESTIONNAIRE CANDIDAT ====================

    public function startQuestionnaire() {
        $req = Flight::request();
        $data = $_SESSION['formation_data'] ?? null;

        // If session is missing, attempt to rebuild from POST/GET id_candidature and DB
        if (!$data) {
            $id_candidature = $req->data->id_candidature ?? $req->query->id_candidature ?? null;
            if (!$id_candidature) {
                Flight::redirect('/entretiens?error=no_formation_data');
                return;
            }

            $db = Flight::db();
            // find latest mise en formation
            $stmt = $db->prepare("SELECT id, statut FROM Candidat_Mise_Formation WHERE id_candidature = ? ORDER BY id DESC LIMIT 1");
            $stmt->execute([$id_candidature]);
            $mise = $stmt->fetch(\PDO::FETCH_ASSOC);

            $formulaire = null;
            if ($mise && isset($mise['id'])) {
                $stmt2 = $db->prepare("SELECT * FROM Formulaire_Formation WHERE id_candidat_mise_formation = ? ORDER BY id DESC LIMIT 1");
                $stmt2->execute([$mise['id']]);
                $formulaire = $stmt2->fetch(\PDO::FETCH_ASSOC);
            }

            // build minimal session data
            $data = [
                'id_candidat_mise_formation' => $mise['id'] ?? null,
                'id_candidature' => $id_candidature,
                'id_formulaire_formation' => $formulaire['id'] ?? null,
                'contenu_formation' => $formulaire['contenu_formation'] ?? null,
                'duree_heures' => $formulaire['duree_heures'] ?? null,
                'methodologie' => $formulaire['methodologie'] ?? null,
                'formateur' => $formulaire['formateur'] ?? null,
                'date_debut_formation' => $formulaire['date_debut_formation'] ?? null,
                'date_fin_formation' => $formulaire['date_fin_formation'] ?? null,
                'resultats' => $formulaire['resultats'] ?? null,
                'observations' => $formulaire['observations'] ?? null,
                'certificat' => $formulaire['certificat'] ?? null,
            ];

            // persist session so candidate can continue
            $_SESSION['formation_data'] = $data;
        }

        error_log("=== START QUESTIONNAIRE DEBUG ===");
        error_log("id_candidature from session: " . ($data['id_candidature'] ?? 'MISSING'));
        error_log("Full data: " . json_encode($data));

        if (empty($data['id_candidature'])) {
            error_log("ERROR: id_candidature is missing in session data!");
            Flight::redirect('/entretiens?error=missing_id');
            return;
        }

        $db = Flight::db();
        $query_postes = "SELECT id, label FROM Poste ORDER BY label";
        $result_postes = $db->query($query_postes);
        $postes = $result_postes->fetchAll(\PDO::FETCH_ASSOC);

        error_log("=== END DEBUG ===");

        Flight::render('candidat/questionnaire_formation', [
            'data' => $data,
            'postes' => $postes,
            'page_title' => 'Questionnaire de Formation'
        ]);
    }

    public function submitQuestionnaire() {
        try {
            error_log("=== QUESTIONNAIRE SUBMIT DEBUG ===");
            
            $req = Flight::request();
            $id_candidature = $req->data->id_candidature ?? null;
            
            error_log("id_candidature from POST: " . ($id_candidature ?? 'NULL'));
            
            if (!$id_candidature) {
                error_log("FAIL: No id_candidature found in POST!");
                Flight::redirect('/entretiens?error=questionnaire_no_id');
                return;
            }
            
            $id_candidature = (int)$id_candidature;
            $db = Flight::db();

            // Récupérer le formulaire_id pour cette candidature
            $getIdStmt = $db->prepare("SELECT cmf.id, ff.id as formulaire_id FROM Candidat_Mise_Formation cmf 
                                       LEFT JOIN Formulaire_Formation ff ON cmf.id = ff.id_candidat_mise_formation
                                       WHERE cmf.id_candidature = ? ORDER BY cmf.id DESC LIMIT 1");
            $getIdStmt->execute([$id_candidature]);
            $data_mise = $getIdStmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$data_mise || empty($data_mise['formulaire_id'])) {
                error_log("WARNING: No formulaire found for candidature: " . $id_candidature . " - attempting to recover/create one.");

                // try session
                $sess = $_SESSION['formation_data'] ?? null;
                if (!empty($sess['id_formulaire_formation'])) {
                    $formulaire_id = $sess['id_formulaire_formation'];
                } else {
                    // create a minimal Formulaire_Formation linked to this mise
                    $recordId = $data_mise['id'] ?? null;
                    if (!$recordId) {
                        // attempt to fetch mise id by candidature
                        $stmtM = $db->prepare("SELECT id FROM Candidat_Mise_Formation WHERE id_candidature = ? ORDER BY id DESC LIMIT 1");
                        $stmtM->execute([$id_candidature]);
                        $m = $stmtM->fetch(\PDO::FETCH_ASSOC);
                        $recordId = $m['id'] ?? null;
                    }

                    if ($recordId) {
                        try {
                            $insForm = $db->prepare("INSERT INTO Formulaire_Formation (id_candidat_mise_formation, date_submission) VALUES (?, NOW())");
                            $insForm->execute([$recordId]);
                            $formulaire_id = $db->lastInsertId();
                            // update session
                            $_SESSION['formation_data']['id_formulaire_formation'] = $formulaire_id;
                            error_log("Created minimal Formulaire_Formation id=" . $formulaire_id . " for mise_id=" . $recordId);
                        } catch (\Exception $e) {
                            error_log("ERROR: Could not create minimal Formulaire_Formation: " . $e->getMessage());
                            Flight::redirect('/entretiens?error=no_formulaire');
                            return;
                        }
                    } else {
                        error_log("ERROR: No mise id available to create formulaire for candidature: " . $id_candidature);
                        Flight::redirect('/entretiens?error=no_formulaire');
                        return;
                    }
                }
            } else {
                $recordId = $data_mise['id'];
                $formulaire_id = $data_mise['formulaire_id'];
            }
            
            // Récupérer les réponses du formulaire
            $q1 = $req->data->q_1 ?? null; // oui, partiellement, non
            $q2 = (int)($req->data->q_2 ?? 3); // 1-5
            $q3 = $req->data->q_3 ?? null; // oui, partiellement, non
            $q4 = $req->data->q_4 ?? null; // texte
            $q5 = $req->data->q_5 ?? null; // oui, non
            
            error_log("Réponses: q1=$q1, q2=$q2, q3=$q3, q5=$q5");
            
            // Créer ou mettre à jour l'enregistrement Questionnaire_Formation_Candidat avec les VRAIES réponses
            try {
                // Vérifier si un enregistrement existe déjà
                $checkStmt = $db->prepare("SELECT id FROM Questionnaire_Formation_Candidat WHERE id_formulaire_formation = ?");
                $checkStmt->execute([$formulaire_id]);
                $existing = $checkStmt->fetchColumn();
                
                if ($existing) {
                    // Mettre à jour l'existant
                    $updateQStmt = $db->prepare("UPDATE Questionnaire_Formation_Candidat 
                        SET q1_contenu_attentes = ?, 
                            q2_qualite_pedagogique = ?,
                            q3_objectifs_atteints = ?,
                            q4_ameliorations = ?,
                            q5_formation_complementaire = ?,
                            statut = 'soumis',
                            date_soumission = NOW()
                        WHERE id = ?");
                    $updateQStmt->execute([$q1, $q2, $q3, $q4, $q5, $existing]);
                    error_log("Questionnaire_Formation_Candidat updated: $existing");
                } else {
                    // Créer un nouvel enregistrement
                    $insertQStmt = $db->prepare("INSERT INTO Questionnaire_Formation_Candidat 
                        (id_formulaire_formation, id_candidature, 
                         q1_contenu_attentes, q2_qualite_pedagogique, q3_objectifs_atteints, 
                         q4_ameliorations, q5_formation_complementaire, statut, date_soumission)
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'soumis', NOW())");
                    $insertQStmt->execute([$formulaire_id, $id_candidature, $q1, $q2, $q3, $q4, $q5]);
                    error_log("Questionnaire_Formation_Candidat created for formulaire_id: " . $formulaire_id);
                }
            } catch (\Exception $e) {
                error_log("ERROR: Could not process Questionnaire_Formation_Candidat: " . $e->getMessage());
                Flight::redirect('/entretiens?error=questionnaire_db_error');
                return;
            }
            
            // Mettre à jour le statut Candidat_Mise_Formation à questionnaire_soumis
            try {
                $update = $db->prepare("UPDATE Candidat_Mise_Formation SET statut = 'questionnaire_soumis' WHERE id = ?");
                $update->execute([$recordId]);
                $affected = $update->rowCount();
                error_log("Candidat_Mise_Formation updated: " . $affected . " rows");
            } catch (\Exception $e) {
                error_log("ERROR: Could not update Candidat_Mise_Formation: " . $e->getMessage());
                Flight::redirect('/entretiens?error=status_update_failed');
                return;
            }

            error_log("✓ Questionnaire successfully submitted for candidature: " . $id_candidature);
            error_log("=== END DEBUG ===");

            // Rediriger avec succès
            Flight::redirect('/entretiens?success=questionnaire_submitted');
        } catch (\Exception $e) {
            error_log('EXCEPTION in submitQuestionnaire: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            Flight::redirect('/entretiens?error=questionnaire_exception');
        }
    }

    // ==================== RESTE DES MÉTHODES (inchangé) ====================
    
    public function evaluerFormation($id_formulaire) {
        $formulaire = $this->competenceModel->getFormationForm($id_formulaire);
        
        if (!$formulaire) {
            Flight::redirect('/rh/formations?error=not_found');
            return;
        }

        $db = Flight::db();
        $query = "SELECT f.*, c.nom, c.prenom, c.email, cmf.id_competence_deficitaire, comp.libelle as competence_nom
                  FROM Formulaire_Formation f
                  JOIN Candidat_Mise_Formation cmf ON f.id_candidat_mise_formation = cmf.id
                  JOIN candidature c ON cmf.id_candidature = c.id
                  LEFT JOIN Competence comp ON cmf.id_competence_deficitaire = comp.id
                  WHERE f.id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_formulaire]);
        $detail = $stmt->fetch(\PDO::FETCH_ASSOC);

        Flight::render('rh/evaluer_formation', [
            'formulaire' => $detail,
            'page_title' => 'Évaluer Formation - ' . $detail['prenom'] . ' ' . $detail['nom']
        ]);
    }

    public function submitEvaluationFormation() {
        try {
            $id_formulaire = Flight::request()->data->id_formulaire;
            
            $data = [
                'note_formation' => (float)Flight::request()->data->note_formation,
                'note_competence_finale' => (float)Flight::request()->data->note_competence_finale,
                'observations' => trim(Flight::request()->data->observations ?? ''),
                'decision' => (Flight::request()->data->note_formation >= 10 && 
                              Flight::request()->data->note_competence_finale >= 10) ? 'accepte' : 'rejete'
            ];

            if ($this->competenceModel->createEvaluationFormation([
                'id_formulaire_formation' => $id_formulaire,
                'id_rh' => $_SESSION['user']['id'],
                'note_formation' => $data['note_formation'],
                'note_competence_finale' => $data['note_competence_finale'],
                'observations' => $data['observations'],
                'decision' => $data['decision']
            ])) {
                $formulaire = $this->competenceModel->getFormationForm($id_formulaire);
                
                $query = $data['decision'] === 'accepte' 
                    ? "UPDATE Candidat_Mise_Formation SET statut = 'terminee' WHERE id = ?"
                    : "UPDATE Candidat_Mise_Formation SET statut = 'echouee' WHERE id = ?";
                $stmt = Flight::db()->prepare($query);
                $stmt->execute([$formulaire['id_candidat_mise_formation']]);
                
                $query2 = "SELECT id_candidature FROM Candidat_Mise_Formation WHERE id = ?";
                $stmt2 = Flight::db()->prepare($query2);
                $stmt2->execute([$formulaire['id_candidat_mise_formation']]);
                $mise = $stmt2->fetch(\PDO::FETCH_ASSOC);
                
                $this->candidatureModel->updateStatut($mise['id_candidature'], 
                    $data['decision'] === 'accepte' ? 'accepte' : 'rejete');
                
                Flight::redirect('/rh/formations?success=' . $data['decision']);
            } else {
                Flight::redirect('/rh/evaluer-formation/' . $id_formulaire . '?error=eval_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur évaluation formation: " . $e->getMessage());
            Flight::redirect('/rh/evaluer-formation?error=exception');
        }
    }

    // ==================== EMPLOYÉS - COMPÉTENCES ET PERFORMANCE ====================

    public function competencesEmployes() {
        $db = Flight::db();
        
        $query = "
            SELECT e.id as id_employe, e.nom as nom_employe, e.prenom as prenom_employe,
                   e.email as email_employe, e.contact, ce.id_poste, p.label as poste_nom,
                   d.libelle as nom_departement, ce.date_debut as date_embauche,
                   COALESCE(eqp.score_performance, 50) as score_performance,
                   COALESCE(eqp.statut_employe, 'actif') as statut_performance,
                   eqp.raison_suspension, eqp.frais_formation, eqp.date_suspension,
                   CASE WHEN COALESCE(eqp.score_performance, 50) < 10 THEN 'critique'
                        WHEN COALESCE(eqp.score_performance, 50) < 25 THEN 'attention_requise'
                        WHEN COALESCE(eqp.score_performance, 50) < 50 THEN 'amelioration_possible'
                        WHEN COALESCE(eqp.score_performance, 50) < 75 THEN 'satisfaisant'
                        ELSE 'excellent' END as categorie_performance
            FROM Employe e
            LEFT JOIN Employe_Qualite_Performance eqp ON e.id = eqp.id_employe
            LEFT JOIN contrat_employe ce ON e.id = ce.id_employe AND ce.id_statut_contrat = 1
            LEFT JOIN Poste p ON ce.id_poste = p.id
            LEFT JOIN departement d ON p.id_departement = d.id
            ORDER BY e.nom, e.prenom
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $employes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($employes as &$emp) {
            if ($emp['score_performance'] === null) {
                $insertQuery = "INSERT INTO Employe_Qualite_Performance (id_employe, score_performance, statut_employe) 
                               VALUES (?, 50, 'actif') ON DUPLICATE KEY UPDATE id_employe = id_employe";
                $insertStmt = $db->prepare($insertQuery);
                $insertStmt->execute([$emp['id_employe']]);
                $emp['score_performance'] = 50;
                $emp['statut_performance'] = 'actif';
            }
            
            $histQuery = "SELECT * FROM Historique_Remise_Formation_Employe WHERE id_employe = ? 
                         ORDER BY date_remise_formation DESC LIMIT 5";
            $histStmt = $db->prepare($histQuery);
            $histStmt->execute([$emp['id_employe']]);
            $emp['formations'] = $histStmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        Flight::render('rh/competence_employes', [
            'employes' => $employes,
            'page_title' => 'Qualité et Performance des Employés'
        ]);
    }

    public function detailEmploye($id_employe) {
        $db = Flight::db();
        
        $query = "
            SELECT e.id, e.nom, e.prenom, e.email, e.contact, e.cin, e.date_naissance, e.adresse, e.photo,
                   ce.id_poste, p.label as poste_nom, d.libelle as nom_departement, ce.date_debut as date_embauche,
                   COALESCE(eqp.score_performance, 50) as score_performance,
                   COALESCE(eqp.statut_employe, 'actif') as statut_employe,
                   eqp.raison_suspension, eqp.frais_formation, eqp.date_suspension
            FROM Employe e
            LEFT JOIN Employe_Qualite_Performance eqp ON e.id = eqp.id_employe
            LEFT JOIN contrat_employe ce ON e.id = ce.id_employe AND ce.id_statut_contrat = 1
            LEFT JOIN Poste p ON ce.id_poste = p.id
            LEFT JOIN departement d ON p.id_departement = d.id
            WHERE e.id = ? LIMIT 1
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id_employe]);
        $employe = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$employe) {
            Flight::redirect('/rh/competences/competence-employes?error=not_found');
            return;
        }

        if ($employe['score_performance'] === null) {
            $insertQuery = "INSERT INTO Employe_Qualite_Performance (id_employe, score_performance, statut_employe) 
                           VALUES (?, 50, 'actif') ON DUPLICATE KEY UPDATE id_employe = id_employe";
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->execute([$id_employe]);
            $employe['score_performance'] = 50;
            $employe['statut_employe'] = 'actif';
        }

        $competenceQuery = "
            SELECT ec.id, ec.id_competence, c.libelle as competence, c.libelle as competence_nom,
                   c.description as competence_description, ec.niveau as niveau_employe, ec.niveau,
                   nc.libelle as niveau_label, ec.date_acquisition
            FROM Employe_Competence ec
            JOIN Competence c ON ec.id_competence = c.id
            LEFT JOIN Niveau_Competence nc ON ec.niveau = nc.id
            WHERE ec.id_employe = ?
            ORDER BY c.libelle
        ";
        $competenceStmt = $db->prepare($competenceQuery);
        $competenceStmt->execute([$id_employe]);
        $competences = $competenceStmt->fetchAll(\PDO::FETCH_ASSOC);

        $historiqueQuery = "
            SELECT hrfe.id, hrfe.id_employe, hrfe.id_competence, c.libelle as competence_nom,
                   hrfe.ancien_score, hrfe.statut, hrfe.frais_formation,
                   hrfe.raison as raison_formation, hrfe.date_remise_formation as date_formation
            FROM Historique_Remise_Formation_Employe hrfe
            LEFT JOIN Competence c ON hrfe.id_competence = c.id
            WHERE hrfe.id_employe = ?
            ORDER BY hrfe.date_remise_formation DESC
        ";
        $historiqueStmt = $db->prepare($historiqueQuery);
        $historiqueStmt->execute([$id_employe]);
        $historique = $historiqueStmt->fetchAll(\PDO::FETCH_ASSOC);

        Flight::render('rh/detail_employe_competences', [
            'employe' => $employe,
            'competences' => $competences,
            'historique' => $historique,
            'page_title' => 'Compétences - ' . ($employe['prenom'] ?? 'N/A') . ' ' . ($employe['nom'] ?? 'N/A')
        ]);
    }

    public function updateScorePerformance($id_employe) {
        try {
            $db = Flight::db();
            $nouveau_score = isset($_POST['score_performance']) ? (int)$_POST['score_performance'] : null;
            
            if ($nouveau_score === null || $nouveau_score < 0 || $nouveau_score > 100) {
                Flight::json(['success' => false, 'error' => 'Score invalide (0-100)'], 400);
                return;
            }

            $nouveau_statut = $nouveau_score < 10 ? 'licencie' : ($nouveau_score < 25 ? 'suspendu_formation' : 'actif');

            $checkQuery = "SELECT id FROM Employe_Qualite_Performance WHERE id_employe = ?";
            $checkStmt = $db->prepare($checkQuery);
            $checkStmt->execute([$id_employe]);
            $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$exists) {
                $insertQuery = "INSERT INTO Employe_Qualite_Performance (id_employe, score_performance, statut_employe) 
                               VALUES (?, ?, ?)";
                $insertStmt = $db->prepare($insertQuery);
                $insertStmt->execute([$id_employe, $nouveau_score, $nouveau_statut]);
            } else {
                $updateQuery = "UPDATE Employe_Qualite_Performance SET score_performance = ?, statut_employe = ? 
                               WHERE id_employe = ?";
                $updateStmt = $db->prepare($updateQuery);
                $updateStmt->execute([$nouveau_score, $nouveau_statut, $id_employe]);
            }

            $verifyQuery = "SELECT score_performance, statut_employe FROM Employe_Qualite_Performance WHERE id_employe = ?";
            $verifyStmt = $db->prepare($verifyQuery);
            $verifyStmt->execute([$id_employe]);
            $result = $verifyStmt->fetch(PDO::FETCH_ASSOC);

            Flight::json([
                'success' => true,
                'nouveau_score' => (int)$result['score_performance'],
                'nouveau_statut' => $result['statut_employe'],
                'message' => 'Score mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            error_log("EXCEPTION updateScorePerformance: " . $e->getMessage());
            Flight::json(['success' => false, 'error' => 'Exception: ' . $e->getMessage()], 500);
        }
    }

    public function remettreEnFormation($id_employe) {
        try {
            $raison = Flight::request()->data->raison ?? 'Score de performance insuffisant';
            $frais = (float)(Flight::request()->data->frais_formation ?? 0);

            if ($this->competenceModel->suspendreEmployeFormation($id_employe, $raison, $frais)) {
                $db = Flight::db();
                $query = "INSERT INTO Historique_Remise_Formation_Employe 
                         (id_employe, id_competence, ancien_score, statut, frais_formation, raison, date_remise_formation)
                         SELECT ?, NULL, score_performance, 'proposee', ?, ?, NOW()
                         FROM Employe_Qualite_Performance WHERE id_employe = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$id_employe, $frais, $raison, $id_employe]);

                Flight::redirect('/rh/detail-employe/' . $id_employe . '?success=remise_formation');
            } else {
                Flight::redirect('/rh/detail-employe/' . $id_employe . '?error=update_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur remise formation: " . $e->getMessage());
            Flight::redirect('/rh/detail-employe/' . $id_employe . '?error=exception');
        }
    }

    public function reintegrerEmploye($id_employe) {
        try {
            if ($this->competenceModel->reintegrerEmploye($id_employe)) {
                Flight::redirect('/rh/competences-employes?success=employe_reintegre');
            } else {
                Flight::redirect('/rh/competences-employes?error=update_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur réintégration: " . $e->getMessage());
            Flight::redirect('/rh/competences-employes?error=exception');
        }
    }

    public function licencierEmploye($id_employe) {
        try {
            $raison = Flight::request()->data->raison ?? 'Performance insuffisante après formation';
            if ($this->competenceModel->licencierEmploye($id_employe, $raison)) {
                Flight::redirect('/rh/competences-employes?success=employe_licencie');
            } else {
                Flight::redirect('/rh/competences-employes?error=update_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur licenciement: " . $e->getMessage());
            Flight::redirect('/rh/competences-employes?error=exception');
        }
    }

    // ==================== STATISTIQUES ====================

    public function statistiques() {
        $db = Flight::db();
        $stats = $this->competenceModel->getStatsCompetences();
        
        // TOP 10 compétences
        $queryTopComp = "
            SELECT comp.id, comp.libelle as nom, comp.categorie, COUNT(DISTINCT pc.id_poste) as nb_postes,
                   (COUNT(DISTINCT pc.id_poste) * 100.0 / (SELECT COUNT(*) FROM Poste)) as couverture_pct
            FROM Competence comp
            JOIN Poste_Competence pc ON comp.id = pc.id_competence
            GROUP BY comp.id ORDER BY nb_postes DESC LIMIT 10
        ";
        $stmtTopComp = $db->prepare($queryTopComp);
        $stmtTopComp->execute();
        $stats['top_competences'] = $stmtTopComp->fetchAll(\PDO::FETCH_ASSOC);
        
        // Formations récentes
        $queryFormations = "
            SELECT ff.id as formulaire_id, CONCAT(c.prenom, ' ', c.nom) as candidat_nom,
                   ff.date_submission as date, ff.duree_heures as duree, ff.formateur,
                   ef.note_formation, ef.note_competence_finale, ef.decision
            FROM Formulaire_Formation ff
            JOIN Candidat_Mise_Formation cmf ON ff.id_candidat_mise_formation = cmf.id
            JOIN candidature c ON cmf.id_candidature = c.id
            LEFT JOIN Evaluation_Formation ef ON ff.id = ef.id_formulaire_formation
            ORDER BY ff.date_submission DESC LIMIT 10
        ";
        $stmtFormations = $db->prepare($queryFormations);
        $stmtFormations->execute();
        $stats['formations_recentes'] = $stmtFormations->fetchAll(\PDO::FETCH_ASSOC);
        
        // Employés faible performance
        $queryEmpFaible = "
            SELECT e.id, CONCAT(e.prenom, ' ', e.nom) as nom, p.label as poste,
                   COALESCE(eqp.score_performance, 50) as score, eqp.statut_employe
            FROM Employe e
            LEFT JOIN Employe_Qualite_Performance eqp ON e.id = eqp.id_employe
            LEFT JOIN contrat_employe ce ON e.id = ce.id_employe AND ce.id_statut_contrat = 1
            LEFT JOIN Poste p ON ce.id_poste = p.id
            WHERE COALESCE(eqp.score_performance, 50) < 25
            ORDER BY eqp.score_performance ASC LIMIT 20
        ";
        $stmtEmpFaible = $db->prepare($queryEmpFaible);
        $stmtEmpFaible->execute();
        $stats['employes_faible_performance'] = $stmtEmpFaible->fetchAll(\PDO::FETCH_ASSOC);

        Flight::render('rh/stats_competences', [
            'stats' => $stats,
            'page_title' => 'Statistiques Compétences'
        ]);
    }

    // ==================== API ====================

    public function getMatchingAPI($id_candidature) {
        $matching = $this->competenceModel->getMatchingDetail($id_candidature);
        Flight::json(['success' => true, 'data' => $matching]);
    }
    // ==================== AJOUT DANS CompetenceController.php ====================

/**
 * Afficher le formulaire de formation pré-rempli
 */
public function voirFormulaireFormation($id_candidature) {
    try {
        $candidature = $this->candidatureModel->getCandidatureById($id_candidature);
        
        if (!$candidature) {
            Flight::redirect('/entretiens?error=candidature_not_found');
            return;
        }

        // Récupérer la mise en formation
        $db = Flight::db();
        $query = "SELECT cmf.*, ff.id as formulaire_id, ff.*
                  FROM Candidat_Mise_Formation cmf
                  LEFT JOIN Formulaire_Formation ff ON cmf.id = ff.id_candidat_mise_formation
                  WHERE cmf.id_candidature = ? AND cmf.statut = 'en_formation'
                  ORDER BY cmf.date_debut_formation DESC LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_candidature]);
        $formation_data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$formation_data) {
            Flight::redirect('/entretiens?error=formation_not_found');
            return;
        }

        Flight::render('candidat/voir_formulaire_formation', [
            'candidature' => $candidature,
            'formation_data' => $formation_data,
            'page_title' => 'Formulaire de Formation'
        ]);
    } catch (\Exception $e) {
        error_log("Erreur affichage formulaire: " . $e->getMessage());
        Flight::redirect('/entretiens?error=formulaire_error');
    }
}

/**
 * Afficher le questionnaire formation
 */
public function afficherQuestionnaireFormation($id_formulaire) {
    try {
        $db = Flight::db();
        
        // Récupérer le formulaire et les infos candidat
        $query = "SELECT ff.*, cmf.id_candidature, c.nom, c.prenom, c.email
                  FROM Formulaire_Formation ff
                  JOIN Candidat_Mise_Formation cmf ON ff.id_candidat_mise_formation = cmf.id
                  JOIN candidature c ON cmf.id_candidature = c.id
                  WHERE ff.id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_formulaire]);
        $formulaire = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$formulaire) {
            Flight::redirect('/entretiens?error=formulaire_not_found');
            return;
        }

        // Vérifier si questionnaire déjà complété
        $checkQuery = "SELECT COUNT(*) as count FROM Questionnaire_Formation_Candidat 
                       WHERE id_formulaire_formation = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$id_formulaire]);
        $exists = $checkStmt->fetch(\PDO::FETCH_ASSOC);

        if ($exists['count'] > 0) {
            Flight::redirect('/entretiens?info=questionnaire_deja_complete');
            return;
        }

        Flight::render('candidat/questionnaire_formation', [
            'formulaire' => $formulaire,
            'page_title' => 'Questionnaire de Formation'
        ]);
    } catch (\Exception $e) {
        error_log("Erreur affichage questionnaire: " . $e->getMessage());
        Flight::redirect('/entretiens?error=questionnaire_error');
    }
}

/**
 * Soumettre le questionnaire formation
 */
public function submitQuestionnaireFormation() {
    try {
        $id_formulaire = Flight::request()->data->id_formulaire;
        
        // Récupérer l'ID candidature
        $db = Flight::db();
        $query = "SELECT cmf.id_candidature FROM Formulaire_Formation ff
                  JOIN Candidat_Mise_Formation cmf ON ff.id_candidat_mise_formation = cmf.id
                  WHERE ff.id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_formulaire]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            Flight::redirect('/entretiens?error=formulaire_not_found');
            return;
        }

        $id_candidature = $result['id_candidature'];

        // Insérer le questionnaire
        $insertQuery = "INSERT INTO Questionnaire_Formation_Candidat 
                        (id_formulaire_formation, id_candidature, q1_contenu_attentes, 
                         q2_qualite_pedagogique, q3_objectifs_atteints, q4_ameliorations, 
                         q5_formation_complementaire) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->execute([
            $id_formulaire,
            $id_candidature,
            Flight::request()->data->q_1,
            (int)Flight::request()->data->q_2,
            Flight::request()->data->q_3,
            Flight::request()->data->q_4 ?? null,
            Flight::request()->data->q_5
        ]);

        // Le trigger se charge de marquer qcm_envoye = TRUE
        Flight::redirect('/entretiens?success=questionnaire_soumis');
    } catch (\Exception $e) {
        error_log("Erreur soumission questionnaire: " . $e->getMessage());
        Flight::redirect('/entretiens?error=questionnaire_submission_failed');
    }
}

/**
 * RH : Voir les détails d'un questionnaire formation
 */
public function voirQuestionnaireFormationRH($id_formulaire) {
    try {
        $db = Flight::db();
        
        $query = "SELECT qfc.*, ff.*, c.nom, c.prenom, c.email, a.titre as annonce_titre
                  FROM Questionnaire_Formation_Candidat qfc
                  JOIN Formulaire_Formation ff ON qfc.id_formulaire_formation = ff.id
                  JOIN Candidat_Mise_Formation cmf ON ff.id_candidat_mise_formation = cmf.id
                  JOIN candidature c ON cmf.id_candidature = c.id
                  LEFT JOIN annonce_emploi a ON c.id_annonce = a.id
                  WHERE ff.id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id_formulaire]);
        $questionnaire = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$questionnaire) {
            Flight::redirect('/rh/qcm-evaluations?error=questionnaire_not_found');
            return;
        }

        Flight::render('rh/voir_questionnaire_formation', [
            'questionnaire' => $questionnaire,
            'page_title' => 'Détails Questionnaire Formation'
        ]);
    } catch (\Exception $e) {
        error_log("Erreur affichage questionnaire RH: " . $e->getMessage());
        Flight::redirect('/rh/qcm-evaluations?error=questionnaire_error');
    }
}

/**
 * RH : Afficher l'interface d'évaluation du questionnaire formation
 */
public function evaluerQuestionnaireFormation($id_candidature) {
    try {
        $db = Flight::db();
        
        // Récupérer les infos candidat
        $query = "
            SELECT c.id, c.nom, c.prenom, c.email, cand.id as id_candidature,
                   a.titre as annonce_titre
            FROM candidature cand
            JOIN candidat c ON cand.id_candidat = c.id
            LEFT JOIN annonce_emploi a ON cand.id_annonce = a.id
            WHERE cand.id = ?
        ";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id_candidature]);
        $candidat = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$candidat) {
            Flight::redirect('/rh/candidats-en-attente?error=candidat_not_found');
            return;
        }
        
        // Récupérer le dernier questionnaire depuis la base de données
        $db = Flight::db();
        $q = $db->prepare("SELECT qfc.*, ff.*, cmf.id as id_candidat_mise_formation, c.nom, c.prenom, c.email, a.titre as annonce_titre
                           FROM Questionnaire_Formation_Candidat qfc
                           JOIN Formulaire_Formation ff ON qfc.id_formulaire_formation = ff.id
                           JOIN Candidat_Mise_Formation cmf ON ff.id_candidat_mise_formation = cmf.id
                           JOIN candidature cand ON qfc.id_candidature = cand.id
                           JOIN candidat c ON cand.id_candidat = c.id
                           LEFT JOIN annonce_emploi a ON cand.id_annonce = a.id
                           WHERE qfc.id_candidature = ?
                           ORDER BY qfc.date_soumission DESC LIMIT 1");
        $q->execute([$id_candidature]);
        $questionnaire_row = $q->fetch(\PDO::FETCH_ASSOC);

        if (!$questionnaire_row) {
            error_log("ERROR: No questionnaire found in DB for candidature: " . $id_candidature);
            Flight::redirect('/rh/candidats-en-attente?error=questionnaire_not_found');
            return;
        }

        // Normaliser la structure attendue par la vue (answers peut être un tableau ou JSON)
        $questionnaire_data = [
            'id' => $questionnaire_row['id'],
            'id_formulaire_formation' => $questionnaire_row['id_formulaire_formation'] ?? $questionnaire_row['id_formulaire_formation'],
            'statut' => $questionnaire_row['statut'] ?? null,
            'date_soumission' => $questionnaire_row['date_soumission'] ?? null,
            'answers' => [
                'q_1' => $questionnaire_row['q1_contenu_attentes'] ?? null,
                'q_2' => $questionnaire_row['q2_qualite_pedagogique'] ?? null,
                'q_3' => $questionnaire_row['q3_objectifs_atteints'] ?? null,
                'q_4' => $questionnaire_row['q4_ameliorations'] ?? null,
                'q_5' => $questionnaire_row['q5_formation_complementaire'] ?? null,
            ]
        ];
        
        // Récupérer les postes pour affichage
        $query_postes = "SELECT id, label FROM Poste ORDER BY label";
        $result_postes = $db->query($query_postes);
        $postes = $result_postes->fetchAll(\PDO::FETCH_ASSOC);

        Flight::render('rh/evaluer_questionnaire_formation', [
            'candidat' => $candidat,
            'questionnaire' => $questionnaire_data,
            'postes' => $postes,
            'id_candidature' => $id_candidature,
            'page_title' => 'Évaluer Formation - ' . $candidat['prenom'] . ' ' . $candidat['nom']
        ]);
    } catch (\Exception $e) {
        error_log("Erreur affichage évaluation: " . $e->getMessage());
        Flight::redirect('/rh/candidats-en-attente?error=eval_error');
    }
}


/**
 * RH : Soumettre l'évaluation du questionnaire formation (noter et rediriger)
 */
public function evaluerQuestionnaireSubmit() {
    try {
        $req = Flight::request();
        $id_candidature = (int)($req->data->id_candidature ?? 0);
        $note_rh = (float)($req->data->note_rh ?? 0);
        $observations_rh = trim($req->data->observations_rh ?? '');
        
        error_log("=== EVALUATION SUBMIT DEBUG ===");
        error_log("id_candidature: " . $id_candidature);
        error_log("note_rh: " . $note_rh);
        
        if (!$id_candidature || $note_rh < 0 || $note_rh > 20) {
            error_log("ERROR: Invalid data!");
            Flight::redirect('/rh/candidats-en-attente?error=invalid_data');
            return;
        }
        
        $db = Flight::db();
        
        // Sauvegarder l'évaluation
        $evaluation_data = [
            'id_candidature' => $id_candidature,
            'note_rh' => $note_rh,
            'observations_rh' => $observations_rh,
            'evaluated_at' => date('c'),
            'evaluated_by_rh' => $_SESSION['user']['id'] ?? null
        ];
        
        $dir = realpath(__DIR__ . '/../../public') . '/uploads/evaluations_questionnaires';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        
        $file = $dir . '/evaluation_' . $id_candidature . '.json';
        file_put_contents($file, json_encode($evaluation_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        error_log("Evaluation saved to: " . $file);
        
        // Mettre à jour le statut de la candidature en BD
        $nouveau_statut = $note_rh > 10 ? 'accepte' : 'rejete';
        $update = $db->prepare("UPDATE candidature SET decision_finale = ?, date_decision = NOW() WHERE id = ?");
        $update->execute([$nouveau_statut, $id_candidature]);
        
        error_log("Candidature updated with decision: " . $nouveau_statut);
        
        // Mettre à jour Candidat_Mise_Formation
        $update_formation = $db->prepare("UPDATE Candidat_Mise_Formation SET statut = ? WHERE id_candidature = ?");
        $statut_formation = $note_rh > 10 ? 'terminee' : 'echouee';
        $update_formation->execute([$statut_formation, $id_candidature]);
        
        error_log("=== END DEBUG ===");
        
        // Rediriger selon la note
        if ($note_rh > 10) {
            Flight::redirect('/rh/candidatures-acceptees?success=formation_accepted&id=' . $id_candidature);
        } else {
            Flight::redirect('/rh/candidatures-rejetees?success=formation_rejected&id=' . $id_candidature);
        }
    } catch (\Exception $e) {
        error_log('EXCEPTION in evaluerQuestionnaireSubmit: ' . $e->getMessage());
        Flight::redirect('/rh/candidats-en-attente?error=eval_submission_failed');
    }
}
}