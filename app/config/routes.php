<?php

use app\controllers\WelcomeController;
use app\controllers\HController;
use app\controllers\CongeController;
use app\controllers\DashboardController;

use flight\Engine;
use flight\net\Router;
//use Flight;


$Welcome_Controller = new WelcomeController();
$router->get('/', [ $Welcome_Controller, 'home' ]); 
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
$router->get('/', [ $Welcome_Controller, 'home' ]);

$Conge_Controller = new CongeController();
$router->get('/demande_conge', [ $Conge_Controller, 'versDemande' ]);
$router->get('/liste_conge', [ $Conge_Controller, 'versListe' ]);
$router->get('/solde_conge', [ $Conge_Controller, 'versSolde' ]);
$Paie_Controller = new \app\controllers\PaieController;


$router->get('/', [ $Welcome_Controller, 'home' ]); 
$router->get('/paie', [$Paie_Controller,'etatDePaie']);
$router->get('/paie/fiche/@id', [$Paie_Controller, 'fichePaie']);
$router->get('/paie/details', [$Paie_Controller, 'detailsEmp']);

$DashboardController = new DashboardController();
$router->get('/dashboard', [$DashboardController, 'showDashboardPage']);
$router->get('/dashboard/employees-by-age', [$DashboardController, 'getEmployeesByAge']);
