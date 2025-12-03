<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Liste des Annonces' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .page-header h1 {
            font-size: 1.8rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid #2563eb;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .stat-box.success { border-left-color: #10b981; }
        .stat-box.warning { border-left-color: #f59e0b; }
        .stat-box.danger { border-left-color: #ef4444; }
        
        .stat-box h3 {
            font-size: 2rem;
            color: #1e293b;
            margin: 0 0 5px 0;
            font-weight: 700;
        }
        
        .stat-box p {
            color: #64748b;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
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
            padding: 10px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #2563eb;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
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
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        
        .data-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .badge.active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge.inactive {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge.expired {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-category {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-contrat {
            background: #e0e7ff;
            color: #4338ca;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
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
        
        .btn-candidates {
            background: #d1fae5;
            color: #065f46;
        }
        
        .btn-candidates:hover {
            background: #10b981;
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
        
        .empty-state h3 {
            font-size: 1.5rem;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .btn-filter {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
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
            background: #e2e8f0;
            color: #475569;
        }
        
        .btn-secondary:hover {
            background: #cbd5e1;
        }
        
        .manager-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .manager-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .date-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .date-label {
            font-size: 0.75rem;
            color: #94a3b8;
            text-transform: uppercase;
        }
        
        .date-value {
            font-weight: 600;
            color: #1e293b;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1>
                    <i class="fas fa-clipboard-list"></i>
                    Liste des Annonces d'Emploi
                </h1>
            </div>

            <!-- Statistiques -->
            <div class="stats-row">
                <div class="stat-box">
                    <h3><?= count(array_filter($annonces ?? [], fn($a) => $a['statut'] === 'active')) ?></h3>
                    <p><i class="fas fa-check-circle"></i> Annonces actives</p>
                </div>
                <div class="stat-box warning">
                    <h3><?= count(array_filter($annonces ?? [], fn($a) => strtotime($a['date_limite']) < time())) ?></h3>
                    <p><i class="fas fa-clock"></i> Annonces expirées</p>
                </div>
                <div class="stat-box success">
                    <h3><?= count($annonces ?? []) ?></h3>
                    <p><i class="fas fa-list"></i> Total des annonces</p>
                </div>
            </div>

            <!-- Filtres -->
            <div class="filters-section">
                <form method="GET" action="/rh/annonces">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label><i class="fas fa-search"></i> Recherche</label>
                            <input type="text" name="search" placeholder="Titre de l'annonce..." 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label><i class="fas fa-filter"></i> Statut</label>
                            <select name="statut">
                                <option value="">Tous les statuts</option>
                                <option value="active" <?= ($_GET['statut'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= ($_GET['statut'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label><i class="fas fa-briefcase"></i> Type de contrat</label>
                            <select name="contrat">
                                <option value="">Tous les contrats</option>
                                <option value="1">CDI</option>
                                <option value="2">CDD</option>
                                <option value="3">Stage</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-buttons">
                        <button type="submit" class="btn-filter btn-primary">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        <a href="/rh/annonces" class="btn-filter btn-secondary">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tableau des annonces -->
            <div class="table-container">
                <?php if (empty($annonces)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Aucune annonce disponible</h3>
                        <p>Les managers n'ont pas encore créé d'annonces d'emploi.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-file-alt"></i> Titre</th>
                                <th><i class="fas fa-briefcase"></i> Poste</th>
                                <th><i class="fas fa-layer-group"></i> Catégorie</th>
                                <th><i class="fas fa-building"></i> Département</th>
                                <th><i class="fas fa-file-contract"></i> Type Contrat</th>
                                <th><i class="fas fa-user-tie"></i> Manager</th>
                                <th><i class="fas fa-calendar-alt"></i> Dates</th>
                                <th><i class="fas fa-toggle-on"></i> Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($annonces as $annonce): ?>
                            <tr>
                                <td><strong>#<?= $annonce['id'] ?></strong></td>
                                <td>
                                    <strong style="color: #1e293b;">
                                        <?= htmlspecialchars($annonce['titre']) ?>
                                    </strong>
                                </td>
                                <td><?= htmlspecialchars($annonce['poste_nom']) ?></td>
                                <td>
                                    <span class="badge badge-category">
                                        <?= htmlspecialchars($annonce['categorie_nom']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($annonce['departement_nom']) ?></td>
                                <td>
                                    <span class="badge badge-contrat">
                                        <?= htmlspecialchars($annonce['type_contrat_nom']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="manager-info">
                                        <div class="manager-avatar">
                                            <?= strtoupper(substr($annonce['manager_prenom'], 0, 1)) ?>
                                        </div>
                                        <span><?= htmlspecialchars($annonce['manager_prenom'] . ' ' . $annonce['manager_nom']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <span class="date-label">Publication</span>
                                        <span class="date-value"><?= date('d/m/Y', strtotime($annonce['date_publication'])) ?></span>
                                        <span class="date-label" style="margin-top: 5px;">Limite</span>
                                        <span class="date-value"><?= date('d/m/Y', strtotime($annonce['date_limite'])) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $now = time();
                                    $limite = strtotime($annonce['date_limite']);
                                    $is_expired = $limite < $now;
                                    
                                    if ($is_expired) {
                                        $badge_class = 'expired';
                                        $status_text = 'Expirée';
                                    } elseif ($annonce['statut'] === 'active') {
                                        $badge_class = 'active';
                                        $status_text = 'Active';
                                    } else {
                                        $badge_class = 'inactive';
                                        $status_text = 'Inactive';
                                    }
                                    ?>
                                    <span class="badge <?= $badge_class ?>">
                                        <?= $status_text ?>
                                    </span>
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