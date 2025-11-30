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
        <table border="2">
            <thead>
                <tr>
                    <th style="width: 80px;">Date</th>
                    <th>H. Norm.</th>
                    <th>H. Supp.</th>
                    <th>Montant Norm.</th>
                    <th>Montant Supp.</th>
                    <th style="width: 40%;">Détail des Pointages / Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($retour as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo number_format((float)($row['heure_normale'] ?? 0), 2, ',', ' '); ?> h</td>
                        <td><?php echo number_format((float)($row['heure_supplementaire'] ?? 0), 2, ',', ' '); ?> h</td>
                        <td><?php echo number_format((float)($row['montant_normale'] ?? 0), 2, ',', ' '); ?></td>
                        <td><?php echo number_format((float)($row['montant_supplementaire'] ?? 0), 2, ',', ' '); ?></td>
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
                                                : number_format((float)$montant, 2, ',', ' ') . ' €';
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