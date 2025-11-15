<?php

namespace app\controllers;


use Flight;
use app\models\HModel;
class HController {

	public function __construct() {

	}

    public function into_presence()
    {
        $employees = Flight::HModel()->get_generalised("employe", "*", [], [], "ORDER BY nom ASC");
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
                $rep[]=0;
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