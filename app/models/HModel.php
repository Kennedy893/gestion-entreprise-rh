<?php

namespace app\models;

use Flight;
use PDO;


class HModel {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function get_generalised($table, $colonne_name, $colonnes, $valeurs, $request,$val_request)
    {
        $sql = "SELECT ".$colonne_name." FROM ".$table." WHERE 1=1";
        foreach ($colonnes as $colonne) 
        {
            $sql .= " AND " . $colonne . " = ?";
        }
        if($request != null && $request != "")
        {
            $sql .= " ".$request;
        }
        $stmt = $this->db->prepare($sql);
        $all_valeurs = array_merge($valeurs, $val_request);
        $stmt->execute($all_valeurs);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert_generalised($table, $colonnes, $valeurs)
    {
        $sql = "INSERT INTO ".$table." (";
        foreach ($colonnes as $colonne)
        {
            if($colonne != end($colonnes))
            {
                $sql.= $colonne . ",";
            }
            else
            {
                $sql.= $colonne . ") VALUES (";
            }
        }
        foreach ($valeurs as $valeur)
        {
            if($valeur != end($valeurs))
            {
                $sql.= "?,";
            }
            else
            {
                $sql.= "?)";
            }
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($valeurs);
    }

    public function update_generalised($table, $colonnes, $valeurs, $where_colonnes, $where_valeurs, $request = '', $val_request = [])
    {
        $setParts = array_map(fn($c) => "$c = ?", $colonnes);
        $sql = "UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE 1=1";

        $params = $valeurs;

        foreach ($where_colonnes as $i => $wc) {
            $val = $where_valeurs[$i] ?? null;
            if ($val === null) {
                $sql .= " AND {$wc} IS NULL";
            } else {
                $sql .= " AND {$wc} = ?";
                $params[] = $val;
            }
        }

        if (!empty($request)) {
            $sql .= " " . $request;
            if (!is_array($val_request)) { $val_request = [$val_request]; }
            $params = array_merge($params, $val_request);
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    

}