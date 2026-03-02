<?php

namespace app\models;

use PDO;

class CompetenceModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // ==================== COMPÉTENCES ====================

    /**
     * Récupère toutes les compétences
     */
    public function getAllCompetences() {
        $query = "SELECT * FROM Competence ORDER BY libelle";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une compétence par ID
     */
    public function getCompetenceById($id) {
        $query = "SELECT * FROM Competence WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle compétence
     */
    public function createCompetence($data) {
        $query = "INSERT INTO Competence (libelle, description, categorie) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['libelle'],
            $data['description'] ?? null,
            $data['categorie'] ?? null
        ]);
    }

    /**
     * Récupère les niveaux de compétence
     */
    public function getNiveauxCompetence() {
        $query = "SELECT * FROM Niveau_Competence ORDER BY id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================== POSTE COMPETENCE ====================

    /**
     * Récupère les compétences requises pour un poste
     */
    public function getCompetencesPoste($id_poste) {
        $query = "SELECT * FROM v_poste_competence_detail WHERE id_poste = ? ORDER BY importance DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_poste]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Associe une compétence à un poste
     */
    public function associerCompetencePoste($data) {
        $query = "INSERT INTO Poste_Competence (id_poste, id_competence, niveau_requis, importance) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['id_poste'],
            $data['id_competence'],
            $data['niveau_requis'],
            $data['importance'] ?? 5
        ]);
    }

    /**
     * Supprime une association compétence-poste
     */
    public function supprimerCompetencePoste($id_poste, $id_competence) {
        $query = "DELETE FROM Poste_Competence WHERE id_poste = ? AND id_competence = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_poste, $id_competence]);
    }

    // ==================== CANDIDAT COMPETENCE ====================

    /**
     * Ajoute une compétence à un candidat
     */
    public function addCompetenceCandidat($id_candidature, $id_competence, $niveau = null) {
        $query = "INSERT INTO Candidat_Competence (id_candidature, id_competence, niveau_declare) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE niveau_declare = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_candidature, $id_competence, $niveau, $niveau]);
    }

    /**
     * Évalue une compétence du candidat
     */
    public function evaluerCompetenceCandidat($id_candidature, $id_competence, $niveau_evalue) {
        $query = "UPDATE Candidat_Competence 
                  SET niveau_evalue = ?, date_evaluation = NOW() 
                  WHERE id_candidature = ? AND id_competence = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$niveau_evalue, $id_candidature, $id_competence]);
    }

    /**
     * Récupère les compétences d'un candidat
     */
    public function getCompetencesCandidat($id_candidature) {
        $query = "SELECT cc.*, comp.libelle, comp.description, nc.libelle as niveau_label
                  FROM Candidat_Competence cc
                  JOIN Competence comp ON cc.id_competence = comp.id
                  LEFT JOIN Niveau_Competence nc ON cc.niveau_evalue = nc.id
                  WHERE cc.id_candidature = ?
                  ORDER BY comp.libelle";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_candidature]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================== MATCHING ====================

    /**
     * Calcule le score de matching entre candidat et poste
     */
    public function getScoreMatching($id_candidature) {
        $query = "SELECT * FROM v_candidature_score_matching WHERE id_candidature = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_candidature]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le matching détaillé (compétence par compétence)
     */
    public function getMatchingDetail($id_candidature) {
        $query = "SELECT * FROM v_candidature_competence_matching WHERE id_candidature = ? 
                  ORDER BY importance DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_candidature]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les compétences déficitaires
     */
    public function getCompetencesDeficitaires($id_candidature) {
        $query = "SELECT * FROM v_candidature_competences_deficitaires WHERE id_candidature = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_candidature]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================== MISE EN FORMATION ====================

    /**
     * Crée une mise en formation pour un candidat
     */
    public function creerMiseEnFormation($data) {
        $query = "INSERT INTO Candidat_Mise_Formation 
                  (id_candidature, id_competence_deficitaire, niveau_requis, niveau_candidat, 
                   statut, raison, date_mise_en_formation) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['id_candidature'],
            $data['id_competence_deficitaire'],
            $data['niveau_requis'],
            $data['niveau_candidat'],
            $data['statut'] ?? 'proposee',
            $data['raison'] ?? null
        ]);
    }

    /**
     * Récupère les candidats en mise en formation
     */
    public function getCandidatsMiseEnFormation() {
        $query = "SELECT * FROM v_candidats_mise_en_formation ORDER BY date_mise_en_formation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le statut d'une mise en formation
     */
    public function updateMiseEnFormation($id, $statut) {
        $query = "UPDATE Candidat_Mise_Formation SET statut = ?";
        $params = [$statut];
        
        if ($statut === 'en_formation') {
            $query .= ", date_debut_formation = NOW()";
        } elseif ($statut === 'terminee' || $statut === 'echouee') {
            $query .= ", date_fin_formation = NOW()";
        }
        
        $query .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    // ==================== FORMULAIRE FORMATION ====================

    /**
     * Crée un formulaire de formation
     */
    public function createFormationForm($data) {
        $query = "INSERT INTO Formulaire_Formation 
                  (id_candidat_mise_formation, id_formateur, duree_heures, contenu_formation, 
                   methodes_enseignement, evaluations_intermediaires, date_debut, date_fin) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['id_candidat_mise_formation'],
            $data['id_formateur'] ?? null,
            $data['duree_heures'] ?? 0,
            $data['contenu_formation'] ?? null,
            $data['methodes_enseignement'] ?? null,
            $data['evaluations_intermediaires'] ?? null,
            $data['date_debut'] ?? null,
            $data['date_fin'] ?? null
        ]);
    }

    /**
     * Récupère un formulaire de formation
     */
    public function getFormationForm($id) {
        $query = "SELECT * FROM Formulaire_Formation WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les formations avec évaluations
     */
    public function getFormationsDetail() {
        $query = "SELECT * FROM v_formations_candidats_detail ORDER BY date_submission DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================== ÉVALUATION FORMATION ====================

    /**
     * Crée une évaluation de formation
     */
    public function createEvaluationFormation($data) {
        $query = "INSERT INTO Evaluation_Formation 
                  (id_formulaire_formation, id_rh, note_formation, note_competence_finale, 
                   observations, decision) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['id_formulaire_formation'],
            $data['id_rh'],
            $data['note_formation'],
            $data['note_competence_finale'],
            $data['observations'] ?? null,
            $data['decision'] ?? 'rejete'
        ]);
    }

    /**
     * Met à jour l'évaluation d'une formation
     */
    public function updateEvaluationFormation($id, $data) {
        $query = "UPDATE Evaluation_Formation 
                  SET note_formation = ?, note_competence_finale = ?, 
                      observations = ?, decision = ?, date_evaluation = NOW()
                  WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['note_formation'],
            $data['note_competence_finale'],
            $data['observations'] ?? null,
            $data['decision'],
            $id
        ]);
    }

    // ==================== EMPLOYÉ COMPÉTENCE ====================

    /**
     * Ajoute une compétence à un employé
     */
    public function addCompetenceEmploye($id_employe, $id_competence, $niveau) {
        $query = "INSERT INTO Employe_Competence 
                  (id_employe, id_competence, niveau, date_acquisition) 
                  VALUES (?, ?, ?, NOW()) 
                  ON DUPLICATE KEY UPDATE niveau = ?, date_acquisition = NOW()";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_employe, $id_competence, $niveau, $niveau]);
    }

    /**
     * Récupère les compétences d'un employé
     */
    public function getCompetencesEmploye($id_employe) {
        $query = "SELECT * FROM v_employe_competence_detail WHERE id_employe = ? 
                  ORDER BY competence";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_employe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================== PERFORMANCE EMPLOYÉ ====================

    /**
     * Récupère ou crée la performance d'un employé
     */
    public function getOrCreatePerformance($id_employe) {
        $query = "SELECT * FROM Employe_Qualite_Performance WHERE id_employe = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_employe]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $insert = "INSERT INTO Employe_Qualite_Performance (id_employe, score_performance) 
                      VALUES (?, 50)";
            $insertStmt = $this->db->prepare($insert);
            $insertStmt->execute([$id_employe]);
            return $this->getOrCreatePerformance($id_employe);
        }

        return $result;
    }

    /**
     * Met à jour le score de performance d'un employé
     */
    public function updateScorePerformance($id_employe, $score, $statut = null) {
        $query = "UPDATE Employe_Qualite_Performance 
                  SET score_performance = ?";
        $params = [$score];

        if ($statut) {
            $query .= ", statut_employe = ?";
            $params[] = $statut;
        }

        $query .= " WHERE id_employe = ?";
        $params[] = $id_employe;

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Récupère tous les employés avec performance
     */
    public function getAllEmployesPerformance() {
        $query = "SELECT * FROM v_employes_performance ORDER BY nom_employe";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Suspend un employé pour formation
     */
    public function suspendreEmployeFormation($id_employe, $raison, $frais = 0) {
        $query = "UPDATE Employe_Qualite_Performance 
                  SET statut_employe = 'suspendu_formation', 
                      raison_suspension = ?,
                      frais_formation = ?,
                      date_suspension = NOW()
                  WHERE id_employe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$raison, $frais, $id_employe]);
    }

    /**
     * Réintègre un employé (fin de formation)
     */
    public function reintegrerEmploye($id_employe) {
        $query = "UPDATE Employe_Qualite_Performance 
                  SET statut_employe = 'actif', score_performance = 50
                  WHERE id_employe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_employe]);
    }

    /**
     * Licencie un employé
     */
    public function licencierEmploye($id_employe, $raison) {
        $query = "UPDATE Employe_Qualite_Performance 
                  SET statut_employe = 'licencie', raison_suspension = ?
                  WHERE id_employe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$raison, $id_employe]);
    }

    // ==================== STATISTIQUES ====================

    /**
     * Récupère les statistiques globales
     */
    public function getStatsCompetences() {
        $query = "SELECT * FROM v_stats_competences";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
