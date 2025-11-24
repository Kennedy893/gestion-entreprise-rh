<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solde de Congés - RH Manager</title>
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

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .filter-icon {
            font-size: 20px;
        }

        .filter-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .filter-form {
            display: flex;
            gap: 12px;
            align-items: end;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            color: #1e293b;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-family: inherit;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select:hover {
            border-color: #cbd5e1;
        }

        .btn-filter {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
        }

        .btn-filter:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .btn-filter:active {
            transform: translateY(0);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            background: white;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .summary-card.total::before {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
        }

        .summary-card.used::before {
            background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        }

        .summary-card.remaining::before {
            background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
        }

        .summary-icon {
            font-size: 28px;
            margin-bottom: 12px;
        }

        .summary-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }

        .summary-subtitle {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 2px solid #f1f5f9;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
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
            border-bottom: 2px solid #e2e8f0;
        }

        th:first-child {
            border-radius: 12px 0 0 0;
        }

        th:last-child {
            border-radius: 0 12px 0 0;
        }

        td {
            padding: 18px 20px;
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

        tbody tr:last-child td {
            border-bottom: none;
        }

        .year-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .days-cell {
            font-weight: 600;
            font-size: 15px;
        }

        .days-acquired {
            color: #10b981;
        }

        .days-used {
            color: #f59e0b;
        }

        .days-remaining {
            color: #3b82f6;
        }

        .days-cumulative {
            color: #8b5cf6;
            font-size: 16px;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 6px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
            transition: width 0.3s ease;
            border-radius: 3px;
        }

        .btn-details {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #eff6ff;
            color: #1e40af;
            border: 1.5px solid #dbeafe;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-details:hover {
            background: #dbeafe;
            border-color: #93c5fd;
            transform: translateY(-1px);
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

            .filter-form {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }

            .btn-filter {
                width: 100%;
                justify-content: center;
            }

            .summary-cards {
                grid-template-columns: 1fr;
            }

            .table-wrapper {
                overflow-x: scroll;
            }

            table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include('app/views/sidebar/sidebar.php') ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="breadcrumb">
                <a href="<?= constant('BASE_URL') ?>">Accueil</a>
                <span class="breadcrumb-separator">›</span>
                <a href="<?= constant('BASE_URL') ?>conges">Congés</a>
                <span class="breadcrumb-separator">›</span>
                <span>Solde de congés</span>
            </div>
            
            <h1 class="page-title">
                <span class="page-title-icon">💼</span>
                Solde de Congés
            </h1>
        </div>

        <!-- Filtre -->
        <div class="filter-card">
            <div class="filter-header">
                <span class="filter-icon">🔍</span>
                <h3 class="filter-title">Filtrer par employé</h3>
            </div>
            
            <form method="get" action="<?= constant('BASE_URL') ?>vers_solde_conge" class="filter-form">
                <div class="form-group">
                    <label class="form-label" for="id_employe">Sélectionner un employé</label>
                    <select name="id_employe" id="id_employe" class="form-select">
                        <option value="">👥 Tous les employés</option>
                        <?php foreach ($employes as $emp): ?>
                            <option value="<?= $emp['id'] ?>" 
                                <?= isset($_GET['id_employe']) && $_GET['id_employe'] == $emp['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($emp['nom'] . " " . $emp['prenom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-filter">
                    <span>🔍</span>
                    Filtrer
                </button>
            </form>
        </div>

        <?php if (!empty($solde)): ?>
            <?php 
                // Calcul des totaux
                $totalAcquis = array_sum(array_column($solde, 'jours_acquis'));
                $totalPris = array_sum(array_column($solde, 'jours_conso'));
                $totalRestant = array_sum(array_column($solde, 'jours_restants'));
            ?>

            <!-- Cartes récapitulatives -->
            <div class="summary-cards">
                <div class="summary-card total">
                    <div class="summary-icon">📊</div>
                    <div class="summary-label">Jours acquis</div>
                    <div class="summary-value"><?= $totalAcquis ?></div>
                    <div class="summary-subtitle">Total sur toutes les années</div>
                </div>

                <div class="summary-card used">
                    <div class="summary-icon">✈️</div>
                    <div class="summary-label">Jours pris</div>
                    <div class="summary-value"><?= $totalPris ?></div>
                    <div class="summary-subtitle"><?= $totalAcquis > 0 ? round(($totalPris / $totalAcquis) * 100, 1) : 0 ?>% utilisés</div>
                </div>

                <div class="summary-card remaining">
                    <div class="summary-icon">🎯</div>
                    <div class="summary-label">Solde disponible</div>
                    <div class="summary-value"><?= $totalRestant ?></div>
                    <div class="summary-subtitle">Jours restants</div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tableau détaillé -->
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">
                    📅 Historique détaillé par année
                </h2>
            </div>

            <div class="table-wrapper">
                <?php if (empty($solde)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <div class="empty-state-text">Aucun solde trouvé</div>
                        <div class="empty-state-subtext">
                            <?= isset($_GET['id_employe']) && $_GET['id_employe'] ? 'Cet employé n\'a pas encore de données de congés' : 'Sélectionnez un employé pour voir ses soldes' ?>
                        </div>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Année</th>
                                <th>Jours acquis</th>
                                <th>Jours pris</th>
                                <th>Taux d'utilisation</th>
                                <th>Solde restant</th>
                                <th>Solde cumulé</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $soldeCumule = 0;
                            ?>

                            <?php foreach ($solde as $s) : ?>

                                <?php
                                    // -------------------------------------------------------------
                                    //  Reset du solde cumulé tous les 3 ans (lié au début de contrat)
                                    // -------------------------------------------------------------
                                    if (isset($annee_debut)) {
                                        $cycleIndex = floor(($s['annee'] - $annee_debut) / 3);   // cycle de 3 ans
                                        $debutCycle = $annee_debut + ($cycleIndex * 3);

                                        if ((int)$s['annee'] === (int)$debutCycle) {
                                            $soldeCumule = 0; // RESET !
                                        }
                                    }

                                    // Ajout du solde restant de l’année
                                    $soldeCumule += $s['jours_restants'];

                                    // Calcul taux
                                    $tauxUtilisation = $s['jours_acquis'] > 0 
                                                        ? ($s['jours_conso'] / $s['jours_acquis']) * 100 
                                                        : 0;
                                ?>

                                <tr>
                                    <td>
                                        <span class="year-badge">
                                            📅 <?= htmlspecialchars($s['annee']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="days-cell days-acquired">
                                            <?= htmlspecialchars($s['jours_acquis']) ?> jours
                                        </div>
                                    </td>
                                    <td>
                                        <div class="days-cell days-used">
                                            <?= htmlspecialchars($s['jours_conso']) ?> jours
                                        </div>
                                    </td>
                                    <td>
                                        <div style="min-width: 120px;">
                                            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">
                                                <?= round($tauxUtilisation, 1) ?>%
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: <?= min($tauxUtilisation, 100) ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="days-cell days-remaining">
                                            <?= htmlspecialchars($s['jours_restants']) ?> jours
                                        </div>
                                    </td>
                                    <td>
                                        <div class="days-cell days-cumulative">
                                            <?= $soldeCumule ?> jours
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?= constant('BASE_URL') . 'details_solde?employe=' . htmlspecialchars($s['id_employe']) . '&annee=' .  htmlspecialchars($s['annee']) ?>" 
                                           class="btn-details">
                                            <span>👁️</span>
                                            Voir détails
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

    <script>
        // Animation de la barre de progression au chargement
        document.addEventListener('DOMContentLoaded', () => {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>
</html>