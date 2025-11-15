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