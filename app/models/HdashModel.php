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
                if($config_poste!=null)
                {
                    $montant=Flight::HpresenceModel()->get_salaire_heure($id_employe,$day['date']);
                    $montant_gagne=$presence['montant'];
                    $duree_travail=(strtotime($presence['sortie']) - strtotime($presence['entree']))/3600;
                    $montant_heure=$montant_gagne / $duree_travail;
                    $diff=$montant_gagne/$montant;
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
                ["id_employe","date_travail"],
                [$id_employe,$day['date']],
                "",
                []
            );
            if($presences==null)
            {
                $notes[]=[
                    'ponctualite'=>0,
                    'gestion_temps'=>0,
                    'productivite'=>0
                ];
            }
            else
            {
                $heure_arrivee=strtotime($presences[0]['entree']);                
                $config_poste = Flight::HpresenceModel()->get_config_poste($id_employe,$day['date']);
                $heure_poste_arrivee=strtotime($config_poste['entree']);
                $ponctualite=5;
                $gestion_temps=5;
                $productivite=5;
                if($heure_arrivee > $heure_poste_arrivee)
                {
                    $diff=($heure_arrivee - $heure_poste_arrivee)/60;
                    if($diff <=15)
                    {
                        $ponctualite=4;
                    }
                    else if($diff >15 && $diff <=30)
                    {
                        $ponctualite=3;
                    }
                    else if($diff >30 && $diff <=60)
                    {
                        $ponctualite=2;
                    }
                    else
                    {
                        $ponctualite=1;
                    }
                }
                $duree_travail=0;
                $note_temps=[];
                $note_temps['flux']=5;
                $note_temps['time']=5;
                if(count($presences)>2)
                {
                    $note_temps['flux']=$note_temps['flux']-(count($presences)-2)/2;
                }
                for($i=0 ; $i<count($presences) ; $i++)
                {
                    $duree_travail+=(strtotime($presences[$i]['sortie']) - strtotime($presences[$i]['entree']))/60;
                    $norme_sortie=strtotime($config_poste['sortie'])+(3600*3);
                    if(strtotime($presences[$i]['sortie'])>= $norme_sortie)
                    {
                        $difference_sortie=(strtotime($presences[$i]['sortie']) - $norme_sortie)/3600;
                        $note_temps['time']=$note_temps['time']-$difference_sortie;
                    }

                }
                $gestion_temps=($note_temps['flux'] + $note_temps['time'])/2;
                $duree_poste=(strtotime($config_poste['duree_travail']))*60;
                if($duree_travail >= $duree_poste)
                {
                    $productivite=5;
                }
                else
                {
                    $ratio=$duree_travail / $duree_poste;
                    if($ratio >=0.9)
                    {
                        $productivite=4;
                    }
                    else if($ratio >=0.75)
                    {
                        $productivite=3;
                    }
                    else if($ratio >=0.5)
                    {
                        $productivite=2;
                    }
                    else
                    {
                        $productivite=1;
                    }
                }
                $note[]=[
                    'ponctualite'=>$ponctualite,
                    'gestion_temps'=>$gestion_temps,
                    'productivite'=>$productivite
                ];
            }
            
        }
        return $notes;
    }
    

}