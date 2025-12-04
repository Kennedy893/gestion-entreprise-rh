<link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/dashboard.css">

<div class="dashboard-container">
    <h1><i class="fa-solid fa-chart-line" style="color: var(--primary);"></i> Tableau de Bord RH - Statistiques</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Taux de Rétention/Turnover</div>
            <div class="stat-value" id="turnover-value"><?php echo htmlspecialchars($turnover); ?></div>
            <div>Contrats Actifs / Total de contrats</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">ABSENTÉISME</div>
            <div class="stat-value" id="absenteeism-value"><?php echo htmlspecialchars($absenteeism); ?></div>
            <div>Total des heures d'absences</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">ANCIENNETÉ MOYENNE</div>
            <div class="stat-value" id="seniority-value"><?php echo htmlspecialchars($averageSeniority); ?></div>
            <div>Années d'ancienneté moyenne par employé</div>
        </div>
    </div>

    <div class="chart-and-search-grid">

        <div class="chart-container">
            <h3><i class="fa-solid fa-chart-bar" style="color: var(--primary);"></i> Distribution des employés par âge (contrats actifs)</h3>
            <canvas id="ageChart" width="400" height="200"></canvas>
        </div>

        <div class="age-search">
            <h3><i class="fa-solid fa-user-magnifying-glass" style="color: var(--primary);"></i> Recherche rapide par âge</h3>
            <p>Entrez un âge pour voir le nombre d'employés de cet âge avec contrat actif :</p>
            <div class="search-controls">
                <input type="number" id="age-input" min="18" max="65" placeholder="Ex: 25">
                <button onclick="searchByAge()"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
            </div>
            <div id="age-result" class="result-display"></div>
        </div>
    </div>
</div>

<!-- TODO : Fix the chart -->

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

<script>
    // Le code PHP de Chart.js doit être placé avant le script JS, comme c'était déjà le cas.
    // L'initialisation du graphique Chart.js est ajustée pour utiliser la couleur principale.
    const ageDistribution = <?php echo json_encode($ageDistribution); ?>;

    const ages = ageDistribution.map(item => item.age);
    const counts = ageDistribution.map(item => parseInt(item.count_employees));

    const ctx = document.getElementById('ageChart').getContext('2d');
    const ageChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ages,
            datasets: [{
                label: "Nombre d'employés",
                // Utilisation de la variable CSS --primary pour la couleur des barres
                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                /* Couleur primaire avec transparence */
                borderColor: 'rgba(79, 70, 229, 1)',
                /* Couleur primaire pleine */
                data: counts,
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Permet un meilleur contrôle de la hauteur
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Nombre d'employés",
                        font: {
                            size: 14,
                            weight: 'bold',
                            family: 'Outfit'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Âge',
                        font: {
                            size: 14,
                            weight: 'bold',
                            family: 'Outfit'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    function searchByAge() {
        const age = document.getElementById('age-input').value;
        const resultDiv = document.getElementById('age-result');

        if (!age || age < 18 || age > 65) {
            resultDiv.innerHTML = `<span style="color: var(--danger-text);"><i class="fa-solid fa-triangle-exclamation"></i> Veuillez entrer un âge valide (18-65)</span>`;
            resultDiv.style.display = 'block';
            return;
        }

        // Afficher un indicateur de chargement
        resultDiv.innerHTML = `<span style="color: var(--primary);"><i class="fa-solid fa-rotate fa-spin"></i> Recherche en cours...</span>`;
        resultDiv.style.display = 'block';

        // Requête AJAX
        fetch(`<?php echo constant('BASE_URL'); ?>/statistics/employees-by-age?age=${age}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    resultDiv.innerHTML = `<span style="color: var(--danger-text);">❌ Erreur: ${data.error}</span>`;
                } else {
                    resultDiv.innerHTML = `
                        <div style="font-size: 1.1em;">
                            <strong>🎯 Résultat pour ${data.age} ans:</strong><br>
                            <span style="font-size: 1.2em; color: var(--success-text); font-weight: bold;">
                                ${data.count} employé(s)
                            </span> trouvé(s) avec un contrat actif <i class="fa-solid fa-circle-check"></i>
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `<span style="color: var(--danger-text);"><i class="fa-solid fa-triangle-exclamation"></i> Erreur lors de la recherche</span>`;
                console.error('Error:', error);
            });
    }

    // Permettre la recherche avec la touche Entrée
    document.getElementById('age-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchByAge();
        }
    });
</script>