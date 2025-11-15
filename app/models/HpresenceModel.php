<?php

namespace app\models;

use Flight;
use PDO;

class HpresenceModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function get_salaire_heure($id_employe, $date)
    {
        $salaire=Flight::HModel()->get_generalised("contrat_employe", 
        "salaire", 
        ["id_employe"], 
        [$id_employe], 
        "AND (date_debut IS NULL AND (date_fin IS NULL OR date_fin>= ? )) OR (date_debut <= ? AND (date_fin >= ? OR date_fin IS NULL)) ORDER BY date_debut DESC LIMIT 1", 
        [$date,$date,$date]);
        return $salaire[0]['salaire'] ?? 0;
    }

}