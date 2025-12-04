<?php

namespace app\models;

use PDO;

class EntretienModel {
    
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO entretien (
                    date_entretien, 
                    lieu, 
                    statut, 
                    id_candidature, 
                    id_manager, 
                    id_rh
                ) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['date_entretien'],
            $data['lieu'],
            'planifie',
            $data['id_candidature'],
            $data['id_manager'] ?? null,
            $data['id_rh'] ?? null
        ]);
    }

    public function getAllEntretiens() {
        $sql = "SELECT e.*, 
                c.nom as candidat_nom, 
                c.prenom as candidat_prenom,
                c.email as candidat_email,
                c.telephone,
                c.experience_annees,
                c.qualifications,
                c.competences,
                a.titre as annonce_titre,
                p.label as poste_nom,
                e.statut_publication
                FROM entretien e
                JOIN candidature c ON e.id_candidature = c.id
                JOIN annonce_emploi a ON c.id_annonce = a.id
                JOIN Poste p ON a.id_poste = p.id
                ORDER BY e.date_entretien DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEntretienById($id) {
        $sql = "SELECT e.*, 
                c.nom as candidat_nom, 
                c.prenom as candidat_prenom,
                c.email as candidat_email,
                c.telephone,
                c.experience_annees,
                c.qualifications,
                c.competences,
                c.demande_contrat_direct,
                a.titre as annonce_titre,
                a.id_poste,
                p.label as poste_nom
                FROM entretien e
                JOIN candidature c ON e.id_candidature = c.id
                JOIN annonce_emploi a ON c.id_annonce = a.id
                JOIN Poste p ON a.id_poste = p.id
                WHERE e.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateNoteManager($id, $data) {
        $sql = "UPDATE entretien 
                SET note_manager = ?,
                    qualites_manager = ?,
                    defauts_manager = ?,
                    statut = 'termine'
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['note_manager'],
            $data['qualites'] ?? $data['qualites_manager'] ?? null,
            $data['defauts'] ?? $data['defauts_manager'] ?? null,
            $id
        ]);
    }

    public function updateNoteRH($id, $data) {
        // Récupérer l'entretien pour calculer la note finale
        $entretien = $this->getEntretienById($id);
        
        if (!$entretien) {
            return false;
        }

        $note_manager = $entretien['note_manager'];
        $note_rh = $data['note_rh'];
        $note_finale = ($note_manager + $note_rh) / 2;
        $notation_etoiles = round($note_finale / 4);

        $sql = "UPDATE entretien 
                SET note_rh = ?,
                    observation_rh = ?,
                    note_finale = ?,
                    notation_etoiles = ?,
                    decision = ?,
                    date_decision = NOW()
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $note_rh,
            $data['observation'] ?? null,
            $note_finale,
            $notation_etoiles,
            $data['decision'] ?? null,
            $id
        ]);
    }

    public function publierEntretien($id) {
        $sql = "UPDATE entretien 
                SET statut_publication = 'publie', 
                    visible_public = 1 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getEntretiensPublics() {
        $sql = "SELECT e.*, 
                c.nom as candidat_nom, 
                c.prenom as candidat_prenom,
                c.email as candidat_email,
                c.telephone,
                c.experience_annees,
                a.titre as annonce_titre,
                p.label as poste_nom,
                c.id as id_candidature,
                cmf.statut as statut_formation,
                cmf.id as id_mise_formation,
                c.decision_finale
                FROM entretien e
                JOIN candidature c ON e.id_candidature = c.id
                JOIN annonce_emploi a ON c.id_annonce = a.id
                JOIN Poste p ON a.id_poste = p.id
                LEFT JOIN Candidat_Mise_Formation cmf ON c.id = cmf.id_candidature AND cmf.id = (
                    SELECT MAX(id) FROM Candidat_Mise_Formation 
                    WHERE id_candidature = c.id
                )
                WHERE e.visible_public = 1 
                   OR e.statut_publication = 'publie'
                GROUP BY c.id, e.id
                ORDER BY e.date_entretien DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEntretiensByManager($id_manager) {
        $sql = "SELECT e.*, 
                c.nom as candidat_nom, 
                c.prenom as candidat_prenom,
                c.email as candidat_email,
                c.telephone,
                c.experience_annees,
                a.titre as annonce_titre,
                p.label as poste_nom
                FROM entretien e
                JOIN candidature c ON e.id_candidature = c.id
                JOIN annonce_emploi a ON c.id_annonce = a.id
                JOIN Poste p ON a.id_poste = p.id
                WHERE e.id_manager = ?
                ORDER BY e.date_entretien DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_manager]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEntretienByCandidature($id_candidature) {
        $sql = "SELECT * FROM entretien WHERE id_candidature = ? ORDER BY date_entretien DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_candidature]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteEntretien($id) {
        $sql = "DELETE FROM entretien WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}