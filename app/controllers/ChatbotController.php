<?php

    namespace app\controllers;

    use Flight;

    class ChatbotController
    {
        private $chatbotModel;

        public function __construct()
    {
    }

    public function processQuestion()
    {
        // Lecture du JSON POST
        $input = Flight::request()->data->question ?? null;
        if (!$input) {
            // aussi accepter raw JSON
            $raw = Flight::request()->getBody();
            $body = json_decode($raw, true);
            $input = $body['question'] ?? '';
        }

        if (empty($input)) {
            Flight::json(['error' => 'Aucune question fournie'], 400);
            return;
        }

        $response = Flight::ChatbotPaieModel()->traiterAvecGemini($input);
        Flight::json($response);
    }
        // /**
        //  * Utilise Gemini pour analyser la question et appeler la bonne fonction
        //  */
        // private function traiterAvecGemini($question)
        // {
        //     // 1. Gemini analyse la question et décide de l'action
        //     $analyse = Flight::ChatbotPaieModel()->analyserQuestionAvecGemini($question);
            
        //     // 2. On exécute l'action correspondante
        //     switch ($analyse['action']) {
        //         case 'SALAIRE_NET':
        //             return $this->executerSalaireNet($analyse['parametre'], $question);
                    
        //         case 'RETENUES':
        //             return $this->executerRetenues($analyse['parametre'], $question);
                    
        //         case 'AVANTAGES':
        //             return $this->executerAvantages($analyse['parametre'], $question);
                    
        //         case 'MASSE_SALARIALE':
        //             return $this->executerMasseSalariale($question);
                    
        //         case 'LISTE_EMPLOYES':
        //             return $this->executerListeEmployes($question);
                    
        //         default:
        //             return $this->reponseParDefaut($question);
        //     }
        // }

        // /**
        //  * Exécute la recherche de salaire net
        //  */
        // private function executerSalaireNet($nomEmploye, $questionOriginale)
        // {
        //     $resultat = Flight::ChatbotPaieModel()->getSalaireNetParNom($nomEmploye);
            
        //     if (isset($resultat['error'])) {
        //         if (isset($resultat['employes'])) {
        //             // Plusieurs employés trouvés
        //             $noms = array_map(function($emp) {
        //                 return $emp['prenom'] . ' ' . $emp['nom'];
        //             }, $resultat['employes']);
                    
        //             return [
        //                 'type' => 'salaire',
        //                 'reponse' => "Plusieurs employés correspondent à '{$nomEmploye}': " . implode(', ', $noms) . ". Veuillez préciser.",
        //                 'suggestions' => $noms
        //             ];
        //         }
                
        //         return [
        //             'type' => 'salaire',
        //             'reponse' => "Je n'ai pas trouvé d'employé nommé '{$nomEmploye}'.",
        //             'suggestions' => ['liste des employés']
        //         ];
        //     }

        //     // Formater la réponse avec Gemini
        //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle($resultat, 'salaire_net');
            
        //     return [
        //         'type' => 'salaire',
        //         'reponse' => $reponseNaturelle,
        //         'details' => $resultat
        //     ];
        // }

        // /**
        //  * Exécute la recherche de retenues
        //  */
        // private function executerRetenues($nomEmploye, $questionOriginale)
        // {
        //     $resultat = Flight::ChatbotPaieModel()->getRetenuesParNom($nomEmploye);
            
        //     if (isset($resultat['error'])) {
        //         return [
        //             'type' => 'retenues',
        //             'reponse' => "Employé '{$nomEmploye}' non trouvé.",
        //             'suggestions' => ['liste des employés']
        //         ];
        //     }

        //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle($resultat, 'retenues');
            
        //     return [
        //         'type' => 'retenues',
        //         'reponse' => $reponseNaturelle,
        //         'details' => $resultat
        //     ];
        // }

        // /**
        //  * Exécute la recherche d'avantages
        //  */
        // private function executerAvantages($nomEmploye, $questionOriginale)
        // {
        //     $resultat = Flight::ChatbotPaieModel()->getAvantagesParNom($nomEmploye);
            
        //     if (isset($resultat['error'])) {
        //         return [
        //             'type' => 'avantages',
        //             'reponse' => "Employé '{$nomEmploye}' non trouvé.",
        //             'suggestions' => ['liste des employés']
        //         ];
        //     }

        //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle($resultat, 'avantages');
            
        //     return [
        //         'type' => 'avantages',
        //         'reponse' => $reponseNaturelle,
        //         'details' => $resultat
        //     ];
        // }

        // /**
        //  * Exécute la recherche de masse salariale
        //  */
        // private function executerMasseSalariale($questionOriginale)
        // {
        //     $resultat = Flight::ChatbotPaieModel()->getMasseSalariale();
        //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle($resultat, 'masse_salariale');
            
        //     return [
        //         'type' => 'masse_salariale',
        //         'reponse' => $reponseNaturelle,
        //         'details' => $resultat
        //     ];
        // }

        // /**
        //  * Exécute la liste des employés
        //  */
        // private function executerListeEmployes($questionOriginale)
        // {
        //     $employes = Flight::ChatbotPaieModel()->getListeEmployes();
        //     $reponseNaturelle = Flight::ChatbotPaieModel()->formaterReponseNaturelle(['employes' => $employes], 'liste_employes');
            
        //     return [
        //         'type' => 'liste_employes',
        //         'reponse' => $reponseNaturelle,
        //         'details' => $employes
        //     ];
        // }

        // /**
        //  * Réponse par défaut
        //  */
        // private function reponseParDefaut($question)
        // {
        //     return [
        //         'type' => 'aide',
        //         'reponse' => "Je peux vous aider avec les salaires, retenues, avantages, masse salariale et liste des employés. Posez-moi une question comme 'salaire net de Dupont' ou 'retenues CNAPS pour Marie'.",
        //         'suggestions' => [
        //             'salaire net de Dupont',
        //             'retenues CNAPS pour Marie',
        //             'avantages Martin',
        //             'masse salariale',
        //             'liste des employés'
        //         ]
        //     ];
        // }
    }
    ?>