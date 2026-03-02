<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-file-contract"></i> Génération des Contrats</h1>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Contrat généré avec succès !
                </div>
            <?php endif; ?>

            <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #22c55e;">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count($contrats) ?></h3>
                        <p>Contrats générés</p>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <?php if (empty($contrats)): ?>
                    <div class="empty-state">
                        <i class="fas fa-file-contract"></i>
                        <h2>Aucun contrat généré</h2>
                        <p>Les contrats des candidats acceptés apparaîtront ici.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Email</th>
                                <th>Poste</th>
                                <th>Type Contrat</th>
                                <th>Date début</th>
                                <th>Salaire</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contrats as $contrat): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($contrat['employe_prenom'] . ' ' . $contrat['employe_nom']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($contrat['employe_email']) ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= htmlspecialchars($contrat['poste_nom']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($contrat['type_contrat_nom']) ?></td>
                                <td><?= date('d/m/Y', strtotime($contrat['date_debut'])) ?></td>
                                <td><strong><?= number_format($contrat['salaire'], 0, ',', ' ') ?> Ar</strong></td>
                                <td>
                                    <span class="badge badge-success">
                                        <?= htmlspecialchars($contrat['statut_contrat_nom']) ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="/rh/contrat/<?= $contrat['id'] ?>" 
                                       class="btn-icon btn-view" 
                                       title="Voir le contrat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/rh/contrat/<?= $contrat['id'] ?>/pdf" 
                                       class="btn-icon" 
                                       style="background: #fee2e2; color: #991b1b;"
                                       title="Télécharger PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
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