<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relevé de présence</title>
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
        td:nth-child(2), td:nth-child(3),
        td:nth-child(4), td:nth-child(5) { white-space: nowrap; }
        td:nth-child(4), td:nth-child(5) { text-align: right; }

        /* Tableau stats */
        .stats-table { max-width: 700px; margin-bottom: 18px; margin-top: 12px; }
        .stats-table th { width: 30%; text-align: left; background: #f7f7f7; }
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
    </style>
</head>
<body>
<div class="container">
<?php
    // Préparation libellés
    $moisNoms = [1=>'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
    $moisLabel = isset($moisNoms[(int)$mois]) ? $moisNoms[(int)$mois] : $mois;
    $empNom = isset($employe['nom']) ? $employe['nom'] : '';
    $empPrenom = isset($employe['prenom']) ? $employe['prenom'] : '';

    // Totaux mensuels
    $totalHeureNorm = 0.0;
    $totalHeureSupp = 0.0;
    $totalMontantNorm = 0.0;
    $totalMontantSupp = 0.0;
    foreach ($retour as $row) {
        $totalHeureNorm += (float)$row['heure_normale'];
        $totalHeureSupp += (float)$row['heure_supplementaire'];
        $totalMontantNorm += (float)$row['montant_normale'];
        $totalMontantSupp += (float)$row['montant_supplementaire'];
    }
?>
    <h2>Relevé de présence - <?php echo htmlspecialchars($moisLabel.' '.$annee); ?></h2>
    <h3>Employé : <?php echo htmlspecialchars(trim($empNom.' '.$empPrenom)); ?></h3>

    <table class="stats-table">
        <tr>
            <th>Total heures normales</th>
            <td><?php echo number_format($totalHeureNorm, 2, ',', ' '); ?> h</td>
        </tr>
        <tr>
            <th>Total heures supplémentaires</th>
            <td><?php echo number_format($totalHeureSupp, 2, ',', ' '); ?> h</td>
        </tr>
        <tr>
            <th>Montant normal</th>
            <td><?php echo number_format($totalMontantNorm, 2, ',', ' '); ?></td>
        </tr>
        <tr>
            <th>Montant supplémentaire</th>
            <td><?php echo number_format($totalMontantSupp, 2, ',', ' '); ?></td>
        </tr>
    </table>

    <table>
        <colgroup>
            <col style="width: 120px;">
            <col style="width: 120px;">
            <col style="width: 140px;">
            <col style="width: 140px;">
            <col style="width: 160px;">
            <col><!-- presences -->
        </colgroup>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heures normales</th>
                <th>Heures supplémentaires</th>
                <th>Montant normal</th>
                <th>Montant supplémentaire</th>
                <th>Présences (Entrée / Sortie / Montant gagné)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($retour as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo number_format((float)$row['heure_normale'], 2, ',', ' '); ?> h</td>
                <td><?php echo number_format((float)$row['heure_supplementaire'], 2, ',', ' '); ?> h</td>
                <td><?php echo number_format((float)$row['montant_normale'], 2, ',', ' '); ?></td>
                <td><?php echo number_format((float)$row['montant_supplementaire'], 2, ',', ' '); ?></td>
                <td>
                    <?php if (!empty($row['presence'])): ?>
                        <ul class="presences">
                            <?php foreach ($row['presence'] as $p): ?>
                                <li>
                                    <span class="time">
                                        <?php echo htmlspecialchars(($p['entree'] ?? '').' / '.($p['sortie'] ?? '')); ?>
                                    </span>
                                    <?php
                                        $montant = $p['montant'];
                                        $montantTxt = ($montant === null)
                                            ? '—'
                                            : number_format((float)$montant, 2, ',', ' ');
                                    ?>
                                    <span class="amount"><?php echo $montantTxt; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <span class="badge-muted">Absent</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>