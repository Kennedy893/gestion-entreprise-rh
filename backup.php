<?php

// namespace app\models;

// use Flight;
// use PDO;
// // app/models/ChatbotPaieModel.php

// class ChatbotPaieModel
// {
//     private $db;
//     private $paieModel;

//     public function __construct(PDO $db)
//     {
//         $this->db = $db;
//         $this->paieModel = new PaieModel($db);
//     }

//     /**
//      * Flux principal : analyser la question puis exécuter l'action
//      */
//     public function traiterAvecGemini($question)
//     {
//         $analyse = $this->analyserQuestionAvecGemini($question);

//         switch ($analyse['action']) {
//             case 'SALAIRE_NET':
//                 return $this->executerSalaireNet($analyse['parametre'], $question);
//             case 'RETENUES':
//                 return $this->executerRetenues($analyse['parametre'], $question);
//             case 'AVANTAGES':
//                 return $this->executerAvantages($analyse['parametre'], $question);
//             case 'MASSE_SALARIALE':
//                 return $this->executerMasseSalariale($question);
//             case 'LISTE_EMPLOYES':
//                 return $this->executerListeEmployes($question);
//             default:
//                 return $this->reponseParDefaut($question);
//         }
//     }

//     /* ------------------ actions ------------------ */

//     private function executerSalaireNet($nomEmploye, $questionOriginale)
//     {
//         $resultat = $this->getSalaireNetParNom($nomEmploye);

//         if (isset($resultat['error'])) {
//             if (isset($resultat['employes'])) {
//                 $noms = array_map(function ($emp) {
//                     return trim(($emp['prenom'] ?? '') . ' ' . ($emp['nom'] ?? ''));
//                 }, $resultat['employes']);
//                 return [
//                     'type' => 'salaire',
//                     'reponse' => "Plusieurs employés correspondent à '{$nomEmploye}': " . implode(', ', $noms) . ". Veuillez préciser.",
//                     'suggestions' => $noms
//                 ];
//             }
//             return [
//                 'type' => 'salaire',
//                 'reponse' => "Je n'ai pas trouvé d'employé nommé '{$nomEmploye}'.",
//                 'suggestions' => ['liste des employés']
//             ];
//         }

//         $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'salaire_net');

//         return [
//             'type' => 'salaire',
//             'reponse' => $reponseNaturelle,
//             'details' => $resultat
//         ];
//     }

//     private function executerRetenues($nomEmploye, $questionOriginale)
//     {
//         $resultat = $this->getRetenuesParNom($nomEmploye);
//         if (isset($resultat['error'])) {
//             return [
//                 'type' => 'retenues',
//                 'reponse' => "Employé '{$nomEmploye}' non trouvé.",
//                 'suggestions' => ['liste des employés']
//             ];
//         }
//         $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'retenues');
//         return [
//             'type' => 'retenues',
//             'reponse' => $reponseNaturelle,
//             'details' => $resultat
//         ];
//     }

//     private function executerAvantages($nomEmploye, $questionOriginale)
//     {
//         $resultat = $this->getAvantagesParNom($nomEmploye);
//         if (isset($resultat['error'])) {
//             return [
//                 'type' => 'avantages',
//                 'reponse' => "Employé '{$nomEmploye}' non trouvé.",
//                 'suggestions' => ['liste des employés']
//             ];
//         }
//         $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'avantages');
//         return [
//             'type' => 'avantages',
//             'reponse' => $reponseNaturelle,
//             'details' => $resultat
//         ];
//     }

//     private function executerMasseSalariale($questionOriginale)
//     {
//         $resultat = $this->paieModel->getContratEmployeByDate(date('Y-m-d'));
//         $reponseNaturelle = $this->formaterReponseNaturelle(['masse' => $resultat], 'masse_salariale');
//         return [
//             'type' => 'masse_salariale',
//             'reponse' => $reponseNaturelle,
//             'details' => $resultat
//         ];
//     }

