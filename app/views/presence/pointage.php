    <link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/pointage.css">
    
    <div class="main-content">
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
                        // Utilisez $data['employees'] si disponible, sinon un tableau vide pour éviter les erreurs.
                        $employees = $data['employees'] ?? [];
                        $currentMonth = date('n');

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
                                            <?php $months = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre']; ?>
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

            // Pré-sélectionner le mois actuel (déjà fait en PHP mais on le garde pour la robustesse)
            const currentMonth = new Date().getMonth() + 1;
            document.querySelectorAll('select[name="mois"]').forEach(select => {
                select.value = currentMonth;
            });
        });
    </script>