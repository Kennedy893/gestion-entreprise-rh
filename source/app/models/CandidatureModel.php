<?php

namespace app\models;

use PDO;

class CandidatureModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO candidature 
                (nom, prenom, email, telephone, date_naissance, adresse, experience_annees,
                qualifications, competences, dernier_diplome, etablissement, langue_parlee,
                demande_contrat_direct, statut, date_candidature, id_annonce, genre) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['nom'], $data['prenom'], $data['email'], $data['telephone'],
            $data['date_naissance'], $data['adresse'], $data['experience_annees'],
            $data['qualifications'], $data['competences'], $data['dernier_diplome'],
            $data['etablissement'], $data['langue_parlee'], $data['demande_contrat_direct'],
            'en_attente', date('Y-m-d'), $data['id_annonce'], $data['genre']
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    public function getAllCandidatures() {
        $sql = "SELECT * FROM v_candidatures_details ORDER BY date_candidature DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCandidatureById($id) {
        $sql = "SELECT * FROM v_candidatures_details WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCandidaturesByAnnonce($annonce_id) {
        $sql = "SELECT * FROM v_candidatures_details WHERE id_annonce = ? ORDER BY date_candidature DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$annonce_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function getCandidaturesManager($id_manager) {
        $sql = "SELECT 
                c.*,
                a.titre as annonce_titre,
                p.label as poste_nom,
                e.id as entretien_id,
                e.date_entretien as entretien_date,
                e.lieu as entretien_lieu,
                e.statut as entretien_statut,
                e.statut_publication,
                e.note_manager as entretien_note_manager
                FROM candidature c
                JOIN annonce_emploi a ON c.id_annonce = a.id
                JOIN Poste p ON a.id_poste = p.id
                LEFT JOIN entretien e ON c.id = e.id_candidature
                WHERE c.statut IN ('envoye_manager', 'entretien_planifie', 'entretien_termine')
                   OR e.id IS NOT NULL
                ORDER BY c.date_candidature DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchCandidatures($filters) {
        $sql = "SELECT * FROM v_candidatures_details WHERE 1=1";
        $params = [];

        // Filtre par nom
        if (!empty($filters['nom'])) {
            $sql .= " AND (nom LIKE ? OR prenom LIKE ?)";
            $params[] = "%{$filters['nom']}%";
            $params[] = "%{$filters['nom']}%";
        }

        // Filtre par poste
        if (!empty($filters['poste'])) {
            $sql .= " AND poste_nom LIKE ?";
            $params[] = "%{$filters['poste']}%";
        }

        // Filtre par statut
        if (!empty($filters['statut'])) {
            $sql .= " AND statut = ?";
            $params[] = $filters['statut'];
        }

        // Filtre par annonce
        if (!empty($filters['annonce'])) {
            $sql .= " AND id_annonce = ?";
            $params[] = $filters['annonce'];
        }

        // Filtre par expérience
        if (!empty($filters['experience'])) {
            switch($filters['experience']) {
                case '0-2':
                    $sql .= " AND experience_annees BETWEEN 0 AND 2";
                    break;
                case '3-5':
                    $sql .= " AND experience_annees BETWEEN 3 AND 5";
                    break;
                case '6-10':
                    $sql .= " AND experience_annees BETWEEN 6 AND 10";
                    break;
                case '10+':
                    $sql .= " AND experience_annees >= 10";
                    break;
            }
        }

        $sql .= " ORDER BY date_candidature DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatut($id, $statut) {
        $sql = "UPDATE candidature SET statut = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$statut, $id]);
    }

    public function envoyerVersManager($id, $id_manager) {
        $sql = "UPDATE candidature SET statut = 'envoye_manager', id_manager = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_manager, $id]);
    }

    public function updateDecision($id, $data) {
        $sql = "UPDATE candidature SET 
                decision_finale = ?, 
                type_contrat_accorde = ?, 
                date_debut_travail = ?,
                date_decision = NOW()
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['decision_finale'],
            $data['type_contrat_accorde'] ?? null,
            $data['date_debut_travail'] ?? null,
            $id
        ]);
    }

    public function getCandidaturesAcceptees() {
        $sql = "SELECT * FROM v_candidatures_details WHERE decision_finale = 'accepte' ORDER BY date_decision DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCandidaturesRejetees() {
        $sql = "SELECT * FROM v_candidatures_details WHERE decision_finale = 'rejete' ORDER BY date_decision DESC";
        $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        // Pour chaque candidature, ajouter info sur la dernière mise en formation et questionnaire (BD)
        foreach ($rows as &$r) {
            $cid = $r['id'] ?? null;
            if (!$cid) continue;

            // dernière mise en formation
            $stmt = $this->db->prepare("SELECT id, statut FROM Candidat_Mise_Formation WHERE id_candidature = ? ORDER BY id DESC LIMIT 1");
            $stmt->execute([$cid]);
            $mise = $stmt->fetch(PDO::FETCH_ASSOC);
            $r['id_candidat_mise_formation'] = $mise['id'] ?? null;
            $r['mise_statut'] = $mise['statut'] ?? null;

            // dernier questionnaire (par date_soumission)
            $q = $this->db->prepare("SELECT id, statut, date_soumission, id_formulaire_formation FROM Questionnaire_Formation_Candidat WHERE id_candidature = ? ORDER BY date_soumission DESC LIMIT 1");
            $q->execute([$cid]);
            $qrow = $q->fetch(PDO::FETCH_ASSOC);
            $r['questionnaire_exists'] = $qrow ? true : false;
            $r['questionnaire_statut'] = $qrow['statut'] ?? null;
            $r['questionnaire_id'] = $qrow['id'] ?? null;
            $r['questionnaire_date'] = $qrow['date_soumission'] ?? null;
            $r['questionnaire_formulaire_id'] = $qrow['id_formulaire_formation'] ?? null;
        }

        return $rows;
    }
}