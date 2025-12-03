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
                <h1><i class="fas fa-users"></i> Liste des Employés</h1>
            </div>

            <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr);">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #3b82f6;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count($employes) ?></h3>
                        <p>Employés actifs</p>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <?php if (empty($employes)): ?>
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h2>Aucun employé</h2>
                        <p>Les employés apparaîtront après la génération de leurs contrats.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Poste</th>
                                <th>Type Contrat</th>
                                <th>Date début</th>
                                <th>Salaire</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employes as $emp): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($emp['prenom'] . ' ' . $emp['nom']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($emp['email']) ?></td>
                                <td><?= htmlspecialchars($emp['contact']) ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= htmlspecialchars($emp['poste_nom'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($emp['type_contrat'] ?? 'N/A') ?></td>
                                <td>
                                    <?= isset($emp['contrat_debut']) ? date('d/m/Y', strtotime($emp['contrat_debut'])) : 'N/A' ?>
                                </td>
                                <td>
                                    <strong><?= isset($emp['salaire']) ? number_format($emp['salaire'], 0, ',', ' ') . ' Ar' : 'N/A' ?></strong>
                                </td>
                                <td class="action-buttons">
                                    <a href="/rh/employe/<?= $emp['id'] ?>" 
                                       class="btn-icon btn-view" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($emp['contrat_id']): ?>
                                    <a href="/rh/contrat/<?= $emp['contrat_id'] ?>" 
                                       class="btn-icon" 
                                       style="background: #dcfce7; color: #166534;"
                                       title="Voir contrat">
                                        <i class="fas fa-file-contract"></i>
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