<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails des Congés - RH Manager</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
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

        .page-subtitle {
            color: #64748b;
            font-size: 14px;
            margin-top: 8px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .card-icon.normal {
            background: #dbeafe;
            color: #1e40af;
        }

        .card-icon.exceptional {
            background: #fef3c7;
            color: #92400e;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-box {
            text-align: center;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        .stat-unit {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .chart-container {
            position: relative;
            height: 200px;
            margin-top: 20px;
        }

        .progress-section {
            margin-top: 24px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .progress-label {
            font-size: 14px;
            font-weight: 500;
            color: #334155;
        }

        .progress-percentage {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .progress-bar-wrapper {
            width: 100%;
            height: 12px;
            background: #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 6px;
            transition: width 0.8s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-bar-fill.normal {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        }

        .progress-bar-fill.exceptional {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }

        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .summary-table {
            width: 100%;
            margin-top: 20px;
        }

        .summary-table tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-table tr:last-child {
            border-bottom: none;
        }

        .summary-table td {
            padding: 14px 0;
            font-size: 14px;
        }

        .summary-table td:first-child {
            color: #64748b;
            font-weight: 500;
        }

        .summary-table td:last-child {
            text-align: right;
            font-weight: 600;
            color: #1e293b;
        }

        .full-width-card {
            grid-column: 1 / -1;
        }

        .comparison-container {
            display: flex;
            gap: 24px;
            align-items: center;
            margin-top: 20px;
        }

        .comparison-chart {
            flex: 1;
            height: 250px;
        }

        .comparison-legend {
            flex: 0 0 200px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .legend-color.normal {
            background: #3b82f6;
        }

        .legend-color.exceptional {
            background: #f59e0b;
        }

        .legend-text {
            flex: 1;
        }

        .legend-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .legend-value {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .alert-box {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: start;
            gap: 12px;
        }

        .alert-box.info {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
        }

        .alert-box.warning {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
        }

        .alert-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .alert-text {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .comparison-container {
                flex-direction: column;
            }

            .comparison-legend {
                flex: 1;
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .page-title {
                font-size: 24px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .comparison-legend {
                grid-template-columns: 1fr;
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
                <a href="<?= constant('BASE_URL') ?>vers_solde_conge">Solde</a>
                <span class="breadcrumb-separator">›</span>
                <span>Détails</span>
            </div>
            
            <h1 class="page-title">
                <span class="page-title-icon">📊</span>
                Détails des Congés - <?= isset($_GET['annee']) ? htmlspecialchars($_GET['annee']) : date('Y') ?>
            </h1>
            <p class="page-subtitle">Vue détaillée de votre consommation de congés par type pour l'année <?= isset($_GET['annee']) ? htmlspecialchars($_GET['annee']) : date('Y') ?></p>
        </div>

        <?php 
            $totalDroit = 30 + 10; // Normal + Exceptionnel
            $totalPris = $jrsPris_normal + $jrsPris_exc;
            $totalRestant = $totalDroit - $totalPris;
            $tauxGlobal = ($totalPris / $totalDroit) * 100;
        ?>

        <!-- Alerte si peu de jours restants -->
        <?php if ($totalRestant < 10): ?>
        <div class="alert-box warning">
            <span class="alert-icon">⚠️</span>
            <div class="alert-content">
                <div class="alert-title">Attention - Solde faible</div>
                <div class="alert-text">
                    Il vous reste moins de 10 jours de congés disponibles. Planifiez vos prochaines absences en conséquence.
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert-box info">
            <span class="alert-icon">ℹ️</span>
            <div class="alert-content">
                <div class="alert-title">Informations</div>
                <div class="alert-text">
                    Vous avez utilisé <?= round($tauxGlobal, 1) ?>% de vos droits à congés. <?= $totalRestant ?> jours restants disponibles.
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Cartes par type de congé -->
        <div class="content-grid">
            <!-- Congé Normal -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-icon normal">🏖️</div>
                        Congé Normal
                    </div>
                </div>

                <div class="stats-row">
                    <div class="stat-box">
                        <div class="stat-label">Droit annuel</div>
                        <div class="stat-value">30<span class="stat-unit">j</span></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Pris</div>
                        <div class="stat-value"><?= $jrsPris_normal ?><span class="stat-unit">j</span></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Restant</div>
                        <div class="stat-value"><?= 30 - $jrsPris_normal ?><span class="stat-unit">j</span></div>
                    </div>
                </div>

                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Taux d'utilisation</span>
                        <span class="progress-percentage"><?= round(($jrsPris_normal / 30) * 100, 1) ?>%</span>
                    </div>
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar-fill normal" style="width: <?= ($jrsPris_normal / 30) * 100 ?>%"></div>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="normalChart"></canvas>
                </div>
            </div>

            <!-- Congé Exceptionnel -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-icon exceptional">⭐</div>
                        Congé Exceptionnel
                    </div>
                </div>

                <div class="stats-row">
                    <div class="stat-box">
                        <div class="stat-label">Droit annuel</div>
                        <div class="stat-value">10<span class="stat-unit">j</span></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Pris</div>
                        <div class="stat-value"><?= $jrsPris_exc ?><span class="stat-unit">j</span></div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Restant</div>
                        <div class="stat-value"><?= 10 - $jrsPris_exc ?><span class="stat-unit">j</span></div>
                    </div>
                </div>

                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Taux d'utilisation</span>
                        <span class="progress-percentage"><?= round(($jrsPris_exc / 10) * 100, 1) ?>%</span>
                    </div>
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar-fill exceptional" style="width: <?= ($jrsPris_exc / 10) * 100 ?>%"></div>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="exceptionalChart"></canvas>
                </div>
            </div>

            <!-- Vue Comparative -->
            <div class="card full-width-card">
                <div class="card-header">
                    <div class="card-title">
                        📈 Vue Comparative
                    </div>
                </div>

                <div class="comparison-container">
                    <div class="comparison-chart">
                        <canvas id="comparisonChart"></canvas>
                    </div>
                    
                    <div class="comparison-legend">
                        <div class="legend-item">
                            <div class="legend-color normal"></div>
                            <div class="legend-text">
                                <div class="legend-label">Congé Normal</div>
                                <div class="legend-value"><?= $jrsPris_normal ?> / 30 jours</div>
                            </div>
                        </div>
                        
                        <div class="legend-item">
                            <div class="legend-color exceptional"></div>
                            <div class="legend-text">
                                <div class="legend-label">Congé Exceptionnel</div>
                                <div class="legend-value"><?= $jrsPris_exc ?> / 10 jours</div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="summary-table">
                    <tr>
                        <td>Total droits annuels</td>
                        <td><?= $totalDroit ?> jours</td>
                    </tr>
                    <tr>
                        <td>Total jours pris</td>
                        <td><?= $totalPris ?> jours</td>
                    </tr>
                    <tr>
                        <td>Total jours restants</td>
                        <td><?= $totalRestant ?> jours</td>
                    </tr>
                    <tr>
                        <td>Taux d'utilisation global</td>
                        <td><?= round($tauxGlobal, 1) ?>%</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Configuration commune des graphiques
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        };

        // Graphique Congé Normal (Donut)
        const normalCtx = document.getElementById('normalChart').getContext('2d');
        new Chart(normalCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pris', 'Restant'],
                datasets: [{
                    data: [<?= $jrsPris_normal ?>, <?= 30 - $jrsPris_normal ?>],
                    backgroundColor: ['#3b82f6', '#e2e8f0'],
                    borderWidth: 0
                }]
            },
            options: {
                ...commonOptions,
                cutout: '70%',
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' jours';
                            }
                        }
                    }
                }
            }
        });

        // Graphique Congé Exceptionnel (Donut)
        const exceptionalCtx = document.getElementById('exceptionalChart').getContext('2d');
        new Chart(exceptionalCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pris', 'Restant'],
                datasets: [{
                    data: [<?= $jrsPris_exc ?>, <?= 10 - $jrsPris_exc ?>],
                    backgroundColor: ['#f59e0b', '#e2e8f0'],
                    borderWidth: 0
                }]
            },
            options: {
                ...commonOptions,
                cutout: '70%',
                plugins: {
                    ...commonOptions.plugins,
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' jours';
                            }
                        }
                    }
                }
            }
        });

        // Graphique de Comparaison (Bar)
        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: ['Congé Normal', 'Congé Exceptionnel'],
                datasets: [
                    {
                        label: 'Jours pris',
                        data: [<?= $jrsPris_normal ?>, <?= $jrsPris_exc ?>],
                        backgroundColor: ['#3b82f6', '#f59e0b'],
                        borderRadius: 6
                    },
                    {
                        label: 'Jours restants',
                        data: [<?= 30 - $jrsPris_normal ?>, <?= 10 - $jrsPris_exc ?>],
                        backgroundColor: ['#93c5fd', '#fcd34d'],
                        borderRadius: 6
                    }
                ]
            },
            options: {
                ...commonOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' jours';
                            }
                        }
                    }
                }
            }
        });

        // Animation des barres de progression au chargement
        document.addEventListener('DOMContentLoaded', () => {
            const progressBars = document.querySelectorAll('.progress-bar-fill');
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