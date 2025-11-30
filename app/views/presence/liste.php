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
            gap: 8px;
            margin: 0;
        }

        #filterInput { padding: 6px; min-width: 260px; }
    </style>
</head>
<body>
    <h2>Formulaire de Pointage</h2>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: red; margin-bottom: 16px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <h3>Liste des employés</h3>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Sélectionner</th>
                <th>Nom & Prenom</th>
                <th>Relevé</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $employees = $data['employees'];
            for ($i=0 ; $i < count($employees); $i++) 
            {
        ?>
            <tr>
                <td><input type="checkbox" name="employes[]" value="<?php echo $employees[$i]['id']; ?>"></td>
                <td><?php echo $employees[$i]['nom'] . " " . $employees[$i]['prenom']; ?></td>
                <td>
                    <form method="get" action="<?php echo constant('BASE_URL'); ?>/time/releves">
                        <input type="hidden" name="id_employe" value="<?php echo $employees[$i]['id']; ?>">
                        <input type="number" name="annee" placeholder="Année" min="1900" max="2100" required>
                        <select name="mois">
                            <option value="1">janvier</option>
                            <option value="2">février</option>
                            <option value="3">mars</option>
                            <option value="4">avril</option>
                            <option value="5">mai</option>
                            <option value="6">juin</option>
                            <option value="7">juillet</option>
                            <option value="8">août</option>
                            <option value="9">septembre</option>
                            <option value="10">octobre</option>
                            <option value="11">novembre</option>
                            <option value="12">décembre</option>
                        </select>
                        <button type="submit">Relevé</button>
                    </form>
                </td>
            </tr>
        <?php
            }
        ?>
        </tbody>
    </table>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('table');
        const tbody = table ? table.querySelector('tbody') : null;
        const h3 = document.querySelector('h3');

        if (!table || !tbody || !h3) return;

        // Ajoute un champ de filtre juste sous le titre
        const wrapper = document.createElement('div');
        wrapper.style.margin = '8px 0 12px';

        const label = document.createElement('label');
        label.setAttribute('for', 'filterInput');
        label.textContent = 'Filtrer par nom/prénom : ';
        label.style.fontWeight = '600';

        const input = document.createElement('input');
        input.type = 'text';
        input.id = 'filterInput';
        input.placeholder = 'Tapez un nom ou prénom...';

        wrapper.appendChild(label);
        wrapper.appendChild(input);
        h3.insertAdjacentElement('afterend', wrapper);

        const filterRows = () => {
            const q = input.value.trim().toLowerCase();
            const rows = Array.from(tbody.rows);
            rows.forEach(tr => {
                const nameCell = tr.cells[1];
                if (!nameCell) return;
                const txt = nameCell.textContent.toLowerCase();
                tr.style.display = q === '' || txt.includes(q) ? '' : 'none';
            });
        };

        input.addEventListener('input', filterRows);
    });
    </script>
</body>
</html>