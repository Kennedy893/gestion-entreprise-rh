<?php

use app\controllers\GenerationController;
use app\controllers\WelcomeController;
use app\controllers\ManagerController;
use app\controllers\RHController;
use app\controllers\AuthController;
use app\controllers\PublicController;
use app\controllers\PosteLibreController;
use app\controllers\ContratController;
use app\controllers\HController;
use app\controllers\CongeController;
use app\controllers\EmployeController;
use app\controllers\PaieController;
use app\controllers\MessageController;
use app\controllers\DashboardController;
use app\controllers\ChatbotController;
use flight\Engine;
use flight\net\Router;

// SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** @var Router $router */
/** @var Engine $app */

// CONTROLLERS
$authController = new AuthController();
$publicController = new PublicController();
$managerController = new ManagerController();
$rhController = new RHController();
$posteLibreController = new PosteLibreController();
$contratController = new ContratController();

// =====================================================
// ROUTES PUBLIC
// =====================================================
$router->get('/', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
$router->get('/logout', [$authController, 'logout']);
$router->get('/depot-dossier', [$publicController, 'showDepotDossier']);
$router->post('/depot-dossier/submit', [$publicController, 'submitCandidature']);
$router->get('/entretiens', [$publicController, 'showEntretiens']);

// =====================================================
// MIDDLEWARES
// =====================================================
$checkManagerAuth = function() {
    AuthController::requireRole(['manager', 'admin']);
};

$checkRHAuth = function() {
    AuthController::requireRole(['rh', 'manager', 'admin']);
};

// =====================================================
// ROUTES MANAGER
// =====================================================

// Dashboard
$router->get('/manager/dashboard', function() use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->dashboard();
});

// Annonces
$router->get('/manager/create-annonce', function() use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->createAnnonceForm();
});

$router->post('/manager/annonce/create', function() use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->createAnnonce();
});

$router->get('/manager/annonce/@id', function($id) use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->viewAnnonce($id);
});

$router->get('/manager/annonce/@id/edit', function($id) use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->updateAnnonceForm($id);
});

$router->post('/manager/annonce/@id/update', function($id) use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->updateAnnonce($id);
});

$router->post('/manager/annonce/status/@id', function($id) use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->updateStatut($id);
});

$router->get('/manager/annonce/delete/@id', function($id) use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->deleteAnnonce($id);
});

// Postes Libres
$router->get('/manager/postes-libres', function() use ($checkManagerAuth, $posteLibreController) {
    $checkManagerAuth();
    $posteLibreController->index();
});

$router->get('/manager/postes-libres/create', function() use ($checkManagerAuth, $posteLibreController) {
    $checkManagerAuth();
    $posteLibreController->createForm();
});

$router->post('/manager/postes-libres/create', function() use ($checkManagerAuth, $posteLibreController) {
    $checkManagerAuth();
    $posteLibreController->create();
});

$router->get('/manager/postes-libres/edit/@id', function($id) use ($checkManagerAuth, $posteLibreController) {
    $checkManagerAuth();
    $posteLibreController->editForm($id);
});

$router->post('/manager/postes-libres/edit/@id', function($id) use ($checkManagerAuth, $posteLibreController) {
    $checkManagerAuth();
    $posteLibreController->update($id);
});

$router->get('/manager/postes-libres/delete/@id', function($id) use ($checkManagerAuth, $posteLibreController) {
    $checkManagerAuth();
    $posteLibreController->delete($id);
});

$router->post('/manager/postes-libres/@id/augmenter', function($id) use ($checkManagerAuth, $posteLibreController) {
    $checkManagerAuth();
    $posteLibreController->augmenterCapacite($id);
});

// API - Check disponibilité poste
$router->get('/api/poste/@id/disponibilite', function($id) use ($posteLibreController) {
    $posteLibreController->checkDisponibilite($id);
});

// Candidatures Manager
$router->get('/manager/candidatures', function() use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->viewCandidatures();
});

$router->get('/manager/candidature/@candidature_id/entretien/creer',
    function($candidature_id) use ($checkManagerAuth, $managerController) {
        $checkManagerAuth();
        $managerController->creerEntretien($candidature_id);
    }
);

