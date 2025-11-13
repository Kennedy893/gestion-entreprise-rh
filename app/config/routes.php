<?php

use app\controllers\WelcomeController;
use app\controllers\CongeController;
use flight\Engine;
use flight\net\Router;
//use Flight;


$Welcome_Controller = new WelcomeController();
$router->get('/', [ $Welcome_Controller, 'home' ]);

$Conge_Controller = new CongeController();
$router->get('/demande_conge', [ $Conge_Controller, 'versDemande' ]);
$router->get('/liste_conge', [ $Conge_Controller, 'versListe' ]);
$router->get('/solde_conge', [ $Conge_Controller, 'versSolde' ]);