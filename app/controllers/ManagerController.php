<?php

namespace app\controllers;

use Flight;
use app\models\AnnonceModel;
use app\models\PosteModel;
use app\models\ReferenceModel;

class ManagerController {
    
    private $annonceModel;
    private $posteModel;
    private $referenceModel;

    public function __construct() {
        $this->annonceModel = new AnnonceModel(Flight::db());
        $this->posteModel = new PosteModel(Flight::db());
        $this->referenceModel = new ReferenceModel(Flight::db());
    }

    public function dashboard() {
        $annonces = $this->annonceModel->getAllAnnonces();
        Flight::render('manager/dashboard', [
            'annonces' => $annonces,
            'page_title' => 'Tableau de bord Manager'
        ]);
    }

    public function createAnnonceForm() {
        $postes = $this->posteModel->getAllPostes();
        $typeContrats = $this->referenceModel->getAllTypeContrats();
        $categories = $this->referenceModel->getAllCategories();
        
        Flight::render('manager/create_annonce', [
            'postes' => $postes,
            'typeContrats' => $typeContrats,
            'categories' => $categories,
            'page_title' => 'Créer une annonce'
        ]);
    }

    public function createAnnonce() {
        $data = [
            'titre' => Flight::request()->data->titre,
            'description' => Flight::request()->data->description,
            'competences_requises' => Flight::request()->data->competences_requises,
            'diplomes_requis' => Flight::request()->data->diplomes_requis,
            'experience_min' => Flight::request()->data->experience_min,
            'niveau_responsabilite' => Flight::request()->data->niveau_responsabilite,
            'autonomie_requise' => Flight::request()->data->autonomie_requise,
            'date_publication' => date('Y-m-d'),
            'date_limite' => Flight::request()->data->date_limite,
            'id_poste' => Flight::request()->data->id_poste,
            'id_type_contrat' => Flight::request()->data->id_type_contrat,
            'id_manager' => 1 // À remplacer par l'ID du manager connecté
        ];

        if ($this->annonceModel->createAnnonce($data)) {
            Flight::redirect('/manager/dashboard?success=1');
        } else {
            Flight::redirect('/manager/create-annonce?error=1');
        }
    }

    public function viewAnnonce($id) {
        $annonce = $this->annonceModel->getAnnonceById($id);
        
        if (!$annonce) {
            Flight::redirect('/manager/dashboard?error=not_found');
        }
        
        Flight::render('manager/view_annonce', [
            'annonce' => $annonce,
            'page_title' => 'Détails de l\'annonce'
        ]);
    }

    public function updateStatut($id) {
        $statut = Flight::request()->data->statut;
        
        if ($this->annonceModel->updateStatut($id, $statut)) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['success' => false], 500);
        }
    }

    public function deleteAnnonce($id) {
        if ($this->annonceModel->deleteAnnonce($id)) {
            Flight::redirect('/manager/dashboard?deleted=1');
        } else {
            Flight::redirect('/manager/dashboard?error=delete_failed');
        }
    }
}