$router->post('/manager/candidature/@candidature_id/entretien/creer',
    function($candidature_id) use ($checkManagerAuth, $managerController) {
        $checkManagerAuth();
        $managerController->submitCreerEntretien($candidature_id);
    }
);

$router->post('/manager/entretien/@entretien_id/publier',
    function($entretien_id) use ($checkManagerAuth, $managerController) {
        $checkManagerAuth();
        $managerController->publierEntretien($entretien_id);
    }
);

$router->get('/manager/entretien/@id', function($id) use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->faireEntretien($id);
});

$router->post('/manager/entretien/@id/noter', function($id) use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->submitNoteEntretien($id);
});

$router->post('/manager/entretien/@id/confirmer', function($id) use ($checkManagerAuth, $managerController) {
    $checkManagerAuth();
    $managerController->confirmEntretien($id);
});

// =====================================================
// ROUTES RH
// =====================================================

// Dashboard RH
$router->get('/rh/dashboard', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->dashboard();
});

// Annonces
$router->get('/rh/annonces', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->viewAnnonces();
});

$router->get('/rh/annonce/@id', function($id) use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->viewAnnonce($id);
});

// Candidatures RH
$router->get('/rh/candidatures', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->viewCandidatures();
});

$router->get('/rh/candidature/@id', function($id) use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->viewCandidature($id);
});

$router->post('/rh/candidature/envoyer-manager', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->envoyerVersManager();
});

// Candidats en attente d'évaluation
$router->get('/rh/candidats-en-attente', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->viewCandidatsEnAttente();
});

// Entretiens
$router->post('/rh/entretien/planifier', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->planifierEntretien();
});

$router->get('/rh/resultats-entretiens', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->viewResultatsEntretiens();
});

$router->get('/rh/entretien/@id/evaluer', function($id) use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->evaluerEntretien($id);
});

$router->post('/rh/entretien/@id/evaluer', function($id) use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->submitEvaluationEntretien($id);
});

// Candidatures Acceptées/Rejetées
$router->get('/rh/candidatures-acceptees', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->viewCandidaturesAcceptees();
});

$router->get('/rh/candidatures-rejetees', function() use ($checkRHAuth, $rhController) {
    $checkRHAuth();
    $rhController->viewCandidaturesRejetees();
});

// Résultats candidats (unifié)
$router->get('/rh/resultats-candidats', function() use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\ValidationController())->resultatsCandidatsPage();
});

// API JSON pour validation
$router->get('/validation/candidats_recus', function() {
    (new \app\controllers\ValidationController())->candidatsRecus();
});

// Contrats
$router->get('/rh/contrats', function() use ($checkRHAuth, $contratController) {
    $checkRHAuth();
    $contratController->index();
});

$router->get('/rh/contrat/create/@candidature_id', function($candidature_id) use ($checkRHAuth, $contratController) {
    $checkRHAuth();
    $contratController->createForm($candidature_id);
});

$router->post('/rh/contrat/create/@candidature_id', function($candidature_id) use ($checkRHAuth, $contratController) {
    $checkRHAuth();
    $contratController->create($candidature_id);
});

$router->get('/rh/contrat/@id', function($id) use ($checkRHAuth, $contratController) {
    $checkRHAuth();
    $contratController->view($id);
});

$router->get('/rh/contrat/@id/pdf', function($id) use ($checkRHAuth, $contratController) {
    $checkRHAuth();
    $contratController->downloadPDF($id);
});

// Employés
$router->get('/rh/employes', function() use ($checkRHAuth, $contratController) {
    $checkRHAuth();
    $contratController->employes();
});

$router->get('/rh/employe/@id', function($id) use ($checkRHAuth, $contratController) {
    $checkRHAuth();
    $contratController->viewEmploye($id);
});


// Matching automatique
$router->get('/rh/matching-automatique', function() use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->matchingAutomatique();
});

// Candidats en attente
$router->get('/rh/candidats-en-attente', function() use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->candidatsEnAttente();
});

// Accepter/Rejeter formation
$router->post('/rh/competences/accepter-formation/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->accepterMiseEnFormation($id);
});

$router->post('/rh/competences/rejeter-formation/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->rejeterMiseEnFormation($id);
});

// Démarrer formation
$router->post('/rh/competences/demarrer-formation/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->demarrerFormation($id);
});

