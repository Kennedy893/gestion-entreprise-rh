<?php

// Récupération des données dynamiques
$mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
// Données de performance dynamiques
$productivite = [];
$gestion_temps = [];
$ponctualite = [];

foreach ($data['notes'] as $note) {
    $productivite[] = round($note['productivite'], 1);
    $gestion_temps[] = round($note['gestion_temps'], 1);
    $ponctualite[] = round($note['ponctualite'], 1);
}

// Données employés dynamiques
$employes_actifs = $data['nbr_employes'];
$postes = [];
foreach ($data['postes'] as $poste) {
    $postes[] = $poste['label'];
}
$actifs_par_poste = $data['nbr_postes'];

// Données heures de travail dynamiques
$heures_normales = [];
$heures_weekend = [];
$heures_hors_service = [];
$heures_ferie = [];

foreach ($data['heures'] as $heure) {
    $heures_normales[] = $heure['heures_normales'];
    $heures_weekend[] = $heure['week-end'];
    $heures_hors_service[] = $heure['hors-service'];
    $heures_ferie[] = $heure['jours_feries'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Performance</title>
    <link rel="stylesheet" href="<?php echo constant('BASE_URL'); ?>/public/assets/css/Hcss/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Harmonisation des champs du formulaire (select, input, button) */
        .dashboard-header form select,
        .dashboard-header form input[type="number"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background-color: #ffffff;
            color: #1f2937;
            font-size: 14px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .dashboard-header form select:hover,
        .dashboard-header form input[type="number"]:hover {
            border-color: #cbd5e1;
        }
        .dashboard-header form select:focus,
        .dashboard-header form input[type="number"]:focus {
            outline: none;
            border-color: #1a237e;
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.12);
        }
        .dashboard-header form label {
            color: #1a237e;
        }
        .dashboard-header form button {
            padding: 8px 14px;
            border: 1px solid #1a237e;
            background: #1a237e;
            color: #ffffff;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s ease, transform .02s ease;
        }
        .dashboard-header form button:hover {
            background: #283593;
        }
        .dashboard-header form button:active {
            transform: translateY(1px);
        }
        /* Flèche personnalisée du select */
        .dashboard-header form select {
            background-image: url("data:image/svg+xml;utf8,<svg fill='%231f2937' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 16px;
            padding-right: 32px;
        }
        /* Bandeau mise à jour */
        .update-info {
            margin: 10px 0 16px 0;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
            color: #334155;
            font-weight: 600;
        }
        /* Responsive: empiler sur mobile */
        @media (max-width: 640px) {
            .dashboard-header form {
                flex-wrap: wrap;
                gap: 10px;
            }
            .dashboard-header form select,
            .dashboard-header form input[type="number"],
            .dashboard-header form button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Dashboard Performance Département</h1>
            <!-- Sélecteur statique/dynamique de département -->
            <form action="<?php echo constant('BASE_URL'); ?>performance/dashboard" method="get" style="margin-top:10px; display:flex; gap:8px; align-items:center;">
                <label for="idDept" style="font-weight:600;">Département:</label>
                <select name="idDept" id="idDept">
                <?php
                    foreach($data['list_dept'] as $dept) {
                ?>
                        <option value="<?php echo $dept['id']; ?>" <?php echo (($data['idDept'] ?? null) == $dept['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept['libelle']); ?></option>
                <?php
                    }
                ?>
                </select>
                <label for="annee">Annee:</label>
                <input type="number" name="annee" id="annee" value="<?php echo htmlspecialchars($data['annee'] ?? date('Y')); ?>">
                <button type="submit">Confirmer</button>
            </form>
        </header>

        <!-- Bandeau "Mise à jour" placé entre le bloc titre/sélecteurs et les statistiques -->
        <div class="update-info">
            Mise à jour: <?php echo date('d/m/Y'); ?>
        </div>

        <div class="dashboard-grid">
            <!-- Section Performance -->
            <section class="card performance-card">
                <h2>Performance du Département</h2>
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>
            </section>

            <!-- Section Employés -->
            <section class="card employees-card">
                <h2>Employés Actifs</h2>
                <div class="employees-stats">
                    <a href="<?php echo constant('BASE_URL'); ?>performance/calendar?idDept=<?= $data['idDept'] ?? 1 ?>">
                        <div class="total-employees">
                            <span class="number"><?php echo $employes_actifs; ?></span>
                            <span class="label">Employés actifs</span>
                        </div>
                    </a>
                    <div class="chart-container">
                        <canvas id="postesChart"></canvas>
                    </div>
                </div>
            </section>

            <!-- Section Heures de Travail -->
            <section class="card hours-card">
                <h2>Heures de Travail</h2>
                <div class="hours-stats">
                    <div class="chart-container">
                        <canvas id="hoursChart"></canvas>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Configuration globale pour des courbes plus fines
        Chart.defaults.elements.line.borderWidth = 2;
        Chart.defaults.elements.point.radius = 3;
        Chart.defaults.elements.point.hoverRadius = 5;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;

        // Chart Performance
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($mois); ?>,
                datasets: [
                    {
                        label: 'Productivité',
                        data: <?php echo json_encode($productivite); ?>,
                        borderColor: '#1565C0',                         // bleu
                        backgroundColor: 'rgba(21, 101, 192, 0.15)',     // bleu translucide
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Gestion du Temps',
                        data: <?php echo json_encode($gestion_temps); ?>,
                        borderColor: '#E53935',                         // rouge
                        backgroundColor: 'rgba(229, 57, 53, 0.15)',     // rouge translucide
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Ponctualité',
                        data: <?php echo json_encode($ponctualite); ?>,
                        borderColor: '#43A047',                         // vert
                        backgroundColor: 'rgba(67, 160, 71, 0.15)',     // vert translucide
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2.5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1,
                            color: '#5c6bc0',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(92, 107, 192, 0.1)',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(92, 107, 192, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#5c6bc0',
                            font: {
                                size: 11
                            }
                        },
                        border: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#1a237e',
                            font: {
                                size: 12
                            },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 35, 126, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#1a237e',
                        borderWidth: 1,
                        cornerRadius: 4,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(1);
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Chart Postes
        const postesCtx = document.getElementById('postesChart').getContext('2d');
        new Chart(postesCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($postes); ?>,
                datasets: [{
                    data: <?php echo json_encode($actifs_par_poste); ?>,
                    backgroundColor: [
                        '#1a237e',
                        '#283593',
                        '#303f9f',
                        '#3949ab',
                        '#5c6bc0',
                        '#7986cb',
                        '#9fa8da',
                        '#c5cae9'
                    ],
                    borderWidth: 1.5,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 2,
                    hoverBorderColor: '#1a237e'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#2c3e50',
                            font: {
                                size: 11
                            },
                            padding: 15,
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 35, 126, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#1a237e',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Chart Heures
        const hoursCtx = document.getElementById('hoursChart').getContext('2d');
        new Chart(hoursCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($mois); ?>,
                datasets: [
                    {
                        label: 'Heures Normales',
                        data: <?php echo json_encode($heures_normales); ?>,
                        borderColor: '#1a237e',
                        backgroundColor: 'rgba(26, 35, 126, 0.05)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Heures Week-end',
                        data: <?php echo json_encode($heures_weekend); ?>,
                        borderColor: '#d32f2f',
                        backgroundColor: 'rgba(211, 47, 47, 0.05)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
                    },
                    {
                        label: 'Heures Hors-service',
                        data: <?php echo json_encode($heures_hors_service); ?>,
                        borderColor: '#f57c00',
                        backgroundColor: 'rgba(245, 124, 0, 0.05)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
                    },
                    {
                        label: 'Heures Jours Fériés',
                        data: <?php echo json_encode($heures_ferie); ?>,
                        borderColor: '#7b1fa2',
                        backgroundColor: 'rgba(123, 31, 162, 0.05)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#5c6bc0',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(92, 107, 192, 0.1)',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(92, 107, 192, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#5c6bc0',
                            font: {
                                size: 11
                            }
                        },
                        border: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#1a237e',
                            font: {
                                size: 12
                            },
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(26, 35, 126, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#1a237e',
                        borderWidth: 1,
                        cornerRadius: 4,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + 'h';
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>
</body>
</html>