<?php

use app\controllers\WelcomeController;
use app\controllers\HController;
use flight\Engine;
use flight\net\Router;
//use Flight;


$Welcome_Controller = new WelcomeController();
$router->get('/', [ $Welcome_Controller, 'home' ]); 

$hController = new HController();
$router->group('/time' , function () use ($router,$hController){
    $router->get('/presences' , [ $hController, 'into_presence' ]);
    $router->post('/presences' , [ $hController, 'insert_presence' ]);
    $router->get('/releves' , [ $hController, 'into_releves' ]);
    $router->get('/timecards' , [ $hController, 'into_timecards' ]);
}) ;