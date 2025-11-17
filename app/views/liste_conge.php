<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Congés - RH Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 260px;
            padding: 40px;
            transition: margin-left 0.3s ease;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 16px;
        }

        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #1e40af;
        }

        .breadcrumb-separator {
            color: #cbd5e1;
        }

        .page-title-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
        }

        .stat-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
        }

        .table-filters {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            color: #1e293b;
            background: white;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .search-box {
            padding: 8px 12px 8px 36px;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            color: #1e293b;
            background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
            transition: border-color 0.2s;
            min-width: 200px;
        }

        .search-box:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        th {
            padding: 16px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        td {
            padding: 16px 20px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        tbody tr {
            transition: background-color 0.2s;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .employee-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .employee-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 13px;
            flex-shrink: 0;
        }

        .employee-info {
            display: flex;
            flex-direction: column;
        }

        .employee-name {
            font-weight: 500;
            color: #1e293b;
        }

        .employee-role {
            font-size: 12px;
            color: #64748b;
        }

        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
        }

        .days-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 60px;
            padding: 6px 12px;
            background: #fef3c7;
            color: #92400e;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .days-badge.urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        .motif-text {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-approve {
            background: #10b981;
            color: white;
        }

        .btn-approve:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .btn-reject {
            background: #ef4444;
            color: white;
        }

        .btn-reject:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .empty-state-subtext {
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .page-title {
                font-size: 24px;
            }

            .stats-cards {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .table-filters {
                flex-direction: column;
                width: 100%;
            }

            .search-box,
            .filter-select {
                width: 100%;
            }

            .table-wrapper {
                overflow-x: scroll;
            }

            table {
                min-width: 800px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include('app/views/sidebar/sidebar.php') ?>

    <div class="main-content">
        <? if ($message != null) echo $message  ?>
        <div class="page-header">
            <div class="breadcrumb">
                <a href="<?= constant('BASE_URL') ?>">Accueil</a>
                <span class="breadcrumb-separator">›</span>
                <a href="<?= constant('BASE_URL') ?>conges">Congés</a>
                <span class="breadcrumb-separator">›</span>
                <span>Demandes en attente</span>
            </div>
            
            <div class="page-title-wrapper">
                <h1 class="page-title">
                    <span class="page-title-icon">📋</span>
                    Gestion des Demandes de Congés
                </h1>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-label">En attente</div>
                <div class="stat-value"><?= count($liste) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✓</div>
                <div class="stat-label">Validées ce mois</div>
                <div class="stat-value">24</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✕</div>
                <div class="stat-label">Refusées ce mois</div>
                <div class="stat-value">3</div>
            </div>
        </div>

        <!-- Tableau -->
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Demandes en attente de validation</h2>
                <div class="table-filters">
                    <input type="text" class="search-box" placeholder="Rechercher un employé...">
                    <select class="filter-select">
                        <option>Tous les types</option>
                        <option>Congé normal</option>
                        <option>Congé exceptionnel</option>
                        <option>Congé maladie</option>
                    </select>
                </div>
            </div>

            <form action="<?= constant('BASE_URL') ?>valider_conge" method="post">
                <div class="table-wrapper">
                    <?php if (empty($liste)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <div class="empty-state-text">Aucune demande en attente</div>
                            <div class="empty-state-subtext">Toutes les demandes ont été traitées</div>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Département</th>
                                    <th>Employé</th>
                                    <th>Motif</th>
                                    <th>Période d'absence</th>
                                    <th>Durée d'attente</th>
                                    <th>Date du jour</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liste as $l): ?>
                                <input type="hidden" value="<?= $l['id_absence'] ?>" name="id_absence">
                                <input type="hidden" value="<?= $l['poste'] ?>" name="poste">
                                <input type="hidden" value="<?= $l['id_employe'] ?>" name="id_employe">
                                <tr>
                                    <td>
                                        <span class="employee-role" title="<?= htmlspecialchars($l['departement']) ?>">
                                            <?= htmlspecialchars($l['departement']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="employee-cell">
                                            <div class="employee-avatar">
                                                <?= strtoupper(substr($l['prenom'], 0, 1) . substr($l['nom'], 0, 1)) ?>
                                            </div>
                                            <div class="employee-info">
                                                <span class="employee-name"><?= $l['nom'] . ' ' . $l['prenom'] ?></span>
                                                <span class="employee-role"><?= $l['poste'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="motif-text" title="<?= htmlspecialchars($l['motif']) ?>">
                                            <?= htmlspecialchars($l['motif']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="date-badge">
                                            📅 <?= date('d/m/Y', strtotime($l['date_debut'])) ?> - <?= date('d/m/Y', strtotime($l['date_fin'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="days-badge <?= $l['jours_attente'] > 5 ? 'urgent' : '' ?>">
                                            <?= $l['jours_attente'] ?> jour<?= $l['jours_attente'] > 1 ? 's' : '' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span>
                                            <input type="date" name="date_validation">
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="submit" name="button" value="0" class="btn btn-approve">
                                                ✓ Valider
                                            </button>
                                            <button type="submit" name="button" value="1" class="btn btn-reject">
                                                ✕ Refuser
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Recherche en temps réel
        const searchBox = document.querySelector('.search-box');
        const tableRows = document.querySelectorAll('tbody tr');

        searchBox?.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            
            tableRows.forEach(row => {
                const employeeName = row.querySelector('.employee-name')?.textContent.toLowerCase() || '';
                const employeeRole = row.querySelector('.employee-role')?.textContent.toLowerCase() || '';
                
                if (employeeName.includes(searchTerm) || employeeRole.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Confirmation avant validation/refus
        const actionButtons = document.querySelectorAll('.btn');
        actionButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const isApprove = btn.classList.contains('btn-approve');
                const action = isApprove ? 'valider' : 'refuser';
                const employeeName = btn.closest('tr').querySelector('.employee-name').textContent;
                
                const confirmed = confirm(`Êtes-vous sûr de vouloir ${action} la demande de congé de ${employeeName} ?`);
                
                if (!confirmed) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>