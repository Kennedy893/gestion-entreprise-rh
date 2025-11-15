<?php

namespace app\models;

use Flight;
use PDO;

class DashboardModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function update_key_table($table,$key_name)
    {
        $stmt = $this->db->prepare("SELECT setval('{$table}_{$key_name}_seq', (SELECT MAX({$key_name}) FROM {$table}))");
        $stmt->execute();
    }
    
    public function getTurnoverStats($date_debut = '', $date_fin = '') 
    { 
        $sql = "SELECT COUNT(*) as turnover_count FROM contrat_employe WHERE 1=1";
        $params = [];
        
        if (!empty($date_debut)) {
            $sql .= " AND date_debut = ?";
            $params[] = $date_debut;
        }
        
        if (!empty($date_fin)) {
            $sql .= " AND date_fin = ?";
            $params[] = $date_fin;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params); 
        $result = $stmt->fetch();
        return $result['turnover_count'] ?? 0;
    }

    public function getAbsenteeismStats() 
    { 
        $stmt = $this->db->prepare("SELECT COUNT(*) as absence_count FROM absence");
        $stmt->execute(); 
        $result = $stmt->fetch();
        return $result['absence_count'] ?? 0;
    }

    public function getAverageSeniority() 
    { 
        $stmt = $this->db->prepare("
            SELECT 
                AVG(EXTRACT(YEAR FROM AGE(
                    DATE '2025-01-01',
                    date_debut
                ))) AS anciennete_moyenne
            FROM contrat_employe
        ");
        $stmt->execute(); 
        $result = $stmt->fetch();
        return $result['anciennete_moyenne'] ?? 0;
    }

    public function getEmployeesByAge($age) 
    { 
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count_employees
            FROM contrat_employe ce
            JOIN employe e ON ce.id_employe = e.id
            WHERE EXTRACT(YEAR FROM AGE(CURRENT_DATE, e.date_naissance)) = ?
            AND ce.date_fin >= '2999-12-31'
        ");
        $stmt->execute([$age]); 
        $result = $stmt->fetch();
        return $result['count_employees'] ?? 0;
    }

    public function getAgeDistribution() 
    { 
        $stmt = $this->db->prepare("
            SELECT 
                EXTRACT(YEAR FROM AGE(CURRENT_DATE, e.date_naissance)) as age,
                COUNT(*) as count_employees
            FROM contrat_employe ce
            JOIN employe e ON ce.id_employe = e.id
            WHERE ce.date_fin >= '2999-12-31'
            GROUP BY age
            ORDER BY age
        ");
        $stmt->execute(); 
        return $stmt->fetchAll();
    }
}