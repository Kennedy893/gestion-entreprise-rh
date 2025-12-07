<?php

namespace app\models;

use Flight;
use PDO;

class PaieModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // si secteur non agricole
    public function getPlafondCnaps()
    {
        $salaire_min = 200000;
        return $salaire_min * 8;
    }

    public function calculCnaps($montant, $pourcentage)
    {
        if ($montant > $this->getPlafondCnaps()) {
            return $this->getPlafondCnaps() * ($pourcentage / 100);
        }
        return $montant * ($pourcentage / 100);
    }

    public function calculOstie($montant, $pourcentage)
    {
        return $montant * ($pourcentage / 100);
    }

    public function calculerIRSA($salaire, $withDetails = false)
    {
        $impot = 0;
        $details = [];

        // Tranches IRSA
        $tranches = [
            ['min' => 0,       'max' => 350000,   'taux' => 0],
            ['min' => 350001,  'max' => 400000,   'taux' => 5],
            ['min' => 400001,  'max' => 500000,   'taux' => 10],
            ['min' => 500001,  'max' => 600000,   'taux' => 15],
            ['min' => 600001,  'max' => 4000000,  'taux' => 20],
            ['min' => 4000001, 'max' => PHP_INT_MAX, 'taux' => 25]
        ];

        // Calcul tranche par tranche
        foreach ($tranches as $t) {
            if ($salaire > $t['min']) {
                $montant_tranche = min($salaire, $t['max']) - $t['min'];
                $impot_tranche = $montant_tranche * ($t['taux'] / 100);
                $impot += $impot_tranche;

                if ($withDetails) {
                    $details[] = [
                        'min' => $t['min'],
                        'max' => $t['max'],
                        'taux' => $t['taux'],
                        'montant' => round($impot_tranche, 2)
                    ];
                }
            } else {
                break;
            }
        }

        if ($withDetails) {
            return [
                'total_irsa' => round($impot, 2),
                'details' => $details
            ];
        }

        return round($impot, 2);
    }



    public function getAvantagesEmployesByContrat($id_contrat)
    {
        $sql = "SELECT * FROM avantage WHERE id_contrat_employe = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_contrat]);
        $avantages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Si aucun résultat ou erreur → renvoie un tableau vide
        if (!$avantages || !is_array($avantages)) {
            return [];
        }

        return $avantages;
    }


    public function sommeAvantageEmployeByContrat($id_contrat)
    {
        $avantages = $this->getAvantagesEmployesByContrat($id_contrat);

        if (!is_array($avantages) || empty($avantages)) {
            return 0;
        }

        $result = 0;
        foreach ($avantages as $av) {
            if (isset($av['montant']) && is_numeric($av['montant'])) {
                $result += $av['montant'];
            }
        }

        return $result;
    }


    public function getContratEmployeByDate($date)
    {
        $stmt = $this->db->prepare(" SELECT * FROM contrat_employe ce JOIN employe e ON ce.id_employe = e.id 
        JOIN poste p ON ce.id_poste = p.id WHERE :date >= date_debut AND (date_fin IS NULL OR :date <= date_fin) AND id_statut_contrat = 1 ");
        $stmt->execute(['date' => $date]);
        $contrats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_employes = count($contrats);
        $masse_salaire_brut = 0;
        $total_net = 0;
        $charges_sociales = 0; // CNAPS 8% + OSTIE 5%


        foreach ($contrats as $i => $con) {
            $contrats[$i]['avantages'] = $this->sommeAvantageEmployeByContrat($con['id']);
            $contrats[$i]['heure_sup'] = 15000;
            $contrats[$i]['salaire_brut'] = $contrats[$i]['salaire'] + $contrats[$i]['heure_sup'] + $contrats[$i]['avantages'];
            $contrats[$i]['cnaps_1'] = $this->calculCnaps($contrats[$i]['salaire_brut'], 1);
            $contrats[$i]['cnaps_8'] = $this->calculCnaps($contrats[$i]['salaire_brut'], 8);
            $contrats[$i]['ostie_1'] = $this->calculOstie($contrats[$i]['salaire_brut'], 1);
            $contrats[$i]['ostie_5'] = $this->calculOstie($contrats[$i]['salaire_brut'], 5);
            $contrats[$i]['autres_ret'] = 0;
            $contrats[$i]['total_ret'] = $contrats[$i]['cnaps_1'] + $contrats[$i]['ostie_1'] + $contrats[$i]['autres_ret'];
            $contrats[$i]['revenu_impo'] = $contrats[$i]['salaire_brut'] - ($contrats[$i]['total_ret']);
            $contrats[$i]['irsa'] = $this->calculerIRSA($contrats[$i]['revenu_impo']);
            $contrats[$i]['salaire_net'] = $contrats[$i]['salaire_brut'] - ($contrats[$i]['total_ret'] + $contrats[$i]['irsa']);

            $masse_salaire_brut += $contrats[$i]['salaire_brut'];
            $total_net += $contrats[$i]['salaire_net'];
            $charges_sociales += $contrats[$i]['cnaps_8'] + $contrats[$i]['ostie_5'];
        }

        return [
            "details" => $contrats,
            "resume" => [
                "total_employes" => $total_employes,
                "masse_brut" => $masse_salaire_brut,
                "total_net" => $total_net,
                "charges_sociales" => $charges_sociales,
                "date_generation" => date("d F Y")
            ]
        ];
    }


    public function getContratEmployeByIdEmploye($id_employe, $date)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM contrat_employe ce
            JOIN employe e ON ce.id_employe = e.id
            JOIN poste p ON ce.id_poste = p.id
            WHERE 
                :date >= date_debut
                AND (date_fin IS NULL OR :date <= date_fin)
                AND id_statut_contrat = 1
                AND id_employe = :id_emp
        ");
        $stmt->execute(['date' => $date, 'id_emp' => $id_employe]);

        $emp = $stmt->fetch(PDO::FETCH_ASSOC);

        $emp['avantages'] = $this->sommeAvantageEmployeByContrat($emp['id_employe']);
        $emp['heure_sup'] = 15000;
        $emp['salaire_brut'] = $emp['salaire'] + $emp['heure_sup'] + $emp['avantages'];
        $emp['cnaps_1'] = $this->calculCnaps($emp['salaire_brut'], 1);
        $emp['cnaps_8'] = $this->calculCnaps($emp['salaire_brut'], 8);
        $emp['ostie_1'] = $this->calculOstie($emp['salaire_brut'], 1);
        $emp['ostie_5'] = $this->calculOstie($emp['salaire_brut'], 5);
        $emp['autres_ret'] = 0;
        $emp['total_ret'] = $emp['cnaps_1'] + $emp['ostie_1'] + $emp['autres_ret'];
        $emp['revenu_impo'] = $emp['salaire_brut'] - ($emp['total_ret']);
        $emp['irsa'] = $this->calculerIRSA($emp['revenu_impo'], true)['total_irsa'];
        $emp['irsa_details'] = $this->calculerIRSA($emp['revenu_impo'], true)['details'];
        $emp['salaire_net'] = $emp['salaire_brut'] - ($emp['total_ret'] + $emp['irsa']);
        $emp['label_avantage'] = $this->getAvantagesEmployesByContrat($emp['id']);

        return $emp;
    }
}
