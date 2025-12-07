<style>
    .main-content {
        padding: 30px;
        width: 1350px;
        margin: 20px 350px;
    }

    /* --- EN-TÊTE DE PAGE --- */
    .page-header {
        margin-bottom: 30px;
    }
    .breadcrumb {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 10px;
    }
    .breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
    }
    .breadcrumb-separator {
        margin: 0 5px;
    }
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
        display: flex;
        align-items: center;
    }
    .page-title-icon {
        font-size: 1.5em;
        margin-right: 10px;
        color: var(--primary);
    }
    .page-subtitle {
        font-size: 1rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* --- ALERTES (INFO & WARNING) --- */
    .alert-box {
        display: flex;
        align-items: flex-start;
        padding: 15px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 25px;
        border: 1px solid transparent;
        font-size: 0.95rem;
    }

    .alert-box.warning {
        background-color: var(--alert-warning-bg);
        color: var(--alert-warning-text);
        border-color: var(--alert-warning-text);
    }
    .alert-box.info {
        background-color: var(--alert-info-bg);
        color: var(--alert-info-text);
        border-color: var(--alert-info-text);
    }
    
    .alert-icon {
        font-size: 1.5rem;
        margin-right: 15px;
        flex-shrink: 0;
    }
    .alert-title {
        font-weight: 700;
        margin-bottom: 5px;
    }
    .alert-text {
        font-weight: 400;
    }

    /* --- GRILLE DE CONTENU (Cartes) --- */
    .content-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .card {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        padding: 25px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .card.full-width-card {
        grid-column: 1 / -1;
    }

    /* --- En-tête de Carte --- */
    .card-header {
        border-bottom: 1px solid var(--border);
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-main);
    }

    .card-icon {
        font-size: 1.5rem;
        padding: 5px;
        border-radius: 50%;
    }
    .card-icon.normal { background-color: rgba(59, 130, 246, 0.1); color: var(--color-normal); }
    .card-icon.exceptional { background-color: rgba(245, 158, 11, 0.1); color: var(--color-exceptional); }

    /* --- Ligne de Statistiques (Jours) --- */
    .stats-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 20px;
        text-align: center;
    }

    .stat-box {
        flex: 1;
        padding: 10px 0;
        border-right: 1px solid var(--border);
    }
    .stat-box:last-child {
        border-right: none;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .stat-unit {
        font-size: 0.8em;
        margin-left: 2px;
        color: var(--text-muted);
    }

    /* --- Barre de Progression --- */
    .progress-section {
        margin-top: 15px;
        margin-bottom: 20px;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .progress-label {
        color: var(--text-main);
    }

    .progress-percentage {
        color: var(--text-muted);
    }

    .progress-bar-wrapper {
        background-color: var(--border);
        border-radius: var(--radius-sm);
        height: 8px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        transition: width 1s ease-out;
    }

    .progress-bar-fill.normal { background-color: var(--color-normal); }
    .progress-bar-fill.exceptional { background-color: var(--color-exceptional); }

    /* --- Graphiques (Chart.js) --- */
    .chart-container {
        height: 180px; /* Taille fixe pour les donuts */
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* --- VUE COMPARATIVE (FULL WIDTH) --- */
    .comparison-container {
        display: flex;
        gap: 30px;
        align-items: flex-start;
        margin-bottom: 25px;
    }

    .comparison-chart {
        flex: 3;
        height: 300px; /* Taille pour le graphique en barres */
    }

    .comparison-legend {
        flex: 1;
        padding: 20px;
        background-color: var(--bg-body);
        border-radius: var(--radius-sm);
    }

    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        gap: 10px;
    }

    .legend-color {
        width: 15px;
        height: 15px;
        border-radius: 4px;
        flex-shrink: 0;
    }
    .legend-color.normal { background-color: var(--color-normal); }
    .legend-color.exceptional { background-color: var(--color-exceptional); }

    .legend-label {
        font-weight: 600;
        font-size: 0.95rem;
    }
    .legend-value {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* Tableau récapitulatif */
    .summary-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }
    .summary-table tr:nth-child(even) {
        background-color: var(--bg-body);
    }
    .summary-table td {
        padding: 12px 15px;
        border-top: 1px solid var(--border);
    }
    .summary-table tr td:last-child {
        font-weight: 700;
        text-align: right;
        color: var(--primary-dark);
    }
    .summary-table tr:nth-child(3) td:last-child {
        color: var(--color-normal); /* Vert ou Bleu pour restant */
    }

    /* --- Responsive --- */
    @media (max-width: 1200px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
        .comparison-container {
            flex-direction: column;
        }
        .comparison-chart, .comparison-legend {
            flex: none;
            width: 100%;
        }
        .comparison-chart {
            height: 250px;
        }
    }
</style>

<body>
    <div class="main-content">
        <div class="page-header">    
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fa-solid fa-chart-pie"></i></span>
                Détails des Congés - <?= isset($_GET['annee']) ? htmlspecialchars($_GET['annee']) : date('Y') ?>
            </h1>
            <p class="page-subtitle">Vue détaillée de votre consommation de congés par type pour l'année <?= isset($_GET['annee']) ? htmlspecialchars($_GET['annee']) : date('Y') ?></p>
        </div>

        <?php 
            // Les variables PHP sont conservées ici pour le rendu
            $totalDroit = 30 + 10; // Normal + Exceptionnel
            $totalPris = $jrsPris_normal + $jrsPris_exc;
            $totalRestant = $totalDroit - $totalPris;
            $tauxGlobal = $totalDroit > 0 ? ($totalPris / $totalDroit) * 100 : 0;
        ?>

        <?php if ($totalRestant < 10): ?>
        <div class="alert-box warning">
            <span class="alert-icon">⚠️</span>
            <div class="alert-content">
                <div class="alert-title">Attention - Solde faible</div>
                <div class="alert-text">
                    Il vous reste **<?= $totalRestant ?>** jours de congés disponibles. Planifiez vos prochaines absences en conséquence.
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert-box info">
            <span class="alert-icon">ℹ️</span>
            <div class="alert-content">
                <div class="alert-title">Informations</div>
                <div class="alert-text">
                    Vous avez utilisé **<?= round($tauxGlobal, 1) ?>%** de vos droits à congés. Il vous reste **<?= $totalRestant ?>** jours disponibles.
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="content-grid">
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

                <?php $tauxNormal = 30 > 0 ? ($jrsPris_normal / 30) * 100 : 0; ?>
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Taux d'utilisation</span>
                        <span class="progress-percentage"><?= round($tauxNormal, 1) ?>%</span>
                    </div>
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar-fill normal" style="width: <?= min($tauxNormal, 100) ?>%"></div>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="normalChart"></canvas>
                </div>
            </div>

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

                <?php $tauxExceptional = 10 > 0 ? ($jrsPris_exc / 10) * 100 : 0; ?>
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Taux d'utilisation</span>
                        <span class="progress-percentage"><?= round($tauxExceptional, 1) ?>%</span>
                    </div>
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar-fill exceptional" style="width: <?= min($tauxExceptional, 100) ?>%"></div>
                    </div>
                </div>

                <div class="chart-container">
                    <canvas id="exceptionalChart"></canvas>
                </div>
            </div>

            <div class="card full-width-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fa-solid fa-chart-bar" style="color: var(--primary);"></i> Vue Comparative
                    </div>
                </div>

                <div class="comparison-container">
                    <div class="comparison-chart">
                        <canvas id="comparisonChart"></canvas>
                    </div>
                    
                    <div class="comparison-legend">
                        <table class="summary-table">
                            <tr>
                                <td><i class="fa-solid fa-layer-group" style="color: var(--primary);"></i> Total droits annuels</td>
                                <td><?= $totalDroit ?> jours</td>
                            </tr>
                            <tr>
                                <td><i class="fa-solid fa-plane-departure" style="color: var(--color-exceptional);"></i> Total jours pris</td>
                                <td><?= $totalPris ?> jours</td>
                            </tr>
                            <tr>
                                <td><i class="fa-solid fa-piggy-bank" style="color: var(--color-normal);"></i> Total jours restants</td>
                                <td><?= $totalRestant ?> jours</td>
                            </tr>
                            <tr>
                                <td><i class="fa-solid fa-percent" style="color: var(--text-muted);"></i> Taux d'utilisation global</td>
                                <td><?= round($tauxGlobal, 1) ?>%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // Variables PHP transformées en JS
        const jrsPrisNormal = <?= $jrsPris_normal ?>;
        const jrsPrisExc = <?= $jrsPris_exc ?>;
        const droitNormal = 30;
        const droitExc = 10;
        
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
                    data: [jrsPrisNormal, droitNormal - jrsPrisNormal],
                    backgroundColor: ['var(--color-normal)', 'var(--border)'],
                    hoverBackgroundColor: ['var(--color-normal)', 'var(--border)'],
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
                    data: [jrsPrisExc, droitExc - jrsPrisExc],
                    backgroundColor: ['var(--color-exceptional)', 'var(--border)'],
                    hoverBackgroundColor: ['var(--color-exceptional)', 'var(--border)'],
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
                        data: [jrsPrisNormal, jrsPrisExc],
                        backgroundColor: ['var(--color-normal)', 'var(--color-exceptional)'],
                        borderRadius: 6
                    },
                    {
                        label: 'Jours restants',
                        data: [droitNormal - jrsPrisNormal, droitExc - jrsPrisExc],
                        backgroundColor: ['#93c5fd', '#fcd34d'], /* Couleurs claires des types */
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
                            color: 'var(--border)'
                        }
                    },
                    x: {
                        stacked: true, /* Empilement pour montrer le total */
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
                            pointStyle: 'rect'
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
                    bar.style.transition = 'width 1s ease-out';
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>