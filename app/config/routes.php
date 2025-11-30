<?php

use app\controllers\GenerationController;
use app\controllers\WelcomeController;
use app\controllers\HController;
use app\controllers\CongeController;
use app\controllers\EmployeController;
use app\controllers\PaieController;
use app\controllers\MessageController;
use app\controllers\DashboardController;
use app\controllers\ChatbotController;

use flight\Engine;
use flight\net\Router;
//use Flight;


$Welcome_Controller = new WelcomeController();
$router->get('/', [ $Welcome_Controller, 'home' ]); 
$chatcontoller_Controller = new ChatbotController();
$gen=new GenerationController() ;
// Routes pour les statistiques
$DashboardController = new DashboardController();
// Configuration des routes pour le tableau de bord RH
$router->get('/dashboard', [$DashboardController, 'showDashboardPage']);
$router->get('/dashboard/employees-by-age', [$DashboardController, 'getEmployeesByAge']);
$router->get('/loginRH', [ $Welcome_Controller, 'homeRH' ]); 
$router->get('/loginManager', [ $Welcome_Controller, 'homeManager' ]); 
$router->get('/login', [ $Welcome_Controller, 'homeEmp' ]); 

$hController = new HController();
$router->group('/time' , function () use ($router,$hController){
    $router->get('/presences' , [ $hController, 'into_presence' ]);
    $router->post('/presences' , [ $hController, 'insert_presence' ]);
    $router->get('/employees' , [ $hController, 'into_employees' ]);
    $router->get('/releves' , [ $hController, 'into_releves' ]);
    $router->get('/timecards' , [ $hController, 'into_timecards' ]);
    $router->get('/form-sheet' , [ $hController, 'into_temp_general' ]);
}) ;

$router->group('/performance' , function () use ($router,$hController){
    $router->get('/calendar' , [ $hController, 'into_calendar' ]);
    $router->get('/dashboard', [ $hController, 'into_performance_dashboard' ]);
}) ;

$router->get('/', [ $Welcome_Controller, 'home' ]);


$Conge_Controller = new CongeController();
// DEMANDE CONGE
$router->get('/vers_demande_conge', [ $Conge_Controller, 'versDemande' ]);
$router->post('/demande_conge', [ $Conge_Controller, 'demanderConge' ]);
// VALIDATION MANAGER
$router->get('/vers_liste_conge', [ $Conge_Controller, 'listerConge' ]);
$router->get('/liste_conge', [ $Conge_Controller, 'listerConge' ]);
$router->post('/valider_conge', [ $Conge_Controller, 'validerConge' ]);
// VALIDATION RH
$router->get('/validation_rh', [ $Conge_Controller, 'listerCongeRH' ]);
$router->post('/valider_conge_rh', [ $Conge_Controller, 'validerCongeRH' ]);
// SOLDE
$router->get('/vers_solde_conge', [ $Conge_Controller, 'consulterSolde' ]);
$router->get('/details_solde', [ $Conge_Controller, 'detailsSolde' ]);

$Message_Controller = new MessageController();
$router->get('/vers_messagerie', [ $Message_Controller, 'messagerie' ]);
$router->post('/envoyer_message', [ $Message_Controller, 'envoyer' ]);


$Employe_Controller = new EmployeController();
$router->get('/choose_consultation', [ $Employe_Controller, 'chooseConsultation' ]);
$router->get('/choose_soumission', [ $Employe_Controller, 'chooseSoumission' ]);
$router->get('/demande_attestation', [ $Employe_Controller, 'chooseAttestation' ]);
$router->get('/demande_remboursement', [ $Employe_Controller, 'versRemboursement' ]);

$Paie_Controller = new PaieController();
$router->get('/paie', [$Paie_Controller,'etatDePaie']);
$router->get('/paie/fiche/@id', [$Paie_Controller, 'fichePaie']);
$router->get('/paie/details', [$Paie_Controller, 'detailsEmp']);
$router->get('/paie/etats/export',[$Paie_Controller, 'exportEtatDePaie']);
$router->get('/paie/fiche/export/@id', [$Paie_Controller, 'exportFichePaiePDF']);

$hController = new HController();
$router->group('/time' , function () use ($router,$hController){
    $router->get('/presences' , [ $hController, 'into_presence' ]);
    $router->post('/presences' , [ $hController, 'insert_presence' ]);
    $router->get('/employees' , [ $hController, 'into_employees' ]);
    $router->get('/releves' , [ $hController, 'into_releves' ]);
    $router->get('/timecards' , [ $hController, 'into_timecards' ]);
    $router->get('/form-sheet' , [ $hController, 'into_temp_general' ]);
}) ;
$router->get('/demande_conge', [ $Conge_Controller, 'versDemande' ]);
$router->get('/liste_conge', [ $Conge_Controller, 'versListe' ]);
$router->get('/solde_conge', [ $Conge_Controller, 'versSolde' ]);
$Paie_Controller = new \app\controllers\PaieController;


$router->get('/', [ $Welcome_Controller, 'home' ]); 
$router->get('/paie', [$Paie_Controller,'etatDePaie']);
$router->get('/paie/fiche/@id', [$Paie_Controller, 'fichePaie']);
$router->get('/paie/details', [$Paie_Controller, 'detailsEmp']);
$router->post('/chatbot/ask',[$chatcontoller_Controller,'processQuestion']);
$router->get('/contratGen/@id',[$gen,'genererContratPdf']);
$router->get('/attestation/@id',[$gen,'genererAttestationTravailPdf']);
$router->get('/generation',[$gen,'homeGen']);
$router->get('/prediction/@id',[$hController,'get_prediction']);

// Ajoutez ces routes à votre configuration Flight existante

// Routes du chatbot


// $router->get('/paie/etats/export',[$Paie_Controller, 'exportEtatDePaie']);
// $router->get('/paie/fiche/export/@id', [$Paie_Controller, 'exportFichePaiePDF']);

// $DashboardController = new DashboardController();
// $router->get('/dashboard', [$DashboardController, 'showDashboardPage']);
// $router->get('/dashboard/employees-by-age', [$DashboardController, 'getEmployeesByAge']);
?>
