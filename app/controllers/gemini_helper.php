<?php
class GeminiHelper {
    private $api_key;
    
    public function __construct($api_key) {
        $this->api_key = $api_key;
    }
    
    public function generateSQL($question) {
        $prompt = $this->buildSQLPrompt($question);
        return $this->callGeminiAPI($prompt);
    }
    
    public function formatResponse($results, $question) {
        if (empty($results) || isset($results['error'])) {
            return $this->formatErrorResponse($results);
        }
        
        $prompt = $this->buildFormatPrompt($results, $question);
        return $this->callGeminiAPI($prompt);
    }
    
    private function buildSQLPrompt($question) {
        return "Tu es un expert SQL MySQL. Convertit la question suivante en requête SQL VALIDE.

SCHEMA de la table:
Table: etudiants
Colonnes: id (INT), nom (VARCHAR), prenom (VARCHAR), age (INT), email (VARCHAR), filiere (VARCHAR), moyenne (FLOAT)

Table: note 
Colonnes: id(INT),note(INT),idEtudiants(INT)
Question: \"{$question}\"

Règles IMPORTANTES:
Pour la table etudiants : 
- Retourne UNIQUEMENT le code SQL sans explications
- Utilise SELECT * FROM etudiants sauf si besoin spécifique
- Sois précis dans les conditions WHERE
- Pas de backticks, pas de formatage markdown
- Si c'est un COUNT ou calcul, utilise la fonction appropriée


Pour la table note : 
- Retourne UNIQUEMENT le code SQL sans explications
- Utilise SELECT * FROM note sauf si besoin spécifique
- Sois précis dans les conditions WHERE
- Pas de backticks, pas de formatage markdown
- Si c'est un COUNT ou calcul, utilise la fonction appropriée
- idEtudiant est dans la table etudiants
SQL:";
    }
    
    private function buildFormatPrompt($results, $question) {
        $results_json = json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        return "Question: \"{$question}\"

Résultats de la base de données:
{$results_json}

Formate ces résultats en une réponse naturelle, claire et concise en français.
Sois informatif mais pas trop technique.
Si c'est une liste, présente-la de façon lisible.
Réponse:";
    }
    
    private function callGeminiAPI($prompt) {
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=AIzaSyCHHaOb0YzidGU9m7UZxUoEgDOulFKcjpM";
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1,
                'topK' => 1,
                'maxOutputTokens' => 1000,
            ]
        ];
        
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];
        
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        if ($response === FALSE) {
            return ['error' => 'Erreur API Gemini'];
        }
        
        $response_data = json_decode($response, true);
        
        if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
            return trim($response_data['candidates'][0]['content']['parts'][0]['text']);
        } else {
            return ['error' => 'Réponse Gemini invalide'];
        }
    }
    
    private function formatErrorResponse($results) {
        if (empty($results)) {
            return "Aucun résultat trouvé dans la base de données.";
        }
        
        if (isset($results['error'])) {
            return "Désolé, une erreur s'est produite: " . $results['error'];
        }
        
        return "Aucune donnée correspondante trouvée.";
    }
}
?>