<?php

namespace app\controllers;

use Flight;
use app\models\AuthModel;

class AuthController {
    
    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModel(Flight::db());
    }

    public function showLogin() {
        // Déjà connecté → rediriger vers dashboard
        if (isset($_SESSION['user'])) {
            $this->redirectToDashboard($_SESSION['user']['role']);
            return;
        }

        // Sinon → afficher la page login
        Flight::render('auth/login');
    }

    public function login() {
        $username = Flight::request()->data->username;
        $password = Flight::request()->data->password;
        $role = Flight::request()->data->role;

        // Validation des données
        if (empty($username) || empty($password) || empty($role)) {
            Flight::redirect('/?error=missing_fields');
            return;
        }

        // ✅ Login simple sans hash
        $user = $this->authModel->loginSimple($username, $password);

        if ($user) {
            // ✅ CORRECTION : Vérifier si le rôle sélectionné correspond au rôle de l'utilisateur
            // OU si l'utilisateur est admin (peut se connecter avec n'importe quel rôle)
            
            if ($user['role'] === $role) {
                // Cas normal : l'utilisateur se connecte avec son propre rôle
                $_SESSION['user'] = $user;
                $this->redirectToDashboard($user['role']);
            } 
            elseif ($user['role'] === 'admin') {
                // Cas admin : peut se connecter avec n'importe quel rôle sélectionné
                $_SESSION['user'] = $user;
                $this->redirectToDashboard($role); // Redirige vers le dashboard demandé
            }
            else {
                // Le rôle sélectionné ne correspond pas au rôle de l'utilisateur
                Flight::redirect('/?error=role_mismatch');
            }
        } else {
            // Username ou password incorrect
            Flight::redirect('/?error=invalid_credentials');
        }
    }

    public function logout() {
        session_destroy();
        Flight::redirect('/');
    }

    private function redirectToDashboard($role) {
        if ($role === 'manager' || $role === 'admin') {
            Flight::redirect('/manager/dashboard');
        } 
        elseif ($role === 'rh') {
            Flight::redirect('/rh/dashboard');
        } 
        else {
            Flight::redirect('/logout');
        }
    }

    public static function requireAuth() {
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/?error=unauthorized');
            exit;
        }
    }

    public static function requireRole($allowed_roles) {
        self::requireAuth();
        
        $user_role = $_SESSION['user']['role'];
        
        // Admin a accès à tout
        if ($user_role === 'admin') {
            return;
        }
        
        if (!in_array($user_role, $allowed_roles)) {
            Flight::redirect('/?error=forbidden');
            exit;
        }
    }
}