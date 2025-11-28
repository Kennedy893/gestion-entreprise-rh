<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord RH - Statistiques</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-value {
            font-size: 2.5em;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 1.1em;
        }

        .age-search {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .age-search input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
            width: 100px;
        }

        .age-search button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .age-search button:hover {
            background: #2980b9;
        }

        .result-display {
            margin-top: 20px;
            padding: 15px;
            background: #ecf0f1;
            border-radius: 4px;
            display: none;
        }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <?= include ('app/views/Chatbot.php')?>
    <div class="dashboard-container">
        <h1>Tableau de Bord RH - Statistiques</h1>

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
            <h3>Recherche d'employés par âge</h3>
            <p>Entrez un âge pour voir le nombre d'employés de cet âge avec contrat actif :</p>
            <input type="number" id="age-input" min="18" max="65" placeholder="Ex: 18">
            <button onclick="searchByAge()">Rechercher</button>
            <div id="age-result" class="result-display"></div>
        </div>

        <div class="chart-container">
            <h3>Distribution des employés par âge (contrats actifs)</h3>
            <canvas id="ageChart" width="400" height="200"></canvas>
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
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: "Nombre d'employés"
                        },
                    
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Âge'
                        }
                    }
                }
            }
        });

        function searchByAge() {
            const age = document.getElementById('age-input').value;
            const resultDiv = document.getElementById('age-result');

            if (!age || age < 18 || age > 65) {
                resultDiv.innerHTML = '<span style="color: red;">Veuillez entrer un âge valide (18-65)</span>';
                resultDiv.style.display = 'block';
                return;
            }

            // Afficher un indicateur de chargement
            resultDiv.innerHTML = 'Recherche en cours...';
            resultDiv.style.display = 'block';

            // Requête AJAX
            fetch(`/statistics/employees-by-age?age=${age}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        resultDiv.innerHTML = `<span style="color: red;">Erreur: ${data.error}</span>`;
                    } else {
                        resultDiv.innerHTML = `
                            <strong>Résultat pour l'âge ${data.age} ans:</strong><br>
                            ${data.count} employé(s) trouvé(s) avec un contrat actif
                        `;
                    }
                })
                .catch(error => {
                    resultDiv.innerHTML = `<span style="color: red;">Erreur lors de la recherche</span>`;
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