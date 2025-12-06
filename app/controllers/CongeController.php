<?php

namespace app\controllers;

use DateTime;
use Flight;

class CongeController
{

    public function __construct() {}

    public function versDemande()
    {
        Flight::render('conge/demande', [], 'contenu');
        Flight::render('shared/home');
    }

    

    public function demanderConge()
    {
        session_start();
        // Récupérer les données du formulaire
        $date_demande = $_POST['date_demande'] ?? null;
        $date_debut = $_POST['date_debut'] ?? null;
        $date_fin = $_POST['date_fin'] ?? null;
        $motif = $_POST['motif'] ?? null;
        $type_conge = $_POST['type_conge'] ?? null;

        $_SESSION['date_demande'] = $date_demande;
        $_SESSION['date_debut'] = $date_debut;
        $_SESSION['date_fin'] = $date_fin;

        // Vérifier que tous les champs sont remplis
        if (!$date_debut || !$date_fin || !$motif || $type_conge === null) {
            Flight::json(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
            return;
        }

        // Appeler la fonction de demande de congé
        $result = Flight::CongeModel()->demandeConge($date_debut, $date_fin, $type_conge, $motif, $date_demande);

        $liste = Flight::CongeModel()->listeConge();
        // Retourner une réponse (JSON ou redirection)
        if ($result['success']) {
            Flight::render('conge/liste', ['liste' => $liste], 'contenu');
            Flight::render('shared/home');
        } else {
            Flight::json(['success' => false, 'error' => $result['error']]);
        }
    }

    public function listerConge()
    {
        $liste = Flight::CongeModel()->listeConge();

        Flight::render('conge/liste', ['liste' => $liste], 'contenu');
        Flight::render('shared/home');
    }

    public function listerCongeRH()
    {
        $liste = Flight::CongeModel()->listeCongeRH();

        Flight::render('conge/liste_rh', ['liste' => $liste], 'contenu');
        Flight::render('shared/home');
    }


    public function validerConge()
    {
        // --- Vérifier si POST existe ---
        // if (empty($_POST['id_absence']) || empty($_POST['button'])) {
        //     Flight::redirect(constant('BASE_URL').'liste_conge?error=' . urlencode('Requête invalide'));
        //     return;
        // }

        $id_absence = $_POST['id_absence'];
        $poste = $_POST['poste'] ?? null;
        $id_employe = $_POST['id_employe'] ?? null;
        $date_validation = !empty($_POST['date_validation']) ? $_POST['date_validation'] : null;

        $button = $_POST['button'];   // 0 = valider, 1 = refuser


        // --------------------------------------------------------------------
        //  REFUSER LE CONGÉ
        // --------------------------------------------------------------------
        if ($button == "1") {

            Flight::CongeModel()->updateStatut(2, $id_absence, $date_validation);

            Flight::redirect(constant('BASE_URL') . 'liste_conge?refuse=1');
            return;
        }


        // --------------------------------------------------------------------
        //  VALIDER LE CONGÉ
        // --------------------------------------------------------------------

        // 1. Récupérer absence
        $absence = Flight::CongeModel()->getAbsenceById($id_absence);
        if (!$absence) {
            Flight::redirect(constant('BASE_URL') . 'liste_conge?error=' . urlencode("Absence introuvable"));
            return;
        }

        $date_demande = $absence['date_demande'];
        $date_debut   = $absence['date_debut'];
        $date_fin     = $absence['date_fin'];

        if (!$date_demande || !$date_debut || !$date_fin) {
            Flight::redirect(constant('BASE_URL') . 'liste_conge?error=' . urlencode("Impossible de valider : données manquantes"));
            return;
        }


        // --------------------------------------------------------------------
        //  Calcul PREAVIS
        // --------------------------------------------------------------------
        $conge = Flight::CongeModel()->getCongeByIdAbs($id_absence);
        $preavis = 15;

        if ($conge != null) {
            $fonction = (int) $conge['fonction'];
            if ($fonction < 2) {
                $preavis = 10;
            }
        }

        $dateDemande = new DateTime($date_demande);
        $dateDebut   = new DateTime($date_debut);

        $jours_preavis = $dateDemande->diff($dateDebut)->days;

        if ($jours_preavis < $preavis) {
            Flight::redirect(constant('BASE_URL') . 'liste_conge?error=' . urlencode("Préavis insuffisant : minimum $preavis jours"));
            return;
        }


        // --------------------------------------------------------------------
        //  Vérifier SI AUTRE employé du même poste a un congé simultané
        // --------------------------------------------------------------------
        if (Flight::CongeModel()->issetAutreConge($poste, $id_absence, $date_debut, $date_fin)) {
            Flight::redirect(constant('BASE_URL') . 'liste_conge?error=' . urlencode(
                "Un employé du même poste a déjà un congé à ces dates"
            ));
            return;
        }


        // --------------------------------------------------------------------
        //  Vérifier le SOLDE
        // --------------------------------------------------------------------
        if (!Flight::CongeModel()->hasSoldeSuffisant($id_employe, $date_debut, $date_fin)) {
            Flight::redirect(constant('BASE_URL') . 'liste_conge?error=' . urlencode("Solde insuffisant"));
            return;
        }


        // --------------------------------------------------------------------
        //  VALIDER
        // --------------------------------------------------------------------
        Flight::CongeModel()->updateStatut(1, $id_absence, $date_validation);

        Flight::redirect(constant('BASE_URL') . 'validation_rh');
        return;
    }


    public function validerCongeRH()
    {
        // --- Vérifier si POST existe ---
        // if (empty($_POST['id_absence']) || empty($_POST['button'])) {
        //     Flight::redirect(constant('BASE_URL').'liste_conge?error=' . urlencode('Requête invalide'));
        //     return;
        // }

        $id_absence = $_POST['id_absence'];
        $poste = $_POST['poste'] ?? null;
        $id_employe = $_POST['id_employe'] ?? null;
        $date_validation = !empty($_POST['date_validation']) ? $_POST['date_validation'] : null;

        $button = $_POST['button'];   // 0 = valider, 1 = refuser


        // --------------------------------------------------------------------
        //  REFUSER LE CONGÉ
        // --------------------------------------------------------------------
        if ($button == "1") {
            Flight::CongeModel()->updateStatut(2, $id_absence, $date_validation);

            Flight::redirect(constant('BASE_URL') . 'validation_rh?refuse=1');
            return;
        }


        // --------------------------------------------------------------------
        //  VALIDER LE CONGÉ
        // --------------------------------------------------------------------

        // 1. Récupérer absence
        $absence = Flight::CongeModel()->getAbsenceById($id_absence);
        if (!$absence) {
            Flight::redirect(constant('BASE_URL') . 'validation_rh?error=' . urlencode("Absence introuvable"));
            return;
        }

        $date_demande = $absence['date_demande'];
        $date_debut   = $absence['date_debut'];
        $date_fin     = $absence['date_fin'];

        if (!$date_demande || !$date_debut || !$date_fin) {
            Flight::redirect(constant('BASE_URL') . 'validation_rh?error=' . urlencode("Impossible de valider : données manquantes"));
            return;
        }


        // --------------------------------------------------------------------
        //  Calcul PREAVIS
        // --------------------------------------------------------------------
        $conge = Flight::CongeModel()->getCongeByIdAbs($id_absence);
        $preavis = 15;

        if ($conge != null) {
            $fonction = (int) $conge['fonction'];
            if ($fonction < 2) {
                $preavis = 10;
            }
        }

        $dateDemande = new DateTime($date_demande);
        $dateDebut   = new DateTime($date_debut);

        $anneeConge = (int) $dateDebut->format('Y');

        $dtDebut = new DateTime($date_debut);
        $dtFin   = new DateTime($date_fin);
        $nbJours = $dtDebut->diff($dtFin)->days + 1; // +1 si inclusif

        // Calcul du préavis en jours
        $diff = $dateDemande->diff($dateDebut);
        $jours = $diff->days;

        $jours_preavis = $dateDemande->diff($dateDebut)->days;

        if ($jours_preavis < $preavis) {
            Flight::redirect(constant('BASE_URL') . 'validation_rh?error=' . urlencode("Préavis insuffisant : minimum $preavis jours"));
            return;
        }


        // --------------------------------------------------------------------
        //  Vérifier SI AUTRE employé du même poste a un congé simultané
        // --------------------------------------------------------------------
        if (Flight::CongeModel()->issetAutreConge($poste, $id_absence, $date_debut, $date_fin)) {
            Flight::redirect(constant('BASE_URL') . 'validation_rh?error=' . urlencode(
                "Un employé du même poste a déjà un congé à ces dates"
            ));
            return;
        }


        // --------------------------------------------------------------------
        //  Vérifier le SOLDE
        // --------------------------------------------------------------------
        if (!Flight::CongeModel()->hasSoldeSuffisant($id_employe, $date_debut, $date_fin)) {
            Flight::redirect(constant('BASE_URL') . 'validation_rh?error=' . urlencode("Solde insuffisant"));
            return;
        }


        // --------------------------------------------------------------------
        //  VALIDER
        // --------------------------------------------------------------------
        Flight::CongeModel()->updateSolde($anneeConge, $id_employe, $nbJours);
        Flight::CongeModel()->updateStatut(11, $id_absence, $date_validation);

        Flight::redirect(constant('BASE_URL') . 'validation_rh');
        return;
    }

    public function consulterSolde()
    {
        $employes = Flight::CongeModel()->getAllEmployes();

        if (isset($_GET['id_employe']) && $_GET['id_employe'] != "") {
            // raha misy id_employe
            $solde = Flight::CongeModel()->getSoldeCongeByEmploye($_GET['id_employe']);
            $annee_debut = Flight::CongeModel()->getAnneeDebutContrat($_GET['id_employe']);
        } else {
            $solde = Flight::CongeModel()->getSoldeCongeAll();
        }

        Flight::render('conge/solde', ['solde' => $solde, 'employes' => $employes, 'annee_debut' => $annee_debut ?? null] , 'contenu');
        Flight::render('shared/home');
    }

    public function detailsSolde()
    {
        if (!isset($_GET['employe']) || !isset($_GET['annee'])) {
            Flight::redirect(constant('BASE_URL') . 'vers_solde_conge?error=' . urlencode("Paramètres manquants"));
            return;
        }

        $id_employe = $_GET['employe'];
        $annee = $_GET['annee'];

        $jrsPris_normal = Flight::CongeModel()->getJrsPrisByTypeConge($id_employe, $annee, 'Conge normal');
        $jrsPris_exc = Flight::CongeModel()->getJrsPrisByTypeConge($id_employe, $annee, 'Conge exceptionnel');

        Flight::render('conge/details_solde', ['id_employe' => $id_employe, 'annee' => $annee, 'jrsPris_normal' => $jrsPris_normal, 'jrsPris_exc' => $jrsPris_exc], 'contenu');
        Flight::render('shared/home');
    }
}
