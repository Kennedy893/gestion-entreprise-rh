<?php

namespace app\controllers;

use Flight;
use app\models\FormationModel;

class FormationController {
    private $formationModel;

    public function __construct() {
        $this->formationModel = new FormationModel(Flight::db());
    }

    // Endpoint to receive the formulaire submission (delegates to model)
    public function submitFormulaire() {
        $req = Flight::request();
        $data = $req->data->getData();

        $id_candidature = $data['id_candidature'] ?? null;
        if (!$id_candidature) {
            Flight::redirect('/entretiens?error=no_candidature');
            return;
        }

        $latest = $this->formationModel->getLatestMiseByCandidature($id_candidature);
        $id_mise = $this->formationModel->ensureMiseEnFormation($id_candidature, $latest);

        $formId = $this->formationModel->createFormulaire($id_mise, $data);

        // store in session for candidate flow continuity
        $_SESSION['formation_data'] = $_SESSION['formation_data'] ?? [];
        $_SESSION['formation_data']['id_candidat_mise_formation'] = $id_mise;
        $_SESSION['formation_data']['id_formulaire_formation'] = $formId;

        Flight::redirect('/entretiens?success=formation_submitted&id_candidature=' . $id_candidature);
    }

    // Endpoint to receive questionnaire submission
    public function submitQuestionnaire() {
        $req = Flight::request();
        $data = $req->data->getData();

        $id_candidature = $data['id_candidature'] ?? null;
        $formulaire_id = $data['id_formulaire_formation'] ?? null;

        if (!$id_candidature || !$formulaire_id) {
            Flight::redirect('/entretiens?error=questionnaire_missing');
            return;
        }

        $qId = $this->formationModel->createOrUpdateQuestionnaire($formulaire_id, $id_candidature, $data);

        // update mise statut
        $stmt = Flight::db()->prepare("SELECT id_candidat_mise_formation FROM Formulaire_Formation WHERE id = ?");
        $stmt->execute([$formulaire_id]);
        $mise = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($mise && isset($mise['id_candidat_mise_formation'])) {
            $this->formationModel->updateMiseStatut($mise['id_candidat_mise_formation'], 'questionnaire_soumis');
        }

        Flight::redirect('/entretiens?success=questionnaire_submitted');
    }
}
