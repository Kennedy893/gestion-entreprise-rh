maintenant comment l'inclure dans ce chatbot
<?php

namespace app\models;

use Flight;
use PDO;
// app/models/ChatbotPaieModel.php

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
            default:
                return $this->reponseParDefaut($question);
        }
    }

    /* ------------------ actions ------------------ */

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

    private function reponseParDefaut($question)
    {
        return [
            'type' => 'aide',
            'reponse' => "Je peux aider sur : salaire net, retenues, avantages, masse salariale, liste des employés. Exemple : 'salaire net de Dupont'.".$question,
            'suggestions' => [
                'salaire net de Dupont',
                'retenues CNAPS de Marie',
                'avantages Martin',
                'masse salariale',
                'liste des employés'
            ]
        ];
    }

    /* ------------------ utilitaires ------------------ */

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

    /* ------------------ Gemini / LLM integration (analyse + formatage) ------------------ */

    public function analyserQuestionAvecGemini($question)
    {
        $prompt = $this->construirePromptAnalyse($question);
        $raw = $this->callGeminiAPI($prompt);
        return $this->parserReponseGemini($raw);
    }

    private function construirePromptAnalyse($question)
{
    return "Analyse cette question et réponds UNIQUEMENT par l'action correspondante.

RÈGLES STRICTES :
- Réponds en UN SEUL MOT ou UNE SEULE LIGNE
- Aucune explication supplémentaire
- Aucun texte avant ou après

FORMAT DE RÉPONSE :
- Pour une question sur le salaire : SALAIRE_NET [nom]
- Pour une question sur les retenues : RETENUES [nom]
- Pour une question sur les avantages : AVANTAGES [nom]
- Pour la masse salariale : MASSE_SALARIALE
- Pour la liste des employés : LISTE_EMPLOYES
- Si tu ne comprends pas : INCONNU

QUESTION : $question

RÉPONSE (UN SEUL FORMAT CI-DESSUS) :";
}

    // NOTE: Remplace par ton propre appel réseau sécurisé. Ici on simule via cURL et VARIABLE D'ENV.
 private function callGeminiAPI($prompt) {
    // 🔍 DÉBOGAGE : Voir le prompt envoyé
    error_log("=== PROMPT ENVOYÉ À GEMINI ===");
    error_log($prompt);
    
    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=AIzaSyDmVtEkyh_MIvzfDU1Xao_JSnq-2J9qkk4";

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

    // 🔍 DÉBOGAGE : Voir le JSON envoyé
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

    // 🔍 DÉBOGAGE : Voir la réponse brute
    error_log("=== RÉPONSE BRUTE GEMINI ===");
    error_log($response);

    if ($response === FALSE) {
        return ['error' => 'Erreur API Gemini'];
    }

    $response_data = json_decode($response, true);

    if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
        $result = trim($response_data['candidates'][0]['content']['parts'][0]['text']);
        
        // 🔍 DÉBOGAGE : Voir la réponse extraite
        error_log("=== TEXTE EXTRAIT ===");
        error_log($result);
        
        return $result;
    } else {
        error_log("=== STRUCTURE RÉPONSE INATTENDUE ===");
        error_log(print_r($response_data, true));
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


    private function parserReponseGemini($reponse)
    {
        $reponse = trim($reponse);
        if (stripos($reponse, 'SALAIRE_NET') === 0) {
            $nom = trim(substr($reponse, strlen('SALAIRE_NET')));
            return ['action' => 'SALAIRE_NET', 'parametre' => $nom];
        }
        if (stripos($reponse, 'RETENUES') === 0) {
            $nom = trim(substr($reponse, strlen('RETENUES')));
            return ['action' => 'RETENUES', 'parametre' => $nom];
        }
        if (stripos($reponse, 'AVANTAGES') === 0) {
            $nom = trim(substr($reponse, strlen('AVANTAGES')));
            return ['action' => 'AVANTAGES', 'parametre' => $nom];
        }
        if (stripos($reponse, 'MASSE_SALARIALE') === 0) {
            return ['action' => 'MASSE_SALARIALE', 'parametre' => null];
        }
        if (stripos($reponse, 'LISTE_EMPLOYES') === 0) {
            return ['action' => 'LISTE_EMPLOYES', 'parametre' => null];
        }
        return ['action' => 'INCONNU', 'parametre' => null];
    }

    public function formaterReponseNaturelle($donnees, $type)
    {
        // Pour simplifier, on renvoie une réponse formatée localement
        switch ($type) {
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
            default:
                return "Je ne peux pas formater cette réponse pour l'instant.";
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////////
    //CHATBOTConge/////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////

    public function afficherListe(){
    }
}   il fait toujours la gestion de paie d=tu ne touche pas a la gestion de paie tu ne touche qu'aux connge si je pose une question combien de conger reste pour l'employer rakoto il affiche le conge de cet employer  par exemple mais je vais te donner tous les mots cle de conge :
savoir quels conge est deja valider par le manager(sattus_absence =1) , combien de conge reste cet employer ,combien de conge reste cet employer depuis qu'il est sous contrat,
voici ma base de donne:
CREATE DATABASE gestion_entreprise_rh;
\c gestion_entreprise_rh;

CREATE TABLE Type_Contrat(
   id SERIAL PRIMARY KEY,
   label VARCHAR(50)
);

CREATE TABLE Employe(
   id SERIAL PRIMARY KEY,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   contact VARCHAR(50),
   photo VARCHAR(100),
   cin VARCHAR(50),
   date_naissance DATE,
   email VARCHAR(50),
   adresse VARCHAR(50),
   genre INT
);

CREATE TABLE Statut_Contrat(
   id SERIAL PRIMARY KEY,
   label VARCHAR(50)
);

CREATE TABLE Type_Document(
   id SERIAL PRIMARY KEY,
   label VARCHAR(50)
);

CREATE TABLE Document(
   id SERIAL PRIMARY KEY,
   chemin VARCHAR(250),
   id_type_document INT,
   id_employe INT,
   FOREIGN KEY(id_employe) REFERENCES Employe(id),
   FOREIGN KEY(id_type_document) REFERENCES Type_Document(id)
);

CREATE TABLE conge(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(150),
   paye INT,
   duree DECIMAL(15,2),
   frequence INT,
   jour INT,
   mois INT
);

CREATE TABLE absence(
   id SERIAL PRIMARY KEY,
   motif VARCHAR(255),
   date_debut DATE,
   date_fin DATE,
   id_document INT,
   id_conge INT,
   FOREIGN KEY(id_document) REFERENCES Document(id),
   FOREIGN KEY(id_conge) REFERENCES conge(id)
);

CREATE TABLE statut_abscence(
   id SERIAL PRIMARY KEY,
   date_statut DATE,
   statut INT,
   id_absence INT,
   FOREIGN KEY(id_absence) REFERENCES absence(id)
);

CREATE TABLE presence(
   id SERIAL PRIMARY KEY,
   date_travail DATE,
   entree TIME,
   sortie TIME,
   montant DECIMAL(15,2),
   id_employe INT,
   FOREIGN KEY(id_employe) REFERENCES Employe(id)
);

CREATE TABLE type_retenu(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(255)
);

CREATE TABLE data(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100),
   valeur DECIMAL(25,2)
);

CREATE TABLE categorie(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100)
);

