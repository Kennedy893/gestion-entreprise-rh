<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointage</title>
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
            max-width: 1200px;
            margin: 0 auto;
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

        /* Messages d'erreur */
        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 4px solid #e53e3e;
            margin-bottom: 25px;
            font-weight: 500;
        }

        /* Tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        tbody tr:nth-child(even):hover {
            background-color: #f1f5f9;
        }

        /* Cases à cocher */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Mini-formulaires dans chaque ligne */
        table td form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            flex-wrap: wrap;
        }

        /* Champs de formulaire */
        input, select, button {
            padding: 10px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input[type="number"],
        select {
            min-width: 120px;
            background: white;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.15);
        }

        /* Boutons */
        button,
        input[type="submit"] {
            padding: 10px 20px;
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        button:hover,
        input[type="submit"]:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        button:focus,
        input[type="submit"]:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.25);
        }

        /* Filtre */
        .filter-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 12px 8px;
            }

            table td form {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            input[type="number"],
            select {
                min-width: auto;
                width: 100%;
            }

            #filterInput {
                min-width: 250px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
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
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include("app/views/bar/sidebar.php")?>

    <div class="main-content">
        <div class="container">
            <h2>📋 Formulaire de Pointage</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    ❌ <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

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
                        <th style="width: 400px;">Relevé</th>
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
                        <td>
                            <form method="get" action="<?php echo constant('BASE_URL'); ?>/time/releves">
                                <input type="hidden" name="id_employe" value="<?php echo $employees[$i]['id']; ?>">
                                <input type="number" name="annee" placeholder="Année" min="1900" max="2100" required value="<?php echo date('Y'); ?>">
                                <select name="mois">
                                    <option value="1">Janvier</option>
                                    <option value="2">Février</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Août</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                                <button type="submit">📊 Relevé</button>
                            </form>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('table');
        const tbody = table ? table.querySelector('tbody') : null;

        if (!table || !tbody) return;

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

        // Pré-sélectionner le mois actuel
        const currentMonth = new Date().getMonth() + 1;
        document.querySelectorAll('select[name="mois"]').forEach(select => {
            select.value = currentMonth;
        });
    });
    </script>
</body>
</html>