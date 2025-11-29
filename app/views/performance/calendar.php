<?php
// Liste des jours spéciaux (exemple)
$list_jours = [
    '2024-01-15',
    '2024-01-20', 
    '2024-02-10',
    '2024-02-14',
];

// Récupérer le jour sélectionné et l'employé depuis GET
$jour_selectionne = isset($_GET['jour']) ? $_GET['jour'] : null;
$id_employe = isset($_GET['idEmp']) ? $_GET['idEmp'] : 1;
$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

// Données statiques des employés
$employes = [
    1 => [
        'id' => 1,
        'nom' => 'DUPONT',
        'prenom' => 'Jean',
        'poste' => 'Développeur',
        'pointages' => [
            '2024-01-15' => ['08:00-12:00', '14:00-17:00'],
            '2024-01-20' => ['09:00-13:00', '15:00-18:00'],
            '2024-02-10' => ['08:30-12:30', '14:30-17:30'],
        ],
        'scores' => [
            'ponctualite' => 4,
            'gestion_temps' => 3,
            'motivation' => 5
        ]
    ],
    2 => [
        'id' => 2,
        'nom' => 'MARTIN',
        'prenom' => 'Sophie',
        'poste' => 'Designer',
        'pointages' => [
            '2024-01-15' => ['08:15-12:15', '14:15-17:15'],
            '2024-02-14' => ['09:00-13:00', '14:00-18:00'],
        ],
        'scores' => [
            'ponctualite' => 5,
            'gestion_temps' => 4,
            'motivation' => 3
        ]
    ],
    3 => [
        'id' => 3,
        'nom' => 'LEFRANC',
        'prenom' => 'Pierre',
        'poste' => 'Chef de projet',
        'pointages' => [
            '2024-01-20' => ['08:00-12:30', '14:00-18:30'],
        ],
        'scores' => [
            'ponctualite' => 2,
            'gestion_temps' => 3,
            'motivation' => 4
        ]
    ],
    4 => [
        'id' => 4,
        'nom' => 'RAKOTO',
        'prenom' => 'Marie',
        'poste' => 'Commerciale',
        'pointages' => [
            '2024-01-15' => ['07:30-12:30', '13:30-16:30'],
        ],
        'scores' => [
            'ponctualite' => 4,
            'gestion_temps' => 5,
            'motivation' => 4
        ]
    ],
    5 => [
        'id' => 5,
        'nom' => 'RAKOTONDRABE',
        'prenom' => 'Eric',
        'poste' => 'Technicien',
        'pointages' => [
            '2024-02-10' => ['08:00-17:00'],
        ],
        'scores' => [
            'ponctualite' => 3,
            'gestion_temps' => 4,
            'motivation' => 3
        ]
    ]
];

// Filtrer les employés selon la recherche
function filtrerEmployes($employes, $recherche) {
    if (empty($recherche)) {
        return $employes;
    }
    
    $resultats = [];
    $termes = explode(' ', strtolower($recherche));
    
    foreach ($employes as $id => $employe) {
        $nom_complet = strtolower($employe['nom'] . ' ' . $employe['prenom']);
        $correspond = true;
        
        foreach ($termes as $terme) {
            if (!empty($terme) && strpos($nom_complet, $terme) === false) {
                $correspond = false;
                break;
            }
        }
        
        if ($correspond) {
            $resultats[$id] = $employe;
        }
    }
    
    return $resultats;
}

$employes_filtres = filtrerEmployes($employes, $recherche);

// Si l'employé sélectionné n'est pas dans les résultats filtrés, on prend le premier
if (!empty($employes_filtres) && !isset($employes_filtres[$id_employe])) {
    $id_employe = key($employes_filtres);
} elseif (empty($employes_filtres)) {
    $id_employe = null;
}

