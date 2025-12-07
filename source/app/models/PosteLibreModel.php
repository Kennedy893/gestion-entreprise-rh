<?php

namespace app\models;

use PDO;

class PosteLibreModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupère tous les postes avec leur disponibilité
     */
    public function getAllPostesLibres() {
        $sql = "SELECT * FROM v_postes_libres ORDER BY categorie_nom, label";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un poste avec sa disponibilité
     */
    public function getPosteLibreById($id) {
        $sql = "SELECT * FROM v_postes_libres WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si un poste a des places disponibles
     */
    public function hasPlacesDisponibles($id_poste) {
        $sql = "SELECT postes_disponibles FROM v_postes_libres WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_poste]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['postes_disponibles'] > 0;
    }

    /**
     * Récupère les statistiques globales des postes
     */
    public function getStatsPostes() {
        $sql = "SELECT * FROM v_stats_postes";
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau poste
     */
    public function createPoste($data) {
        $sql = "INSERT INTO Poste (label, valeur, id_categorie, id_departement) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['label'],
            $data['valeur'],
            $data['id_categorie'],
            $data['id_departement']
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Met à jour un poste
     */
    public function updatePoste($id, $data) {
        $sql = "UPDATE Poste SET 
                label = ?, 
                valeur = ?, 
                id_categorie = ?, 
                id_departement = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['label'],
            $data['valeur'],
            $data['id_categorie'],
            $data['id_departement'],
            $id
        ]);
    }

    /**
     * Supprime un poste (si possible)
     */
    public function deletePoste($id) {
        // Vérifier d'abord s'il n'y a pas d'annonces ou de contrats liés
        $sql = "SELECT COUNT(*) as count FROM annonce_emploi WHERE id_poste = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $annonces = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        $sql = "SELECT COUNT(*) as count FROM contrat_employe WHERE id_poste = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $contrats = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($annonces > 0 || $contrats > 0) {
            return false; // Ne peut pas supprimer
        }
        
        $sql = "DELETE FROM Poste WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Augmente le nombre de postes disponibles
     */
    public function augmenterCapacite($id, $nombre) {
        $sql = "UPDATE Poste SET valeur = valeur + ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $id]);
    }

    /**
     * Récupère les postes disponibles pour créer une annonce
     */
    public function getPostesDisponiblesPourAnnonce() {
        $sql = "SELECT * FROM v_postes_libres 
                WHERE postes_disponibles > 0 
                ORDER BY categorie_nom, label";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}