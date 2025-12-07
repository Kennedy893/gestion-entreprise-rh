<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Manager Dashboard' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_manager.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-briefcase"></i> Gestion des Annonces d'Emploi</h1>
                <a href="/manager/create-annonce" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer une annonce
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Annonce créée avec succès !
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Annonce supprimée avec succès !
                </div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #4CAF50;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count(array_filter($annonces, fn($a) => $a['statut'] === 'active')) ?></h3>
                        <p>Annonces actives</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #2196F3;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count($annonces) ?></h3>
                        <p>Total annonces</p>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Poste</th>
                            <th>Catégorie</th>
                            <th>Département</th>
                            <th>Type Contrat</th>
                            <th>Date Publication</th>
                            <th>Date Limite</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($annonces as $annonce): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($annonce['titre']) ?></strong></td>
                            <td><?= htmlspecialchars($annonce['poste_nom']) ?></td>
                            <td>
                                <span class="badge badge-info">
                                    <?= htmlspecialchars($annonce['categorie_nom']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($annonce['departement_nom']) ?></td>
                            <td>
                                <span class="badge badge-secondary">
                                    <?= htmlspecialchars($annonce['type_contrat_nom']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($annonce['date_publication'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($annonce['date_limite'])) ?></td>
                            <td>
                                <?php 
                                $statusClass = $annonce['statut'] === 'active' ? 'badge-success' : 'badge-danger';
                                ?>
                                <span class="badge <?= $statusClass ?>">
                                    <?= ucfirst($annonce['statut']) ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="/manager/annonce/<?= $annonce['id'] ?>" class="btn-icon btn-view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button onclick="deleteAnnonce(<?= $annonce['id'] ?>)" class="btn-icon btn-delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function deleteAnnonce(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')) {
                window.location.href = '/manager/annonce/delete/' + id;
            }
        }
    </script>
</body>
</html>