<?php

namespace app\models;

use Flight;
use PDO;
use DateTime;

class HpresenceModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function is_ferier($date)
    {
        $dt   = DateTime::createFromFormat('Y-m-d', $date);
        $mois = (int)$dt->format('m'); // 1..12
        $jour = (int)$dt->format('d'); // 1..31
        $ferier=Flight::HModel()->get_generalised("conge","*",["mois","jour"],[$mois,$jour],"",[]);
        if(count($ferier)<1)
        {
            return 1;
        }
        return 3;
    }
    public function is_weekend($date)
    {
        $dt   = DateTime::createFromFormat('Y-m-d', $date);
        $isSunday = $dt && $dt->format('N') === '7';
        if($isSunday)
        {
            return 2;
        }
        return 1;
    }
    public function h_supplementaire($heure_debut,$heure_fin,$id_employe,$date)
    {
        $duree=strtotime($heure_fin) - strtotime($heure_debut);
        $duree=$duree/3600;
        $config_poste = $this->get_config_poste($id_employe,$date);
        if(
            strtotime($heure_debut)<strtotime($config_poste['entree']) &&
            strtotime($heure_fin)<strtotime($config_poste['entree']) ||
            strtotime($heure_debut)>strtotime($config_poste['sortie']) &&
            strtotime($heure_fin)>strtotime($config_poste['sortie'])
        )
        {
            return 1.3;
        }
        return 1;
    }
    public function get_config_poste($id_employe,$date)
    {
        $sql="SELECT * FROM config_poste cp JOIN contrat_employe ce ON ce.id_poste=cp.id_poste
        WHERE ce.id_employe= ? AND
       (date_debut IS NULL AND (date_fin IS NULL OR date_fin>= ? )) OR 
       (date_debut <= ? AND (date_fin >= ? OR date_fin IS NULL)) 
       ORDER BY date_debut DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_employe,$date,$date,$date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function get_salaire_heure($id_employe, $date , $option)
    {
        $config_poste = $this->get_config_poste($id_employe, $date);
        $pourcentage=[1,1.2,1.5,2];
        $salaire=Flight::HModel()->get_generalised("contrat_employe", 
        "salaire", 
        ["id_employe"], 
        [$id_employe], 
        "AND (date_debut IS NULL AND (date_fin IS NULL OR date_fin>= ? )) OR (date_debut <= ? AND (date_fin >= ? OR date_fin IS NULL)) ORDER BY date_debut DESC LIMIT 1", 
        [$date,$date,$date]);
        $retour=$salaire[0]['salaire'] ?? 0;
        $retour=$retour/$config_poste['duree_travail']/30;
        $retour=$retour*$pourcentage[$option-1];

        return $retour;
    }


}