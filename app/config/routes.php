<?php

use app\controllers\WelcomeController;
use app\controllers\DashboardController;

use flight\Engine;
use flight\net\Router;
//use Flight;


$Welcome_Controller = new WelcomeController();
$router->get('/', [ $Welcome_Controller, 'home' ]); 

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
