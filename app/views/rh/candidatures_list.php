<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Liste des candidatures' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .filters-panel {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .filters-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #1e293b;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .filter-group label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .btn-filter {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }
        
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-mini {
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            border-left: 4px solid;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        
        .stat-mini.total { border-left-color: #3b82f6; }
        .stat-mini.pending { border-left-color: #f59e0b; }
        .stat-mini.interview { border-left-color: #8b5cf6; }
        .stat-mini.accepted { border-left-color: #10b981; }
        .stat-mini.rejected { border-left-color: #ef4444; }
        
        .stat-mini h4 {
            font-size: 1.8rem;
            margin: 0 0 5px 0;
            color: #1e293b;
        }
        
        .stat-mini p {
            margin: 0;
            color: #64748b;
            font-size: 0.85rem;
        }
        
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .data-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .data-table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .data-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-interview { background: #e0e7ff; color: #3730a3; }
        .badge-accepted { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .btn-view {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .btn-view:hover {
            background: #2563eb;
            color: white;
            transform: scale(1.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-filter"></i> Tri et Classification des Candidatures</h1>
            </div>

            <!-- Statistiques rapides -->
            <div class="stats-row">
                <div class="stat-mini total">
                    <h4><?= count($candidatures ?? []) ?></h4>
                    <p><i class="fas fa-inbox"></i> Total</p>
                </div>
                <div class="stat-mini pending">
                    <h4><?= count(array_filter($candidatures ?? [], fn($c) => $c['statut'] === 'en_attente')) ?></h4>
                    <p><i class="fas fa-clock"></i> En attente</p>
                </div>
                <div class="stat-mini interview">
                    <h4><?= count(array_filter($candidatures ?? [], fn($c) => $c['statut'] === 'entretien_planifie')) ?></h4>
                    <p><i class="fas fa-calendar"></i> Entretien</p>
                </div>
                <div class="stat-mini accepted">
                    <h4><?= count(array_filter($candidatures ?? [], fn($c) => ($c['decision_finale'] ?? '') === 'accepte')) ?></h4>
                    <p><i class="fas fa-check"></i> Acceptés</p>
                </div>
                <div class="stat-mini rejected">
                    <h4><?= count(array_filter($candidatures ?? [], fn($c) => ($c['decision_finale'] ?? '') === 'rejete')) ?></h4>
                    <p><i class="fas fa-times"></i> Rejetés</p>
                </div>
            </div>

            <!-- Panneau de filtres -->
            <div class="filters-panel">
                <div class="filters-title">
                    <i class="fas fa-sliders-h"></i>
                    Filtres de recherche avancés
                </div>
                
                <form method="GET" action="/rh/candidatures">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label><i class="fas fa-search"></i> Rechercher par nom</label>
                            <input type="text" 
                                   name="search" 
                                   placeholder="Nom ou prénom..." 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label><i class="fas fa-briefcase"></i> Poste</label>
                            <select name="poste">
                                <option value="">Tous les postes</option>
                                <?php
                                // Extraire les postes uniques des candidatures
                                $postes = array_unique(array_filter(array_column($candidatures ?? [], 'poste_nom')));
                                foreach ($postes as $poste):
                                ?>
                                <option value="<?= htmlspecialchars($poste) ?>" 
                                        <?= ($_GET['poste'] ?? '') === $poste ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($poste) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label><i class="fas fa-toggle-on"></i> Statut</label>
                            <select name="statut">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente" <?= ($_GET['statut'] ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="envoye_manager" <?= ($_GET['statut'] ?? '') === 'envoye_manager' ? 'selected' : '' ?>>Envoyé au manager</option>
                                <option value="entretien_planifie" <?= ($_GET['statut'] ?? '') === 'entretien_planifie' ? 'selected' : '' ?>>Entretien planifié</option>
                                <option value="entretien_termine" <?= ($_GET['statut'] ?? '') === 'entretien_termine' ? 'selected' : '' ?>>Entretien terminé</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label><i class="fas fa-sort-numeric-down"></i> Expérience minimale</label>
                            <select name="experience">
                                <option value="">Toutes</option>
                                <option value="0-2" <?= ($_GET['experience'] ?? '') === '0-2' ? 'selected' : '' ?>>0-2 ans</option>
                                <option value="3-5" <?= ($_GET['experience'] ?? '') === '3-5' ? 'selected' : '' ?>>3-5 ans</option>
                                <option value="6-10" <?= ($_GET['experience'] ?? '') === '6-10' ? 'selected' : '' ?>>6-10 ans</option>
                                <option value="10+" <?= ($_GET['experience'] ?? '') === '10+' ? 'selected' : '' ?>>10+ ans</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <a href="/rh/candidatures" class="btn-filter btn-secondary">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
                        <button type="submit" class="btn-filter btn-primary">
                            <i class="fas fa-search"></i> Appliquer les filtres
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table des candidatures -->
            <div class="table-container">
                <?php if (empty($candidatures)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Aucune candidature trouvée</h3>
                        <p>Essayez de modifier vos filtres de recherche</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-user"></i> Candidat</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-briefcase"></i> Poste</th>
                                <th><i class="fas fa-chart-line"></i> Expérience</th>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-toggle-on"></i> Statut</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatures as $c): ?>
                            <tr>
                                <td><strong>#<?= $c['id'] ?></strong></td>
                                <td>
                                    <strong style="color: #1e293b;">
                                        <?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?>
                                    </strong>
                                </td>
                                <td><?= htmlspecialchars($c['email']) ?></td>
                                <td>
                                    <span style="font-weight: 600; color: #475569;">
                                        <?= htmlspecialchars($c['poste_nom'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 600;">
                                        <?= $c['experience_annees'] ?? 0 ?> an(s)
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($c['date_candidature'])) ?></td>
                                <td>
                                    <?php
                                    $statut = $c['statut'] ?? 'en_attente';
                                    $badge_class = match($statut) {
                                        'en_attente' => 'badge-pending',
                                        'envoye_manager' => 'badge-interview',
                                        'entretien_planifie' => 'badge-interview',
                                        'entretien_termine' => 'badge-interview',
                                        default => 'badge-pending'
                                    };
                                    $statut_text = match($statut) {
                                        'en_attente' => 'En attente',
                                        'envoye_manager' => 'Envoyé',
                                        'entretien_planifie' => 'Entretien',
                                        'entretien_termine' => 'Terminé',
                                        default => ucfirst($statut)
                                    };
                                    ?>
                                    <span class="badge <?= $badge_class ?>">
                                        <?= $statut_text ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/rh/candidature/<?= $c['id'] ?>" 
                                       class="btn-icon btn-view" 
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
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