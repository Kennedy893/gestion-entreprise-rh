<?php

namespace app\models;

use Flight;
use PDO;

class DashboardModel
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function update_key_table($table, $key_name)
    {
        $stmt = $this->db->prepare("SELECT setval('{$table}_{$key_name}_seq', (SELECT MAX({$key_name}) FROM {$table}))");
        $stmt->execute();
    }

    public function getTurnoverStats($date_debut = '', $date_fin = '')
    {
        $sql = "SELECT COUNT(*) as turnover_count FROM contrat_employe WHERE 1=1";
        $params = [];

        if (!empty($date_debut)) {
            $sql .= " AND date_debut = '2025-01-01'";
            $params[] = $date_debut;
        }

        if (!empty($date_fin)) {
            $sql .= " AND date_fin = '2027-01-01'";
            $params[] = $date_fin;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['turnover_count'] ?? 0;
    }

    public function getAbsenteeismStats()

    //tsy ampy condition
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
    ROUND(AVG(
        EXTRACT(YEAR FROM AGE(CURRENT_DATE, date_debut)) + 
        EXTRACT(MONTH FROM AGE(CURRENT_DATE, date_debut)) / 12.0 +
        EXTRACT(DAY FROM AGE(CURRENT_DATE, date_debut)) / 365.0
    ), 2) AS anciennete_moyenne
FROM contrat_employe 
WHERE date_fin IS NULL 
  AND id_statut_contrat = 1;
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
            AND ce.date_fin <= '2999-12-31'
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
            WHERE ce.date_fin <= '2999-12-31'
            GROUP BY age
            ORDER BY age
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function countParFille()
    {
        $stmt = $this->db->prepare("
    SELECT COUNT(*) as cou
FROM contrat_employe ce
JOIN employe e ON ce.id_employe = e.id
WHERE ce.id_statut_contrat = 1 
  AND e.genre = 1;
        ");
        $stmt->execute([]);
        $result = $stmt->fetch();
        return $result['cou'] ?? 0;
    }
    public function countParGarcon()
    {
        $stmt = $this->db->prepare("
    SELECT COUNT(*) as cou
FROM contrat_employe ce
JOIN employe e ON ce.id_employe = e.id
WHERE ce.id_statut_contrat = 1 
  AND e.genre = 2;
        ");
        $stmt->execute([]);
        $result = $stmt->fetch();
        return $result['cou'] ?? 0;
    }
}
