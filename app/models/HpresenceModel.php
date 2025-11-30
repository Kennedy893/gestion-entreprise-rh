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
    function jours_du_mois(int $annee, int $mois): array 
    {
        $start = new DateTime(sprintf('%04d-%02d-01', $annee, $mois));
        $end   = (clone $start)->modify('first day of next month');
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        // 0=dimanche ... 6=samedi
        $jours = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];

        $result = [];
        foreach ($period as $d) {
            $result[] = [
                'date' => $d->format('Y-m-d'),
                'jour' => $jours[(int)$d->format('w')],
            ];
        }
        return $result;
    }
    public function get_semaine($date)
    {
        $dt = new DateTime($date);
        $startOfWeek = clone $dt;
        $startOfWeek->modify('monday this week');
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');
        $debut = $startOfWeek->format('Y-m-d');
        $fin   = $endOfWeek->format('Y-m-d');
        $retour=[$debut,$fin];
    }
    public function condition_heure_supp($date,$id_employe,$duree)
    {
        $duree_semaine=$this->duree_supp_semaine($date,$id_employe);
        $duree_semaine+=$duree;
        if($duree_semaine<=8)
        {
            return 1;
        }
        else if($duree_semaine>8 && $duree_semaine<=20)
        {
            return 2;
        }
        else
        {
             throw new Exception("Durée supplémentaire hors plage attendue");
        }
    }
    public function duree_supp_semaine($date,$id_employe)
    {
        $semaine = $this->get_semaine($date);
        $sql = "SELECT * FROM presence WHERE id_employe = ? AND date_travail BETWEEN ? AND ? ORDER BY date_travail ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_employe, $semaine[0], $semaine[1]]);
        $list=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $duree=0;

        foreach($list as $presence)
        {
            if($presence['entree'] != null && 
                $presence['sortie'] != null && 
                ($this->is_ferier($presence['date_travail'])>1 || 
                $this->is_weekend($presence['date_travail'])>1 ||
                $this->h_supplementaire($presence['entree'],$presence['sortie'],$id_employe,$presence['date_travail'])>1)
            )
            {
                $debut = strtotime($presence['entree']);
                $fin = strtotime($presence['sortie']);
                $duree += ($fin - $debut) / 3600;
            }
        }
        return $duree;
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
    public function is_heure_supp($heure,$id_employe,$date)
    {
        $config_poste = $this->get_config_poste($id_employe,$date);
        if(
            strtotime($heure)<strtotime($config_poste['entree']) ||
            strtotime($heure)>strtotime($config_poste['sortie'])
        )
        {
            return true;
        }
        return false;
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
            $h_supp=$this->condition_heure_supp($date,$id_employe,$duree);
            if($h_supp ==1)
            {
                return 1.3;
            }            
            else if($h_supp ==2)
            {
                return 1.5;
            }
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
    public function get_salaire_heure($id_employe, $date)
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

        return $retour;
    }


    public function get_plage_horaire($id_employe,$debut,$fin,$date)
    {
        $config_poste = $this->get_config_poste($id_employe,$date);
        $plage_horaire=[];
        if($config_poste==null)
        {
            throw new Exception("Aucun poste assigné pour cet employé à la date ".$date);
        }
        if(
            strtotime($debut) < strtotime($config_poste['entree']) &&
            strtotime($fin) < strtotime($config_poste['entree']) ||
            strtotime($debut) > strtotime($config_poste['sortie']) &&
            strtotime($fin) > strtotime($config_poste['sortie']) ||
            strtotime($debut) >= strtotime($config_poste['entree']) &&
            strtotime($fin) <= strtotime($config_poste['sortie'])
        )
        {
            $plage_horaire[]=['debut'=>$debut,'fin'=>$fin];
        }
        else if(
           strtotime($debut) < strtotime($config_poste['entree']) &&
            strtotime($fin) >= strtotime($config_poste['entree']) &&
            strtotime($fin) <= strtotime($config_poste['sortie'])
        )
        {
            $debut_2=strtotime($config_poste['entree']);
            $debut_2=$debut_2-60;
            $plage_horaire[]=['debut'=>$debut,'fin'=>date('H:i:s',$debut_2)];
            $plage_horaire[]=['debut'=>$config_poste['entree'],'fin'=>$fin];
        }
        else if(
            strtotime($debut) >= strtotime($config_poste['entree']) &&
            strtotime($debut) <= strtotime($config_poste['sortie']) &&
            strtotime($fin) > strtotime($config_poste['sortie'])
        )
        {
            $fin_1=strtotime($config_poste['sortie']);
            $fin_1=$fin_1-60;
            $fin_1=date('H:i:s',$fin_1);
            $plage_horaire[]=['debut'=>$debut,'fin'=>$fin_1];
            $plage_horaire[]=['debut'=>$config_poste['sortie'],'fin'=>$fin];
        }
        else if(
            strtotime($debut) < strtotime($config_poste['entree']) &&
            strtotime($fin) > strtotime($config_poste['sortie'])
        )
        {
            $fin_1=strtotime($config_poste['entree']);
            $fin_1=$fin_1-60;
            $plage_horaire[]=['debut'=>$debut,'fin'=>date('H:i:s',$fin_1)];

            $plage_horaire[]=['debut'=>$config_poste['entree'],'fin'=>$config_poste['sortie']];

            $debut_2=strtotime($config_poste['sortie']);
            $debut_2=$debut_2+60;
            $plage_horaire[]=['debut'=>date('H:i:s',$debut_2),'fin'=>$fin];
        }
        
        return $plage_horaire;
    }
  
    
}