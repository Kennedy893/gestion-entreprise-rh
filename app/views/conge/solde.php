<style>
    .main-content {
        width: 1400px;
        margin: 20px 350px;
        padding: 0;
    }

    h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 30px;
        border-bottom: 2px solid var(--border);
        padding-bottom: 15px;
    }

    /* --- CARTE DE FILTRE --- */
    .filter-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 30px;
        border-left: 5px solid var(--primary);
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .filter-icon {
        font-size: 1.5rem;
        color: var(--primary);
    }

    .filter-title {
        font-size: 1.2rem;
        margin: 0;
        color: var(--text-main);
    }

    .filter-form {
        display: flex;
        align-items: flex-end;
        gap: 20px;
    }

    .filter-form .form-group {
        flex-grow: 1;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--text-main);
        font-size: 0.95rem;
    }

    .form-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 1rem;
        background-color: var(--bg-body);
        transition: border-color 0.2s;
        min-width: 250px;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .btn-filter {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s;
        flex-shrink: 0;
    }

    .btn-filter:hover {
        background-color: var(--primary-dark);
    }

    /* --- CARTES RÉCAPITULATIVES --- */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: var(--bg-card);
        padding: 25px;
        border-radius: var(--radius);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .summary-card.total { border-bottom: 4px solid var(--warning-text); }
    .summary-card.used { border-bottom: 4px solid var(--danger-text); }
    .summary-card.remaining { border-bottom: 4px solid var(--success-text); }

    .summary-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    .summary-label {
        font-size: 0.9rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 500;
    }
    .summary-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-top: 5px;
    }
    .summary-card.total .summary-value { color: var(--warning-text); }
    .summary-card.used .summary-value { color: var(--danger-text); }
    .summary-card.remaining .summary-value { color: var(--success-text); }

    .summary-subtitle {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 10px;
    }

    /* --- TABLEAU DÉTAILLÉ --- */
    .table-container {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 20px 30px;
    }
    
    .table-title {
        font-size: 1.25rem;
        margin: 0 0 20px 0;
        color: var(--text-main);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .table-wrapper table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px; /* Espacement entre les lignes */
        min-width: 1000px;
    }
    
    .table-wrapper thead th {
        text-align: left;
        padding: 15px 12px;
        color: var(--text-muted);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border);
    }
    
    .table-wrapper tbody tr {
        background-color: var(--bg-card);
        border-radius: var(--radius-sm);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: box-shadow 0.2s;
    }

    .table-wrapper tbody tr:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .table-wrapper tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        font-size: 0.95rem;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    
    .table-wrapper tbody tr td:first-child { border-left: 1px solid var(--border); border-top-left-radius: var(--radius-sm); border-bottom-left-radius: var(--radius-sm); }
    .table-wrapper tbody tr td:last-child { border-right: 1px solid var(--border); border-top-right-radius: var(--radius-sm); border-bottom-right-radius: var(--radius-sm); }
    .table-wrapper tbody tr td:not(:last-child) { border-right: none; }


    /* --- CONTENU SPÉCIFIQUE AU TABLEAU --- */
    .year-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background-color: var(--primary-dark);
        color: white;
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .days-cell {
        font-weight: 700;
    }
    .days-acquired { color: var(--warning-text); }
    .days-used { color: var(--danger-text); }
    .days-remaining, .days-cumulative { color: var(--success-text); }
    
    /* Barre de progression */
    .progress-bar {
        background-color: var(--border);
        border-radius: var(--radius-sm);
        height: 6px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background-color: var(--danger-text); /* Le taux d'utilisation est négatif */
        transition: width 1s ease-out;
    }
    
    /* Bouton d'action */
    .btn-details {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background-color: var(--bg-body);
        color: var(--primary);
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: background-color 0.2s, border-color 0.2s;
    }
    .btn-details:hover {
        background-color: #e2e8f0;
        border-color: var(--primary);
    }
    
    /* --- ÉTAT VIDE --- */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        border: 2px dashed var(--border);
        border-radius: var(--radius);
        margin-top: 20px;
    }
    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 10px;
        color: var(--text-muted);
    }
    .empty-state-text {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 5px;
    }
    .empty-state-subtext {
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-form .form-group {
            width: 100%;
        }
        .form-select {
            min-width: 100%;
        }
    }
</style>

