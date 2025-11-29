<?php
// Liste des jours spéciaux (exemple)
$list_jours = [
    '2024-01-15',
    '2024-01-20', 
    '2024-02-10',
    '2024-02-14',
];

// Récupérer les données du contrôleur
$annee = isset($annee) ? $annee : date('Y');
$mois = isset($mois) ? $mois : date('n');
$jour_selectionne = isset($jour) ? $jour : null;
$id_employe = isset($idEmp) ? $idEmp : 1;

// Données statiques des employés
$employes = [
    1 => [
        'nom' => 'DUPONT Jean',
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
        'nom' => 'MARTIN Sophie',
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
        'nom' => 'LEFRANC Pierre',
        'poste' => 'Chef de projet',
        'pointages' => [
            '2024-01-20' => ['08:00-12:30', '14:00-18:30'],
        ],
        'scores' => [
            'ponctualite' => 2,
            'gestion_temps' => 3,
            'motivation' => 4
        ]
    ]
];

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
        $url = "?mois=$mois&annee=$annee&jour=" . urlencode($date_courante) . "&idEmp=$id_employe";
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
    
    $employe = $employes[$id_employe];
    $date_formatee = date('d/m/Y', strtotime($jour_selectionne));
    $nom_jour = date('l', strtotime($jour_selectionne));
    
    echo "<div class='panneau-jour'>";
    echo "<h2>📅 $date_formatee</h2>";
    echo "<div class='info-employe'>";
    echo "<p><strong>Employé :</strong> " . $employe['nom'] . "</p>";
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
</head>
<body>
    <div class="container">
        <h1>Calendrier des Présences</h1>
        
        <!-- Sélecteur d'employé -->
        <div class="selecteur-employe">
            <label for="employe"><strong>Sélectionner un employé :</strong></label>
            <select id="employe" onchange="changerEmploye(this.value)">
                <?php foreach ($employes as $id => $emp): ?>
                    <option value="<?= $id ?>" <?= $id == $id_employe ? 'selected' : '' ?>>
                        <?= $emp['nom'] ?> - <?= $emp['poste'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="contenu-principal">
            <div class="colonne-gauche">
                <?php
                // Utiliser les variables du contrôleur
                afficherCalendrier($mois, $annee, $list_jours, $id_employe);
                ?>
                
                <div class="navigation">
                    <?php
                    $moisActuel = $mois;
                    $anneeActuelle = $annee;
                    
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
                    
                    // Conserver le jour sélectionné et l'employé dans les liens de navigation
                    $param_jour = $jour_selectionne ? "&jour=" . urlencode($jour_selectionne) : "";
                    $param_employe = "&idEmp=$id_employe";
                    ?>
                    
                    <a href="?mois=<?= $moisPrecedent ?>&annee=<?= $anneePrecedente ?><?= $param_jour ?><?= $param_employe ?>">← Précédent</a>
                    <a href="?mois=<?= date('n') ?>&annee=<?= date('Y') ?><?= $param_jour ?><?= $param_employe ?>">Aujourd'hui</a>
                    <a href="?mois=<?= $moisSuivant ?>&annee=<?= $anneeSuivante ?><?= $param_jour ?><?= $param_employe ?>">Suivant →</a>
                </div>
            </div>
            
            <div class="colonne-droite">
                <?php afficherPanneauJour($jour_selectionne, $id_employe, $employes); ?>
            </div>
        </div>
    </div>

    <script>
    function changerEmploye(idEmploye) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('idEmp', idEmploye);
        window.location.href = '?' + urlParams.toString();
    }
    </script>
</body>
</html>