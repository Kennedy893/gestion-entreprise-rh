<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feuille de Temps - Sélection</title>
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
            color: #222;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 40px 30px;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
        }

        h2 {
            color: #2d3748;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: 600;
            color: #4a5568;
            font-size: 1.1rem;
        }

        input[type="date"] {
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        input[type="date"]:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.15);
        }

        button {
            padding: 15px 25px;
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.3);
        }

        button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.25);
        }

        .info-box {
            background: #ebf8ff;
            border-left: 4px solid #1976d2;
            padding: 20px;
            border-radius: 8px;
            margin-top: 25px;
        }

        .info-box h3 {
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .info-box ul {
            color: #4a5568;
            padding-left: 20px;
        }

        .info-box li {
            margin-bottom: 8px;
            line-height: 1.4;
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
                padding: 30px 20px;
            }

            .container {
                padding: 30px 25px;
            }

            h2 {
                font-size: 1.6rem;
            }

            form {
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 20px 15px;
            }

            .container {
                padding: 25px 20px;
            }

            h2 {
                font-size: 1.4rem;
            }

            input[type="date"] {
                padding: 12px;
                font-size: 14px;
            }

            button {
                padding: 12px 20px;
                font-size: 1rem;
            }
        }

        /* Style pour la date sélectionnée */
        .date-preview {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: #f0fff4;
            border-radius: 8px;
            border: 1px solid #c6f6d5;
            color: #22543d;
            font-weight: 500;
            display: none;
        }
    </style>
</head>
<body>
    <?php include("app/views/bar/sidebar.php")?>

    <div class="main-content">
        <div class="container">
            <h2>📅 Feuille de Temps - Sélection de la date</h2>

            <form method="get" action="<?php echo constant('BASE_URL'); ?>/time/timecards">
                <div class="form-group">
                    <label for="date">Sélectionnez une date :</label>
                    <input type="date" id="date" name="date" required>
                    <div id="date-preview" class="date-preview"></div>
                </div>
                
                <button type="submit">📊 Voir les détails de la feuille de temps</button>
            </form>

            <div class="info-box">
                <h3>ℹ️ Information</h3>
                <ul>
                    <li>Sélectionnez une date pour consulter la feuille de temps correspondante</li>
                    <li>Vous pourrez visualiser les pointages de tous les employés pour cette date</li>
                    <li>La feuille de temps inclut les heures travaillées, absences et retards</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('date');
            const datePreview = document.getElementById('date-preview');
            
            // Définir la date d'aujourd'hui par défaut
            const today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
            updateDatePreview(today);

            // Mettre à jour l'aperçu quand la date change
            dateInput.addEventListener('change', function() {
                updateDatePreview(this.value);
            });

            function updateDatePreview(dateString) {
                if (dateString) {
                    const date = new Date(dateString);
                    const options = { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    };
                    const formattedDate = date.toLocaleDateString('fr-FR', options);
                    datePreview.textContent = `Date sélectionnée : ${formattedDate}`;
                    datePreview.style.display = 'block';
                } else {
                    datePreview.style.display = 'none';
                }
            }

            // Mettre à jour l'aperçu au chargement
            updateDatePreview(today);
        });
    </script>
</body>
</html>