<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointage</title>
    <style>
        /* Styles simples pour la page */
        body { font-family: Arial, sans-serif; margin: 20px; color: #222; }
        h2 { margin-bottom: 16px; }
        form label { display: inline-block; min-width: 120px; }
        input[type="date"], input[type="time"], select { padding: 6px 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 8px 10px; border: 1px solid #ddd; }
        thead th { background: #f5f5f5; text-align: left; }
        input[type="submit"] {
            padding: 8px 14px; background: #1976d2; color: #fff;
            border: 0; border-radius: 4px; cursor: pointer;
        }
        input[type="submit"]:hover { background: #155fa7; }
        #filterInput { padding: 6px; min-width: 260px; }
    </style>
</head>
<body>
    <h2>Formulaire de Pointage</h2>
    <form method="post" action="<?php echo constant('BASE_URL'); ?>/time/presences">

        <label for="date">Date :</label>
        <input type="date" id="date" name="date" required><br><br>

        <label for="type">Type :</label>
        <select id="type" name="type" required>
            <option value=1>Entrée</option>
            <option value=2>Sortie</option>
        </select><br><br>

        <label for="heure">Heure :</label>
        <input type="time" id="heure" name="heure" required><br><br>
        <h3>Liste des employés</h3>
        <table border="1" cellpadding="5">
            <input type="submit" value="Valider la sélection">
            <thead>
                <tr>
                    <th>Sélectionner</th>
                    <th>Nom & Prenom</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $employees = $data['employees'];
                for ($i=0 ; $i < count($employees); $i++) 
                {
            ?>
                <!-- Exemple statique, à remplacer par une boucle PHP -->
                <tr>
                    <td><input type="checkbox" name="employes[]" value="<?php echo $employees[$i]['id']; ?>"></td>
                    <td><?php echo $employees[$i]['nom'] . " " . $employees[$i]['prenom']; ?></td>
                </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
        <br>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cible la section "Liste des employés"
        const form = document.querySelector('form');
        const heading = form.querySelector('h3');
        const table = form.querySelector('table');
        const tbody = table ? table.querySelector('tbody') : null;

        if (!heading || !tbody) return;

        // Crée et insère un champ de filtre juste sous le titre, uniquement en JS
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
        input.style.padding = '6px';
        input.style.minWidth = '260px';

        wrapper.appendChild(label);
        wrapper.appendChild(input);
        heading.insertAdjacentElement('afterend', wrapper);

        // Fonction de filtrage
        const filterRows = () => {
            const q = input.value.trim().toLowerCase();
            const rows = Array.from(tbody.rows);
            rows.forEach(tr => {
                const nameCell = tr.cells[1]; // "Nom & Prenom"
                if (!nameCell) return;
                const txt = nameCell.textContent.toLowerCase();
                tr.style.display = q === '' || txt.includes(q) ? '' : 'none';
            });
        };

        // Ecoute les frappes pour filtrer en direct
        input.addEventListener('input', filterRows);
    });
    </script>
</body>
</html>