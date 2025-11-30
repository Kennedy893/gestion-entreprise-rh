<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointage - Employé</title>
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
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #2d3748;
            margin-bottom: 25px;
            font-size: 1.8rem;
            font-weight: 700;
        }

        h3 {
            color: #2d3748;
            margin-bottom: 20px;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 4px solid #e53e3e;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .form-container {
            background: #f8fafc;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        label {
            font-weight: 600;
            color: #4a5568;
            min-width: 80px;
        }

        input[type="date"],
        input[type="time"],
        select {
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            min-width: 200px;
        }

        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.15);
        }

        .submit-btn {
            padding: 12px 25px;
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        th, td {
            border: 1px solid #e2e8f0;
            padding: 15px 12px;
            text-align: left;
        }

        th {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f7fafc;
        }

        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Cases à cocher */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Filtre */
        .filter-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
        }

        .filter-container label {
            font-weight: 600;
            color: #4a5568;
            margin-right: 10px;
        }

        #filterInput {
            padding: 10px 15px;
            min-width: 300px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }

        #filterInput:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
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

            .container {
                padding: 20px;
            }

            .form-group {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            input[type="date"],
            input[type="time"],
            select {
                min-width: auto;
                width: 100%;
            }

            #filterInput {
                min-width: 250px;
                width: 100%;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 12px 8px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 15px;
            }

            .container {
                padding: 15px;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.2rem;
            }

            .filter-container {
                padding: 15px;
            }

            #filterInput {
                min-width: 200px;
            }
        }
    </style>
</head>
<body>
    <?php include("app/views/bar/sidebarEmp.php")?>

    <div class="main-content">
        <div class="container">
            <h2>📋 Formulaire de Pointage</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    ❌ <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo constant('BASE_URL'); ?>/time/presences">
                <div class="form-container">
                    <div class="form-group">
                        <label for="date">Date :</label>
                        <input type="date" id="date" name="date" required>
                        
                        <label for="type">Type :</label>
                        <select id="type" name="type" required>
                            <option value="1">Entrée</option>
                            <option value="2">Sortie</option>
                        </select>
                        
                        <label for="heure">Heure :</label>
                        <input type="time" id="heure" name="heure" required>
                    </div>
                    
                    <input type="submit" class="submit-btn" value="✅ Valider la sélection">
                </div>

                <h3>👥 Liste des employés</h3>
                
                <div class="filter-container">
                    <label for="filterInput">Filtrer par nom/prénom :</label>
                    <input type="text" id="filterInput" placeholder="Tapez un nom ou prénom...">
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">Sélectionner</th>
                            <th>Nom & Prénom</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $employees = $data['employees'];
                        for ($i=0 ; $i < count($employees); $i++) 
                        {
                    ?>
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="employes[]" value="<?php echo $employees[$i]['id']; ?>">
                            </td>
                            <td>
                                <strong><?php echo $employees[$i]['nom'] . " " . $employees[$i]['prenom']; ?></strong>
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const heading = form.querySelector('h3');
        const table = form.querySelector('table');
        const tbody = table ? table.querySelector('tbody') : null;

        if (!heading || !tbody) return;

        const filterInput = document.getElementById('filterInput');

        const filterRows = () => {
            const q = filterInput.value.trim().toLowerCase();
            const rows = Array.from(tbody.rows);
            rows.forEach(tr => {
                const nameCell = tr.cells[1];
                if (!nameCell) return;
                const txt = nameCell.textContent.toLowerCase();
                tr.style.display = q === '' || txt.includes(q) ? '' : 'none';
            });
        };

        filterInput.addEventListener('input', filterRows);

        // Pré-remplir la date d'aujourd'hui
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').value = today;

        // Pré-remplir l'heure actuelle
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        document.getElementById('heure').value = `${hours}:${minutes}`;
    });
    </script>
</body>
</html>