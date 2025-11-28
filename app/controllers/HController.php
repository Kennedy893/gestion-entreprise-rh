<?php

namespace app\controllers;


use Flight;
use app\models\HModel;
use app\models\HpresenceModel;
class HController {

	public function __construct() {

	}

    public function into_presence()
    {
        $employees = Flight::HModel()->get_generalised("employe", "*", [], [], "ORDER BY nom ASC",[]);
        $data = [ 'employees' => $employees ];
        Flight::render('presence/pointage', $data);
    }
    public function insert_presence()
    {
        $date=Flight::request()->data->date;
        $type=Flight::request()->data->type;
        $heure=Flight::request()->data->heure;
        $ids = (array)(Flight::request()->data->employes ?? []);
        $valeurs = [];
        foreach($ids as $id)
        {
            $rep=[];
            $rep[]=$id;
            $rep[]=$date;
            $rep[]=$heure;
            if($type==2)
            {
                $rep[]=Flight::HpresenceModel()->get_salaire_heure($id,$date);
            }
            $valeurs[]=$rep;
        }

        $colonnes = ["id_employe","date_travail"];
        if($type == 1)
        {
            $colonnes[] = "entree";
            foreach($valeurs as $valeur)
            {
                if(Flight::HpresenceModel()->is_heure_supp($valeur[2],$valeur[0],$valeur[1]))
                {
                    try
                    {
                        $valeur[]=Flight::HpresenceModel()->condition_heure_supp($valeur[1],$valeur[0],0);
                    } catch (Exception $e)
                    {
                        Flight::redirect(constant('BASE_URL').'/time/presences?error='.$e->getMessage());
                    }
                }
                Flight::HModel()->insert_generalised("presence", $colonnes, $valeur);
            }
        }
        else if($type == 2)
        {
            $colonnes[] = "sortie";
            $colonnes[] = "montant";
            foreach($valeurs as $valeur)
            {
                $where_colonnes = ["id_employe", "date_travail"];
                $where_valeurs = [$valeur[0], $valeur[1]];
                $presence = Flight::HModel()->get_generalised("presence", "*", $where_colonnes, $where_valeurs,"AND sortie IS NULL AND montant IS NULL",[]);
                $heure_entree=$presence[0]['entree'];
                $heure_sortie=$valeur[2];

                $ferier=Flight::HpresenceModel()->is_ferier($date);
                $weekend=Flight::HpresenceModel()->is_weekend($date);
                $h_supplementaire=Flight::HpresenceModel()->h_supplementaire($heure_entree,$heure_sortie,$valeur[0],$date);
                $coeff=[$ferier,$weekend,$h_supplementaire];
                $mult=max($coeff);
                $duree=strtotime($heure_sortie) - strtotime($heure_entree);
                $valeur[3]=$valeur[3]*$mult*$duree/3600;

                if(Flight::HpresenceModel()->is_heure_supp($valeur[2],$valeur[0],$valeur[1]))
                {
                    try
                    {
                        $valeur[]=Flight::HpresenceModel()->condition_heure_supp($valeur[1],$valeur[0],$duree/3600);
                    } catch (Exception $e)
                    {
                        Flight::redirect(constant('BASE_URL').'/time/presences?error='.$e->getMessage());
                    }
                }
                Flight::HModel()->update_generalised("presence", $colonnes, $valeur, $where_colonnes, $where_valeurs,"AND sortie IS NULL AND montant IS NULL",[]);
            }
        }
        
        Flight::redirect(constant('BASE_URL').'/time/presences');
    }
    public function into_temp_general()
    {

        Flight::render('presence/tempGeneral');
    }
    public function into_timecards()
    {

        Flight::render('presence/tempDetails');
    }
    public function into_employees()
    {
        $employees = Flight::HModel()->get_generalised("employe", "*", [], [], "ORDER BY nom ASC",[]);
        $data = [ 'employees' => $employees ];
        Flight::render('presence/liste', $data);
    }
    public function into_releves()
    {
        $id_employe = Flight::request()->query->id_employe;
        $annee = Flight::request()->query->annee;
        $mois = Flight::request()->query->mois;
        $employe=Flight::HModel()->get_generalised("employe", "*", ["id"], [$id_employe], "",[]);
        $days=Flight::HpresenceModel()->jours_du_mois($annee,$mois);

        $presences = Flight::HModel()->get_generalised(
            "presence",
            "*",
            ["id_employe"],
            [$id_employe],
            "AND EXTRACT(YEAR FROM date_travail) = ?::int AND EXTRACT(MONTH FROM date_travail) = ?::int ORDER BY date_travail ASC",
            [$annee, $mois]
        );
        $retour=[];
        foreach ($days as $day)
        {
            $presence_jour=[];
            $montant=0;
            foreach($presences as $presence)
            {
                if($presence['date_travail']==$day['date'] && $presence['entree'] != null && $presence['sortie'] != null)
                {
                    $presence_jour[]=$presence;
                    $montant+=$presence['montant'];
                }
            }
            $heure_normale=0;
            $heure_supplementaire=0;
            $montant_normale=0;
            $montant_supplementaire=0;
            foreach($presence_jour as $presence)
            {
                $duree= (strtotime($presence['sortie']) - strtotime($presence['entree']))/3600;
                $salaire_normal=Flight::HpresenceModel()->get_salaire_heure($id_employe,$presence['date_travail'])*$duree;
                $montant=$presence['montant'];
                if($montant!=null)
                {
                    if($montant<=$salaire_normal)
                    {
                        $heure_normale+=$duree;
                        $montant_normale+=$montant;
                    }
                    else
                    {
                        $heure_supplementaire+=$duree;
                        $montant_supplementaire+=$montant;
                    }
                }

            }
            $retour[]= [
                'date' => $day['date'],
                'presence' => $presence_jour,
                'montant_total' => $montant,
                'heure_normale' => $heure_normale,
                'heure_supplementaire' => $heure_supplementaire,
                'montant_normale' => $montant_normale,
                'montant_supplementaire' => $montant_supplementaire
            ];
        }
        Flight::render('presence/releve',['retour'=> $retour, 
        'employe' => $employe, 
        'annee' => $annee, 
        'mois' => $mois]);
    }

}