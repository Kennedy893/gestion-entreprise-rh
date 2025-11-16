<?php

use app\controllers\WelcomeController;
use app\controllers\ManagerController;
use app\controllers\RHController;
use flight\Engine;
use flight\net\Router;

/** @var Router $router */
/** @var Engine $app */

// Page d'accueil
$welcomeController = new WelcomeController();
$router->get('/', [$welcomeController, 'home']); 

// ==================== ROUTES MANAGER ====================
$managerController = new ManagerController();

// Dashboard Manager
$router->get('/manager/dashboard', [$managerController, 'dashboard']);

// Créer une annonce
$router->get('/manager/create-annonce', [$managerController, 'createAnnonceForm']);
$router->post('/manager/annonce/create', [$managerController, 'createAnnonce']);

// Voir les détails d'une annonce
$router->get('/manager/annonce/@id', [$managerController, 'viewAnnonce']);

// Mettre à jour le statut d'une annonce
$router->post('/manager/annonce/status/@id', [$managerController, 'updateStatut']);

// Supprimer une annonce
$router->get('/manager/annonce/delete/@id', [$managerController, 'deleteAnnonce']);

// ==================== ROUTES RH ====================
$rhController = new RHController();

// Dashboard RH
$router->get('/rh/dashboard', [$rhController, 'dashboard']);

// Liste des annonces
$router->get('/rh/annonces', [$rhController, 'viewAnnonces']);

// Voir une annonce spécifique
$router->get('/rh/annonce/@id', [$rhController, 'viewAnnonce']);