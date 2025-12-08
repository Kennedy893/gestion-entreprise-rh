<?php

namespace app\models;

use Flight;
use PDO;

class ChatbotPaieModel
{
    private $db;
    private $paieModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->paieModel = new PaieModel($db);
    }

    /**
     * Flux principal : analyser la question puis exécuter l'action
     */
    public function traiterAvecGemini($question)
    {
        $analyse = $this->analyserQuestionAvecGemini($question);

        switch ($analyse['action']) {
            // ===== GESTION PAIE (NE PAS TOUCHER) =====
            case 'SALAIRE_NET':
                return $this->executerSalaireNet($analyse['parametre'], $question);
            case 'RETENUES':
                return $this->executerRetenues($analyse['parametre'], $question);
            case 'AVANTAGES':
                return $this->executerAvantages($analyse['parametre'], $question);
            case 'MASSE_SALARIALE':
                return $this->executerMasseSalariale($question);
            case 'LISTE_EMPLOYES':
                return $this->executerListeEmployes($question);

                // ===== GESTION CONGÉS (NOUVEAU) =====
            case 'CONGES_VALIDES':
                return $this->executerCongesValides($analyse['parametre'], $question);
            case 'CONGES_RESTANTS':
                return $this->executerCongesRestants($analyse['parametre'], $question);
            case 'CONGES_PRIS_TOTAL':
                return $this->executerCongesPrisTotal($analyse['parametre'], $question);
            case 'HISTORIQUE_CONGES':
                return $this->executerHistoriqueConges($analyse['parametre'], $question);

            default:
                return $this->reponseParDefaut($question);
        }
    }

    /* ==================== ACTIONS PAIE (NE PAS TOUCHER) ==================== */

    private function executerSalaireNet($nomEmploye, $questionOriginale)
    {
        $resultat = $this->getSalaireNetParNom($nomEmploye);

        if (isset($resultat['error'])) {
            if (isset($resultat['employes'])) {
                $noms = array_map(function ($emp) {
                    return trim(($emp['prenom'] ?? '') . ' ' . ($emp['nom'] ?? ''));
                }, $resultat['employes']);
                return [
                    'type' => 'salaire',
                    'reponse' => "Plusieurs employés correspondent à '{$nomEmploye}': " . implode(', ', $noms) . ". Veuillez préciser.",
                    'suggestions' => $noms
                ];
            }
            return [
                'type' => 'salaire',
                'reponse' => "Je n'ai pas trouvé d'employé nommé '{$nomEmploye}'.",
                'suggestions' => ['liste des employés']
            ];
        }

        $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'salaire_net');

        return [
            'type' => 'salaire',
            'reponse' => $reponseNaturelle,
            'details' => $resultat
        ];
    }

    private function executerRetenues($nomEmploye, $questionOriginale)
    {
        $resultat = $this->getRetenuesParNom($nomEmploye);
        if (isset($resultat['error'])) {
            return [
                'type' => 'retenues',
                'reponse' => "Employé '{$nomEmploye}' non trouvé.",
                'suggestions' => ['liste des employés']
            ];
        }
        $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'retenues');
        return [
            'type' => 'retenues',
            'reponse' => $reponseNaturelle,
            'details' => $resultat
        ];
    }

    private function executerAvantages($nomEmploye, $questionOriginale)
    {
        $resultat = $this->getAvantagesParNom($nomEmploye);
        if (isset($resultat['error'])) {
            return [
                'type' => 'avantages',
                'reponse' => "Employé '{$nomEmploye}' non trouvé.",
                'suggestions' => ['liste des employés']
            ];
        }
        $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'avantages');
        return [
            'type' => 'avantages',
            'reponse' => $reponseNaturelle,
            'details' => $resultat
        ];
    }

    private function executerMasseSalariale($questionOriginale)
    {
        $resultat = $this->paieModel->getContratEmployeByDate(date('Y-m-d'));
        $reponseNaturelle = $this->formaterReponseNaturelle(['masse' => $resultat], 'masse_salariale');
        return [
            'type' => 'masse_salariale',
            'reponse' => $reponseNaturelle,
            'details' => $resultat
        ];
    }

    private function executerListeEmployes($questionOriginale)
    {
        $stmt = $this->db->query("SELECT id, nom, prenom FROM employe ORDER BY nom, prenom");
        $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reponseNaturelle = $this->formaterReponseNaturelle(['employes' => $employes], 'liste_employes');
        return [
            'type' => 'liste_employes',
            'reponse' => $reponseNaturelle,
            'details' => $employes
        ];
    }

    /* ==================== ACTIONS CONGÉS (NOUVEAU) ==================== */

    private function executerCongesValides($nomEmploye, $questionOriginale)
    {
        $resultat = $this->getCongesValidesParNom($nomEmploye);

        if (isset($resultat['error'])) {
            return [
                'type' => 'conges_valides',
                'reponse' => $resultat['error'],
                'suggestions' => ['liste des employés']
            ];
        }

        $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'conges_valides');
        return [
            'type' => 'conges_valides',
            'reponse' => $reponseNaturelle,
            'details' => $resultat
        ];
    }

    private function executerCongesRestants($nomEmploye, $questionOriginale)
    {
        $resultat = $this->getCongesRestantsParNom($nomEmploye);

        if (isset($resultat['error'])) {
            return [
                'type' => 'conges_restants',
                'reponse' => $resultat['error'],
                'suggestions' => ['liste des employés']
            ];
        }

        $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'conges_restants');
        return [
            'type' => 'conges_restants',
            'reponse' => $reponseNaturelle,
            'details' => $resultat
        ];
    }

    private function executerCongesPrisTotal($nomEmploye, $questionOriginale)
    {
        $resultat = $this->getCongesPrisTotalParNom($nomEmploye);

        if (isset($resultat['error'])) {
            return [
                'type' => 'conges_pris_total',
                'reponse' => $resultat['error'],
                'suggestions' => ['liste des employés']
            ];
        }

        $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'conges_pris_total');
        return [
            'type' => 'conges_pris_total',
            'reponse' => $reponseNaturelle,
            'details' => $resultat
        ];
    }

    private function executerHistoriqueConges($nomEmploye, $questionOriginale)
    {
        $resultat = $this->getHistoriqueCongesParNom($nomEmploye);

        if (isset($resultat['error'])) {
            return [
                'type' => 'historique_conges',
                'reponse' => $resultat['error'],
                'suggestions' => ['liste des employés']
            ];
        }

        $reponseNaturelle = $this->formaterReponseNaturelle($resultat, 'historique_conges');
        return [
            'type' => 'historique_conges',
            'reponse' => $reponseNaturelle,
            'details' => $resultat
        ];
    }

    private function reponseParDefaut($question)
    {
        return [
            'type' => 'aide',
            'reponse' => "Je peux vous aider sur :\n\n📊 PAIE :\n- Salaire net d'un employé\n- Retenues CNAPS/OSTIE/IRSA\n- Avantages\n- Masse salariale\n- Liste des employés\n\n🏖️ CONGÉS :\n- Congés validés\n- Congés restants\n- Total congés pris depuis le contrat\n- Historique des congés\n\nExemples : 'salaire net de Dupont', 'congés restants de Rakoto'",
            'suggestions' => [
                'salaire net de Dupont',
                'congés restants de Rakoto',
                'congés validés de Marie',
                'total congés de Martin',
                'liste des employés'
            ]
        ];
    }

    /* ==================== UTILITAIRES PAIE (NE PAS TOUCHER) ==================== */

    public function rechercherEmployeParNom($nom)
    {
        $sql = "SELECT * FROM employe WHERE LOWER(nom) LIKE LOWER(?) OR LOWER(prenom) LIKE LOWER(?)";
        $stmt = $this->db->prepare($sql);
        $term = "%" . trim($nom) . "%";
        $stmt->execute([$term, $term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSalaireNetParNom($nomEmploye)
    {
        $employes = $this->rechercherEmployeParNom($nomEmploye);
        if (empty($employes)) return ['error' => 'Employé non trouvé'];
        if (count($employes) > 1) return ['error' => 'plusieurs', 'employes' => $employes];

        $id = $employes[0]['id'];
        $fiche = $this->paieModel->getContratEmployeByIdEmploye($id, date('Y-m-d'));
        if (!$fiche) return ['error' => 'Aucun contrat actif pour cet employé'];

        return [
            'employe' => $employes[0],
            'salaire_net' => $fiche['salaire_net'],
            'salaire_brut' => $fiche['salaire_brut'],
            'fiche_complete' => $fiche
        ];
    }

    public function getRetenuesParNom($nomEmploye)
    {
        $employes = $this->rechercherEmployeParNom($nomEmploye);
        if (empty($employes)) return ['error' => 'Employé non trouvé'];

        $id = $employes[0]['id'];
        $fiche = $this->paieModel->getContratEmployeByIdEmploye($id, date('Y-m-d'));
        if (!$fiche) return ['error' => 'Aucun contrat actif'];

        return [
            'employe' => $employes[0],
            'retenues' => [
                'cnaps' => $fiche['cnaps_1'],
                'ostie' => $fiche['ostie_1'],
                'irsa' => $fiche['irsa'],
                'total' => $fiche['total_ret']
            ],
            'fiche_complete' => $fiche
        ];
    }

    public function getAvantagesParNom($nomEmploye)
    {
        $employes = $this->rechercherEmployeParNom($nomEmploye);
        if (empty($employes)) return ['error' => 'Employé non trouvé'];

        $id = $employes[0]['id'];
        $fiche = $this->paieModel->getContratEmployeByIdEmploye($id, date('Y-m-d'));
        if (!$fiche) return ['error' => 'Aucun contrat actif'];

        return [
            'employe' => $employes[0],
            'avantages' => $fiche['avantages'],
            'details_avantages' => $fiche['label_avantage'],
            'heures_sup' => $fiche['heure_sup'],
            'fiche_complete' => $fiche
        ];
    }

    /* ==================== UTILITAIRES CONGÉS (NOUVEAU) ==================== */

    /**
     * Obtenir les congés validés par le manager (statut_absence = 1)
     */
    public function getCongesValidesParNom($nomEmploye)
    {
        $employes = $this->rechercherEmployeParNom($nomEmploye);
        if (empty($employes)) return ['error' => "Employé '{$nomEmploye}' non trouvé."];
        if (count($employes) > 1) return ['error' => 'Plusieurs employés trouvés', 'employes' => $employes];

        $id = $employes[0]['id'];

        $sql = "SELECT * FROM absence a 
                JOIN statut_abscence st on a.id = st.id_absence
                JOIN Document dc on dc.id=a.id_document
                WHERE dc.id_employe = ? AND st.statut = 1 
                ORDER BY a.date_debut DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $conges = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'employe' => $employes[0],
            'conges_valides' => $conges,
            'nombre_conges_valides' => count($conges)
        ];
    }

    /**
     * Calculer les congés restants pour un employé
     */
    public function getCongesRestantsParNom($nomEmploye)
    {
        $employes = $this->rechercherEmployeParNom($nomEmploye);
        if (empty($employes)) return ['error' => "Employé '{$nomEmploye}' non trouvé."];
        if (count($employes) > 1) return ['error' => 'Plusieurs employés trouvés', 'employes' => $employes];

        $id = $employes[0]['id'];

        // Obtenir le contrat actif
        $sqlContrat = "SELECT date_debut FROM contrat_employe 
                       WHERE id_employe = ? AND id_statut_contrat = 1
                       AND :date BETWEEN date_debut AND date_fin";
        $stmtContrat = $this->db->prepare($sqlContrat);
        $stmtContrat->execute(['date' => date('Y-m-d'), $id]);
        $contrat = $stmtContrat->fetch(PDO::FETCH_ASSOC);

        if (!$contrat) {
            return ['error' => 'Aucun contrat actif trouvé pour cet employé'];
        }

        // Calculer l'ancienneté en années
        $dateDebut = new \DateTime($contrat['date_debut']);
        $dateActuelle = new \DateTime();
        $anciennete = $dateDebut->diff($dateActuelle)->y;

        // Droits aux congés (exemple : 2.5 jours par mois = 30 jours par an)
        $conges_annuels = 30;
        $conges_disponibles = $conges_annuels * ($anciennete > 0 ? $anciennete : 1);

        // Compter les congés déjà pris (validés)
        $sqlPris = "SELECT SUM(DATEDIFF(a.date_fin, a.date_debut) + 1) as jours_pris 
                    FROM absence a
                    JOIN statut_abscence st on a.id = st.id_absence 
                    JOIN Document dc on dc.id=a.id_document
                    WHERE dc.id_employe = ? AND st.statut = 1";
        $stmtPris = $this->db->prepare($sqlPris);
        $stmtPris->execute([$id]);
        $result = $stmtPris->fetch(PDO::FETCH_ASSOC);

        $conges_pris = $result['jours_pris'] ?? 0;
        $conges_restants = $conges_disponibles - $conges_pris;

        return [
            'employe' => $employes[0],
            'anciennete_annees' => $anciennete,
            'conges_disponibles' => $conges_disponibles,
            'conges_pris' => $conges_pris,
            'conges_restants' => max(0, $conges_restants)
        ];
    }

    /**
     * Obtenir le total des congés pris depuis le début du contrat
     */
    public function getCongesPrisTotalParNom($nomEmploye)
{
    $employes = $this->rechercherEmployeParNom($nomEmploye);
    if (empty($employes)) return ['error' => "Employé '{$nomEmploye}' non trouvé."];
    if (count($employes) > 1) return ['error' => 'Plusieurs employés trouvés', 'employes' => $employes];

    $id = $employes[0]['id'];

    // Obtenir le contrat actif pour la date de début
    $sqlContrat = "SELECT date_debut FROM contrat_employe 
                   WHERE id_employe = ? AND id_statut_contrat = 1";
    $stmtContrat = $this->db->prepare($sqlContrat);
    $stmtContrat->execute([$id]);
    $contrat = $stmtContrat->fetch(PDO::FETCH_ASSOC);

    if (!$contrat) {
        return ['error' => 'Aucun contrat trouvé pour cet employé'];
    }

    // Total des jours de congé pris (validés uniquement) - CORRIGÉ
    $sqlTotal = "SELECT 
                    COUNT(*) as nombre_demandes,
                    SUM((a.date_fin - a.date_debut) + 1) as total_jours
                 FROM absence a 
                 JOIN statut_abscence st on a.id = st.id_absence
                 JOIN Document dc on dc.id = a.id_document 
                 WHERE dc.id_employe = ? 
                 AND st.statut = 1
                 AND a.date_debut >= ?";
    $stmtTotal = $this->db->prepare($sqlTotal);
    $stmtTotal->execute([$id, $contrat['date_debut']]);
    $stats = $stmtTotal->fetch(PDO::FETCH_ASSOC);

    return [
        'employe' => $employes[0],
        'date_debut_contrat' => $contrat['date_debut'],
        'nombre_demandes' => $stats['nombre_demandes'] ?? 0,
        'total_jours_pris' => $stats['total_jours'] ?? 0
    ];
}
    /**
     * Obtenir l'historique complet des congés
     */
    public function getHistoriqueCongesParNom($nomEmploye)
    {
        $employes = $this->rechercherEmployeParNom($nomEmploye);
        if (empty($employes)) return ['error' => "Employé '{$nomEmploye}' non trouvé."];
        if (count($employes) > 1) return ['error' => 'Plusieurs employés trouvés', 'employes' => $employes];

        $id = $employes[0]['id'];

        $sql = "SELECT * FROM absence a
                JOIN Document dc on dc.id=a.id_document
                WHERE dc.id_employe = ? 
                ORDER BY a.date_debut DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $historique = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter le nombre de jours pour chaque congé
        foreach ($historique as &$conge) {
            $debut = new \DateTime($conge['date_debut']);
            $fin = new \DateTime($conge['date_fin']);
            $conge['nombre_jours'] = $debut->diff($fin)->days + 1;
        }

        return [
            'employe' => $employes[0],
            'historique' => $historique,
            'total_demandes' => count($historique)
        ];
    }

    /* ==================== GEMINI / LLM INTEGRATION ==================== */

    public function analyserQuestionAvecGemini($question)
    {
        $prompt = $this->construirePromptAnalyse($question);
        $raw = $this->callGeminiAPI($prompt);
        return $this->parserReponseGemini($raw);
    }

    private function construirePromptAnalyse($question)
    {
        return "
Analyse cette question et réponds UNIQUEMENT par l'action correspondante.

RÈGLES STRICTES :
- Réponds en UN SEUL MOT ou UNE SEULE LIGNE
- Aucune explication supplémentaire
- Aucun texte avant ou après

FORMAT DE RÉPONSE PAIE :
- Pour une question sur le salaire : SALAIRE_NET [nom]
- Pour une question sur les retenues : RETENUES [nom]
- Pour une question sur les avantages : AVANTAGES [nom]
- Pour la masse salariale : MASSE_SALARIALE
- Pour la liste des employés : LISTE_EMPLOYES

FORMAT DE RÉPONSE CONGÉS :
- Pour les congés validés d'UN employé : CONGES_VALIDES [nom]
- Pour TOUS les congés validés par le manager : TOUS_CONGES_VALIDES
- Pour les congés restants d'un employé : CONGES_RESTANTS [nom]
- Pour le total des congés pris depuis le contrat : CONGES_PRIS_TOTAL [nom]
- Pour l'historique des congés d'un employé : HISTORIQUE_CONGES [nom]

Si tu ne comprends pas : INCONNU

QUESTION : $question

RÉPONSE (UN SEUL FORMAT CI-DESSUS) :
";
    }


    private function callGeminiAPI($prompt)
    {
        error_log("=== PROMPT ENVOYÉ À GEMINI ===");
        error_log($prompt);

        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=AIzaSyD3anBC9bVIwjYslbGqM8vGOao3BqV2Xew";

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

        error_log("=== JSON ENVOYÉ ===");
        error_log(json_encode($data, JSON_PRETTY_PRINT));

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];

        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        error_log("=== RÉPONSE BRUTE GEMINI ===");
        error_log($response);

        if ($response === FALSE) {
            return ['error' => 'Erreur API Gemini'];
        }

        $response_data = json_decode($response, true);

        if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
            $result = trim($response_data['candidates'][0]['content']['parts'][0]['text']);

            error_log("=== TEXTE EXTRAIT ===");
            error_log($result);

            return $result;
        } else {
            error_log("=== STRUCTURE RÉPONSE INATTENDUE ===");
            error_log(print_r($response_data, true));
            return ['error' => 'Réponse Gemini invalide'];
        }
    }

    private function parserReponseGemini($reponse)
    {
        $reponse = trim($reponse);

        // PAIE
        if (stripos($reponse, 'SALAIRE_NET') === 0) {
            $nom = trim(str_replace(['SALAIRE_NET', '[', ']'], '', $reponse));
            return ['action' => 'SALAIRE_NET', 'parametre' => $nom];
        }
        if (stripos($reponse, 'RETENUES') === 0) {
            $nom = trim(str_replace(['RETENUES', '[', ']'], '', $reponse));
            return ['action' => 'RETENUES', 'parametre' => $nom];
        }
        if (stripos($reponse, 'AVANTAGES') === 0) {
            $nom = trim(str_replace(['AVANTAGES', '[', ']'], '', $reponse));
            return ['action' => 'AVANTAGES', 'parametre' => $nom];
        }
        if (stripos($reponse, 'MASSE_SALARIALE') === 0) {
            return ['action' => 'MASSE_SALARIALE', 'parametre' => null];
        }
        if (stripos($reponse, 'LISTE_EMPLOYES') === 0) {
            return ['action' => 'LISTE_EMPLOYES', 'parametre' => null];
        }

        // CONGÉS
        if (stripos($reponse, 'CONGES_VALIDES') === 0) {
            $nom = trim(str_replace(['CONGES_VALIDES', '[', ']'], '', $reponse));
            return ['action' => 'CONGES_VALIDES', 'parametre' => $nom];
        }
        if (stripos($reponse, 'CONGES_RESTANTS') === 0) {
            $nom = trim(str_replace(['CONGES_RESTANTS', '[', ']'], '', $reponse));
            return ['action' => 'CONGES_RESTANTS', 'parametre' => $nom];
        }
        if (stripos($reponse, 'CONGES_PRIS_TOTAL') === 0) {
            $nom = trim(str_replace(['CONGES_PRIS_TOTAL', '[', ']'], '', $reponse));
            return ['action' => 'CONGES_PRIS_TOTAL', 'parametre' => $nom];
        }
        if (stripos($reponse, 'HISTORIQUE_CONGES') === 0) {
            $nom = trim(str_replace(['HISTORIQUE_CONGES', '[', ']'], '', $reponse));
            return ['action' => 'HISTORIQUE_CONGES', 'parametre' => $nom];
        }

        return ['action' => 'INCONNU', 'parametre' => null];
    }

    public function formaterReponseNaturelle($donnees, $type)
    {
        switch ($type) {
            // PAIE
            case 'salaire_net':
                return "Le salaire net est de " . number_format($donnees['salaire_net'], 0, ',', ' ') . " MGA.";
            case 'retenues':
                return "Total retenues : " . number_format($donnees['retenues']['total'], 0, ',', ' ') . " MGA.";
            case 'avantages':
                return "Avantages totaux : " . number_format($donnees['avantages'], 0, ',', ' ') . " MGA.";
            case 'masse_salariale':
                return "Masse salariale calculée.";
            case 'liste_employes':
                $names = array_map(fn($e) => trim(($e['prenom'] ?? '') . ' ' . ($e['nom'] ?? '')), $donnees['employes']);
                return "Employés : " . implode(', ', $names);

                // CONGÉS
            case 'conges_valides':
                $nom = trim(($donnees['employe']['prenom'] ?? '') . ' ' . ($donnees['employe']['nom'] ?? ''));
                $nb = $donnees['nombre_conges_valides'];
                return "{$nom} a {$nb} congé(s) validé(s) par le manager.";

            case 'conges_restants':
                $nom = trim(($donnees['employe']['prenom'] ?? '') . ' ' . ($donnees['employe']['nom'] ?? ''));
                $restants = $donnees['conges_restants'];
                $pris = $donnees['conges_pris'];
                $disponibles = $donnees['conges_disponibles'];
                return "{$nom} a encore {$restants} jour(s) de congé disponible(s) ({$pris}/{$disponibles} jours utilisés).";

            case 'conges_pris_total':
                $nom = trim(($donnees['employe']['prenom'] ?? '') . ' ' . ($donnees['employe']['nom'] ?? ''));
                $total = $donnees['total_jours_pris'];
                $nb_demandes = $donnees['nombre_demandes'];
                return "{$nom} a pris {$total} jour(s) de congé au total depuis le début de son contrat ({$nb_demandes} demande(s)).";

            case 'historique_conges':
                $nom = trim(($donnees['employe']['prenom'] ?? '') . ' ' . ($donnees['employe']['nom'] ?? ''));
                $nb = $donnees['total_demandes'];
                return "{$nom} a {$nb} demande(s) de congé dans son historique.";

            default:
                return "Je ne peux pas formater cette réponse pour l'instant.";
        }
    }
}
