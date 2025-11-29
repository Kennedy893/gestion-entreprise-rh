<?php

use app\controllers\GenerationController;
use app\controllers\WelcomeController;
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
$Paie_Controller = new \app\controllers\PaieController;


$router->get('/', [ $Welcome_Controller, 'home' ]); 
$router->get('/paie', [$Paie_Controller,'etatDePaie']);
$router->get('/paie/fiche/@id', [$Paie_Controller, 'fichePaie']);
$router->get('/paie/details', [$Paie_Controller, 'detailsEmp']);
$router->post('/chatbot/ask',[$chatcontoller_Controller,'processQuestion']);
$router->get('/contratGen/@id',[$gen,'genererContratPdf']);
$router->get('/attestation/@id',[$gen,'genererAttestationTravailPdf']);
$router->get('/generation',[$gen,'homeGen']);

// Ajoutez ces routes à votre configuration Flight existante

// Routes du chatbot
Flight::route('GET /chatbot/toggle', function() {
    $_SESSION['chatbot_open'] = !($_SESSION['chatbot_open'] ?? false);
    Flight::redirect($_SERVER['HTTP_REFERER'] ?? '/');
});

?>