CREATE TABLE departement(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100),
   fonction INT
);

CREATE TABLE Poste(
   id SERIAL PRIMARY KEY,
   label VARCHAR(50),
   valeur INT,
   id_categorie INT,
   id_departement INT,
   FOREIGN KEY(id_categorie) REFERENCES categorie(id),
   FOREIGN KEY(id_departement) REFERENCES departement(id)
);

CREATE TABLE config_poste(
   id SERIAL PRIMARY KEY,
   duree_travail INT,
   entree TIME,
   sortie TIME,
   id_poste INT,
   FOREIGN KEY(id_poste) REFERENCES Poste(id)
);

CREATE TABLE config_retenu(
   id SERIAL PRIMARY KEY,
   pourcentage_entreprise DECIMAL(5,2),
   pourcentage_employer DECIMAL(5,2),
   salaire_min DECIMAL(25,2),
   salaire_max DECIMAL(25,2),
   id_categorie INT,
   id_type_retenu INT,
   FOREIGN KEY(id_categorie) REFERENCES categorie(id),
   FOREIGN KEY(id_type_retenu) REFERENCES type_retenu(id)
);

CREATE TABLE contrat_employe(
   id SERIAL PRIMARY KEY,
   date_debut DATE,
   date_fin DATE,
   duree INT,
   salaire DECIMAL(25,2),
   id_poste INT,
   id_employe INT,
   id_statut_contrat INT,
   id_type_contrat INT,
   FOREIGN KEY(id_poste) REFERENCES Poste(id),
   FOREIGN KEY(id_employe) REFERENCES Employe(id),
   FOREIGN KEY(id_statut_contrat) REFERENCES Statut_Contrat(id),
   FOREIGN KEY(id_type_contrat) REFERENCES Type_Contrat(id)
);

CREATE TABLE avantage(
   id SERIAL PRIMARY KEY,
   libelle VARCHAR(100),
   montant DECIMAL(25,2),
   id_contrat_employe INT,
   FOREIGN KEY(id_contrat_employe) REFERENCES contrat_employe(id)
);
