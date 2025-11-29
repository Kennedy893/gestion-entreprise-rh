<?php

namespace app\models;

use Flight;
use PDO;
use DateTime;
use Exception;  
use DatePeriod;
use DateInterval;

class HpresenceModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getPrediction($id_employer,$jour){
        $data = Flight::HdashModel()->get_note_jours($jour,$id_employer);
    }
}

