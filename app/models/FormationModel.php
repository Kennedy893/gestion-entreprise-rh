<?php

namespace app\models;

use PDO;

class FormationModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getLatestMiseByCandidature($id_candidature) {
        $stmt = $this->db->prepare("SELECT * FROM Candidat_Mise_Formation WHERE id_candidature = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$id_candidature]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ensureMiseEnFormation($id_candidature, $existing = null) {
        // If there's an existing row with statut 'acceptee', move to en_formation.
        if (!empty($existing) && isset($existing['id'])) {
            if ($existing['statut'] === 'acceptee') {
                $u = $this->db->prepare("UPDATE Candidat_Mise_Formation SET statut = 'en_formation', date_debut_formation = NOW() WHERE id = ?");
                $u->execute([$existing['id']]);
            }
            return $existing['id'];
        }

        // Otherwise create a new mise en formation
        $ins = $this->db->prepare("INSERT INTO Candidat_Mise_Formation (id_candidature, statut, date_mise_en_formation) VALUES (?, 'en_formation', NOW())");
        $ins->execute([$id_candidature]);
        return $this->db->lastInsertId();
    }

    public function createFormulaire($id_candidat_mise_formation, array $data) {
        $query = "INSERT INTO Formulaire_Formation 
            (id_candidat_mise_formation, contenu_formation, duree_heures, methodologie, formateur, date_debut_formation, date_fin_formation, resultats, observations, certificat)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $id_candidat_mise_formation,
            $data['contenu_formation'] ?? null,
            $data['duree_heures'] ?? null,
            $data['methodologie'] ?? null,
            $data['formateur'] ?? null,
            $data['date_debut_formation'] ?? null,
            $data['date_fin_formation'] ?? null,
            $data['resultats'] ?? null,
            $data['observations'] ?? null,
            $data['certificat'] ?? null,
        ]);

        return $this->db->lastInsertId();
    }

    public function createOrUpdateQuestionnaire($formulaire_id, $id_candidature, array $answers) {
        // Map expected keys
        $q1 = $answers['q_1'] ?? null;
        $q2 = isset($answers['q_2']) ? (int)$answers['q_2'] : null;
        $q3 = $answers['q_3'] ?? null;
        $q4 = $answers['q_4'] ?? null;
        $q5 = $answers['q_5'] ?? null;

        $check = $this->db->prepare("SELECT id FROM Questionnaire_Formation_Candidat WHERE id_formulaire_formation = ?");
        $check->execute([$formulaire_id]);
        $existing = $check->fetchColumn();

        if ($existing) {
            $upd = $this->db->prepare("UPDATE Questionnaire_Formation_Candidat SET q1_contenu_attentes = ?, q2_qualite_pedagogique = ?, q3_objectifs_atteints = ?, q4_ameliorations = ?, q5_formation_complementaire = ?, statut = 'soumis', date_soumission = NOW() WHERE id = ?");
            $upd->execute([$q1, $q2, $q3, $q4, $q5, $existing]);
            return $existing;
        }

        $ins = $this->db->prepare("INSERT INTO Questionnaire_Formation_Candidat (id_formulaire_formation, id_candidature, q1_contenu_attentes, q2_qualite_pedagogique, q3_objectifs_atteints, q4_ameliorations, q5_formation_complementaire, statut, date_soumission) VALUES (?, ?, ?, ?, ?, ?, ?, 'soumis', NOW())");
        $ins->execute([$formulaire_id, $id_candidature, $q1, $q2, $q3, $q4, $q5]);
        return $this->db->lastInsertId();
    }

    public function updateMiseStatut($id, $statut) {
        $stmt = $this->db->prepare("UPDATE Candidat_Mise_Formation SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }
}