// Formulaire candidat
$router->get('/candidat/formation/@id_candidature', function($id) {
    (new \app\controllers\CompetenceController())->formationFormulaire($id);
});

// Submit formation (candidat rempli le formulaire de formation)
$router->post('/rh/competences/submit-formation', function() {
    (new \app\controllers\CompetenceController())->submitFormationFormulaire();
});

// Confirmation formation (voir les infos remplies)
$router->get('/candidat/confirmer-formation', function() {
    (new \app\controllers\CompetenceController())->showFormationReview();
});

// Questionnaire candidat
$router->get('/candidat/questionnaire-start', function() {
    (new \app\controllers\CompetenceController())->startQuestionnaire();
});

$router->post('/candidat/questionnaire-start', function() {
    (new \app\controllers\CompetenceController())->startQuestionnaire();
});

$router->post('/candidat/questionnaire/submit', function() {
    (new \app\controllers\CompetenceController())->submitQuestionnaire();
});

$router->post('/debug/questionnaire-post', function() {
    (new \app\controllers\CompetenceController())->debugPostData();
});

// Questionnaire candidat (après soumission du formulaire de formation)
$router->get('/candidat/questionnaire/@id', function($id) {
    (new \app\controllers\CompetenceController())->showQuestionnaire($id);
});

// Évaluation formation RH
$router->get('/rh/evaluer-formation/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->evaluerFormation($id);
});

$router->post('/rh/competences/submit-evaluation-formation', function() use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->submitEvaluationFormation();
});

// Gestion employés
$router->get('/rh/competences/competence-employes', function() use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->competencesEmployes();
});

$router->get('/rh/competences/detail-employe/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->detailEmploye($id);
});

$router->post('/rh/competences/update-score/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->updateScorePerformance($id);
});

$router->post('/rh/competences/remettre-formation/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->remettreEnFormation($id);
});

$router->post('/rh/competences/reintegrer/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->reintegrerEmploye($id);
});

$router->post('/rh/competences/licencier/@id', function($id) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->licencierEmploye($id);
});

// Statistiques
$router->get('/rh/competences/statistiques', function() use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->statistiques();
});

$router->post('/candidat/accepter-formation/@id_candidature', function($id_candidature) {
    (new \app\controllers\CompetenceController())->candidatAccepteFormation($id_candidature);
});

// Route RH : Voir détails questionnaire formation
$router->get('/rh/questionnaire-formation/@id_formulaire', function($id_formulaire) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->voirQuestionnaireFormationRH($id_formulaire);
});

// =====================================================
// ÉVALUATION QUESTIONNAIRE FORMATION (NEW ROUTES)
// =====================================================

// RH : Afficher interface d'évaluation du questionnaire
$router->get('/rh/competences/evaluer-questionnaire/@id_candidature', function($id_candidature) use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->evaluerQuestionnaireFormation($id_candidature);
});

// RH : Soumettre l'évaluation du questionnaire
$router->post('/rh/competences/evaluer-questionnaire-submit', function() use ($checkRHAuth) {
    $checkRHAuth();
    (new \app\controllers\CompetenceController())->evaluerQuestionnaireSubmit();
});

// =====================================================
// MODIFIER LA ROUTE EXISTANTE DU FORMULAIRE FORMATION
// =====================================================
$Welcome_Controller = new WelcomeController();
$chatcontoller_Controller = new ChatbotController();
$gen = new GenerationController();
$hController = new HController();
$Conge_Controller = new CongeController();
$Message_Controller = new MessageController();
$Employe_Controller = new EmployeController();
$DashboardController = new DashboardController();
$Paie_Controller = new PaieController();
$hController = new HController();
$Paie_Controller = new \app\controllers\PaieController();



// Routes pour login 
$router->get('/loginRH', [$Welcome_Controller, 'homeRH']);
$router->get('/loginManager', [$Welcome_Controller, 'homeManager']);
$router->get('/login', [$Welcome_Controller, 'homeEmp']);



$router->get('/', [$Welcome_Controller, 'home']);


$router->get('/dashboard', [$DashboardController, 'showDashboardPage']);
$router->get('/dashboard/employees-by-age', [$DashboardController, 'getEmployeesByAge']);


