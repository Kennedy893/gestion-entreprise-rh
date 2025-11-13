<?php

namespace app\controllers;


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

}