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


    function calculateTauxJournaliers ($montant) {
        return $montant/30;
    }

    function calculateTauxHoraire ($montant) {
        return ($montant / 173.33);
    }

    function calculMajorationHeureSup ($montant,$pourcentage) {
        return $montant + ($montant * ($pourcentage/100));
    }
