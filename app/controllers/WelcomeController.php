<?php

namespace app\controllers;


use Flight;

class WelcomeController
{

    public function __construct() {}

    public function home()
    {
        Flight::render('sidebar/sidebar');
    }
    public function homeRH()
    {
        Flight::render('login/loginRH');
    }
    public function homeManager()
    {
        Flight::render('login/loginManager');
    }
    public function homeEmp()
    {
        Flight::render('login/loginEmployer');
    }

    public function loginRH () {
        Flight::redirect(constant('BASE_URL'));
    }

    public function loginManager () {
        Flight::redirect(constant('BASE_URL') . 'time/form-sheet');
    }

    public function login () {

        if ($_POST['role'] == 1) {
            Flight::redirect(constant('BASE_URL') . 'choose_soumission');
        } elseif ($_POST['role'] == 2) {
            Flight::redirect(constant('BASE_URL') . 'time/form-sheet');
        } elseif ($_POST['role'] == 3) {
            Flight::redirect(constant('BASE_URL'));
        }
        
    }


    // public function attestation () {
    //     $type
    //     $id_emp = 1;
    // }


}
