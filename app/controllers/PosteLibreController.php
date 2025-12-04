<?php

namespace app\controllers;

use Flight;
use app\models\PosteLibreModel;
use app\models\ReferenceModel;

class PosteLibreController {
    
    private $posteLibreModel;
    private $referenceModel;

    public function __construct() {
        $this->posteLibreModel = new PosteLibreModel(Flight::db());
        $this->referenceModel = new ReferenceModel(Flight::db());
    }

    /**
     * Liste tous les postes libres
     */
    public function index() {
        $postes = $this->posteLibreModel->getAllPostesLibres();
        $stats = $this->posteLibreModel->getStatsPostes();
        
        Flight::render('manager/postes_libres/index', [
            'postes' => $postes,
            'stats' => $stats,
            'page_title' => 'Postes Libres'
        ]);
    }

    /**
     * Formulaire de création d'un poste
     */
    public function createForm() {
        $categories = $this->referenceModel->getAllCategories();
        $departements = $this->referenceModel->getAllDepartements();
        
        Flight::render('manager/postes_libres/create', [
            'categories' => $categories,
            'departements' => $departements,
            'page_title' => 'Créer un Poste'
        ]);
    }

    /**
     * Création d'un poste
     */
    public function create() {
        try {
            $nombre = (int)Flight::request()->data->nombre ?? 1;
            
            $data = [
                'label' => trim(Flight::request()->data->label),
                'valeur' => (int)Flight::request()->data->valeur,
                'id_categorie' => (int)Flight::request()->data->id_categorie,
                'id_departement' => (int)Flight::request()->data->id_departement
            ];

            // Validation
            if (empty($data['label']) || $data['valeur'] < 1) {
                Flight::redirect('/manager/postes-libres/create?error=invalid_data');
                return;
            }

            // Créer les postes (possibilité d'en créer plusieurs)
            $created = 0;
            for ($i = 0; $i < $nombre; $i++) {
                if ($this->posteLibreModel->createPoste($data)) {
                    $created++;
                }
            }

            if ($created > 0) {
                Flight::redirect('/manager/postes-libres?success=created&count=' . $created);
            } else {
                Flight::redirect('/manager/postes-libres/create?error=creation_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur création poste: " . $e->getMessage());
            Flight::redirect('/manager/postes-libres/create?error=exception');
        }
    }

    /**
     * Formulaire de modification d'un poste
     */
    public function editForm($id) {
        $poste = $this->posteLibreModel->getPosteLibreById($id);
        
        if (!$poste) {
            Flight::redirect('/manager/postes-libres?error=not_found');
            return;
        }
        
        $categories = $this->referenceModel->getAllCategories();
        $departements = $this->referenceModel->getAllDepartements();
        
        Flight::render('manager/postes_libres/edit', [
            'poste' => $poste,
            'categories' => $categories,
            'departements' => $departements,
            'page_title' => 'Modifier le Poste'
        ]);
    }

    /**
     * Modification d'un poste
     */
    public function update($id) {
        try {
            $data = [
                'label' => trim(Flight::request()->data->label),
                'valeur' => (int)Flight::request()->data->valeur,
                'id_categorie' => (int)Flight::request()->data->id_categorie,
                'id_departement' => (int)Flight::request()->data->id_departement
            ];

            if ($this->posteLibreModel->updatePoste($id, $data)) {
                Flight::redirect('/manager/postes-libres?success=updated');
            } else {
                Flight::redirect('/manager/postes-libres/edit/' . $id . '?error=update_failed');
            }
        } catch (\Exception $e) {
            error_log("Erreur mise à jour poste: " . $e->getMessage());
            Flight::redirect('/manager/postes-libres/edit/' . $id . '?error=exception');
        }
    }

    /**
     * Suppression d'un poste
     */
    public function delete($id) {
        if ($this->posteLibreModel->deletePoste($id)) {
            Flight::redirect('/manager/postes-libres?success=deleted');
        } else {
            Flight::redirect('/manager/postes-libres?error=cannot_delete');
        }
    }

    /**
     * Augmentation de la capacité d'un poste
     */
    public function augmenterCapacite($id) {
        $nombre = (int)Flight::request()->data->nombre ?? 1;
        
        if ($this->posteLibreModel->augmenterCapacite($id, $nombre)) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['success' => false], 500);
        }
    }

    /**
     * API - Vérifier la disponibilité d'un poste
     */
    public function checkDisponibilite($id) {
        $poste = $this->posteLibreModel->getPosteLibreById($id);
        
        if ($poste) {
            Flight::json([
                'success' => true,
                'disponible' => $poste['postes_disponibles'] > 0,
                'places' => $poste['postes_disponibles']
            ]);
        } else {
            Flight::json(['success' => false], 404);
        }
    }
}