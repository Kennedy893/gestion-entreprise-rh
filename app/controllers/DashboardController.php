<?php

namespace app\controllers;
use Flight;

class DashboardController {

	public function __construct() {

	}

	public function showDashboardPage() 
    {
        $DashboardModel = Flight::DashboardModel();
        
        // Récupérer les statistiques de base
        $turnover = $DashboardModel->getTurnoverStats();
        $absenteeism = $DashboardModel->getAbsenteeismStats();
        $averageSeniority = $DashboardModel->getAverageSeniority();
        
        // Récupérer la distribution par âge pour le graphique
        $ageDistribution = $DashboardModel->getAgeDistribution();
        
        Flight::render('Dashboard/dashboard', [
            'turnover' => $turnover,
            'absenteeism' => $absenteeism,
            'averageSeniority' => round($averageSeniority, 2),
            'ageDistribution' => $ageDistribution
        ]);
    }

    public function getEmployeesByAge() 
    {
        $age = Flight::request()->query->age;
        
        if (!$age || !is_numeric($age)) {
            Flight::json(['error' => 'Âge invalide'], 400);
            return;
        }
        
        $DashboardModel = Flight::DashboardModel();
        $count = $DashboardModel->getEmployeesByAge($age);
        
        Flight::json([
            'age' => $age,
            'count' => $count,
            'message' => "Nombre d'employés de $age ans avec contrat actif: $count"
        ]);
    }
}