    <link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/pointage.css">
    

    <div class="main-content">
        <div class="container">
            <h2>📋 Formulaire de Pointage</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    ❌ <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo constant('BASE_URL'); ?>time/presences">
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
