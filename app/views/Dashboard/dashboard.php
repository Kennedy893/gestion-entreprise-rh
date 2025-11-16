<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord RH - Statistiques</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: #2d3748;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 700;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-left: 5px solid #3498db;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-card:nth-child(2) {
            border-left-color: #e74c3c;
        }

        .stat-card:nth-child(3) {
            border-left-color: #27ae60;
        }

        .stat-value {
            font-size: 3rem;
            font-weight: 800;
            color: #2c3e50;
            margin: 15px 0;
            line-height: 1;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-card div:last-child {
            color: #95a5a6;
            font-size: 0.9em;
        }

        .age-search {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
        }

        .age-search h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.4rem;
        }

        .age-search p {
            color: #718096;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .search-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .age-search input {
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            width: 120px;
            transition: all 0.3s;
        }

        .age-search input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .age-search button {
            padding: 12px 25px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .age-search button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
        }

        .result-display {
            margin-top: 20px;
            padding: 20px;
            background: #ebf8ff;
            border-radius: 10px;
            border-left: 4px solid #3498db;
            display: none;
            font-size: 1.1em;
        }

        .chart-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-top: 30px;
        }

        .chart-container h3 {
            color: #2d3748;
            margin-bottom: 25px;
            font-size: 1.4rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 70px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .stat-card {
                padding: 25px 20px;
            }

            .stat-value {
                font-size: 2.5rem;
            }

            .search-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .age-search input {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 15px;
            }

            h1 {
                font-size: 1.6rem;
            }

            .stat-value {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <?php include("app/views/bar/sidebar.php")?>

    <div class="main-content">
        <div class="dashboard-container">
            <h1>📊 Tableau de Bord RH - Statistiques</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">TURNOVER des postes</div>
                    <div class="stat-value" id="turnover-value"><?php echo htmlspecialchars($turnover); ?></div>
                    <div>Nombre total de contrats</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">ABSENTÉISME</div>
                    <div class="stat-value" id="absenteeism-value"><?php echo htmlspecialchars($absenteeism); ?></div>
                    <div>Total des absences</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">ANCIENNETÉ MOYENNE</div>
                    <div class="stat-value" id="seniority-value"><?php echo htmlspecialchars($averageSeniority); ?></div>
                    <div>Années d'ancienneté moyenne</div>
                </div>
            </div>

            <div class="age-search">
                <h3>🔍 Recherche d'employés par âge</h3>
                <p>Entrez un âge pour voir le nombre d'employés de cet âge avec contrat actif :</p>
                <div class="search-controls">
                    <input type="number" id="age-input" min="18" max="65" placeholder="Ex: 25">
                    <button onclick="searchByAge()">Rechercher</button>
                </div>
                <div id="age-result" class="result-display"></div>
            </div>

            <div class="chart-container">
                <h3>📈 Distribution des employés par âge (contrats actifs)</h3>
                <canvas id="ageChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Graphique de distribution par âge
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
                    data: counts,
                    backgroundColor: 'rgba(52, 152, 219, 0.7)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
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
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Âge',
                            font: {
                                size: 14,
                                weight: 'bold'
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
                resultDiv.innerHTML = '<span style="color: #e74c3c;">⚠️ Veuillez entrer un âge valide (18-65)</span>';
                resultDiv.style.display = 'block';
                return;
            }

            // Afficher un indicateur de chargement
            resultDiv.innerHTML = '<div style="color: #3498db;">🔄 Recherche en cours...</div>';
            resultDiv.style.display = 'block';

            // Requête AJAX
            fetch(`/statistics/employees-by-age?age=${age}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        resultDiv.innerHTML = `<span style="color: #e74c3c;">❌ Erreur: ${data.error}</span>`;
                    } else {
                        resultDiv.innerHTML = `
                            <div style="font-size: 1.2em;">
                                <strong>🎯 Résultat pour l'âge ${data.age} ans:</strong><br>
                                <span style="font-size: 1.4em; color: #27ae60; font-weight: bold;">
                                    ${data.count} employé(s)
                                </span> trouvé(s) avec un contrat actif
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML = `<span style="color: #e74c3c;">❌ Erreur lors de la recherche</span>`;
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
</body>
</html>