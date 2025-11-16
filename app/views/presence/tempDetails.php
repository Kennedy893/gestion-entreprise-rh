<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de la présence</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; margin: 0; }
        .container { max-width: 960px; margin: 16px auto; padding: 0 12px; }
        h2 { margin: 12px 0; }
        h3 { margin: 6px 0 12px; }

        /* Tableau stats et principal */
        table { width: 100%; border-collapse: collapse; margin-top: 8px; table-layout: fixed; font-size: 14px; }
        thead th { background: #f7f7f7; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; vertical-align: top; }
        tbody tr:nth-child(odd) { background: #fafafa; }

        .stats-table { max-width: 700px; margin-bottom: 18px; margin-top: 12px; }
        .stats-table th { width: 40%; text-align: left; background: #f7f7f7; }
        .stats-table td { font-weight: bold; }

        /* Liste des pointages plus esthétique */
        .presences { margin: 0; padding: 0; list-style: none; display: flex; flex-wrap: wrap; gap: 6px; }
        .presences li {
            background: #f5f7fb;
            border: 1px solid #e4e8f0;
            border-radius: 6px;
            padding: 6px 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .presences .time { font-weight: 600; color: #1f2937; }
        .presences .amount {
            background: #eaf2fd;
            color: #1976d2;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .muted { color: #777; }
        .badge-muted {
            display: inline-block;
            background: #f0f0f0;
            color: #666;
            border-radius: 10px;
            padding: 4px 8px;
            font-size: 12px;
        }
        .empty { padding: 12px 0; color: #555; }
    </style>
</head>
<body>
    <?php include("app/views/bar/sidebar.php")?>

<div class="container">
<?php
    $date = Flight::request()->query->date ?? date('Y-m-d');

    // Récupère toutes les présences complètes (avec entrée/sortie) du jour
    $presences = Flight::HModel()->get_generalised(
        "presence",
        "*",
        ["date_travail"],
        [$date],
        "AND entree IS NOT NULL AND sortie IS NOT NULL ORDER BY id_employe, entree ASC",
        []
    );

    // Groupe par employé et calcule les stats (heures normales vs supplémentaires)
    $byEmp = [];
    $empIds = [];
    $empStats = []; // id => ['norm'=>..., 'supp'=>...]
    foreach ($presences as $p) {
        $id = (int)$p['id_employe'];
        $byEmp[$id] = $byEmp[$id] ?? [];
        $byEmp[$id][] = $p;
        $empIds[$id] = true;
    }

    $totalEmployes = count($empIds);
    $totalHeureNorm = 0.0;
    $totalHeureSupp = 0.0;

    $empNames = [];
    foreach (array_keys($byEmp) as $id) {
        // Nom employé
        $emp = Flight::HModel()->get_generalised("employe", "*", ["id"], [$id], "", []);
        $empNames[$id] = trim(($emp[0]['nom'] ?? '').' '.($emp[0]['prenom'] ?? ''));

        // Sommes par employé
        $norm = 0.0; $supp = 0.0;
        foreach ($byEmp[$id] as $p) {
            $duree = (strtotime($p['sortie']) - strtotime($p['entree'])) / 3600;
            $montant = $p['montant'];
            if ($montant !== null) {
                $salaireNormal = Flight::HpresenceModel()->get_salaire_heure($id, $p['date_travail']) * $duree;
                if ($montant <= $salaireNormal) { $norm += $duree; } else { $supp += $duree; }
            }
        }
        $empStats[$id] = ['norm' => $norm, 'supp' => $supp];
        $totalHeureNorm += $norm;
        $totalHeureSupp += $supp;
    }
?>
    <h2>Détail de la présence du <?php echo htmlspecialchars($date); ?></h2>

    <table class="stats-table">
        <tr>
            <th>Total des employés</th>
            <td><?php echo (int)$totalEmployes; ?></td>
        </tr>
        <tr>
            <th>Total des heures normales</th>
            <td><?php echo number_format($totalHeureNorm, 2, ',', ' '); ?> h</td>
        </tr>
        <tr>
            <th>Total des heures supplémentaires</th>
            <td><?php echo number_format($totalHeureSupp, 2, ',', ' '); ?> h</td>
        </tr>
    </table>

    <?php if (empty($byEmp)): ?>
        <div class="empty">Aucun pointage pour cette date.</div>
    <?php else: ?>
        <table>
            <colgroup>
                <col style="width: 220px;">
                <col style="width: 160px;">
                <col style="width: 180px;">
                <col><!-- presences -->
            </colgroup>
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Heures normales</th>
                    <th>Heures supplémentaires</th>
                    <th>Pointages (Entrée / Sortie / Montant gagné)</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($byEmp as $id => $rows): ?>
                <tr>
                    <td><?php echo htmlspecialchars($empNames[$id] ?? ("#".$id)); ?></td>
                    <td><?php echo number_format((float)$empStats[$id]['norm'], 2, ',', ' '); ?> h</td>
                    <td><?php echo number_format((float)$empStats[$id]['supp'], 2, ',', ' '); ?> h</td>
                    <td>
                        <ul class="presences">
                            <?php foreach ($rows as $p): ?>
                                <li>
                                    <span class="time"><?php echo htmlspecialchars(($p['entree'] ?? '').' / '.($p['sortie'] ?? '')); ?></span>
                                    <?php
                                        $m = $p['montant'];
                                        $mTxt = ($m === null) ? '—' : number_format((float)$m, 2, ',', ' ');
                                    ?>
                                    <span class="amount"><?php echo $mTxt; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>