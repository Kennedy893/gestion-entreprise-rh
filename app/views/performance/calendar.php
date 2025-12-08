
<?php
// Récupération des données dynamiques
$jour_selectionne = $data['jour'] ?? null;
$id_employe = $data['idEmp'] ?? null;
$mois = $data['mois'] ?? date('n');
$annee = $data['annee'] ?? date('Y');
$employes = $data['employes'] ?? [];
$poste_label = $data['poste']['label'] ?? '';
$note_jour = $data['note_jour'] ?? null;
$note_mois = $data['note_mois'] ?? [];
$idDept = $data['idDept'] ?? null;
$presence = $data['presence_jour'] ?? [];
$jours_travail_mois = $data['jours_travail_mois'] ?? [];
$jours_travailles = [];
if (!empty($jours_travail_mois)) {
    foreach ($jours_travail_mois as $jtm) {
        $date = $jtm['date_travail'] ?? $jtm['date_travaille'] ?? null;
        if ($date) {
            $jours_travailles[] = $date;
        }
    }
}

// Pour la navigation
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

// Générer la liste des jours du mois pour le calendrier
function joursMois($annee, $mois) {
    $start = new DateTime(sprintf('%04d-%02d-01', $annee, $mois));
    $end   = (clone $start)->modify('first day of next month');
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);
    $result = [];
    foreach ($period as $d) {
        $result[] = $d->format('Y-m-d');
    }
    return $result;
}
$list_jours = joursMois($annee, $mois);

