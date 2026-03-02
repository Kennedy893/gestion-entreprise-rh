<?php

namespace app\models;

use PDO;

class DocumentCandidatureModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function add($data) {
        $sql = "INSERT INTO document_candidature 
                (type_document, chemin_fichier, nom_original, taille, id_candidature) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['type_document'],
            $data['chemin_fichier'],
            $data['nom_original'],
            $data['taille'],
            $data['id_candidature']
        ]);
    }

    public function getDocumentsByCandidature($candidature_id) {
        $sql = "SELECT * FROM document_candidature WHERE id_candidature = ? ORDER BY date_upload DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$candidature_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteDocument($id) {
        $sql = "DELETE FROM document_candidature WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}