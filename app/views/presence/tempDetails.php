<link rel="stylesheet" href="<?= constant('BASE_URL')?>public/assets/css/tempDetails.css">

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
    <h2><i class="fa-solid fa-calendar-day" style="color: var(--primary);"></i> Détail de la présence du <?php echo htmlspecialchars($date); ?></h2>

    <table class="stats-table">
        <tr>
            <th>Total des employés</th>
            <td><i class="fa-solid fa-users"></i> <?php echo (int)$totalEmployes; ?></td>
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
        <div class="empty">Aucun pointage complet pour cette date.</div>
    <?php else: ?>
        <div class="employee-detail-card">
            <table>
                <colgroup>
                    <col style="width: 250px;">
                    <col style="width: 150px;">
                    <col style="width: 150px;">
                    <col></colgroup>
                <thead>
                    <tr>
                        <th>Employé</th>
                        <th>Heures normales</th>
                        <th>Heures supplémentaires</th>
                        <th>Pointages (Entrée / Sortie / Montant)</th>
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
                                        <span class="time">
                                            <i class="fa-solid fa-arrow-right-to-bracket"></i> <?php echo htmlspecialchars(($p['entree'] ?? '—') . ' / '); ?>
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i> <?php echo htmlspecialchars(($p['sortie'] ?? '—')); ?>
                                        </span>
                                        <?php
                                            $m = $p['montant'] ?? null;
                                            $mTxt = ($m === null) ? '—' : number_format((float)$m, 2, ',', ' ') . ' €';
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
        </div>
    <?php endif; ?>
</div>