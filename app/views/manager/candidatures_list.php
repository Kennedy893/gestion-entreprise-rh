<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_manager.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-users"></i> Candidatures reçues</h1>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php if ($_GET['success'] === 'entretien_cree'): ?>
                    Date d'entretien créée avec succès !
                <?php elseif ($_GET['success'] === 'publie'): ?>
                    Entretien publié et visible par les candidats !
                <?php elseif ($_GET['success'] === 'note_envoyee'): ?>
                    Note d'entretien envoyée avec succès !
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (empty($candidatures)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h2>Aucune candidature</h2>
                <p>Les candidatures envoyées par le service RH apparaîtront ici.</p>
            </div>
            <?php else: ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Candidat</th>
                            <th>Poste</th>
                            <th>Expérience</th>
                            <th>Date candidature</th>
                            <th>Entretien</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidatures as $c): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($c['nom'] . ' ' . $c['prenom']) ?></strong><br>
                                <small style="color: #64748b;"><?= htmlspecialchars($c['email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($c['poste_nom']) ?></td>
                            <td><?= $c['experience_annees'] ?? 0 ?> an(s)</td>
                            <td><?= date('d/m/Y', strtotime($c['date_candidature'])) ?></td>
                            <td>
                                <?php if (!empty($c['entretien_date'])): ?>
                                    <div class="entretien-info">
                                        <i class="fas fa-calendar-check"></i>
                                        <?= date('d/m/Y à H:i', strtotime($c['entretien_date'])) ?>
                                        <?php 
                                        $statutPub = $c['statut_publication'] ?? $c['entretien_statut'] ?? 'brouillon';
                                        if ($statutPub === 'brouillon'): 
                                        ?>
                                        <span class="badge badge-warning">Brouillon</span>
                                        <?php else: ?>
                                        <span class="badge badge-success">Publié</span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Non planifié</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <?php if (empty($c['entretien_date'])): ?>
                                    <a href="/manager/candidature/<?= $c['id'] ?>/entretien/creer" 
                                       class="btn btn-sm btn-primary" title="Créer entretien">
                                        <i class="fas fa-calendar-plus"></i> Créer
                                    </a>
                                <?php else: ?>
                                    <?php 
                                    $statutEntretien = $c['entretien_statut'] ?? 'planifie';
                                    $statutPub = $c['statut_publication'] ?? 'brouillon';
                                    ?>
                                    
                                    <?php if ($statutEntretien === 'termine'): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Entretien terminé
                                        </span>
                                    <?php else: ?>
                                        <a href="/manager/candidature/<?= $c['id'] ?>/entretien/creer" 
                                           class="btn btn-sm btn-secondary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if ($statutPub === 'brouillon'): ?>
                                        <button onclick="publierEntretien(<?= $c['entretien_id'] ?>)" 
                                                class="btn btn-sm btn-info" title="Publier">
                                            <i class="fas fa-eye"></i> Publier
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($statutEntretien === 'planifie' || $statutPub === 'publie'): ?>
                                        <a href="/manager/entretien/<?= $c['entretien_id'] ?>" 
                                           class="btn btn-sm btn-success" title="Faire entretien">
                                            <i class="fas fa-user-check"></i> Entretien
                                        </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .empty-state i {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .empty-state h2 {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #64748b;
        }

        .entretien-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 0.9rem;
        }

        .entretien-info i {
            color: #3b82f6;
            margin-right: 5px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #16a34a;
            color: #166534;
        }
    </style>

    <script>
        async function publierEntretien(entretienId) {
            if (!confirm('Publier cet entretien ? Il sera visible par les candidats.')) return;
            
            try {
                const response = await fetch(`/manager/entretien/${entretienId}/publier`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Entretien publié !');
                    window.location.reload();
                } else {
                    alert('Erreur lors de la publication');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur: ' + error.message);
            }
        }
    </script>
</body>
</html>