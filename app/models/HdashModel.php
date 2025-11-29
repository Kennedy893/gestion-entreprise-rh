<?php

namespace app\models;

use Flight;
use PDO;
use DateTime;
use Exception;
use DatePeriod;
use DateInterval;

class HdashModel
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function get_employe_departement_annee($id_departement, $annee)
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
        $stmt->execute([$id_departement, $annee, $annee]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_employe_poste_annee($id_poste, $annee)
    {
        $sql = "SELECT employe.* 
        FROM employe 
        JOIN Contrat_employe ON Contrat_employe.id_employe = employe.id 
        WHERE Contrat_employe.id_poste = ? 
        AND EXTRACT(YEAR FROM Contrat_employe.date_debut) <= ?
        AND (Contrat_employe.date_fin IS NULL 
             OR EXTRACT(YEAR FROM Contrat_employe.date_fin) >= ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_poste, $annee, $annee]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_employe_departement($id_departement, $mois, $annee)
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
        $stmt->execute([$id_departement, $annee, $mois, $annee, $mois]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_sum_hours($mois, $annee, $id_departement)
    {
        $dep_emp = $this->get_employe_departement($id_departement, $mois, $annee);
        $retour = [];
        $retour['heures_normales'] = 0;
        $retour['hors-service'] = 0;
        $retour['week-end'] = 0;
        $retour['jours_feries'] = 0;
        foreach ($dep_emp as $employe) {
            $hours = $this->get_hours($mois, $annee, $employe['id']);
            $retour['heures_normales'] += $hours['heures_normales'];
            $retour['hors-service'] += $hours['hors-service'];
            $retour['week-end'] += $hours['week-end'];
            $retour['jours_feries'] += $hours['jours_feries'];
        }
        return $retour;
    }
    public function get_sum_notes($mois, $annee, $id_departement)
    {
        $retour = [];
        $retour['ponctualite'] = 0;
        $retour['gestion_temps'] = 0;
        $retour['productivite'] = 0;
        $dep_emp = $this->get_employe_departement($id_departement, $mois, $annee);
        $count = 0;
        foreach ($dep_emp as $employe) {
            $notes = $this->get_notes($mois, $annee, $employe['id']);
            foreach ($notes as $note) {
                $retour['ponctualite'] += $note['ponctualite'];
                $retour['gestion_temps'] += $note['gestion_temps'];
                $retour['productivite'] += $note['productivite'];
                $count++;
            }
        }
        if ($count > 0) {
            $retour['ponctualite'] = $retour['ponctualite'] / $count;
            $retour['gestion_temps'] = $retour['gestion_temps'] / $count;
            $retour['productivite'] = $retour['productivite'] / $count;
        }
        return $retour;
    }
    public function get_hours($mois, $annee, $id_employe)
    {
        $retour = [];
        $retour['heures_normales'] = 0;
        $retour['hors-service'] = 0;
        $retour['week-end'] = 0;
        $retour['jours_feries'] = 0;
        $days = Flight::HpresenceModel()->jours_du_mois($annee, $mois);
        $total_heures = 0;
        foreach ($days as $day) {
            $presences = Flight::HModel()->get_generalised(
                "presence",
                "*",
                ["id_employe", "date_travail"],
                [$id_employe, $day['date']],
                "",
                []
            );
            foreach ($presences as $presence) {
                $config_poste = Flight::HpresenceModel()->get_config_poste($id_employe, $day['date']);

                $montant = Flight::HpresenceModel()->get_salaire_heure($id_employe, $day['date']);
                $montant_gagne = $presence['montant'];
                $duree_travail = (strtotime($presence['sortie']) - strtotime($presence['entree'])) / 3600;
                $montant_heure = $montant_gagne / $duree_travail;
                $diff = $montant_heure / $montant;
                if ($diff <= 1) {
                    $retour['heures_normales'] += $duree_travail;
                } else if ($diff > 1 && $diff <= 2) {
                    $retour['hors-service'] += $duree_travail;
                } else if ($diff > 2 && $diff <= 2.5) {
                    $retour['week-end'] += $duree_travail;
                } else {
                    $retour['jours_feries'] += $duree_travail;
                }
            }
        }
        $retour['heures_normales'] = round($retour['heures_normales'], 2);
        $retour['hors-service'] = round($retour['hors-service'], 2);
        $retour['week-end'] = round($retour['week-end'], 2);
        $retour['jours_feries'] = round($retour['jours_feries'], 2);
        return $retour;
    }
    public function get_postes_departement($id_departement)
    {
        $retour = Flight::HModel()->get_generalised(
            "poste",
            "*",
            ["id_departement"],
            [$id_departement],
            "",
            []
        );
        return $retour;
    }
    public function get_note_jours($jour, $id_employe)
    {
        $presence = Flight::HModel()->get_generalised(
            "presence",
            "*",
            ["id_employe", "date_travail"],
            [$id_employe, $jour],
            "",
            []
        );
        if ($presence == null) {
            $note = [
                'ponctualite' => 0,
                'gestion_temps' => 0,
                'productivite' => 0
            ];
        } else {
            $entree_employe = strtotime($presence[0]['entree']);
            $sortie_employe = strtotime($presence[count($presence) - 1]['sortie']);

            $config_poste = Flight::HpresenceModel()->get_config_poste($id_employe, $jour);
            $entree_poste = strtotime($config_poste['entree']);
            $sortie_poste = strtotime($config_poste['sortie']);

            $ponctualite = 5;
            $productivite = 5;
            $gestion_temps = 5;

            if (($entree_employe - $entree_poste) > 0) {
                $diff = $entree_employe - $entree_poste;
                if ($diff <= 15 * 60) {
                    $ponctualite = 4;
                } else if ($diff > 15 * 60 && $diff <= 30 * 60) {
                    $ponctualite = 3;
                } else if ($diff > 30 * 60 && $diff <= 60 * 60) {
                    $ponctualite = 2;
                } else {
                    $ponctualite = 1;
                }
            }
            if (($sortie_poste - $sortie_employe) > 0) {
                $diff = $sortie_poste - $sortie_employe;
                if ($diff <= 60 * 60) {
                    $gestion_temps = 4;
                } else if ($diff > 60 * 60 && $diff <= 120 * 60) {
                    $gestion_temps = 3;
                } else if ($diff > 120 * 60 && $diff <= 180 * 60) {
                    $gestion_temps = 2;
                } else {
                    $gestion_temps = 1;
                }
            }
            $duree_travail = 0;
            foreach ($presence as $pres) {
                $duree_travail += (strtotime($pres['sortie']) - strtotime($pres['entree'])) / 3600;
            }
            $duree_supposee = $config_poste['duree_travail'];
            $diff = $duree_travail - $duree_supposee;
            if ($diff >= 0) {
                $productivite = 5;
            } else {
                $diff = abs($diff);
                if ($diff <= 1) {
                    $productivite = 4;
                } else if ($diff > 1 && $diff <= 2) {
                    $productivite = 3;
                } else if ($diff > 2 && $diff <= 3) {
                    $productivite = 2;
                } else {
                    $productivite = 1;
                }
            }

            $note = [
                'ponctualite' => $ponctualite,
                'gestion_temps' => $productivite,
                'productivite' => $gestion_temps
            ];
        }

        return $note;
    }
    public function get_notes($mois, $annee, $id_employe)
    {
        $days = Flight::HpresenceModel()->jours_du_mois($annee, $mois);
        $notes = [];
        foreach ($days as $day) {
            $note = $this->get_note_jours($day['date'], $id_employe);
            $notes[] = $note;
        }
        return $notes;
    }
    public function get_notes_moisix($mois, $annee, $id_employe)
    {
        $notes_tota = [];
        for ($i = 0; $i < 6; $i++) {
            $days = Flight::HpresenceModel()->jours_du_mois($annee, $mois + $i);
            $notes = [];
            foreach ($days as $day) {
                $note = $this->get_note_jours($day['date'], $id_employe);
                $notes[] = $note;
            }
            $notes_tota[] = $notes;
        }
        return $notes_tota;
    }
public function moyenne_notes_6mois($mois, $annee, $id_employe)
{
    $notes_tota = $this->get_notes_moisix($mois, $annee, $id_employe);

    $total_ponctualite = 0;
    $total_gestion_temps = 0;
    $total_productivite = 0;

    $count = 0;

    foreach ($notes_tota as $mois_notes) {
        foreach ($mois_notes as $note) {

            if ($note !== null) {
                $total_ponctualite += $note['ponctualite'];
                $total_gestion_temps += $note['gestion_temps'];
                $total_productivite += $note['productivite'];
                $count++;
            }
        }
    }

    if ($count === 0) {
        return null;
    }

    return [
        'ponctualite' => $total_ponctualite / $count,
        'gestion_temps' => $total_gestion_temps / $count,
        'productivite' => $total_productivite / $count,
        'moyenne_generale' => ($total_ponctualite + $total_gestion_temps + $total_productivite) / (3 * $count)
    ];
}

}
