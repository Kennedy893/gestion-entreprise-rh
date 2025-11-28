<?php
require_once 'config.php';

class DatabaseManager {
    private $pdo;
    
    public function __construct() {
        
    }
    
    public function executeQuery($sql) {
        // Nettoyer la requête SQL
        $sql = trim( $sql);
        $sql = preg_replace('/;$/', '', $sql);
        
        // Journalisation (pour debug)
        error_log("SQL exécuté: " . $sql);
        
        try {
            // Si c'est un SELECT, COUNT, SUM, AVG, etc.
            if (stripos($sql, 'SELECT') === 0 || 
                stripos($sql, 'COUNT') !== false || 
                stripos($sql, 'SUM') !== false || 
                stripos($sql, 'AVG') !== false) {
                
                $stmt = $this->pdo->query($sql);
                $results = $stmt->fetchAll();
                
                // Si c'est une fonction d'agrégation (COUNT, SUM, AVG)
                if (stripos($sql, 'COUNT') !== false || 
                    stripos($sql, 'SUM') !== false || 
                    stripos($sql, 'AVG') !== false) {
                    return $results[0][array_keys($results[0])[0]] ?? 0;
                }
                
                return $results;
                
            } else {
                // Pour INSERT, UPDATE, DELETE
                $affected_rows = $this->pdo->exec($sql);
                return ['affected_rows' => $affected_rows];
            }
            

            //ra ho
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Méthode sécurisée avec paramètres préparés
     */
    public function executePreparedQuery($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            if (stripos($sql, 'SELECT') === 0) {
                return $stmt->fetchAll();
            } else {
                return ['affected_rows' => $stmt->rowCount()];
            }
            
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Méthode pour récupérer le schéma de la table
     */
    public function getTableSchema() {
        try {
            $stmt = $this->pdo->query("DESCRIBE etudiants");
            $columns = $stmt->fetchAll();
            
            $schema = "Table: etudiants\nColonnes:\n";
            foreach ($columns as $column) {
                $schema .= "- {$column['Field']} ({$column['Type']})\n";
            }
            
            return $schema;
            
        } catch (PDOException $e) {
            return "Table: etudiants (id, nom, prenom, age, email, filiere, moyenne)";
        }
    }
    
    /**
     * Méthode pour tester la connexion
     */
    public function testConnection() {
        try {
            $stmt = $this->pdo->query("SELECT 1");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function __destruct() {
        $this->pdo = null;
    }
}
?>