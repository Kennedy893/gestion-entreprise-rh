<?php

namespace app\models;

use PDO;

class ReferenceModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categorie ORDER BY libelle";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllDepartements() {
        $sql = "SELECT * FROM departement ORDER BY libelle";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTypeContrats() {
        $sql = "SELECT * FROM Type_Contrat ORDER BY label";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategorieById($id) {
        $sql = "SELECT * FROM categorie WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}