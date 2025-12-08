<style>
    /* Styles spécifiques au Dashboard */
    .dashboard-container {
        padding: 0;
        max-width: 1370px;
        margin: 20px 350px;
    }

    h1 {
        font-size: 2rem;
        color: var(--text-main);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* --- GRILLE DE STATISTIQUES (KPI) --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        padding: 25px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border-left: 5px solid var(--primary);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 5px;
    }

    .stat-card>div:last-child {
        font-size: 0.85rem;
        color: var(--secondary);
    }

    /* Couleurs spécifiques aux KPI */
    .stat-card:nth-child(1) {
        border-left-color: var(--primary);
        /* Turnover */
    }

    .stat-card:nth-child(2) .stat-value {
        color: var(--danger-text);
        /* Absentéisme */
    }

    .stat-card:nth-child(2) {
        border-left-color: var(--danger-text);
    }

    .stat-card:nth-child(3) .stat-value {
        color: var(--success-text);
        /* Ancienneté */
    }

    .stat-card:nth-child(3) {
        border-left-color: var(--success-text);
    }

    .stat-card:nth-child(4) .stat-value {
        color: #9333ea;
        /* Filles */
    }

    .stat-card:nth-child(4) {
        border-left-color: #9333ea;
    }

    .stat-card:nth-child(5) .stat-value {
        color: #0ea5e9;
        /* Garçons */
    }

    .stat-card:nth-child(5) {
        border-left-color: #0ea5e9;
    }

    /* --- CONTENEUR DE GRAPHIQUES --- */
    .chart-container {
        background: var(--bg-card);
        border-radius: var(--radius);
        padding: 25px;
        width: 1200px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-top: 30px;
    }

    .chart-container h3 {
        font-size: 1.25rem;
        color: var(--text-main);
        margin-top: 0;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* --- Responsive Design --- */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
        
        .dashboard-container {
            margin: 20px 50px;
        }
    }
</style>

<div class="dashboard-container">
    <h1><i class="fa-solid fa-chart-line" style="color: var(--primary);"></i> Tableau de Bord RH - Statistiques</h1>

    <div class="stats-grid">
        <!-- Turnover -->
        <div class="stat-card">
            <div class="stat-label">Taux de Rétention/Turnover</div>
            <div class="stat-value" id="turnover-value"><?php echo htmlspecialchars($turnover); ?></div>
            <div>Contrats Actifs / Total de contrats</div>
        </div>

        <!-- Absentéisme -->
        <div class="stat-card">
            <div class="stat-label">ABSENTÉISME(NOMBRE)</div>
            <div class="stat-value" id="absenteeism-value"><?php echo htmlspecialchars($absenteeism); ?></div>
            <div>Total des heures d'absences</div>
        </div>

        <!-- Ancienneté moyenne -->
        <div class="stat-card">
            <div class="stat-label">ANCIENNETÉ MOYENNE(ANS)</div>
            <div class="stat-value" id="seniority-value"><?php echo htmlspecialchars($averageSeniority); ?></div>
            <div>Années d'ancienneté moyenne</div>
        </div>

        <!-- Filles -->
        <div class="stat-card">
            <div class="stat-label">FEMMES</div>
            <div class="stat-value" id="fille-value"><?php echo htmlspecialchars($fille); ?></div>
            <div>Employées féminines actives</div>
        </div>

        <!-- Garçons -->
        <div class="stat-card">
            <div class="stat-label">HOMMES</div>
            <div class="stat-value" id="garcon-value"><?php echo htmlspecialchars($garcon); ?></div>
            <div>Employés masculins actifs</div>
        </div>
    </div>

    <!-- Graphique de distribution d'âge -->
    <div class="chart-container">
        <h3 style="font-family: 'Outfit', sans-serif; font-weight: 600; margin-bottom: 10px;">
            <i class="fa-solid fa-chart-bar" style="color: var(--primary);"></i>
            Distribution des employés par âge (contrats actifs)
        </h3>
        <div style="position: relative; height: 350px;">
            <canvas id="ageChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données du graphique d'âge
    const ageDistribution = <?php echo json_encode($ageDistribution); ?>;
    const ages = ageDistribution.map(item => item.age);
    const counts = ageDistribution.map(item => parseInt(item.count_employees));

    const ctx = document.getElementById('ageChart').getContext('2d');

    // Dégradé de couleur pour les barres
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.9)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.3)');

    // Création du graphique
    const ageChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ages,
            datasets: [{
                label: "Nombre d'employés",
                data: counts,
                backgroundColor: gradient,
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1.5,
                borderRadius: 8,
                hoverBackgroundColor: 'rgba(99, 102, 241, 0.9)',
                hoverBorderColor: 'rgba(99, 102, 241, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(79, 70, 229, 0.9)',
                    titleFont: { size: 14, weight: 'bold', family: 'Outfit' },
                    bodyFont: { size: 13, family: 'Outfit' },
                    callbacks: {
                        label: context => ` ${context.parsed.y} employé(s)`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: 'Outfit', size: 12 },
                        color: '#4b5563'
                    },
                    title: {
                        display: true,
                        text: "Nombre d'employés",
                        font: { size: 14, weight: 'bold', family: 'Outfit' },
                        color: '#111827'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        borderDash: [4, 4]
                    }
                },
                x: {
                    ticks: {
                        font: { family: 'Outfit', size: 12 },
                        color: '#4b5563'
                    },
                    title: {
                        display: true,
                        text: 'Âge',
                        font: { size: 14, weight: 'bold', family: 'Outfit' },
                        color: '#111827'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>