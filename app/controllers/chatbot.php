<?php
require_once 'config.php';
require_once 'gemini_helper.php';
require_once 'database_manager.php';


class Chatbot {
    private $gemini;
    private $db;
    
    public function __construct($db) {
        $api = "AIzaSyCHHaOb0YzidGU9m7UZxUoEgDOulFKcjpM";
        $this->gemini = new GeminiHelper( $api );
        $this->db = $db;
    }

    public function processQuestion($question) {
        // Étape 1: Convertir la question en SQL
        $sql_query = $this->gemini->generateSQL($question);
        
        // Vérifier si c'est une erreur
        if (is_array($sql_query) && isset($sql_query['error'])) {
            return [
                'success' => false,
                'reponse' => "Erreur lors de la génération SQL: " . $sql_query['error'],
                'sql_query' => null
            ];
        }
        
        // Nettoyer la requête SQL
        $sql_query = $this->cleanSQLQuery($sql_query);
        
        // Étape 2: Exécuter la requête SQL
        $results = $this->db->executeQuery($sql_query);
        
        // Étape 3: Formater la réponse
        $formatted_response = $this->gemini->formatResponse($results, $question);
        
        return [
            'success' => true,
            'reponse' => $formatted_response,
            'sql_query' => $sql_query,
            'raw_results' => $results
        ];
    }
    
    private function cleanSQLQuery($sql) {
        // Supprimer les backticks, markdown, etc.
        $sql = preg_replace('/```sql|```/', '', $sql);
        $sql = trim($sql);
        
        // S'assurer que ça se termine par un ;
        if (substr($sql, -1) !== ';') {
            $sql .= ';';
        }
        
        return $sql;
    }
}

// Utilisation du chatbot
if (isset($_POST['question'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    $question = trim($_POST['question']);
    
    if (empty($question)) {
        echo json_encode(['success' => false, 'reponse' => 'Veuillez poser une question.']);
        exit;
    }
    

    $response = $chatbot->processQuestion($question);
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}
?>