// Générer le calendrier dynamique
function afficherCalendrier($mois, $annee, $list_jours, $id_employe, $jour_selectionne, $idDept, $jours_travailles = []) {
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
        if ($jour_selectionne == $date_courante) {
            $classes[] = 'selectionne';
        }
        if (in_array($date_courante, $jours_travailles)) {
            $classes[] = 'jour-travaille';
        }
        $classAttr = $classes ? 'class="' . implode(' ', $classes) . '"' : '';
        $url = "?mois=$mois&annee=$annee&jour=" . urlencode($date_courante) . "&idEmp=$id_employe&idDept=$idDept";
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

// Affichage des notes sous forme d'étoiles
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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calendrier Professionnel</title>
    <link rel="stylesheet" href="<?php echo constant('BASE_URL'); ?>/public/assets/css/Hcss/calendar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .container { width: 1350px; margin: 20px 350px;}
        .mois-titre { font-size: 1.3rem; font-weight: bold; margin-bottom: 10px; }
        .calendrier { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .calendrier th, .calendrier td { border: 1px solid #e2e8f0; text-align: center; padding: 8px; }
        .calendrier td.vide { background: #f5f5f5; }
        .calendrier td.aujourdhui { background: #e3f2fd; }
        .calendrier td.selectionne { background: #bbdefb; border: 2px solid #1976d2; }
        .calendrier td.jour-travaille { background: #d1fae5 !important; border-color: #10b981 !important; position: relative; }
        .calendrier td.jour-travaille::after {
            content: '';
            display: block;
            position: absolute;
            bottom: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
        }
        .star { color: #bbb; font-size: 1.2em; }
        .star.active { color: #1976d2; }
        .notes-table { margin: 12px 0 24px 0; }
        .notes-table th { background: #f7f7f7; }
        .notes-table td { text-align: center; }
        .employe-list { margin: 0 0 18px 0; }
        .employe-list li { margin-bottom: 4px; }
        .info-employe { margin-bottom: 10px; }
        .scores-employe { margin-bottom: 10px; }
        .scores-employe .score-label { font-weight: 600; margin-right: 8px; }
        .presence-item { margin-bottom: 8px; }
        .aucune-selection { color: #888; margin: 20px 0; }
        .navigation { margin: 12px 0 18px 0; }
        .navigation a { margin: 0 8px; color: #1976d2; text-decoration: none; font-weight: 600; }
        .navigation a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <h1>Calendrier des Présences</h1>
    <div class="navigation">
        <a href="<?php echo constant('BASE_URL'); ?>performance/calendar?mois=<?= $moisPrecedent ?>&annee=<?= $anneePrecedente ?>&idEmp=<?= $id_employe ?>&idDept=<?= $idDept ?>">← Précédent</a>
        <a href="<?php echo constant('BASE_URL'); ?>performance/calendar?mois=<?= date('n') ?>&annee=<?= date('Y') ?>&idEmp=<?= $id_employe ?>&idDept=<?= $idDept ?>">Aujourd'hui</a>
        <a href="<?php echo constant('BASE_URL'); ?>performance/calendar?mois=<?= $moisSuivant ?>&annee=<?= $anneeSuivante ?>&idEmp=<?= $id_employe ?>&idDept=<?= $idDept ?>">Suivant →</a>
    </div>
    <div class="contenu-principal" style="display: flex; gap: 32px;">
        <div class="colonne-gauche" style="flex:1;">
            <?php afficherCalendrier($mois, $annee, $list_jours, $id_employe, $jour_selectionne, $idDept, $jours_travailles); ?>
            <div style="margin-top:18px;">
                <h3>Liste des employés</h3>
                <ul class="employe-list">
                    <?php foreach ($employes as $emp): ?>
                        <li>
                            <a href="<?php echo constant('BASE_URL'); ?>performance/calendar?mois=<?= $mois ?>&annee=<?= $annee ?>&idEmp=<?= $emp['id'] ?>&idDept=<?= $idDept ?>" 
                               style="<?= ($emp['id'] == $id_employe) ? 'font-weight:bold;color:#1976d2;' : '' ?>">
                                <?= htmlspecialchars($emp['nom'].' '.$emp['prenom']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="colonne-droite" style="flex:2;">
            <?php
            // Affichage du panneau du jour sélectionné
            if (!$jour_selectionne || !$id_employe) {
                echo "<div class='aucune-selection'><h3>Sélectionnez un jour et un employé</h3></div>";
            } else {
                // Trouver l'employé sélectionné
                $employe = null;
                foreach ($employes as $emp) {
                    if ($emp['id'] == $id_employe) {
                        $employe = $emp;
                        break;
                    }
                }
                if (!$employe) {
                    echo "<div class='aucune-selection'><h3>Aucun employé sélectionné</h3></div>";
                } else {
                    $date_formatee = date('d/m/Y', strtotime($jour_selectionne));
                    $nom_jour = ucfirst(date('l', strtotime($jour_selectionne)));
                    echo "<div class='panneau-jour'>";
                    echo "<h2>$date_formatee</h2>";
                    echo "<div class='info-employe'>";
                    echo "<p><strong>Employé :</strong> " . htmlspecialchars($employe['nom'] . " " . $employe['prenom']) . "</p>";
                    echo "<p><strong>Poste :</strong> " . htmlspecialchars($poste_label) . "</p>";
                    echo "<p><strong>Jour :</strong> " . htmlspecialchars($nom_jour) . "</p>";
                    echo "</div>";

                    // Affichage des scores du jour
                    if ($note_jour) {
                        echo "<div class='scores-employe'>";
                        echo "<div><span class='score-label'>Ponctualité :</span>" . genererEtoiles($note_jour['ponctualite'] ?? 0) . "</div>";
                        echo "<div><span class='score-label'>Gestion du temps :</span>" . genererEtoiles($note_jour['gestion_temps'] ?? 0) . "</div>";
                        echo "<div><span class='score-label'>Productivité :</span>" . genererEtoiles($note_jour['productivite'] ?? 0) . "</div>";
                        echo "</div>";
                    }

                    // Affichage des scores mensuels
                    if (!empty($note_mois)) {
                        echo "<table class='notes-table'><thead><tr>";
                        echo "<th>Ponctualité</th><th>Gestion du temps</th><th>Productivité</th>";
                        echo "</tr></thead><tbody><tr>";
                        echo "<td>" . genererEtoiles(round($note_mois['ponctualite'] ?? 0)) . "</td>";
                        echo "<td>" . genererEtoiles(round($note_mois['gestion_temps'] ?? 0)) . "</td>";
                        echo "<td>" . genererEtoiles(round($note_mois['productivite'] ?? 0)) . "</td>";
                        echo "</tr></tbody></table>";
                    }

                    // Affichage des pointages du jour
                    echo "<div class='presences'>";
                    echo "<h3>Pointages</h3>";
                    
                    if (!empty($presence)) {
                        foreach ($presence as $pointage) {
                            echo "<div class='presence-item'>";
                            $entree = $pointage['entree'] ?? '';
                            $sortie = $pointage['sortie'] ?? '';
                            echo "<div class='presence-heures'>" . htmlspecialchars($entree . ' - ' . $sortie) . "</div>";
                            if ($entree && $sortie) {
                                $duree = strtotime($sortie) - strtotime($entree);
                                $heures = floor($duree / 3600);
                                $minutes = floor(($duree % 3600) / 60);
                                echo "<div><strong>Durée :</strong> " . sprintf("%dh%02d", $heures, $minutes) . "</div>";
                            } else {
                                echo "<div><em>Pointage incomplet</em></div>";
                            }
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='aucune-donnee'><p>Aucun pointage pour cette date</p></div>";
                    }
                    echo "</div>"; // .presences
                    echo "</div>"; // .panneau-jour
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>