<?php
// Fichier : app/views/rh/candidats_resultats.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title ?? 'Résultats candidats') ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="/public/assets/css/rh_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .badge { padding:6px 10px; border-radius:6px; font-weight:700; display:inline-flex; gap:8px; align-items:center; }
        .badge-success { background:#dcfce7; color:#166534; }
        .badge-danger { background:#fee2e2; color:#991b1b; }
        .badge-warning { background:#fff7ed; color:#92400e; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>

    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="page-header">
                <h1>Résultats candidats</h1>
            </div>

            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Statut</th>
                            <th>Date validation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidats as $cand): ?>
                        <?php
                            // Priorité : status_computed si présent (controller), sinon recalcul local
                            if (isset($cand['status_computed'])) {
                                $status = $cand['status_computed'];
                            } else {
                                $decision = strtolower(trim((string)($cand['decision_finale'] ?? $cand['statut'] ?? '')));
                                if (in_array($decision, ['accepte','accepté','accepted','accepter','reçu','recu']) || !empty($cand['date_decision'])) {
                                    $status = 'recu';
                                } elseif (in_array($decision, ['rejete','rejeté','rejet','rejected','refuse','refusé'])) {
                                    $status = 'rejete';
                                } else {
                                    $status = 'attente';
                                }
                            }

                            // label
                            if ($status === 'recu') {
                                $labelHtml = '<span class="badge badge-success"><i class="fas fa-check"></i> Reçu</span>';
                            } elseif ($status === 'rejete') {
                                $labelHtml = '<span class="badge badge-danger"><i class="fas fa-times"></i> Rejeté</span>';
                            } else {
                                $labelHtml = '<span class="badge badge-warning"><i class="fas fa-clock"></i> En attente</span>';
                            }

                            $dateDec = !empty($cand['date_decision']) ? date('d/m/Y', strtotime($cand['date_decision'])) : '—';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($cand['nom'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($cand['prenom'] ?? '—') ?></td>
                            <td><?= $labelHtml ?></td>
                            <td><?= $dateDec ?></td>
                            <td>
                                <a href="/rh/candidature/<?= urlencode($cand['id']) ?>" class="btn btn-sm">Voir</a>
                                <a href="/rh/contrats?candidature_id=<?= urlencode($cand['id']) ?>" class="btn btn-sm">Voir contrat</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>
