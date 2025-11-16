
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relevé de présence</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            background-color: #f5f7fa;
            min-height: 100vh;
            color: #222;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #2d3748;
            margin-bottom: 25px;
            font-size: 1.8rem;
            font-weight: 700;
        }

        h3 {
            color: #2d3748;
            margin-bottom: 20px;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #e53e3e;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .form-container {
            background: #f8fafc;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        label {
            font-weight: 600;
            color: #4a5568;
            min-width: 80px;
        }

        input[type="date"],
        input[type="time"],
        select {
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            min-width: 200px;
        }

        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.15);
        }

        .submit-btn {
            padding: 12px 25px;
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        th, td {
            border: 1px solid #e2e8f0;
            padding: 15px 12px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f7fafc;
        }

        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Cases à cocher (hérité si besoin) */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Filtre (hérité si besoin) */
        .filter-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }

        .filter-container label {
            font-weight: 600;
            color: #4a5568;
            margin-right: 10px;
        }

        #filterInput {
            padding: 10px 15px;
            min-width: 300px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }

        #filterInput:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 70px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .container {
                padding: 20px;
            }

            .form-group {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            input[type="date"],
            input[type="time"],
            select {
                min-width: auto;
                width: 100%;
            }

            #filterInput {
                min-width: 250px;
                width: 100%;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 12px 8px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 15px;
            }

            .container {
                padding: 15px;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.2rem;
            }

            .filter-container {
                padding: 15px;
            }

            #filterInput {
                min-width: 200px;
            }
        }

        /* Petites pastilles pour montants (comme précédemment) */
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
    <?php include("app/views/bar/sidebarEmp.php")?>

    <div class="main-content">
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

            <table>
                <thead>
                    <tr>
                        <th>Total heures normales</th>
                        <th>Total heures supplémentaires</th>
                        <th>Montant normal</th>
                        <th>Montant supplémentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo number_format($totalHeureNorm, 2, ',', ' '); ?> h</td>
                        <td><?php echo number_format($totalHeureSupp, 2, ',', ' '); ?> h</td>
                        <td><?php echo number_format($totalMontantNorm, 2, ',', ' '); ?></td>
                        <td><?php echo number_format($totalMontantSupp, 2, ',', ' '); ?></td>
                    </tr>
                </tbody>
            </table>

            <table>
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
    </div>
</body>
</html>