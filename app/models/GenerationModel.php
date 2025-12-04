<?php

namespace app\models;

use PDO;

class GenerationModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }   

    public function getContratData($idEmploye) 
    {
        $sql = "SELECT * FROM vue_contrat_employe WHERE employe_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idEmploye]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
