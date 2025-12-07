<?php

namespace app\models;

use PDO;

class PosteModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllPostes() {
        $sql = "SELECT p.*, c.libelle as categorie_nom, d.libelle as departement_nom
                FROM Poste p
                JOIN categorie c ON p.id_categorie = c.id
                JOIN departement d ON p.id_departement = d.id
                ORDER BY c.libelle, p.label";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPosteById($id) {
        $sql = "SELECT p.*, c.libelle as categorie_nom, d.libelle as departement_nom,
                cp.duree_travail, cp.entree, cp.sortie
                FROM Poste p
                JOIN categorie c ON p.id_categorie = c.id
                JOIN departement d ON p.id_departement = d.id
                LEFT JOIN config_poste cp ON p.id = cp.id_poste
                WHERE p.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPostesByCategorie($categorie_id) {
        $sql = "SELECT * FROM Poste WHERE id_categorie = ? ORDER BY label";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categorie_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}