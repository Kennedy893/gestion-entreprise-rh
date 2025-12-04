<link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/liste.css">

<div class="container">

    <h2><i class="fa-solid fa-clock-check" style="color: var(--primary);"></i> Formulaire de Pointage</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-message">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3>👥 Liste des employés</h3>

        <div class="filter-container">
            <label for="filterInput">Filtrer par nom/prénom :</label>
            <input type="text" id="filterInput" placeholder="Tapez un nom ou prénom...">
        </div>

        <table>
            <thead>
                <tr>
                    <th class="checkbox-cell">Sélectionner</th>
                    <th>Nom & Prénom</th>
                    <th style="width: 450px;">Relevé des Heures</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // PHP pour déterminer le mois actuel
                $currentMonth = date('n');
                $months = [1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin', 7 => 'juillet', 8 => 'août', 9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'];

                $employees = $data['employees'] ?? []; // Utiliser un tableau vide si les données sont manquantes
                for ($i = 0; $i < count($employees); $i++) {
                    $id = $employees[$i]['id'];
                    $fullName = $employees[$i]['nom'] . " " . $employees[$i]['prenom'];
                ?>
                    <tr data-name="<?php echo strtolower($fullName); ?>">
                        <td class="checkbox-cell">
                            <input type="checkbox" name="employes[]" value="<?php echo $id; ?>">
                        </td>
                        <td>
                            <strong><?php echo $fullName; ?></strong>
                        </td>
                        <td>
                            <form method="get" action="<?php echo constant('BASE_URL'); ?>time/releves">
                                <input type="hidden" name="id_employe" value="<?php echo $id; ?>">
                                <input type="number" name="annee" placeholder="Année" min="1900" max="2100" required value="<?php echo date('Y'); ?>">
                                <select name="mois">
                                    <?php foreach ($months as $num => $name): ?>
                                        <option value="<?= $num ?>" <?= ($num == $currentMonth) ? 'selected' : '' ?>><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit">
                                    <i class="fa-solid fa-chart-simple"></i> Relevé
                                </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('table');
        const tbody = table ? table.querySelector('tbody') : null;

        if (!table || !tbody) return;

        const filterInput = document.getElementById('filterInput');

        const filterRows = () => {
            const q = filterInput.value.trim().toLowerCase();
            const rows = Array.from(tbody.rows);
            rows.forEach(tr => {
                // Utilisation de l'attribut data-name pour le filtrage
                const nameAttr = tr.getAttribute('data-name');
                if (!nameAttr) return;

                tr.style.display = nameAttr.includes(q) ? '' : 'none';
            });
        };

        filterInput.addEventListener('input', filterRows);

        // Pré-sélectionner le mois actuel (pour JS, au cas où le PHP manque)
        const currentMonth = new Date().getMonth() + 1;
        document.querySelectorAll('select[name="mois"]').forEach(select => {
            select.value = currentMonth;
        });
    });
</script>