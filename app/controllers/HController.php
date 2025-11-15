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
                $rep[]=Flight::HpresenceModel()->get_salaire_heure($id,$date,1);
            }
            $valeurs[]=$rep;
        }

        $colonnes = ["id_employe","date_travail"];
        if($type == 1)
        {
            $colonnes[] = "entree";
            foreach($valeurs as $valeur)
            {
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
                Flight::HModel()->update_generalised("presence", $colonnes, $valeur, $where_colonnes, $where_valeurs,"AND sortie IS NULL AND montant IS NULL",[]);
            }
        }
        
        Flight::redirect(constant('BASE_URL').'/time/presences');
    }
    public function into_timecards()
    {
        Flight::render('presence/tempGeneral');
    }
    public function into_releves()
    {
        Flight::render('presence/releve');
    }

}