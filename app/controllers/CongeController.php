<?php

namespace app\controllers;

use DateTime;
use Flight;

class CongeController {

	public function __construct() {

	}

    public function versDemande()
    {
        Flight::render('demande_conge');   
    }

    public function versListe()
    {
        Flight::render('liste_conge');   
    }

    public function versSolde()
    {
        Flight::render('solde_conge');   
    }

    public function demanderConge()
    {
        session_start();
        // Récupérer les données du formulaire
        $date_demande = $_POST['date_demande'];
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

        // Retourner une réponse (JSON ou redirection)
        if ($result['success']) {
            Flight::render('liste_conge');
        } else {
            Flight::json(['success' => false, 'error' => $result['error']]);
        }
    }

    public function listerConge()
    {
        $liste = Flight::CongeModel()->listeConge();

        Flight::render('liste_conge', ['liste' => $liste]);
    }

    public function validerConge()
    {
        $liste = Flight::CongeModel()->listeConge();    
        
        $date_validation = $_POST['date_validation'];
        $id_absence = $_POST['id_absence'];

        $button = $_POST['button'];
        if ($button == 0) 
        {
            // -- PREAVIS

            $absence = Flight::CongeModel()->getAbsenceById($id_absence);
            $date_demande = $absence['date_demande'];
            $date_debut = $absence['date_debut'];
            $date_fin = $absence['date_fin'];
            if (!$date_demande || !$date_debut || !$date_fin) {
                Flight::render('liste_conge', ['message' => 'Impossible de valider : données manquantes', 'liste' => $liste]);
                return;
            }

            $conge = Flight::CongeModel()->getCongeByIdAbs($id_absence);
            if ($conge != null)
            {
                $fonction = $conge['fonction'];
                $preavis = 10;

                if ($fonction >= 2)
                {
                    $preavis = 15;
                }
            }
            $preavis = 15;

            // Convertir les dates
            $dateDemande = new DateTime($date_demande);
            $dateDebut   = new DateTime($date_debut);

            $anneeConge = (int) $dateDebut->format('Y');

            $dtDebut = new DateTime($date_debut);
            $dtFin   = new DateTime($date_fin);
            $nbJours = $dtDebut->diff($dtFin)->days + 1; // +1 si inclusif

            // Calcul du préavis en jours
            $diff = $dateDemande->diff($dateDebut);
            $jours = $diff->days;

            if ($jours < $preavis) 
            {
                $erreur = "La date de congé est trop proche : préavis minimum = $preavis jours";
                Flight::render('liste_conge', ['message' => $erreur, 'liste' => $liste]);
                return;
            }

            // -- SIMULTANEE
            $poste = $_POST['poste'];
            if (Flight::CongeModel()->issetAutreConge($poste, $id_absence, $date_debut, $date_fin))
            {
                $erreur = "Un employé du même poste a déjà un congé validé dans cette période";
                Flight::render('liste_conge', ['message' => $erreur, 'liste' => $liste]);
                return;
            }

            // -- SOLDE 
            $id_employe = $_POST['id_employe'];
            if (!Flight::CongeModel()->hasSoldeSuffisant($id_employe, $date_debut, $date_fin))
            {
                $erreur = "Le solde est insuffisant";
                Flight::render('liste_conge', ['message' => $erreur, 'liste' => $liste]);
                return;
            }

            Flight::CongeModel()->updateSolde($anneeConge, $id_employe, $nbJours);
            Flight::CongeModel()->updateStatut(1, $id_absence, $date_validation);
            $succes = "La demande de congé est acceptée";
            Flight::render('liste_conge', ['message' => $succes, 'liste' => $liste]);
            return;
        }

        else if ($button == 1)
        {
            Flight::CongeModel()->updateStatut(2, $id_absence, $date_validation);
            $erreur = "Le congé ne peut pas etre permis";
            Flight::render('liste_conge', ['message' => $erreur, 'liste' => $liste]);
            return;
        }

        Flight::render('liste_conge', ['liste' => $liste]);
    }

}