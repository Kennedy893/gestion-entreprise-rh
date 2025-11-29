<?php
// Récupération des données dynamiques
$mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
print_r($data['test']);
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
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Dashboard Performance Département</h1>
            <div class="header-info">
                <span>Mise à jour: <?php echo date('d/m/Y'); ?></span>
                <span>Année: <?php echo $annee ?? date('Y'); ?></span>
            </div>
        </header>

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
                    <div class="total-employees">
                        <span class="number"><?php echo $employes_actifs; ?></span>
                        <span class="label">Employés actifs</span>
                    </div>
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
                        borderColor: '#1a237e',
                        backgroundColor: 'rgba(26, 35, 126, 0.05)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Gestion du Temps',
                        data: <?php echo json_encode($gestion_temps); ?>,
                        borderColor: '#283593',
                        backgroundColor: 'rgba(40, 53, 147, 0.05)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Ponctualité',
                        data: <?php echo json_encode($ponctualite); ?>,
                        borderColor: '#303f9f',
                        backgroundColor: 'rgba(48, 63, 159, 0.05)',
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