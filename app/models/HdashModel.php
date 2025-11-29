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
                if(count($presences)>2)
                {
                    $note_temps['flux']=$note_temps['flux']-(count($presences)-2)/2;
                }
                for($i=0 ; $i<count($presences) ; $i++)
                {
                    $duree_travail+=(strtotime($presences[$i]['sortie']) - strtotime($presences[$i]['entree']))/60;

                }
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
            }
            
        }
    }
    

}