<body>
    <div class="main-content">
        <h1><i class="fa-solid fa-plane-departure" style="color: var(--rh-color);"></i> Solde de congé</h1>

        <div class="filter-card">
            <div class="filter-header">
                <span class="filter-icon">🔍</span>
                <h3 class="filter-title">Filtrer par employé</h3>
            </div>
            
            <form method="get" action="<?= constant('BASE_URL') ?>vers_solde_conge" class="filter-form">
                <div class="form-group">
                    <label class="form-label" for="id_employe">Sélectionner un employé</label>
                    <select name="id_employe" id="id_employe" class="form-select">
                        <option value="">👥 Tous les employés</option>
                        <?php foreach ($employes as $emp): ?>
                            <option value="<?= $emp['id'] ?>" 
                                <?= isset($_GET['id_employe']) && $_GET['id_employe'] == $emp['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($emp['nom'] . " " . $emp['prenom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Filtrer
                </button>
            </form>
        </div>

        <?php if (!empty($solde)): ?>
            <?php 
                // Calcul des totaux
                $totalAcquis = array_sum(array_column($solde, 'jours_acquis'));
                $totalPris = array_sum(array_column($solde, 'jours_conso'));
                $totalRestant = array_sum(array_column($solde, 'jours_restants'));
                $tauxGlobalUtilisation = $totalAcquis > 0 ? round(($totalPris / $totalAcquis) * 100, 1) : 0;
            ?>

            <div class="summary-cards">
                <div class="summary-card total">
                    <div class="summary-icon">📊</div>
                    <div class="summary-label">Jours acquis</div>
                    <div class="summary-value"><?= htmlspecialchars($totalAcquis) ?></div>
                    <div class="summary-subtitle">Total sur toutes les années</div>
                </div>

                <div class="summary-card used">
                    <div class="summary-icon">✈️</div>
                    <div class="summary-label">Jours pris</div>
                    <div class="summary-value"><?= htmlspecialchars($totalPris) ?></div>
                    <div class="summary-subtitle"><?= htmlspecialchars($tauxGlobalUtilisation) ?>% utilisés</div>
                </div>

                <div class="summary-card remaining">
                    <div class="summary-icon">🎯</div>
                    <div class="summary-label">Solde disponible</div>
                    <div class="summary-value"><?= htmlspecialchars($totalRestant) ?></div>
                    <div class="summary-subtitle">Jours restants à prendre</div>
                </div>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fa-solid fa-table-list" style="color: var(--text-main);"></i> Historique détaillé par année
                </h2>
            </div>

            <div class="table-wrapper">
                <?php if (empty($solde)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <div class="empty-state-text">Aucun solde trouvé</div>
                        <div class="empty-state-subtext">
                            <?= isset($_GET['id_employe']) && $_GET['id_employe'] ? 'Cet employé n\'a pas encore de données de congés.' : 'Sélectionnez un employé pour voir ses soldes.' ?>
                        </div>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Année</th>
                                <th>Jours acquis</th>
                                <th>Jours pris</th>
                                <th style="min-width: 150px;">Taux d'utilisation</th>
                                <th>Solde restant</th>
                                <th>Solde cumulé</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $soldeCumule = 0;
                                // Simuler $annee_debut pour le rendu si non défini
                                $annee_debut = $annee_debut ?? (min(array_column($solde, 'annee')) ?? date('Y'));
                            ?>

                            <?php foreach ($solde as $s) : ?>

                                <?php
                                    // -------------------------------------------------------------
                                    //  Reset du solde cumulé tous les 3 ans
                                    // -------------------------------------------------------------
                                    $current_annee = (int)$s['annee'];
                                    $cycleIndex = floor(($current_annee - $annee_debut) / 3);
                                    $debutCycle = $annee_debut + ($cycleIndex * 3);

                                    if ($current_annee === (int)$debutCycle && $current_annee !== (int)min(array_column($solde, 'annee'))) {
                                        $soldeCumule = 0; // RESET !
                                    }

                                    // Ajout du solde restant de l’année
                                    $soldeCumule += $s['jours_restants'];

                                    // Calcul taux
                                    $tauxUtilisation = $s['jours_acquis'] > 0 
                                                        ? ($s['jours_conso'] / $s['jours_acquis']) * 100 
                                                        : 0;
                                ?>

                                <tr>
                                    <td>
                                        <span class="year-badge">
                                            <i class="fa-regular fa-calendar-alt"></i> <?= htmlspecialchars($s['annee']) ?>
                                        </span>
                                    </td>
                                    <td class="days-acquired">
                                        <div class="days-cell">
                                            <?= htmlspecialchars($s['jours_acquis']) ?> jours
                                        </div>
                                    </td>
                                    <td class="days-used">
                                        <div class="days-cell">
                                            <?= htmlspecialchars($s['jours_conso']) ?> jours
                                        </div>
                                    </td>
                                    <td>
                                        <div style="min-width: 120px;">
                                            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">
                                                <?= round($tauxUtilisation, 1) ?>%
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: <?= min($tauxUtilisation, 100) ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="days-remaining">
                                        <div class="days-cell">
                                            <?= htmlspecialchars($s['jours_restants']) ?> jours
                                        </div>
                                    </td>
                                    <td class="days-cumulative">
                                        <div class="days-cell">
                                            <?= $soldeCumule ?> jours
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?= constant('BASE_URL') . 'details_solde?employe=' . htmlspecialchars($s['id_employe']) . '&annee=' .  htmlspecialchars($s['annee']) ?>" 
                                           class="btn-details">
                                            <i class="fa-solid fa-eye"></i>
                                            Voir détails
                                        </a>
                                    </td> 
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Animation de la barre de progression au chargement
        document.addEventListener('DOMContentLoaded', () => {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    // S'assurer que la transition est activée
                    bar.style.transition = 'width 1.5s ease-out';
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>