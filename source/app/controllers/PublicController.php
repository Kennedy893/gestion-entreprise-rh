<?php

namespace app\controllers;

use Flight;
use app\models\AnnonceModel;
use app\models\CandidatureModel;
use app\models\DocumentCandidatureModel;
use app\models\EntretienModel;

class PublicController {
    
    private $annonceModel;
    private $candidatureModel;
    private $documentModel;
    private $entretienModel;

    public function __construct() {
        $this->annonceModel = new AnnonceModel(Flight::db());
        $this->candidatureModel = new CandidatureModel(Flight::db());
        $this->documentModel = new DocumentCandidatureModel(Flight::db());
        $this->entretienModel = new EntretienModel(Flight::db());
    }

    public function showDepotDossier() {
        $annonces = $this->annonceModel->getAllAnnonces();
        $annonces = array_filter($annonces, fn($a) => $a['statut'] === 'active');
        
        Flight::render('public/depot_dossier', [
            'annonces' => $annonces,
            'page_title' => 'Dépôt de candidature'
        ]);
    }

    public function submitCandidature() {
        $data = [
            'nom' => Flight::request()->data->nom,
            'prenom' => Flight::request()->data->prenom,
            'email' => Flight::request()->data->email,
            'telephone' => Flight::request()->data->telephone,
            'genre' => Flight::request()->data->genre ?? null,
            'date_naissance' => Flight::request()->data->date_naissance,
            'adresse' => Flight::request()->data->adresse,
            'experience_annees' => Flight::request()->data->experience_annees,
            'qualifications' => Flight::request()->data->qualifications,
            'competences' => Flight::request()->data->competences,
            'dernier_diplome' => Flight::request()->data->dernier_diplome,
            'etablissement' => Flight::request()->data->etablissement,
            'langue_parlee' => Flight::request()->data->langue_parlee,
            'demande_contrat_direct' => Flight::request()->data->demande_contrat_direct ?? 0,
            'id_annonce' => Flight::request()->data->id_annonce
        ];

        $candidature_id = $this->candidatureModel->create($data);

        if ($candidature_id) {
            // Upload des fichiers
            $this->handleFileUploads($candidature_id);
            Flight::redirect('/depot-dossier?success=1');
        } else {
            Flight::redirect('/depot-dossier?error=1');
        }
    }

    private function handleFileUploads($candidature_id) {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        $types = ['cv', 'lettre_motivation', 'diplomes'];

        foreach ($types as $type) {
            // Crée le sous-dossier pour ce type si nécessaire
            $typeDir = $uploadDir . $type . '/';
            if (!is_dir($typeDir)) {
                mkdir($typeDir, 0777, true);
            }

            if (isset($_FILES[$type]) && $_FILES[$type]['error'] === UPLOAD_ERR_OK) {
                $files = $_FILES[$type];
                
                // Gérer upload multiple
                if (is_array($files['name'])) {
                    for ($i = 0; $i < count($files['name']); $i++) {
                        if ($files['error'][$i] === UPLOAD_ERR_OK) {
                            $this->uploadSingleFile(
                                $files['name'][$i],
                                $files['tmp_name'][$i],
                                $files['size'][$i],
                                $type,
                                $candidature_id,
                                $typeDir
                            );
                        }
                    }
                } else {
                    $this->uploadSingleFile(
                        $files['name'],
                        $files['tmp_name'],
                        $files['size'],
                        $type,
                        $candidature_id,
                        $typeDir
                    );
                }
            }
        }
    }

    private function uploadSingleFile($name, $tmpName, $size, $type, $candidatureId, $uploadDir) {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $destination)) {
            $this->documentModel->add([
                'type_document' => $type,
                'chemin_fichier' => '/uploads/' . $type . '/' . $filename,
                'nom_original' => $name,
                'taille' => $size,
                'id_candidature' => $candidatureId
            ]);
        }
    }

    public function showEntretiens() {
    try {
        $entretiens = $this->entretienModel->getEntretiensPublics();
    } catch (\Throwable $e) {
        // Logger l'erreur et tomber sur une liste vide (UI friendly)
        error_log("PublicController::showEntretiens error: " . $e->getMessage());
        $entretiens = [];
    }
    
    // Debug: log basic info to help diagnose why buttons may not appear
    try {
        $count = is_array($entretiens) ? count($entretiens) : 0;
        error_log("PublicController::showEntretiens - entretiens count: " . $count);
        if ($count > 0) {
            $sample = $entretiens[0];
            $s = sprintf("sample[0] candidature=%s statut_formation=%s id_mise=%s",
                $sample['id_candidature'] ?? 'N/A',
                $sample['statut_formation'] ?? 'N/A',
                $sample['id_mise_formation'] ?? 'N/A'
            );
            error_log("PublicController::showEntretiens - " . $s);
        }
    } catch (\Throwable $t) {
        error_log('PublicController::showEntretiens debug failed: ' . $t->getMessage());
    }

    Flight::render('public/liste_entretiens', [
        'entretiens' => $entretiens,
        'page_title' => 'Dates d\'entretien'
    ]);
}

}