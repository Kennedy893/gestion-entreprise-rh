<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'RH Dashboard' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-users-cog"></i> Tableau de bord Ressources Humaines</h1>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #2563eb;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count(array_filter($annonces, fn($a) => $a['statut'] === 'active')) ?></h3>
                        <p>Annonces actives</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #f59e0b;">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>0</h3>
                        <p>Candidatures en attente</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #22c55e;">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>0</h3>
                        <p>Candidats sélectionnés</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #8b5cf6;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>0</h3>
                        <p>Entretiens planifiés</p>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div style="padding: 20px; border-bottom: 2px solid #f1f5f9;">
                    <h2 style="font-size: 1.3rem; color: #1e293b;">
                        <i class="fas fa-briefcase"></i> Annonces publiées par les Managers
                    </h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Poste</th>
                            <th>Catégorie</th>
                            <th>Département</th>
                            <th>Type Contrat</th>
                            <th>Manager</th>
                            <th>Date Publication</th>
                            <th>Date Limite</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($annonces)): ?>
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
                                Aucune annonce disponible pour le moment
                            </td>
                        </tr>
                        <?php else: ?>
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
                                <td><?= htmlspecialchars($annonce['manager_prenom'] . ' ' . $annonce['manager_nom']) ?></td>
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
                                    <a href="/rh/annonce/<?= $annonce['id'] ?>" class="btn-icon btn-view" title="Voir l'annonce">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/rh/candidatures/<?= $annonce['id'] ?>" class="btn-icon" 
                                       style="background: #dcfce7; color: #166534;" title="Voir les candidatures">
                                        <i class="fas fa-users"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 30px; background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <h2 style="font-size: 1.3rem; color: #1e293b; margin-bottom: 20px;">
                    <i class="fas fa-info-circle"></i> Actions RH Disponibles
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                    <a href="/rh/annonces" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: inherit; border: 2px solid #e2e8f0; transition: all 0.3s;">
                        <i class="fas fa-clipboard-list" style="font-size: 2rem; color: #2563eb;"></i>
                        <div>
                            <strong style="display: block; color: #1e293b;">Consulter les annonces</strong>
                            <small style="color: #64748b;">Voir toutes les offres d'emploi</small>
                        </div>
                    </a>
                    
                    <a href="/rh/candidatures" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: inherit; border: 2px solid #e2e8f0; transition: all 0.3s;">
                        <i class="fas fa-inbox" style="font-size: 2rem; color: #f59e0b;"></i>
                        <div>
                            <strong style="display: block; color: #1e293b;">Gérer les candidatures</strong>
                            <small style="color: #64748b;">Tri et validation des dossiers</small>
                        </div>
                    </a>
                    
                    <a href="/rh/entretiens" style="display: flex; align-items: center; gap: 15px; padding: 20px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: inherit; border: 2px solid #e2e8f0; transition: all 0.3s;">
                        <i class="fas fa-calendar-alt" style="font-size: 2rem; color: #8b5cf6;"></i>
                        <div>
                            <strong style="display: block; color: #1e293b;">Planifier les entretiens</strong>
                            <small style="color: #64748b;">Organiser les rencontres candidats</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>