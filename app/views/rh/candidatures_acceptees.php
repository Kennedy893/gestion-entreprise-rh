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
                <h1><i class="fas fa-user-check"></i> Candidats Acceptés</h1>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php 
                        switch($_GET['error']) {
                            case 'candidature_not_found':
                                echo "Candidature introuvable.";
                                break;
                            case 'contrat_exists':
                                echo "Un contrat existe déjà pour ce candidat.";
                                break;
                            default:
                                echo "Une erreur s'est produite.";
                        }
                    ?>
                </div>
            <?php endif; ?>

            <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #22c55e;">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count($candidatures) ?></h3>
                        <p>Candidats acceptés</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #3b82f6;">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count(array_filter($candidatures, fn($c) => !empty($c['contrat_id']))) ?></h3>
                        <p>Contrats générés</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #f59e0b;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count(array_filter($candidatures, fn($c) => empty($c['contrat_id']))) ?></h3>
                        <p>En attente de contrat</p>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <?php if (empty($candidatures)): ?>
                    <div class="empty-state">
                        <i class="fas fa-user-check"></i>
                        <h2>Aucun candidat accepté</h2>
                        <p>Les candidats acceptés apparaîtront ici.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Candidat</th>
                                <th>Poste</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Date décision</th>
                                <th>Type contrat accordé</th>
                                <th>Statut contrat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatures as $cand): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($cand['prenom'] . ' ' . $cand['nom']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= htmlspecialchars($cand['poste_nom'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($cand['email']) ?></td>
                                <td><?= htmlspecialchars($cand['telephone']) ?></td>
                                <td>
                                    <?= isset($cand['date_decision']) ? date('d/m/Y', strtotime($cand['date_decision'])) : 'N/A' ?>
                                </td>
                                <td>
                                    <?php if (!empty($cand['type_contrat_accorde'])): ?>
                                        <span class="badge badge-primary">
                                            <?= htmlspecialchars($cand['type_contrat_accorde']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Non défini</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($cand['contrat_id'])): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Contrat généré
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-hourglass-half"></i> En attente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <a href="/rh/candidature/<?= $cand['id'] ?>" 
                                       class="btn-icon btn-view" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if (empty($cand['contrat_id'])): ?>
                                        <a href="/rh/contrat/create/<?= $cand['id'] ?>" 
                                           class="btn-icon" 
                                           style="background: #dcfce7; color: #166534;"
                                           title="Générer le contrat">
                                            <i class="fas fa-file-signature"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="/rh/contrat/<?= $cand['contrat_id'] ?>" 
                                           class="btn-icon" 
                                           style="background: #dbeafe; color: #1e40af;"
                                           title="Voir le contrat">
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