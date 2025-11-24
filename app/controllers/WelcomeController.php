<?php

namespace app\controllers;


use Flight;

class WelcomeController {

	public function __construct() {

	}

    public function home()
    {
        Flight::render('home');
    }
    public function homeRH()
    {
        Flight::render('login/loginRH');
    }public function homeManager()
    {
        Flight::render('login/loginManager');
    }public function homeEmp()
    {
        Flight::render('login/loginEmployer');
    }

}