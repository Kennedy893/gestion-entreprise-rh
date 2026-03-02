<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../../partials/sidebar_manager.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-briefcase"></i> Gestion des Postes Libres</h1>
                <a href="/manager/postes-libres/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer des postes
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php if ($_GET['success'] === 'created'): ?>
                        <?= ($_GET['count'] ?? 1) ?> poste(s) créé(s) avec succès !
                    <?php elseif ($_GET['success'] === 'updated'): ?>
                        Poste mis à jour avec succès !
                    <?php elseif ($_GET['success'] === 'deleted'): ?>
                        Poste supprimé avec succès !
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php if ($_GET['error'] === 'cannot_delete'): ?>
                        Impossible de supprimer ce poste (annonces ou contrats liés).
                    <?php else: ?>
                        Une erreur s'est produite.
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #2563eb;">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_postes'] ?? 0 ?></h3>
                        <p>Postes au total</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #22c55e;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_places_libres'] ?? 0 ?></h3>
                        <p>Places libres</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #f59e0b;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_capacite'] ?? 0 ?></h3>
                        <p>Capacité totale</p>
                    </div>
                </div>
            </div>

            <!-- Table des postes -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Poste</th>
                            <th>Catégorie</th>
                            <th>Département</th>
                            <th>Capacité</th>
                            <th>Annonces actives</th>
                            <th>Employés actifs</th>
                            <th>Places libres</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($postes as $poste): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($poste['label']) ?></strong></td>
                            <td>
                                <span class="badge badge-info">
                                    <?= htmlspecialchars($poste['categorie_nom']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($poste['departement_nom']) ?></td>
                            <td>
                                <strong style="font-size: 1.1rem;"><?= $poste['valeur'] ?></strong>
                            </td>
                            <td><?= $poste['annonces_actives'] ?></td>
                            <td><?= $poste['employes_actifs'] ?></td>
                            <td>
                                <strong style="font-size: 1.2rem; color: <?= $poste['postes_disponibles'] > 0 ? '#22c55e' : '#ef4444' ?>;">
                                    <?= $poste['postes_disponibles'] ?>
                                </strong>
                            </td>
                            <td>
                                <?php if ($poste['statut_disponibilite'] === 'disponible'): ?>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Disponible
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times"></i> Complet
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <a href="/manager/postes-libres/edit/<?= $poste['id'] ?>" 
                                   class="btn-icon btn-view" 
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="augmenterCapacite(<?= $poste['id'] ?>)" 
                                        class="btn-icon" 
                                        style="background: #dcfce7; color: #166534;"
                                        title="Ajouter des places">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button onclick="deletePoste(<?= $poste['id'] ?>)" 
                                        class="btn-icon btn-delete" 
                                        title="Supprimer">
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
        async function augmenterCapacite(id) {
            const nombre = prompt('Combien de places voulez-vous ajouter ?', '1');
            if (!nombre || isNaN(nombre) || parseInt(nombre) < 1) return;
            
            try {
                const response = await fetch(`/manager/postes-libres/${id}/augmenter`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nombre: parseInt(nombre) })
                });
                
                const result = await response.json();
                if (result.success) {
                    location.reload();
                } else {
                    alert('Erreur lors de l\'augmentation de capacité');
                }
            } catch (error) {
                alert('Erreur: ' + error.message);
            }
        }
        
        function deletePoste(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce poste ? Cette action est irréversible si le poste n\'a pas d\'annonces ou de contrats liés.')) {
                window.location.href = '/manager/postes-libres/delete/' + id;
            }
        }
    </script>
</body>
</html>