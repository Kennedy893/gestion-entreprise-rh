<style>
    /* VARIABLES */
    :root {
        --primary: #4f46e5;       /* Indigo */
        --primary-dark: #4338ca;
        --bg-body: #f1f5f9;       /* Gris clair */
        --bg-card: #ffffff;
        --text-main: #0f172a;     /* Noir foncé */
        --text-muted: #64748b;    /* Gris bleu */
        --border: #e2e8f0;        /* Gris très clair */
        --success: #10b981;       /* Vert */
        --danger: #ef4444;        /* Rouge */
        
        --radius: 12px;
        --radius-sm: 6px;
    }

    .main-content {
        font-family: 'Outfit', sans-serif;
        background-color: var(--bg-body);
        color: var(--text-main);
        width: 1350px;
        margin: 20px 350px;
        padding: 30px;
    }

    .container {
        max-width: 700px;
        margin: 0 auto;
        background: var(--bg-card);
        padding: 30px;
        border-radius: var(--radius);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border-top: 5px solid var(--primary);
    }
    
    h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-top: 0;
        margin-bottom: 25px;
        color: var(--primary);
    }
    
    h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-top: 30px;
        margin-bottom: 15px;
        color: var(--text-main);
        padding-bottom: 5px;
        border-bottom: 1px solid var(--border);
    }

    /* --- MESSAGES D'ERREUR --- */
    .error-message {
        background-color: #fee2e2; /* Rouge très clair */
        color: var(--danger);
        border: 1px solid var(--danger);
        padding: 15px;
        border-radius: var(--radius-sm);
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* --- CONTROLES HEURE/DATE/TYPE --- */
    .form-container {
        padding: 20px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background-color: #f8fafc;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: var(--text-main);
        margin-right: 5px;
    }
    
    .form-group input[type="date"],
    .form-group input[type="time"],
    .form-group select {
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 1rem;
        background-color: var(--bg-card);
        flex-grow: 1;
        min-width: 120px;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    /* Bouton de soumission */
    .submit-btn {
        width: 100%;
        padding: 12px;
        background-color: var(--success);
        color: white;
        border: none;
        border-radius: var(--radius-sm);
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .submit-btn:hover {
        background-color: #059669; /* Vert plus foncé */
    }

    /* --- FILTRE --- */
    .filter-container {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .filter-container label {
        font-weight: 600;
        flex-shrink: 0;
    }
    #filterInput {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        flex-grow: 1;
    }

    /* --- TABLEAU DES EMPLOYÉS --- */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        overflow: hidden;
    }
    
    thead th {
        background-color: var(--primary);
        color: white;
        text-align: left;
        padding: 12px 15px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background-color 0.2s;
    }
    tbody tr:last-child {
        border-bottom: none;
    }
    tbody tr:hover {
        background-color: #f8fafc;
    }
    
    tbody td {
        padding: 12px 15px;
        font-size: 0.95rem;
    }

    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary);
    }
    
    /* Responsive pour le form-group */
    @media (max-width: 600px) {
        .form-group {
            flex-direction: column;
            align-items: stretch;
        }
        .form-group label {
            margin-top: 10px;
            margin-right: 0;
        }
        .filter-container {
            flex-direction: column;
            align-items: stretch;
            gap: 5px;
        }
    }
</style>

<body>
    <div class="main-content">
        <div class="container">
            <h2><i class="fa-solid fa-clock-rotate-left"></i> Formulaire de Pointage</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($_GET['error']); ?>
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

                <h3><i class="fa-solid fa-users"></i> Liste des employés</h3>
                
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
                        // Vérification de la variable $data['employees'] pour éviter les erreurs
                        $employees = $data['employees'] ?? [];
                        for ($i=0 ; $i < count($employees); $i++) 
                        {
                    ?>
                        <tr data-name="<?php echo strtolower($employees[$i]['nom'] . " " . $employees[$i]['prenom']); ?>">
                            <td style="text-align: center;">
                                <input type="checkbox" name="employes[]" value="<?php echo $employees[$i]['id']; ?>">
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($employees[$i]['nom'] . " " . $employees[$i]['prenom']); ?></strong>
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
        const filterInput = document.getElementById('filterInput');
        const tbody = form.querySelector('tbody');

        if (!tbody) return;

        const filterRows = () => {
            const q = filterInput.value.trim().toLowerCase();
            const rows = Array.from(tbody.rows);
            rows.forEach(tr => {
                const nameAttribute = tr.getAttribute('data-name');
                if (!nameAttribute) return;
                
                // Afficher la ligne si la recherche est vide OU si le nom contient le terme de recherche
                tr.style.display = q === '' || nameAttribute.includes(q) ? '' : 'none';
            });
        };

        filterInput.addEventListener('input', filterRows);

        // --- Pré-remplissage Heure et Date ---
        
        // Pré-remplir la date d'aujourd'hui
        const dateInput = document.getElementById('date');
        const today = new Date().toISOString().split('T')[0];
        if (!dateInput.value) {
            dateInput.value = today;
        }

        // Pré-remplir l'heure actuelle
        const heureInput = document.getElementById('heure');
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        if (!heureInput.value) {
            heureInput.value = `${hours}:${minutes}`;
        }
        
        // Validation minimale: S'assurer qu'au moins un employé est sélectionné
        form.addEventListener('submit', function(e) {
            const checkboxes = form.querySelectorAll('input[name="employes[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert("Veuillez sélectionner au moins un employé à pointer.");
            } else {
                // Animation de soumission
                const submitBtn = form.querySelector('.submit-btn');
                submitBtn.value = "⏳ Envoi en cours...";
                submitBtn.disabled = true;
            }
        });
    });
    </script>