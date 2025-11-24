<?php

use app\controllers\WelcomeController;
use app\controllers\CongeController;
use flight\Engine;
use flight\net\Router;
//use Flight;


$Welcome_Controller = new WelcomeController();
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