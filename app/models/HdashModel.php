<?php

namespace app\models;

use Flight;
use PDO;
use DateTime;
use Exception;  
use DatePeriod;
use DateInterval;

class HdashModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function get_employe_departement_annee($id_departement,$annee)
    {
        $sql = "SELECT employe.* 
        FROM employe 
        JOIN Contrat_employe ON Contrat_employe.id_employe = employe.id 
        JOIN poste ON poste.id = Contrat_employe.id_poste 
        WHERE poste.id_departement = ? 
        AND EXTRACT(YEAR FROM Contrat_employe.date_debut) <= ?
        AND (Contrat_employe.date_fin IS NULL 
             OR EXTRACT(YEAR FROM Contrat_employe.date_fin) >= ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_departement,$annee,$annee]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_employe_poste_annee($id_poste,$annee)
    {
        $sql = "SELECT employe.* 
        FROM employe 
        JOIN Contrat_employe ON Contrat_employe.id_employe = employe.id 
        WHERE Contrat_employe.id_poste = ? 
        AND EXTRACT(YEAR FROM Contrat_employe.date_debut) <= ?
        AND (Contrat_employe.date_fin IS NULL 
             OR EXTRACT(YEAR FROM Contrat_employe.date_fin) >= ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_poste,$annee,$annee]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_employe_departement($id_departement,$mois,$annee)
    {
        $sql = "SELECT employe.* 
        FROM employe 
        JOIN Contrat_employe ON Contrat_employe.id_employe = employe.id 
        JOIN poste ON poste.id = Contrat_employe.id_poste 
        WHERE poste.id_departement = ? 
        AND EXTRACT(YEAR FROM Contrat_employe.date_debut) <= ? 
        AND EXTRACT(MONTH FROM Contrat_employe.date_debut) <= ?
        AND (Contrat_employe.date_fin IS NULL 
             OR (EXTRACT(YEAR FROM Contrat_employe.date_fin) >= ? 
                 AND EXTRACT(MONTH FROM Contrat_employe.date_fin) >= ?))";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_departement,$annee,$mois,$annee,$mois]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_sum_hours($mois,$annee,$id_departement)
    {
        $dep_emp=$this->get_employe_departement($id_departement,$mois,$annee);
        $retour=[];
        $retour['heures_normales']=0;
        $retour['hors-service']=0;
        $retour['week-end']=0;
        $retour['jours_feries']=0;
        foreach($dep_emp as $employe)
        {
            $hours=$this->get_hours($mois,$annee,$employe['id']);
            $retour['heures_normales']+=$hours['heures_normales'];
            $retour['hors-service']+=$hours['hors-service'];
            $retour['week-end']+=$hours['week-end'];
            $retour['jours_feries']+=$hours['jours_feries'];
        }
        return $retour;    
        
    }
    public function get_sum_notes($mois,$annee,$id_departement)
    {
        $retour=[];
        $retour['ponctualite']=0;
        $retour['gestion_temps']=0;
        $retour['productivite']=0;
        $dep_emp=$this->get_employe_departement($id_departement,$mois,$annee);
        $count=0;
        foreach($dep_emp as $employe)
        {
            $notes=$this->get_notes($mois,$annee,$employe['id']);
            foreach($notes as $note)
            {
                $retour['ponctualite']+=$note['ponctualite'];
                $retour['gestion_temps']+=$note['gestion_temps'];
                $retour['productivite']+=$note['productivite'];
                $count++;
            }
        }
        if($count>0)
        {
            $retour['ponctualite']=$retour['ponctualite'] / $count;
            $retour['gestion_temps']=$retour['gestion_temps'] / $count;
            $retour['productivite']=$retour['productivite'] / $count;
        }
        return $retour;
        
    }
    public function get_hours($mois,$annee,$id_employe)
    {
        $retour=[];
        $retour['heures_normales']=0;
        $retour['hors-service']=0;
        $retour['week-end']=0;
        $retour['jours_feries']=0;
        $days= Flight::HpresenceModel()->jours_du_mois($annee,$mois);
        $total_heures=0;
        foreach($days as $day)
        {
            $presences = Flight::HModel()->get_generalised(
                "presence",
                "*",
                ["id_employe","date_travail"],
                [$id_employe,$day['date']],
                "",
                []
            );
            foreach($presences as $presence)
            {
                $config_poste = Flight::HpresenceModel()->get_config_poste($id_employe,$day['date']);
                
                $montant=Flight::HpresenceModel()->get_salaire_heure($id_employe,$day['date']);
                $montant_gagne=$presence['montant'];
                $duree_travail=(strtotime($presence['sortie']) - strtotime($presence['entree']))/3600;
                $montant_heure=$montant_gagne / $duree_travail;
                $diff=$montant_heure/$montant;
                if($diff<=1)
                {
                    $retour['heures_normales']+= $duree_travail;
                }
                else if ($diff>1 && $diff <=1.5)
                {
                    $retour['hors-service']+= $duree_travail;
                }
                else if ($diff>1.5 && $diff <=2)
                {
                    $retour['week-end']+= $duree_travail;
                }
                else 
                {
                    $retour['jours_feries']+= $duree_travail;
                }
                
            }
        }
        return $retour;
    }
    public function get_postes_departement($id_departement)
    {
        $retour=Flight::HModel()->get_generalised(
            "poste",
            "*",
            ["id_departement"],
            [$id_departement],
            "",
            []
        );
        return $retour;
    }
    public function get_notes($mois,$annee,$id_employe)
    {
        $days= Flight::HpresenceModel()->jours_du_mois($annee,$mois);
        $notes=[];
        foreach($days as $day)
        {
            $presences = Flight::HModel()->get_generalised(
                "presence",
                "*",
                ["id_employe"],
                [$id_employe],
                "",
                []
            );
            
            if($presences==null)
            {
                $notes[]=[
                    'ponctualite'=>1,
                    'gestion_temps'=>1,
                    'productivite'=>1
                ];
            }
            else
            {
                $notes[]=[
                    'ponctualite'=>3,
                    'gestion_temps'=>3,
                    'productivite'=>3
                ];
            }
            
        }
        return $notes;
    }
    

}