$router->group('/time', function () use ($router, $hController) {
    $router->get('/presences', [$hController, 'into_presence']);
    $router->post('/presences', [$hController, 'insert_presence']);
    $router->get('/employees', [$hController, 'into_employees']);
    $router->get('/releves', [$hController, 'into_releves']);
    $router->get('/timecards', [$hController, 'into_timecards']);
    $router->get('/form-sheet', [$hController, 'into_temp_general']);
});

$router->group('/performance', function () use ($router, $hController) {
    $router->get('/calendar', [$hController, 'into_calendar']);
    $router->get('/dashboard', [$hController, 'into_performance_dashboard']);
});

$router->get('/', [$Welcome_Controller, 'home']);



// DEMANDE CONGE
$router->get('/vers_demande_conge', [$Conge_Controller, 'versDemande']);
$router->post('/demande_conge', [$Conge_Controller, 'demanderConge']);
// VALIDATION MANAGER
$router->get('/vers_liste_conge', [$Conge_Controller, 'listerConge']);
$router->get('/liste_conge', [$Conge_Controller, 'listerConge']);
$router->post('/valider_conge', [$Conge_Controller, 'validerConge']);
// VALIDATION RH
$router->get('/validation_rh', [$Conge_Controller, 'listerCongeRH']);
$router->post('/valider_conge_rh', [$Conge_Controller, 'validerCongeRH']);
// SOLDE
$router->get('/vers_solde_conge', [$Conge_Controller, 'consulterSolde']);
$router->get('/details_solde', [$Conge_Controller, 'detailsSolde']);


$router->get('/vers_messagerie', [$Message_Controller, 'messagerie']);
$router->post('/envoyer_message', [$Message_Controller, 'envoyer']);



$router->get('/choose_consultation', [$Employe_Controller, 'chooseConsultation']);
$router->get('/choose_soumission', [$Employe_Controller, 'chooseSoumission']);
$router->get('/demande_attestation', [$Employe_Controller, 'chooseAttestation']);
$router->get('/demande_remboursement', [$Employe_Controller, 'versRemboursement']);



$router->get('/paie', [$Paie_Controller, 'etatDePaie']);
$router->get('/paie/fiche/@id', [$Paie_Controller, 'fichePaie']);
$router->get('/paie/details', [$Paie_Controller, 'detailsEmp']);
$router->get('/paie/etats/export', [$Paie_Controller, 'exportEtatDePaie']);
$router->get('/paie/fiche/export/@id', [$Paie_Controller, 'exportFichePaiePDF']);


$router->group('/time', function () use ($router, $hController) {
    $router->get('/presences', [$hController, 'into_presence']);
    $router->post('/presences', [$hController, 'insert_presence']);
    $router->get('/employees', [$hController, 'into_employees']);
    $router->get('/releves', [$hController, 'into_releves']);
    $router->get('/timecards', [$hController, 'into_timecards']);
    $router->get('/form-sheet', [$hController, 'into_temp_general']);
});
$router->get('/demande_conge', [$Conge_Controller, 'versDemande']);
$router->get('/liste_conge', [$Conge_Controller, 'versListe']);
$router->get('/solde_conge', [$Conge_Controller, 'versSolde']);



$router->get('/', [$Welcome_Controller, 'home']);
$router->get('/paie', [$Paie_Controller, 'etatDePaie']);
$router->get('/paie/fiche/@id', [$Paie_Controller, 'fichePaie']);
$router->get('/paie/details', [$Paie_Controller, 'detailsEmp']);
$router->post('/chatbot/ask', [$chatcontoller_Controller, 'processQuestion']);
$router->get('/contratGen/@id', [$gen, 'genererContratPdf']);
$router->get('/attestation/@id', [$gen, 'genererAttestationTravailPdf']);
$router->get('/generation', [$gen, 'homeGen']);
$router->get('/prediction/@id', [$hController, 'get_prediction']);


// Ajoutez ces routes à votre configuration Flight existante

// Routes du chatbot


// $router->get('/paie/etats/export',[$Paie_Controller, 'exportEtatDePaie']);
// $router->get('/paie/fiche/export/@id', [$Paie_Controller, 'exportFichePaiePDF']);

// $DashboardController = new DashboardController();
// $router->get('/dashboard', [$DashboardController, 'showDashboardPage']);
// $router->get('/dashboard/employees-by-age', [$DashboardController, 'getEmployeesByAge']);