function afficherCalendrier($mois = null, $annee = null, $list_jours = [], $id_employe = 1) {
    if ($mois === null) $mois = date('n');
    if ($annee === null) $annee = date('Y');
    
    $premierJour = mktime(0, 0, 0, $mois, 1, $annee);
    $nombreJours = date('t', $premierJour);
    $jourDebut = date('N', $premierJour);
    $jours = ['LUN', 'MAR', 'MER', 'JEU', 'VEN', 'SAM', 'DIM'];
    
    echo "<div class='mois-titre'>" . strtoupper(date('F Y', $premierJour)) . "</div>";
    echo "<table class='calendrier'>";
    echo "<tr>";
    
    foreach ($jours as $jour) {
        echo "<th>$jour</th>";
    }
    echo "</tr><tr>";
    
    for ($i = 1; $i < $jourDebut; $i++) {
        echo "<td class='vide'></td>";
    }
    
    for ($jour = 1; $jour <= $nombreJours; $jour++) {
        $date_courante = date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
        
        $classes = [];
        if (date('Y-m-d') == $date_courante) {
            $classes[] = 'aujourdhui';
        }
        if (in_array($date_courante, $list_jours)) {
            $classes[] = 'jour-special';
        }
        
        $classAttr = $classes ? 'class="' . implode(' ', $classes) . '"' : '';
        
        // Lien pour sélectionner le jour
        $url = "?mois=$mois&annee=$annee&jour=" . urlencode($date_courante) . "&idEmp=$id_employe" . (isset($_GET['recherche']) ? "&recherche=" . urlencode($_GET['recherche']) : "");
        echo "<td $classAttr onclick=\"window.location.href='$url'\">";
        echo "<span style='display:block; margin-bottom:5px; font-weight:bold;'>$jour</span>";
        echo "</td>";
        
        if (($jour + $jourDebut - 1) % 7 == 0) {
            echo "</tr><tr>";
        }
    }
    
    echo "</tr>";
    echo "</table>";
}

function afficherScoresEmploye($scores) {
    echo "<div class='scores-employe'>";
    echo "<h3>Évaluation</h3>";
    echo "<div class='score-item'>";
    echo "<span class='score-label'>Ponctualité :</span>";
    echo "<div class='stars'>" . genererEtoiles($scores['ponctualite']) . "</div>";
    echo "</div>";
    
    echo "<div class='score-item'>";
    echo "<span class='score-label'>Gestion du temps :</span>";
    echo "<div class='stars'>" . genererEtoiles($scores['gestion_temps']) . "</div>";
    echo "</div>";
    
    echo "<div class='score-item'>";
    echo "<span class='score-label'>Motivation :</span>";
    echo "<div class='stars'>" . genererEtoiles($scores['motivation']) . "</div>";
    echo "</div>";
    echo "</div>";
}

function genererEtoiles($note) {
    $etoiles = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $note) {
            $etoiles .= "<span class='star active'>★</span>";
        } else {
            $etoiles .= "<span class='star'>★</span>";
        }
    }
    return $etoiles;
}

function afficherPanneauJour($jour_selectionne, $id_employe, $employes) {
    if (!$jour_selectionne) {
        echo "<div class='aucune-selection'>";
        echo "<h3>Sélectionnez un jour dans le calendrier</h3>";
        echo "<p>Cliquez sur une date pour afficher les détails</p>";
        echo "</div>";
        return;
    }
    
    if (!isset($employes[$id_employe])) {
        echo "<div class='aucune-selection'>";
        echo "<h3>Aucun employé sélectionné</h3>";
        echo "<p>Veuillez sélectionner un employé valide</p>";
        echo "</div>";
        return;
    }
    
    $employe = $employes[$id_employe];
    $date_formatee = date('d/m/Y', strtotime($jour_selectionne));
    $nom_jour = date('l', strtotime($jour_selectionne));
    
    echo "<div class='panneau-jour'>";
    echo "<h2>📅 $date_formatee</h2>";
    echo "<div class='info-employe'>";
    echo "<p><strong>Employé :</strong> " . $employe['nom'] . " " . $employe['prenom'] . "</p>";
    echo "<p><strong>Poste :</strong> " . $employe['poste'] . "</p>";
    echo "<p><strong>Jour :</strong> " . ucfirst($nom_jour) . "</p>";
    echo "</div>";
    
    // Afficher les scores de l'employé
    afficherScoresEmploye($employe['scores']);
    
    echo "<div class='presences'>";
    echo "<h3>Pointages</h3>";
    
    // Vérifier si des pointages existent pour cette date
    if (isset($employe['pointages'][$jour_selectionne])) {
        $pointages = $employe['pointages'][$jour_selectionne];
        
        foreach ($pointages as $pointage) {
            echo "<div class='presence-item'>";
            echo "<div class='presence-heures'>$pointage</div>";
            list($debut, $fin) = explode('-', $pointage);
            $duree = calculerDuree($debut, $fin);
            echo "<div><strong>Durée :</strong> $duree</div>";
            echo "</div>";
        }
        
        // Tableau des heures (normales et supplémentaires)
        echo "<h3>Récapitulatif des heures</h3>";
        echo "<table class='tableau-heures'>";
        echo "<tr><th>Type d'heures</th><th class='heures-chiffre'>Durée</th></tr>";
        
        // Heures normales
        echo "<tr class='sous-total'><td>Heures normales</td><td class='heures-chiffre'>7h00</td></tr>";
        
        // Heures supplémentaires
        echo "<tr class='sous-total'><td>Heures supplémentaires</td><td class='heures-chiffre'>2h00</td></tr>";
        
        // Sous-catégories des heures supplémentaires
        echo "<tr class='sous-categorie'><td>- Week-end</td><td class='heures-chiffre'>1h00</td></tr>";
        echo "<tr class='sous-categorie'><td>- Hors-service</td><td class='heures-chiffre'>0h30</td></tr>";
        echo "<tr class='sous-categorie'><td>- Jours fériés</td><td class='heures-chiffre'>0h30</td></tr>";
        
        // Total général
        echo "<tr class='total-ligne'>";
        echo "<td><strong>TOTAL JOURNALIER</strong></td>";
        echo "<td class='heures-chiffre'><strong>9h00</strong></td>";
        echo "</tr>";
        
        echo "</table>";
    } else {
        echo "<div class='aucune-donnee'>";
        echo "<p>Données non communiquées pour cette date</p>";
        echo "</div>";
    }
    
    echo "</div>"; // .presences
    echo "</div>"; // .panneau-jour
}

