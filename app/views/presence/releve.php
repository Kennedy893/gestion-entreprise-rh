<link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/releve.css">

<div class="container">
    <?php
    // Récupération des données PHP
    $moisNoms = [1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $moisLabel = isset($moisNoms[(int)$mois]) ? $moisNoms[(int)$mois] : $mois;
    $empNom = isset($employe['nom']) ? $employe['nom'] : '';
    $empPrenom = isset($employe['prenom']) ? $employe['prenom'] : '';

    // Totaux mensuels (recalcul pour s'assurer que les variables existent)
    $totalHeureNorm = 0.0;
    $totalHeureSupp = 0.0;
    $totalMontantNorm = 0.0;
    $totalMontantSupp = 0.0;
    $retour = $retour ?? []; // Assure que $retour est défini
    foreach ($retour as $row) {
        $totalHeureNorm += (float)($row['heure_normale'] ?? 0);
        $totalHeureSupp += (float)($row['heure_supplementaire'] ?? 0);
        $totalMontantNorm += (float)($row['montant_normale'] ?? 0);
        $totalMontantSupp += (float)($row['montant_supplementaire'] ?? 0);
    }
    ?>

    <h2><i class="fa-solid fa-file-invoice-dollar" style="color: var(--primary);"></i> Relevé de présence</h2>
    <h3><?php echo htmlspecialchars(trim($empNom . ' ' . $empPrenom)) . ' — ' . htmlspecialchars($moisLabel . ' ' . $annee); ?></h3>

    <table class="summary-table">
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
                <td data-label="Heures normales"><?php echo number_format($totalHeureNorm, 2, ',', ' '); ?> h</td>
                <td data-label="Heures supp."><?php echo number_format($totalHeureSupp, 2, ',', ' '); ?> h</td>
                <td data-label="Montant normal"><?php echo number_format($totalMontantNorm, 2, ',', ' '); ?> Ar</td>
                <td data-label="Montant supp."><?php echo number_format($totalMontantSupp, 2, ',', ' '); ?> Ar</td>
            </tr>
        </tbody>
    </table>

    <div class="card">
        <style>
            /* VARIABLES */
            :root {
                --primary: #4f46e5;
                /* Indigo */
                --success: #10b981;
                /* Vert */
                --warning: #f59e0b;
                /* Orange pour Supp. */
                --text-main: #0f172a;
                --text-muted: #64748b;
                --border: #e2e8f0;
                --bg-header: #eef2ff;
                /* Fond d'en-tête léger */
                --bg-card: #ffffff;
                --radius-sm: 6px;
            }

            /* --- TABLE STYLES --- */
            .table-responsive {
                overflow-x: auto;
            }

            .table-pointage {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                background-color: var(--bg-card);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                border-radius: var(--radius-sm);
                overflow: hidden;
                min-width: 800px;
                /* Assure que le tableau ne s'écrase pas trop */
            }

            .table-pointage thead th {
                background-color: var(--bg-header);
                color: var(--primary);
                text-align: center;
                padding: 12px 10px;
                font-size: 0.85rem;
                font-weight: 700;
                border-bottom: 2px solid var(--primary);
                white-space: nowrap;
            }

            .table-pointage tbody tr {
                border-bottom: 1px solid var(--border);
                transition: background-color 0.2s;
            }

            .table-pointage tbody tr:hover {
                background-color: #f8fafc;
            }

            .table-pointage tbody tr:last-child {
                border-bottom: none;
            }

            .table-pointage tbody td {
                padding: 10px;
                font-size: 0.9rem;
                color: var(--text-main);
                vertical-align: top;
            }

            /* Colonnes Spécifiques */
            .table-pointage td:nth-child(1) {
                /* Date */
                font-weight: 600;
                text-align: center;
                width: 100px;
                color: var(--primary);
            }

            .table-pointage td:nth-child(2),
            /* H. Norm. */
            .table-pointage td:nth-child(3) {
                /* H. Supp. */
                text-align: right;
                font-weight: 500;
                width: 80px;
            }

            .table-pointage td:nth-child(4),
            /* Montant Norm. */
            .table-pointage td:nth-child(5) {
                /* Montant Supp. */
                text-align: right;
                font-weight: 600;
                font-family: monospace;
                width: 120px;
            }

            /* Couleur des heures supplémentaires et montants */
            .table-pointage thead th:nth-child(3),
            .table-pointage thead th:nth-child(5),
            .table-pointage tbody td:nth-child(3),
            .table-pointage tbody td:nth-child(5) {
                color: var(--warning);
            }

            .table-pointage thead th:nth-child(2),
            .table-pointage thead th:nth-child(4),
            .table-pointage tbody td:nth-child(2),
            .table-pointage tbody td:nth-child(4) {
                color: var(--success);
            }

            /* --- DÉTAIL DES POINTAGES --- */
            .presences {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .presences li {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px 0;
                border-bottom: 1px dashed var(--border);
                font-size: 0.85rem;
            }

            .presences li:last-child {
                border-bottom: none;
            }

            .time {
                font-weight: 500;
                color: var(--text-main);
                flex-grow: 1;
            }

            .time i {
                color: var(--text-muted);
                margin-right: 3px;
            }

            .amount {
                font-weight: 700;
                color: var(--primary);
                flex-shrink: 0;
                text-align: right;
                font-family: monospace;
                min-width: 80px;
            }

            .badge-muted {
                display: inline-block;
                padding: 4px 8px;
                background-color: #e5e7eb;
                color: var(--text-muted);
                border-radius: var(--radius-sm);
                font-size: 0.75rem;
                font-weight: 600;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {

                /* Permet le défilement horizontal sur petits écrans */
                .table-responsive {
                    margin: 0 -15px;
                    /* Déborde légèrement si la table est dans un petit container */
                    padding: 0 15px;
                }
            }
        </style>

        <div class="table-responsive">
            <table class="table-pointage">
                <thead>
                    <tr>
                        <th style="width: 80px;">Date</th>
                        <th>H. Norm. <span style="font-size: 0.7em;">(h)</span></th>
                        <th>H. Supp. <span style="font-size: 0.7em;">(h)</span></th>
                        <th>Montant Norm.</th>
                        <th>Montant Supp.</th>
                        <th style="width: 40%;">Détail des Pointages / Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($retour) && is_array($retour)) {
                        foreach ($retour as $row):
                            // Fonction de formatage des montants
                            $moneyFormat = function ($value) {
                                return number_format((float)($value ?? 0), 2, ',', ' ') . ' Ar';
                            };
                            // Fonction de formatage des heures
                            $hourFormat = function ($value) {
                                return number_format((float)($value ?? 0), 2, ',', ' ') . ' h';
                            };
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date'] ?? 'N/A'); ?></td>
                                <td style="color: var(--success);"><?php echo $hourFormat($row['heure_normale']); ?></td>
                                <td style="color: var(--warning);"><?php echo $hourFormat($row['heure_supplementaire']); ?></td>
                                <td style="color: var(--success);"><?php echo $moneyFormat($row['montant_normale']); ?></td>
                                <td style="color: var(--warning);"><?php echo $moneyFormat($row['montant_supplementaire']); ?></td>
                                <td>
                                    <?php if (!empty($row['presence'])): ?>
                                        <ul class="presences">
                                            <?php foreach ($row['presence'] as $p): ?>
                                                <li>
                                                    <span class="time">
                                                        <i class="fa-solid fa-arrow-right-to-bracket"></i> <?php echo htmlspecialchars(($p['entree'] ?? '—') . ' / '); ?>
                                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> <?php echo htmlspecialchars(($p['sortie'] ?? '—')); ?>
                                                    </span>
                                                    <?php
                                                    $montant = $p['montant'] ?? null;
                                                    $montantTxt = ($montant === null)
                                                        ? '—'
                                                        : $moneyFormat($montant);
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
                        <?php endforeach;
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                Aucun enregistrement de pointage trouvé pour cette période.
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>