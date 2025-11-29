<?php
// app/helpers.php

    function formatDate($date)
    {
        return date("d/m/Y", strtotime($date));
    }

    function moneyFormat($amount)
    {
        return number_format($amount, 2, ',', ' ') . " Ar";
    }

    function isActive($route, $current)
    {
        return $route === $current ? 'active' : '';
    }

    function calculateAge($birthdate)
    {
        $birthDate = new DateTime($birthdate);
        $today = new DateTime();
        $age = $today->diff($birthDate);
        return $age->y;
    }

    function formatSexe($sexe)
    {
        if ($sexe === null) {
            return 'Non spécifié';
        } else if ($sexe === 'Masculin') {
            return 'M';
        } else if ($sexe === 'Féminin') {
            return 'F';
        } else {
            return $sexe;
        }
    }
        function mois_annee_moins_6()
        {
            $date = new DateTime();
            $date->modify('-6 months');
        
            return [
                'mois'  => (int)$date->format('m'),
                'annee' => (int)$date->format('Y')
            ];
        }

    function calculateTauxJournaliers ($montant) {
        return $montant/30;
    }

    function calculateTauxHoraire ($montant) {
        return ($montant / 173.33);
    }

    function calculMajorationHeureSup ($montant,$pourcentage) {
        return $montant + ($montant * ($pourcentage/100));
    }

    function calculerAnciennete($date_debut) {
        $debut = new DateTime($date_debut);
        $aujourdhui = new DateTime();

        $diff = $aujourdhui->diff($debut);

        $texte = "";

        if ($diff->y > 0) {
            $texte .= $diff->y . " an(s) ";
        }

        if ($diff->m > 0) {
            $texte .= $diff->m . " mois ";
        }

        if ($diff->d > 0) {
            $texte .= "et " . $diff->d . " jour(s)";
        }

        if ($texte === "") {
            $texte = "0 jour";
        }
        return trim($texte);
    }

