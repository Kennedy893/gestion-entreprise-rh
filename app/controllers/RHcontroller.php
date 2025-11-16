<?php

namespace app\controllers;

use Flight;
use app\models\AnnonceModel;

class RHController {
    
    private $annonceModel;

    public function __construct() {
        $this->annonceModel = new AnnonceModel(Flight::db());
    }

    public function dashboard() {
        $annonces = $this->annonceModel->getAllAnnonces();
        
        Flight::render('rh/dashboard', [
            'annonces' => $annonces,
            'page_title' => 'Tableau de bord RH'
        ]);
    }

    public function viewAnnonces() {
        $annonces = $this->annonceModel->getAllAnnonces();
        
        Flight::render('rh/annonces_list', [
            'annonces' => $annonces,
            'page_title' => 'Annonces publiées'
        ]);
    }

    public function viewAnnonce($id) {
        $annonce = $this->annonceModel->getAnnonceById($id);
        
        if (!$annonce) {
            Flight::redirect('/rh/dashboard?error=not_found');
        }
        
        Flight::render('rh/view_annonce', [
            'annonce' => $annonce,
            'page_title' => 'Détails de l\'annonce'
        ]);
    }
}