//     private function executerListeEmployes($questionOriginale)
//     {
//         $stmt = $this->db->query("SELECT id, nom, prenom FROM employe ORDER BY nom, prenom");
//         $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $reponseNaturelle = $this->formaterReponseNaturelle(['employes' => $employes], 'liste_employes');
//         return [
//             'type' => 'liste_employes',
//             'reponse' => $reponseNaturelle,
//             'details' => $employes
//         ];
//     }

//     private function reponseParDefaut($question)
//     {
//         return [
//             'type' => 'aide',
//             'reponse' => "Je peux aider sur : salaire net, retenues, avantages, masse salariale, liste des employés. Exemple : 'salaire net de Dupont'.".$question,
//             'suggestions' => [
//                 'salaire net de Dupont',
//                 'retenues CNAPS de Marie',
//                 'avantages Martin',
//                 'masse salariale',
//                 'liste des employés'
//             ]
//         ];
//     }

//     /* ------------------ utilitaires ------------------ */

//     public function rechercherEmployeParNom($nom)
//     {
//         $sql = "SELECT * FROM employe WHERE LOWER(nom) LIKE LOWER(?) OR LOWER(prenom) LIKE LOWER(?)";
//         $stmt = $this->db->prepare($sql);
//         $term = "%" . trim($nom) . "%";
//         $stmt->execute([$term, $term]);
//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     }

//     public function getSalaireNetParNom($nomEmploye)
//     {
//         $employes = $this->rechercherEmployeParNom($nomEmploye);
//         if (empty($employes)) return ['error' => 'Employé non trouvé'];
//         if (count($employes) > 1) return ['error' => 'plusieurs', 'employes' => $employes];

//         $id = $employes[0]['id'];
//         $fiche = $this->paieModel->getContratEmployeByIdEmploye($id, date('Y-m-d'));
//         if (!$fiche) return ['error' => 'Aucun contrat actif pour cet employé'];
//         return [
//             'employe' => $employes[0],
//             'salaire_net' => $fiche['salaire_net'],
//             'salaire_brut' => $fiche['salaire_brut'],
//             'fiche_complete' => $fiche
//         ];
//     }

//     public function getRetenuesParNom($nomEmploye)
//     {
//         $employes = $this->rechercherEmployeParNom($nomEmploye);
//         if (empty($employes)) return ['error' => 'Employé non trouvé'];

//         $id = $employes[0]['id'];
//         $fiche = $this->paieModel->getContratEmployeByIdEmploye($id, date('Y-m-d'));
//         if (!$fiche) return ['error' => 'Aucun contrat actif'];

//         return [
//             'employe' => $employes[0],
//             'retenues' => [
//                 'cnaps' => $fiche['cnaps_1'],
//                 'ostie' => $fiche['ostie_1'],
//                 'irsa' => $fiche['irsa'],
//                 'total' => $fiche['total_ret']
//             ],
//             'fiche_complete' => $fiche
//         ];
//     }

//     public function getAvantagesParNom($nomEmploye)
//     {
//         $employes = $this->rechercherEmployeParNom($nomEmploye);
//         if (empty($employes)) return ['error' => 'Employé non trouvé'];

//         $id = $employes[0]['id'];
//         $fiche = $this->paieModel->getContratEmployeByIdEmploye($id, date('Y-m-d'));
//         if (!$fiche) return ['error' => 'Aucun contrat actif'];

//         return [
//             'employe' => $employes[0],
//             'avantages' => $fiche['avantages'],
//             'details_avantages' => $fiche['label_avantage'],
//             'heures_sup' => $fiche['heure_sup'],
//             'fiche_complete' => $fiche
//         ];
//     }

//     /* ------------------ Gemini / LLM integration (analyse + formatage) ------------------ */

//     public function analyserQuestionAvecGemini($question)
//     {
//         $prompt = $this->construirePromptAnalyse($question);
//         $raw = $this->callGeminiAPI($prompt);
//         return $this->parserReponseGemini($raw);
//     }

//     private function construirePromptAnalyse($question)
// {
//     return "Analyse cette question et réponds UNIQUEMENT par l'action correspondante.

// RÈGLES STRICTES :
// - Réponds en UN SEUL MOT ou UNE SEULE LIGNE
// - Aucune explication supplémentaire
// - Aucun texte avant ou après

