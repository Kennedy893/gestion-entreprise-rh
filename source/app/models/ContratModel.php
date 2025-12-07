<?php

namespace app\models;

use PDO;

class ContratModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Crée un employé à partir d'une candidature
     */
    public function createEmployeFromCandidature($candidature_id) {
        // Récupérer les infos de la candidature
        $sql = "SELECT * FROM candidature WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$candidature_id]);
        $candidature = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$candidature) {
            return false;
        }
        
        // Vérifier si l'employé existe déjà (par email)
        $sql = "SELECT id FROM Employe WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$candidature['email']]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            return $existing['id'];
        }
        
        // Créer l'employé
        $sql = "INSERT INTO Employe (nom, prenom, contact, email, date_naissance, adresse, cin, genre) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $candidature['nom'],
            $candidature['prenom'],
            $candidature['telephone'],
            $candidature['email'],
            $candidature['date_naissance'],
            $candidature['adresse'],
            null, // CIN à remplir plus tard
            $candidature['genre']
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Crée un contrat pour un candidat accepté
     */
    public function createContrat($data) {
        $sql = "INSERT INTO contrat_employe 
                (date_debut, date_fin, duree, salaire, id_poste, id_employe, id_statut_contrat, id_type_contrat) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['date_debut'],
            $data['date_fin'] ?? null,
            $data['duree'] ?? null,
            $data['salaire'],
            $data['id_poste'],
            $data['id_employe'],
            $data['id_statut_contrat'] ?? 1, // 1 = actif par défaut
            $data['id_type_contrat']
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Récupère tous les contrats
     * CORRECTION: Requête directe sans vue pour éviter les problèmes
     */
    public function getAllContrats() {
        $sql = "SELECT 
                    ce.id,
                    ce.date_debut,
                    ce.date_fin,
                    ce.duree,
                    ce.salaire,
                    ce.id_poste,
                    ce.id_employe,
                    ce.id_statut_contrat,
                    ce.id_type_contrat,
                    e.nom as employe_nom,
                    e.prenom as employe_prenom,
                    e.email as employe_email,
                    e.contact as employe_contact,
                    p.label as poste_nom,
                    tc.label as type_contrat_nom,
                    sc.label as statut_contrat_nom
                FROM contrat_employe ce
                JOIN Employe e ON ce.id_employe = e.id
                JOIN Poste p ON ce.id_poste = p.id
                JOIN Type_Contrat tc ON ce.id_type_contrat = tc.id
                JOIN Statut_Contrat sc ON ce.id_statut_contrat = sc.id
                ORDER BY ce.date_debut DESC";
        
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un contrat par ID
     */
    public function getContratById($id) {
        $sql = "SELECT 
                    ce.*,
                    e.nom as employe_nom,
                    e.prenom as employe_prenom,
                    e.email as employe_email,
                    e.contact as employe_contact,
                    p.label as poste_nom,
                    tc.label as type_contrat_nom,
                    sc.label as statut_contrat_nom
                FROM contrat_employe ce
                JOIN Employe e ON ce.id_employe = e.id
                JOIN Poste p ON ce.id_poste = p.id
                JOIN Type_Contrat tc ON ce.id_type_contrat = tc.id
                JOIN Statut_Contrat sc ON ce.id_statut_contrat = sc.id
                WHERE ce.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le contrat d'un employé
     */
    public function getContratByEmployeId($employe_id) {
        $sql = "SELECT 
                    ce.*,
                    p.label as poste_nom,
                    tc.label as type_contrat_nom,
                    sc.label as statut_contrat_nom
                FROM contrat_employe ce
                JOIN Poste p ON ce.id_poste = p.id
                JOIN Type_Contrat tc ON ce.id_type_contrat = tc.id
                JOIN Statut_Contrat sc ON ce.id_statut_contrat = sc.id
                WHERE ce.id_employe = ? 
                ORDER BY ce.date_debut DESC 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$employe_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour un contrat
     */
    public function updateContrat($id, $data) {
        $sql = "UPDATE contrat_employe SET 
                date_debut = ?, 
                date_fin = ?, 
                duree = ?, 
                salaire = ?,
                id_statut_contrat = ?,
                id_type_contrat = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['date_debut'],
            $data['date_fin'] ?? null,
            $data['duree'] ?? null,
            $data['salaire'],
            $data['id_statut_contrat'],
            $data['id_type_contrat'],
            $id
        ]);
    }

    /**
     * Termine un contrat
     */
    public function terminerContrat($id, $date_fin) {
        $sql = "UPDATE contrat_employe SET date_fin = ?, id_statut_contrat = 3 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$date_fin, $id]);
    }

    /**
     * Récupère tous les employés
     */
    public function getAllEmployes() {
        $sql = "SELECT DISTINCT 
                    e.*,
                    ce.id as contrat_id,
                    ce.date_debut as contrat_debut,
                    ce.salaire,
                    p.label as poste_nom,
                    tc.label as type_contrat,
                    sc.label as statut_contrat
                FROM Employe e
                LEFT JOIN contrat_employe ce ON e.id = ce.id_employe
                LEFT JOIN Poste p ON ce.id_poste = p.id
                LEFT JOIN Type_Contrat tc ON ce.id_type_contrat = tc.id
                LEFT JOIN Statut_Contrat sc ON ce.id_statut_contrat = sc.id
                WHERE ce.id IS NOT NULL
                ORDER BY e.nom, e.prenom";
        
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si un contrat existe pour une candidature
     */
    public function contratExistsPourCandidature($candidature_id) {
        $sql = "SELECT ce.id 
                FROM contrat_employe ce
                JOIN Employe e ON ce.id_employe = e.id
                JOIN candidature c ON e.email = c.email
                WHERE c.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$candidature_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}