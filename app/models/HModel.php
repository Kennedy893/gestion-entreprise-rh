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
    public function get_generalised($table, $colonne_name, $colonnes, $valeurs, $request)
    {
        $sql = "SELECT ".$colonne_name." FROM ".$table." WHERE 1=1";
        if($request != null && $request != "")
        {
            $sql .= " ".$request;
        }
        foreach ($colonnes as $colonne) 
        {
            $sql .= " AND " . $colonne . " = ?";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($valeurs);
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

    public function update_generalised($table, $colonnes, $valeurs, $where_colonnes, $where_valeurs)
    {
        $sql = "UPDATE ".$table." SET ";
        foreach ($colonnes as $colonne)
        {
            if($colonne != end($colonnes))
            {
                $sql.= $colonne . " = ?, ";
            }
            else
            {
                $sql.= $colonne . " = ? ";
            }
        }
        $sql .= " WHERE 1=1 ";
        foreach ($where_colonnes as $where_colonne)
        {
            $sql .= " AND " . $where_colonne . " = ? ";
        }
        $stmt = $this->db->prepare($sql);
        $all_valeurs = array_merge($valeurs, $where_valeurs);
        return $stmt->execute($all_valeurs);
    }



}