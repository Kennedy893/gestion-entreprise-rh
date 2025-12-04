<?php

namespace app\models;

use PDO;

class AuthModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * ✅ NOUVELLE MÉTHODE : Login simple sans hash
     * Comparaison directe des mots de passe
     */
    public function loginSimple($username, $password) {
        $sql = "SELECT * FROM utilisateurs WHERE username = ? AND password = ? AND actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            unset($user['password']); // Ne pas retourner le mot de passe en session
            return $user;
        }
        return false;
    }

    /**
     * Ancienne méthode avec hash (gardée au cas où)
     */
    public function login($username, $password) {
        $sql = "SELECT * FROM utilisateurs WHERE username = ? AND actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM utilisateurs WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            unset($user['password']);
        }
        return $user;
    }

    public function getAllManagers() {
        $sql = "SELECT * FROM utilisateurs WHERE role = 'manager' AND actif = 1";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRH() {
        $sql = "SELECT * FROM utilisateurs WHERE role = 'rh' AND actif = 1";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}