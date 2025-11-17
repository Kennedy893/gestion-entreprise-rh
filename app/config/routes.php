<?php

use app\controllers\WelcomeController;
use app\controllers\CongeController;
use flight\Engine;
use flight\net\Router;
//use Flight;


$Welcome_Controller = new WelcomeController();
$router->get('/', [ $Welcome_Controller, 'home' ]);

$Conge_Controller = new CongeController();
$router->get('/vers_demande_conge', [ $Conge_Controller, 'versDemande' ]);
$router->get('/vers_liste_conge', [ $Conge_Controller, 'listerConge' ]);
$router->get('/vers_solde_conge', [ $Conge_Controller, 'versSolde' ]);
$router->post('/demande_conge', [ $Conge_Controller, 'demanderConge' ]);
$router->post('/valider_conge', [ $Conge_Controller, 'validerConge' ]);