// FORMAT DE RÉPONSE :
// - Pour une question sur le salaire : SALAIRE_NET [nom]
// - Pour une question sur les retenues : RETENUES [nom]
// - Pour une question sur les avantages : AVANTAGES [nom]
// - Pour la masse salariale : MASSE_SALARIALE
// - Pour la liste des employés : LISTE_EMPLOYES
// - Si tu ne comprends pas : INCONNU

// QUESTION : $question

// RÉPONSE (UN SEUL FORMAT CI-DESSUS) :";
// }

//     // NOTE: Remplace par ton propre appel réseau sécurisé. Ici on simule via cURL et VARIABLE D'ENV.
//  private function callGeminiAPI($prompt) {
//     // 🔍 DÉBOGAGE : Voir le prompt envoyé
//     error_log("=== PROMPT ENVOYÉ À GEMINI ===");
//     error_log($prompt);
    
//     $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=AIzaSyDmVtEkyh_MIvzfDU1Xao_JSnq-2J9qkk4";

//     $data = [
//         'contents' => [
//             [
//                 'parts' => [
//                     ['text' => $prompt]
//                 ]
//             ]
//         ],
//         'generationConfig' => [
//             'temperature' => 0.1,
//             'topK' => 1,
//             'maxOutputTokens' => 1000,
//         ]
//     ];

//     // 🔍 DÉBOGAGE : Voir le JSON envoyé
//     error_log("=== JSON ENVOYÉ ===");
//     error_log(json_encode($data, JSON_PRETTY_PRINT));

//     $options = [
//         'http' => [
//             'header'  => "Content-Type: application/json\r\n",
//             'method'  => 'POST',
//             'content' => json_encode($data),
//         ],
//     ];

//     $context  = stream_context_create($options);
//     $response = file_get_contents($url, false, $context);

//     // 🔍 DÉBOGAGE : Voir la réponse brute
//     error_log("=== RÉPONSE BRUTE GEMINI ===");
//     error_log($response);

//     if ($response === FALSE) {
//         return ['error' => 'Erreur API Gemini'];
//     }

//     $response_data = json_decode($response, true);

//     if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
//         $result = trim($response_data['candidates'][0]['content']['parts'][0]['text']);
        
//         // 🔍 DÉBOGAGE : Voir la réponse extraite
//         error_log("=== TEXTE EXTRAIT ===");
//         error_log($result);
        
//         return $result;
//     } else {
//         error_log("=== STRUCTURE RÉPONSE INATTENDUE ===");
//         error_log(print_r($response_data, true));
//         return ['error' => 'Réponse Gemini invalide'];
//     }
// }

// private function formatErrorResponse($results) {
//     if (empty($results)) {
//         return "Aucun résultat trouvé dans la base de données.";
//     }

//     if (isset($results['error'])) {
//         return "Désolé, une erreur s'est produite: " . $results['error'];
//     }

//     return "Aucune donnée correspondante trouvée.";
// }


//     private function parserReponseGemini($reponse)
//     {
//         $reponse = trim($reponse);
//         if (stripos($reponse, 'SALAIRE_NET') === 0) {
//             $nom = trim(substr($reponse, strlen('SALAIRE_NET')));
//             return ['action' => 'SALAIRE_NET', 'parametre' => $nom];
//         }
//         if (stripos($reponse, 'RETENUES') === 0) {
//             $nom = trim(substr($reponse, strlen('RETENUES')));
//             return ['action' => 'RETENUES', 'parametre' => $nom];
//         }
//         if (stripos($reponse, 'AVANTAGES') === 0) {
//             $nom = trim(substr($reponse, strlen('AVANTAGES')));
//             return ['action' => 'AVANTAGES', 'parametre' => $nom];
//         }
//         if (stripos($reponse, 'MASSE_SALARIALE') === 0) {
//             return ['action' => 'MASSE_SALARIALE', 'parametre' => null];
//         }
//         if (stripos($reponse, 'LISTE_EMPLOYES') === 0) {
//             return ['action' => 'LISTE_EMPLOYES', 'parametre' => null];
//         }
//         return ['action' => 'INCONNU', 'parametre' => null];
//     }