function calculerDuree($debut, $fin) {
    $debut_timestamp = strtotime($debut);
    $fin_timestamp = strtotime($fin);
    $duree_secondes = $fin_timestamp - $debut_timestamp;
    $heures = floor($duree_secondes / 3600);
    $minutes = floor(($duree_secondes % 3600) / 60);
    return sprintf("%dh%02d", $heures, $minutes);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendrier Professionnel</title>
    <link rel="stylesheet" href="<?php echo constant('BASE_URL'); ?>/public/assets/css/Hcss/calendar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .recherche-employe {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            position: relative;
        }
        
        .recherche-employe input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .recherche-employe input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        
        .resultats-recherche {
            position: absolute;
            top: 100%;
            left: 15px;
            right: 15px;
            margin-top: 5px;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
        }
        
        .resultats-recherche.visible {
            display: block;
        }
        
        .employe-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f1f3f4;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .employe-item:hover {
            background-color: #f8f9fa;
        }
        
        .employe-item:last-child {
            border-bottom: none;
        }
        
        .employe-nom {
            font-weight: bold;
            color: #333;
        }
        
        .employe-poste {
            color: #666;
            font-size: 14px;
            margin-left: 10px;
        }
        
        .aucun-resultat {
            padding: 15px;
            text-align: center;
            color: #666;
            font-style: italic;
        }
        
        .info-recherche {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }
        
        .employe-selectionne {
            margin: 10px 0;
            padding: 10px 15px;
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Calendrier des Présences</h1>
        
        <!-- Recherche d'employé -->
        <div class="recherche-employe">
            <label for="recherche-input"><strong>Rechercher un employé :</strong></label>
            <input 
                type="text" 
                id="recherche-input" 
                placeholder="Tapez un nom ou prénom (ex: rak, marie, dupont...)..." 
                value="<?= htmlspecialchars($recherche) ?>"
            >
            <div class="info-recherche">La recherche s'effectue sur le nom et prénom de l'employé</div>
            
            <div id="resultats-recherche" class="resultats-recherche">
                <!-- Les résultats seront affichés dynamiquement en JavaScript -->
            </div>
        </div>
        
        <!-- Employé sélectionné -->
        <?php if (isset($employes_filtres[$id_employe])): ?>
            <div class="employe-selectionne">
                <strong>Employé sélectionné :</strong> 
                <?= $employes_filtres[$id_employe]['nom'] ?> <?= $employes_filtres[$id_employe]['prenom'] ?> 
                - <?= $employes_filtres[$id_employe]['poste'] ?>
            </div>
        <?php endif; ?>
        
        <div class="contenu-principal">
            <div class="colonne-gauche">
                <?php
                $mois = isset($_GET['mois']) ? (int)$_GET['mois'] : null;
                $annee = isset($_GET['annee']) ? (int)$_GET['annee'] : null;
                
                if (isset($employes_filtres[$id_employe])) {
                    afficherCalendrier($mois, $annee, $list_jours, $id_employe);
                } else {
                    echo "<div class='aucune-selection'>";
                    echo "<p>Veuillez sélectionner un employé pour afficher le calendrier</p>";
                    echo "</div>";
                }
                ?>
                
                <?php if (isset($employes_filtres[$id_employe])): ?>
                <div class="navigation">
                    <?php
                    $moisActuel = $mois ?? date('n');
                    $anneeActuelle = $annee ?? date('Y');
                    
                    $moisPrecedent = $moisActuel - 1;
                    $anneePrecedente = $anneeActuelle;
                    if ($moisPrecedent < 1) {
                        $moisPrecedent = 12;
                        $anneePrecedente--;
                    }
                    
                    $moisSuivant = $moisActuel + 1;
                    $anneeSuivante = $anneeActuelle;
                    if ($moisSuivant > 12) {
                        $moisSuivant = 1;
                        $anneeSuivante++;
                    }
                    
                    // Conserver les paramètres dans les liens de navigation
                    $param_jour = $jour_selectionne ? "&jour=" . urlencode($jour_selectionne) : "";
                    $param_employe = "&idEmp=$id_employe";
                    $param_recherche = !empty($recherche) ? "&recherche=" . urlencode($recherche) : "";
                    ?>
                    
                    <a href="?mois=<?= $moisPrecedent ?>&annee=<?= $anneePrecedente ?><?= $param_jour ?><?= $param_employe ?><?= $param_recherche ?>">← Précédent</a>
                    <a href="?mois=<?= date('n') ?>&annee=<?= date('Y') ?><?= $param_jour ?><?= $param_employe ?><?= $param_recherche ?>">Aujourd'hui</a>
                    <a href="?mois=<?= $moisSuivant ?>&annee=<?= $anneeSuivante ?><?= $param_jour ?><?= $param_employe ?><?= $param_recherche ?>">Suivant →</a>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="colonne-droite">
                <?php 
                if (isset($employes_filtres[$id_employe])) {
                    afficherPanneauJour($jour_selectionne, $id_employe, $employes_filtres);
                } else {
                    echo "<div class='aucune-selection'>";
                    echo "<h3>Aucun employé sélectionné</h3>";
                    echo "<p>Veuillez sélectionner un employé pour afficher les détails</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
    // Données des employés en JavaScript
    const employesData = <?php echo json_encode($employes); ?>;
    
    const rechercheInput = document.getElementById('recherche-input');
    const resultatsRecherche = document.getElementById('resultats-recherche');
    
    // Fonction pour filtrer les employés côté client
    function filtrerEmployesClient(recherche) {
        if (!recherche.trim()) {
            return employesData;
        }
        
        const termes = recherche.toLowerCase().split(' ').filter(terme => terme.trim() !== '');
        const resultats = {};
        
        for (const [id, employe] of Object.entries(employesData)) {
            const nomComplet = (employe.nom + ' ' + employe.prenom).toLowerCase();
            let correspond = true;
            
            for (const terme of termes) {
                if (!nomComplet.includes(terme)) {
                    correspond = false;
                    break;
                }
            }
            
            if (correspond) {
                resultats[id] = employe;
            }
        }
        
        return resultats;
    }
    
    // Fonction pour afficher les résultats
    function afficherResultats(resultats) {
        resultatsRecherche.innerHTML = '';
        
        if (Object.keys(resultats).length === 0) {
            resultatsRecherche.innerHTML = '<div class="aucun-resultat">Aucun employé trouvé pour "' + rechercheInput.value + '"</div>';
        } else {
            for (const [id, employe] of Object.entries(resultats)) {
                const div = document.createElement('div');
                div.className = 'employe-item';
                div.innerHTML = `
                    <span class="employe-nom">${employe.nom} ${employe.prenom}</span>
                    <span class="employe-poste">- ${employe.poste}</span>
                `;
                div.onclick = () => selectionnerEmploye(id);
                resultatsRecherche.appendChild(div);
            }
        }
    }
    
    // Fonction pour sélectionner un employé
    function selectionnerEmploye(idEmploye) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('idEmp', idEmploye);
        // On garde la recherche actuelle dans l'URL
        if (rechercheInput.value.trim()) {
            urlParams.set('recherche', rechercheInput.value);
        }
        window.location.href = '?' + urlParams.toString();
    }
    
    // Événement sur l'input de recherche
    rechercheInput.addEventListener('input', function() {
        const recherche = this.value.trim();
        
        if (recherche.length === 0) {
            resultatsRecherche.classList.remove('visible');
            return;
        }
        
        const resultats = filtrerEmployesClient(recherche);
        afficherResultats(resultats);
        resultatsRecherche.classList.add('visible');
    });
    
    // Afficher les résultats quand on focus l'input
    rechercheInput.addEventListener('focus', function() {
        if (this.value.trim() && resultatsRecherche.children.length > 0) {
            resultatsRecherche.classList.add('visible');
        }
    });
    
    // Masquer les résultats quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.recherche-employe')) {
            resultatsRecherche.classList.remove('visible');
        }
    });
    
    // Afficher les résultats au chargement si on a une recherche
    document.addEventListener('DOMContentLoaded', function() {
        if (rechercheInput.value.trim()) {
            const resultats = filtrerEmployesClient(rechercheInput.value);
            afficherResultats(resultats);
            resultatsRecherche.classList.add('visible');
        }
    });
    
    // Permettre la navigation avec les touches du clavier
    rechercheInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            resultatsRecherche.classList.remove('visible');
            this.blur();
        }
    });
    </script>
</body>
</html>