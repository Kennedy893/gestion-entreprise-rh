<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
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

            <!-- Statistiques principales -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #2563eb;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= count(array_filter($annonces ?? [], fn($a) => $a['statut'] === 'active')) ?></h3>
                        <p>Annonces actives</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #22c55e;">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats_postes['total_places_libres'] ?? 0 ?></h3>
                        <p>Postes libres</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: #f59e0b;">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $candidats_attente ?? 0 ?></h3>
                        <p>Candidats en attente</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #8b5cf6;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $entretiens_count ?? 0 ?></h3>
                        <p>Entretiens planifiés</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #10b981;">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $candidats_acceptes ?? 0 ?></h3>
                        <p>Candidats acceptés</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: #ef4444;">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $candidats_rejetes ?? 0 ?></h3>
                        <p>Candidats rejetés</p>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="quick-actions" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 30px;">
                <a href="/rh/annonces" class="action-card" style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-decoration: none; color: inherit; display: flex; flex-direction: column; gap: 15px;">
                    <div style="width: 70px; height: 70px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; color: #1e293b; margin: 0;">Consulter les Annonces</h3>
                    <p style="color: #64748b; margin: 0; line-height: 1.6;">Voir toutes les offres d'emploi publiées</p>
                </a>
                
                <a href="/rh/candidatures" class="action-card" style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-decoration: none; color: inherit; display: flex; flex-direction: column; gap: 15px;">
                    <div style="width: 70px; height: 70px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; color: #1e293b; margin: 0;">Trier les Candidatures</h3>
                    <p style="color: #64748b; margin: 0; line-height: 1.6;">Filtrer et classer les candidatures</p>
                </a>
                
                <a href="/rh/candidats-en-attente" class="action-card" style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-decoration: none; color: inherit; display: flex; flex-direction: column; gap: 15px;">
                    <div style="width: 70px; height: 70px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white;">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; color: #1e293b; margin: 0;">Candidats en Attente</h3>
                    <p style="color: #64748b; margin: 0; line-height: 1.6;">Suivi des évaluations en cours</p>
                </a>
                
                <a href="/rh/resultats-entretiens" class="action-card" style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-decoration: none; color: inherit; display: flex; flex-direction: column; gap: 15px;">
                    <div style="width: 70px; height: 70px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; color: #1e293b; margin: 0;">Gérer les Entretiens</h3>
                    <p style="color: #64748b; margin: 0; line-height: 1.6;">Évaluer les entretiens</p>
                </a>

                <a href="/rh/candidatures-acceptees" class="action-card" style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-decoration: none; color: inherit; display: flex; flex-direction: column; gap: 15px;">
                    <div style="width: 70px; height: 70px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; color: #1e293b; margin: 0;">Candidats Acceptés</h3>
                    <p style="color: #64748b; margin: 0; line-height: 1.6;">Liste des candidats retenus</p>
                </a>

                <a href="/rh/candidatures-rejetees" class="action-card" style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-decoration: none; color: inherit; display: flex; flex-direction: column; gap: 15px;">
                    <div style="width: 70px; height: 70px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <h3 style="font-size: 1.3rem; color: #1e293b; margin: 0;">Candidats Rejetés</h3>
                    <p style="color: #64748b; margin: 0; line-height: 1.6;">Archives des candidatures</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>