//     public function formaterReponseNaturelle($donnees, $type)
//     {
//         // Pour simplifier, on renvoie une réponse formatée localement
//         switch ($type) {
//             case 'salaire_net':
//                 return "Le salaire net est de " . number_format($donnees['salaire_net'], 0, ',', ' ') . " MGA.";
//             case 'retenues':
//                 return "Total retenues : " . number_format($donnees['retenues']['total'], 0, ',', ' ') . " MGA.";
//             case 'avantages':
//                 return "Avantages totaux : " . number_format($donnees['avantages'], 0, ',', ' ') . " MGA.";
//             case 'masse_salariale':
//                 return "Masse salariale calculée.";
//             case 'liste_employes':
//                 $names = array_map(fn($e) => trim(($e['prenom'] ?? '') . ' ' . ($e['nom'] ?? '')), $donnees['employes']);
//                 return "Employés : " . implode(', ', $names);
//             default:
//                 return "Je ne peux pas formater cette réponse pour l'instant.";
//         }
//     }



//     //////////////////////////////////////////////////////////////////////////////////////////
//     //CHATBOTConge/////////////////////////////////
//     /////////////////////////////////////////////////////////////////////////////////////////

//     public function afficherListe(){
//     }
// }
















    // namespace app\controllers;

    // use Flight;

    // class ChatbotController
    // {
    //     private $chatbotModel;

    //     public function __construct()
    // {
    // }

    // public function processQuestion()
    // {
    //     // Lecture du JSON POST
    //     $input = Flight::request()->data->question ?? null;
    //     if (!$input) {
    //         // aussi accepter raw JSON
    //         $raw = Flight::request()->getBody();
    //         $body = json_decode($raw, true);
    //         $input = $body['question'] ?? '';
    //     }

    //     if (empty($input)) {
    //         Flight::json(['error' => 'Aucune question fournie'], 400);
    //         return;
    //     }

    //     $response = Flight::ChatbotPaieModel()->traiterAvecGemini($input);
    //     Flight::json($response);
    // }
    //     // /**
    //     //  * Utilise Gemini pour analyser la question et appeler la bonne fonction
    //     //  */
    //     // private function traiterAvecGemini($question)
    //     // {
    //     //     // 1. Gemini analyse la question et décide de l'action
    //     //     $analyse = Flight::ChatbotPaieModel()->analyserQuestionAvecGemini($question);
            
    //     //     // 2. On exécute l'action correspondante
    //     //     switch ($analyse['action']) {
    //     //         case 'SALAIRE_NET':
    //     //             return $this->executerSalaireNet($analyse['parametre'], $question);
                    
    //     //         case 'RETENUES':
    //     //             return $this->executerRetenues($analyse['parametre'], $question);
                    
    //     //         case 'AVANTAGES':
    //     //             return $this->executerAvantages($analyse['parametre'], $question);
                    
    //     //         case 'MASSE_SALARIALE':
    //     //             return $this->executerMasseSalariale($question);
                    
    //     //         case 'LISTE_EMPLOYES':
    //     //             return $this->executerListeEmployes($question);
                    
    //     //         default:
    //     //             return $this->reponseParDefaut($question);
    //     //     }
    //     // }

    //     // /**
    //     //  * Exécute la recherche de salaire net
    //     //  */
    //     // private function executerSalaireNet($nomEmploye, $questionOriginale)
    //     // {
    //     //     $resultat = Flight::ChatbotPaieModel()->getSalaireNetParNom($nomEmploye);
            
    //     //     if (isset($resultat['error'])) {
    //     //         if (isset($resultat['employes'])) {
    //     //             // Plusieurs employés trouvés
    //     //             $noms = array_map(function($emp) {
    //     //                 return $emp['prenom'] . ' ' . $emp['nom'];
    //     //             }, $resultat['employes']);
                    
    //     //             return [
    //     //                 'type' => 'salaire',
    //     //                 'reponse' => "Plusieurs employés correspondent à '{$nomEmploye}': " . implode(', ', $noms) . ". Veuillez préciser.",
    //     //                 'suggestions' => $noms
    //     //             ];
    //     //         }
                
    //     //         return [
    //     //             'type' => 'salaire',
    //     //             'reponse' => "Je n'ai pas trouvé d'employé nommé '{$nomEmploye}'.",
    //     //             'suggestions' => ['liste des employés']
    //     //         ];
    //     //     }

    //     //     // Formater la réponse avec Gemini
    //     //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle($resultat, 'salaire_net');
            
    //     //     return [
    //     //         'type' => 'salaire',
    //     //         'reponse' => $reponseNaturelle,
    //     //         'details' => $resultat
    //     //     ];
    //     // }

    //     // /**
    //     //  * Exécute la recherche de retenues
    //     //  */
    //     // private function executerRetenues($nomEmploye, $questionOriginale)
    //     // {
    //     //     $resultat = Flight::ChatbotPaieModel()->getRetenuesParNom($nomEmploye);
            
    //     //     if (isset($resultat['error'])) {
    //     //         return [
    //     //             'type' => 'retenues',
    //     //             'reponse' => "Employé '{$nomEmploye}' non trouvé.",
    //     //             'suggestions' => ['liste des employés']
    //     //         ];
    //     //     }

    //     //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle($resultat, 'retenues');
            
    //     //     return [
    //     //         'type' => 'retenues',
    //     //         'reponse' => $reponseNaturelle,
    //     //         'details' => $resultat
    //     //     ];
    //     // }

    //     // /**
    //     //  * Exécute la recherche d'avantages
    //     //  */
    //     // private function executerAvantages($nomEmploye, $questionOriginale)
    //     // {
    //     //     $resultat = Flight::ChatbotPaieModel()->getAvantagesParNom($nomEmploye);
            
    //     //     if (isset($resultat['error'])) {
    //     //         return [
    //     //             'type' => 'avantages',
    //     //             'reponse' => "Employé '{$nomEmploye}' non trouvé.",
    //     //             'suggestions' => ['liste des employés']
    //     //         ];
    //     //     }

    //     //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle($resultat, 'avantages');
            
    //     //     return [
    //     //         'type' => 'avantages',
    //     //         'reponse' => $reponseNaturelle,
    //     //         'details' => $resultat
    //     //     ];
    //     // }

    //     // /**
    //     //  * Exécute la recherche de masse salariale
    //     //  */
    //     // private function executerMasseSalariale($questionOriginale)
    //     // {
    //     //     $resultat = Flight::ChatbotPaieModel()->getMasseSalariale();
    //     //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle($resultat, 'masse_salariale');
            
    //     //     return [
    //     //         'type' => 'masse_salariale',
    //     //         'reponse' => $reponseNaturelle,
    //     //         'details' => $resultat
    //     //     ];
    //     // }

    //     // /**
    //     //  * Exécute la liste des employés
    //     //  */
    //     // private function executerListeEmployes($questionOriginale)
    //     // {
    //     //     $employes = Flight::ChatbotPaieModel()->getListeEmployes();
    //     //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle(['employes' => $employes], 'liste_employes');
            
    //     //     return [
    //     //         'type' => 'liste_employes',
    //     //         'reponse' => $reponseNaturelle,
    //     //         'details' => $employes
    //     //     ];
    //     // }

    //     // /**
    //     //  * Réponse par défaut
    //     //  */
    //     // private function reponseParDefaut($question)
    //     // {
    //     //     return [
    //     //         'type' => 'aide',
    //     //         'reponse' => "Je peux vous aider avec les salaires, retenues, avantages, masse salariale et liste des employés. Posez-moi une question comme 'salaire net de Dupont' ou 'retenues CNAPS pour Marie'.",
    //     //         'suggestions' => [
    //     //             'salaire net de Dupont',
    //     //             'retenues CNAPS pour Marie',
    //     //             'avantages Martin',
    //     //             'masse salariale',
    //     //             'liste des employés'
    //     //         ]
    //     //     ];
    //     // }
    // }
    ?>







