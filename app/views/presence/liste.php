<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointage</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 16px; color: #222; }
        h2 { margin-bottom: 12px; }
        label { display: inline-block; min-width: 120px; }

        /* Champs de formulaire */
        input, select, button { padding: 6px 8px; }
        input[type="date"],
        input[type="time"],
        input[type="number"],
        select {
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
        }
        input[type="date"]:focus,
        input[type="time"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 2px rgba(25,118,210,0.15);
        }

        /* Style unifié pour tous les boutons et submits */
        button,
        input[type="submit"] {
            padding: 8px 12px;
            background: #1976d2;
            color: #fff;
            border: 1px solid #1976d2;
            border-radius: 4px;
            cursor: pointer;
            line-height: 1.2;
            transition: background .2s ease, border-color .2s ease, box-shadow .2s ease, color .2s ease;
        }
        button:hover,
        input[type="submit"]:hover { background: #155fa7; border-color: #155fa7; }
        button:focus,
        input[type="submit"]:focus { outline: none; box-shadow: 0 0 0 2px rgba(25,118,210,0.25); }
        button:disabled,
        input[type="submit"]:disabled { opacity: .65; cursor: not-allowed; }

        /* Tableau */
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }

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
    <?php include("app/views/bar/sidebar.php")?>
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