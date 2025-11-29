<?php

namespace app\controllers;
use app\helpers\helpers;
use Flight;
use Exception;
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

    public function insert_entree($date,$id,$heure)
    {
        try
        {
            $rep = [];
            $rep=[];
            $rep[]=$id;
            $rep[]=$date;
            $rep[]=$heure;

            $colonnes = ["id_employe","date_travail"];
            $colonnes[] = "entree";
            if(Flight::HpresenceModel()->is_heure_supp($rep[2],$rep[0],$rep[1]))
            {
                try
                {
                    $test_heure_supp=Flight::HpresenceModel()->condition_heure_supp($rep[1],$rep[0],0);
                } catch (Exception $e)
                {
                    Flight::redirect(constant('BASE_URL').'/time/presences?error='.$e->getMessage());
                }
            }
            Flight::HModel()->insert_generalised("presence", $colonnes, $rep);
        }
        catch (Exception $e)
        {
            Flight::redirect(constant('BASE_URL').'/time/presences?error=Erreur lors de l\'insertion de l\'entrée: '.$e->getMessage());
        }
        
    }

    public function insert_sortie($date,$id,$heure)
    {
        try
        {
            $rep=[];
            $rep[]=$id;                                 // 0: id_employe
            $rep[]=$date;                               // 1: date_travail
            $rep[]=$heure;                              // 2: sortie (segment cible)
            $rep[]=Flight::HpresenceModel()->get_salaire_heure($id,$date); // 3: salaire horaire

            // WHERE pour retrouver l'entrée ouverte (sortie/montant NULL)
            $where_colonnes = ["id_employe", "date_travail"];
            $where_valeurs  = [$rep[0], $rep[1]];

            // Récupère l'entrée ouverte
            $presence = Flight::HModel()->get_generalised(
                "presence",
                "*",
                $where_colonnes,
                $where_valeurs,
                "AND sortie IS NULL AND montant IS NULL",
                []
            );

            // Si aucune entrée ouverte, on ne peut pas poser la sortie
            if (empty($presence) || !isset($presence[0]['entree'])) {
                Flight::redirect(constant('BASE_URL').'/time/presences?error=Aucune entrée ouverte pour cet employé à cette date');
                return;
            }

            $heure_entree = $presence[0]['entree'];
            $heure_sortie = $rep[2];

            // Calcule les plages (peut scinder en plusieurs segments)
            $plage_horaire = Flight::HpresenceModel()->get_plage_horaire($rep[0], $heure_entree, $heure_sortie, $rep[1]);

            if (count($plage_horaire) === 0) {
                Flight::redirect(constant('BASE_URL').'/time/presences?error=La plage horaire est invalide');
                return;
            }

            // Fonction utilitaire interne pour fermer un segment (entree/fin)
            $closeSegment = function(string $debut, string $fin) use (&$rep, $where_colonnes, $where_valeurs) {
                $ferier = Flight::HpresenceModel()->is_ferier($rep[1]);
                $weekend = Flight::HpresenceModel()->is_weekend($rep[1]);
                $h_supplementaire = Flight::HpresenceModel()->h_supplementaire($debut, $fin, $rep[0], $rep[1]);

                $coeff = [$ferier, $weekend, $h_supplementaire];
                $mult  = max($coeff);

                $duree = strtotime($fin) - strtotime($debut);
                $montant_segment = $rep[3] * $mult * ($duree/3600);

                // Vérifie la condition heure supp pour ce segment
                if (Flight::HpresenceModel()->is_heure_supp($fin, $rep[0], $rep[1])) {
                    try {
                        $test_heure_supp = Flight::HpresenceModel()->condition_heure_supp($rep[1], $rep[0], $duree/3600);
                    } catch (Exception $e) {
                        Flight::redirect(constant('BASE_URL').'/time/presences?error='.$e->getMessage());
                    }
                }

                // Met à jour la présence ouverte avec la sortie de ce segment et son montant
                $colonnes_set = ["id_employe","date_travail","sortie","montant"];
                $valeurs_set  = [$rep[0], $rep[1], $fin, $montant_segment];

                Flight::HModel()->update_generalised(
                    "presence",
                    $colonnes_set,
                    $valeurs_set,
                    $where_colonnes,
                    $where_valeurs,
                    "AND sortie IS NULL AND montant IS NULL",
                    []
                );
            };

            if (count($plage_horaire) === 1) {
                // Un seul segment: on ferme directement
                $seg = $plage_horaire[0];
                $closeSegment($seg['debut'], $seg['fin']);
                return;
            }

            // Plusieurs segments:
            // 1) Fermer le premier segment sur l'entrée existante
            $first = $plage_horaire[0];
            $closeSegment($first['debut'], $first['fin']);

            // 2) Pour chaque segment suivant, on crée une nouvelle entrée puis on ferme
            for ($i = 1; $i < count($plage_horaire); $i++) {
                $seg = $plage_horaire[$i];
                // Nouvelle entrée à l'heure de début du segment
                $this->insert_entree($date, $id, $seg['debut']);
                // Ferme le segment
                $closeSegment($seg['debut'], $seg['fin']);
            }
        }
        catch (Exception $e)
        {
            Flight::redirect(constant('BASE_URL').'/time/presences?error=Erreur lors de l\'insertion de la sortie: '.$e->getMessage());
        }
        // Prépare les valeurs de base
        
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
            if($type==1)
            {
                $this->insert_entree($date,$id,$heure);

            }
            else if($type==2)
            {
                $this->insert_sortie($date,$id,$heure);
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
    public function into_performance_dashboard()
    {
        $idDept = Flight::request()->query->idDept;
        $annee = Flight::request()->query->annee;

        if(!$annee)
        {
            $annee = date("Y");
        }
        if(!$idDept)
        {
            $idDept = 1;
        }
        $postes = Flight::HdashModel()->get_postes_departement($idDept);
        $nbr_postes=[];
        $notes= [];
        $heures=[];
        for($i=1;$i<=12;$i++)
        {
            $heures[]=Flight::HdashModel()->get_sum_hours($i,$annee,$idDept);
            $notes[]=Flight::HdashModel()->get_sum_notes($i,$annee,$idDept);
        }
        foreach($postes as $poste)
        {
            $employes_poste=Flight::HdashModel()->get_employe_poste_annee($poste['id'],$annee);
            $nbr_postes[]=count($employes_poste);
        }
        $data = [
            'nbr_postes' => $nbr_postes,
            'postes' => $postes,
            'nbr_employes' => array_sum($nbr_postes),
            'heures' => $heures,
            'notes' => $notes
        ];
        Flight::render('performance/dashboard', $data);
    }
    public function into_calendar()
    {
        $annee = Flight::request()->query->annee;
        $mois = Flight::request()->query->mois;
        $idEmp = Flight::request()->query->idEmp;
        $jour = Flight::request()->query->jour;
        $idDept = Flight::request()->query->idDept;
        $presence_jour = null;

        if(!$annee)
        {
            $annee = date("Y");
        }
        if(!$mois)
        {
            $mois = date("n");
        }
        if(!$jour)
        {
            $jour = date("Y-m-d");
        }
        if(!$idDept)
        {
            $idDept = 1;
        }
        $employes = Flight::HdashModel()->get_employe_departement($idDept,$mois,$annee);
        $poste=null;
        $poste_config=null;
        $note_jour=null;
        $note_mois=null;
        $jours_travail_mois=null;
        if($idEmp!=null)
        {
            $poste_config=Flight::HpresenceModel()->get_config_poste($idEmp,$jour);
            $id_poste=$poste_config['id_poste'];
            $poste=Flight::HModel()->get_generalised("poste","*",
            ["id"],[$id_poste],"",[])[0];
            $note_jour=Flight::HdashModel()->get_note_jours($jour,$idEmp);
            $note_mois=Flight::HdashModel()->get_notes($mois,$annee,$idEmp);
            $presence_jour = Flight::HModel()->get_generalised(
                "presence",
                "*",
                ["id_employe", "date_travail"],
                [$idEmp, $jour],
                "",
                []
            );
            $jours_travail_mois=Flight::HModel()->get_generalised(
                "presence",
                "DISTINCT date_travail",
                ["id_employe"],
                [$idEmp],
                "AND EXTRACT(YEAR FROM date_travail) = ?::int AND EXTRACT(MONTH FROM date_travail) = ?::int",
                [$annee, $mois]
            );
        }
        
        // Préparer les données pour le rendu
        $data = [
            'jours_travail_mois' => $jours_travail_mois,
            'presence_jour' => $presence_jour,
            'poste' => $poste,
            'config_poste'=> $poste_config,
            'employes' => $employes,
            'annee' => $annee,
            'mois' => $mois,
            'idEmp' => $idEmp,
            'jour' => $jour,
            'idDept' => $idDept,
            'note_jour' => $note_jour,
            'note_mois' => $note_mois
        ];

        Flight::render('performance/calendar', $data);
    }
    public function into_releves()
    {
        $id_employe = Flight::request()->query->id_employe;
        $annee = Flight::request()->query->annee;
        $mois = Flight::request()->query->mois;
        $employe=Flight::HModel()->get_generalised("employe", "*", ["id"], [$id_employe], "",[])[0];
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
    public function get_prediction(){
        $id_employe = Flight::request()->data->id_employe;
        //date ajourd'hui - 6 mois =data = anne,mois 
        $date = new \DateTime();
        $date->modify('-6 months');

        $mois = (int)$date->format('m');
        $annee = (int)$date->format('Y');

        $data=Flight::HdashModel()->moyenne_notes_6mois($id_employe, $mois, $annee);
        return Flight::render('prediction',['data'=> $data]);
    }
}
