<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-user-times"></i> Candidats Rejetés</h1>
            </div>

            <div class="stats-grid" style="grid-template-columns: 1fr;">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #ef4444;">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count($candidatures) ?></h3>
                        <p>Candidats rejetés</p>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <?php if (empty($candidatures)): ?>
                    <div class="empty-state">
                        <i class="fas fa-user-times"></i>
                        <h2>Aucun candidat rejeté</h2>
                        <p>Les candidats rejetés apparaîtront ici.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Candidat</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Poste</th>
                                <th>Expérience</th>
                                <th>Date candidature</th>
                                <th>Date décision</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatures as $c): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td><?= htmlspecialchars($c['telephone']) ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= htmlspecialchars($c['poste_nom'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td><?= $c['experience_annees'] ?> ans</td>
                                <td><?= date('d/m/Y', strtotime($c['date_candidature'])) ?></td>
                                <td>
                                    <?= isset($c['date_decision']) ? date('d/m/Y', strtotime($c['date_decision'])) : 'N/A' ?>
                                </td>
                                <td class="action-buttons">
                                    <a href="/rh/candidature/<?= $c['id'] ?>" 
                                       class="btn-icon btn-view" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <?php if (!empty($c['questionnaire_exists']) && $c['questionnaire_statut'] === 'soumis'): ?>
                                        <a href="/rh/competences/evaluer-questionnaire/<?= $c['id'] ?>" 
                                           class="btn-icon btn-primary" 
                                           title="Évaluer questionnaire">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>