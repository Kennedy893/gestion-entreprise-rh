<style>
    .main-content {
        padding: 30px;
        margin: 20px auto;
    }

    .container {
        max-width: 1300px;
        margin: 0 auto;
    }

    /* --- ALERTES DE MESSAGE --- */
    .alert-message {
        padding: 15px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid transparent;
    }
    .alert-success {
        background-color: var(--success-bg);
        color: var(--success-text);
        border-color: #34d399;
    }
    .alert-error {
        background-color: var(--danger-bg);
        color: var(--danger-text);
        border-color: var(--danger-text);
    }
    
    /* --- EN-TÊTE DE PAGE --- */
    .page-header {
        margin-bottom: 30px;
    }
    .breadcrumb {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 10px;
    }
    .breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
    }
    .breadcrumb-separator {
        margin: 0 5px;
    }
    .page-title-wrapper {
        display: flex;
        align-items: center;
    }
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
        display: flex;
        align-items: center;
    }
    .page-title-icon {
        font-size: 1.5em;
        margin-right: 10px;
        /* Couleur spécifique RH */
        color: var(--rh-color); 
    }

    /* --- STATISTIQUES EN CARTES --- */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--bg-card);
        padding: 20px;
        border-radius: var(--radius);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        border-left: 5px solid var(--rh-color); /* Bord RH */
    }
    
    .stat-card:nth-child(1) { border-left-color: var(--warning-text); } /* En attente */
    .stat-card:nth-child(2) { border-left-color: var(--success-text); } /* Validées */
    .stat-card:nth-child(3) { border-left-color: var(--danger-text); }  /* Refusées */


    .stat-icon {
        font-size: 1.5rem;
        margin-bottom: 5px;
        color: var(--text-muted);
    }
    .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 500;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
    }

    /* --- CONTENEUR DU TABLEAU --- */
    .table-container {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 20px 30px;
    }
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border);
        margin-bottom: 20px;
    }
    .table-title {
        font-size: 1.25rem;
        margin: 0;
        color: var(--text-main);
    }
    .table-filters {
        display: flex;
        gap: 15px;
    }

    .search-box, .filter-select {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.95rem;
        max-width: 250px;
        transition: border-color 0.2s;
    }
    .search-box:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary);
    }

    /* --- TABLEAU STYLISÉ --- */
    .table-wrapper table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 5px; /* Espacement entre les lignes */
    }

    .table-wrapper thead th {
        text-align: left;
        padding: 15px 10px;
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
        transition: background-color 0.2s;
    }

    .table-wrapper tbody tr:hover {
        background-color: var(--bg-body);
    }

    .table-wrapper tbody td {
        padding: 15px 10px;
        vertical-align: middle;
        font-size: 0.95rem;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    
    /* Pour la forme arrondie des lignes (avec border-spacing et separate) */
    .table-wrapper tbody tr td:first-child { border-left: 1px solid var(--border); border-top-left-radius: var(--radius-sm); border-bottom-left-radius: var(--radius-sm); }
    .table-wrapper tbody tr td:last-child { border-right: 1px solid var(--border); border-top-right-radius: var(--radius-sm); border-bottom-right-radius: var(--radius-sm); }
    .table-wrapper tbody tr td:not(:last-child) { border-right: none; }

    /* Fixe la soumission de formulaire dans le tableau */
    .table-wrapper tbody tr form {
        display: contents; 
    }


    /* --- CONTENU DES CELLULES --- */

    /* Cellule Employé */
    .employee-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .employee-avatar {
        width: 35px;
        height: 35px;
        background-color: var(--rh-color); /* Couleur RH sur l'avatar */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }
    .employee-info {
        display: flex;
        flex-direction: column;
    }
    .employee-name {
        font-weight: 600;
        color: var(--text-main);
    }
    .employee-role {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    /* Cellule Motif */
    .motif-text {
        max-width: 250px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--text-muted);
    }

    /* Badges (Période, Durée) */
    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background-color: var(--info-bg);
        color: var(--info-text);
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-size: 0.85rem;
        font-weight: 500;
        white-space: nowrap;
    }
    .days-badge {
        display: inline-block;
        background-color: var(--warning-bg);
        color: var(--warning-text);
        padding: 5px 10px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.9rem;
        min-width: 70px;
        text-align: center;
    }
    .days-badge.urgent {
        background-color: var(--danger-bg);
        color: var(--danger-text);
    }
    
    /* Input Date de Validation */
    td input[type="date"] {
        padding: 8px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.9rem;
        max-width: 140px;
    }

    /* Boutons d'Action */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
        min-width: 160px;
    }
    .btn {
        padding: 8px 12px;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        transition: opacity 0.2s, background-color 0.2s;
        white-space: nowrap;
    }
    .btn-approve {
        background-color: var(--success-text);
        color: white;
    }
    .btn-approve:hover {
        background-color: #059669;
    }
    .btn-reject {
        background-color: var(--danger-text);
        color: white;
    }
    .btn-reject:hover {
        background-color: #dc2626;
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

    /* --- Responsive --- */
    @media (max-width: 1100px) {
        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .table-filters {
            margin-top: 15px;
        }
    }
    @media (max-width: 768px) {
        .main-content {
            padding: 20px 10px;
        }
        .stats-cards {
            grid-template-columns: 1fr;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        .table-wrapper table {
            min-width: 900px;
        }
    }
</style>

<body>
    <div class="main-content">
        <?php
            // Récupération des messages depuis l'URL (PHP existant)
            $message = null;
            $type = null;

            if (!empty($_GET['error'])) {
                $message = $_GET['error'];
                $type = 'error';
            } elseif (!empty($_GET['success'])) {
                $message = 'La demande a été traitée avec succès.';
                $type = 'success';
            }
        ?>

        <?php if (!empty($message)): ?>
            <div class="alert-message <?= $type === 'error' ? 'alert-error' : 'alert-success' ?>">
                <?php if ($type === 'error'): ?>
                    <i class="fa-solid fa-circle-xmark"></i>
                <?php else: ?>
                    <i class="fa-solid fa-circle-check"></i>
                <?php endif; ?>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>


        <div class="page-header">  
            <div class="page-title-wrapper">
                <h1 class="page-title">
                    <span class="page-title-icon"><i class="fa-solid fa-user-tie"></i></span>
                    Validation de congés (coté RH)
                </h1>
            </div>
        </div>

        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                <div class="stat-label">En attente</div>
                <div class="stat-value"><?= count($liste ?? []) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-check-circle"></i></div>
                <div class="stat-label">Validées ce mois</div>
                <div class="stat-value">24</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-times-circle"></i></div>
                <div class="stat-label">Refusées ce mois</div>
                <div class="stat-value">3</div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Demandes en attente de validation</h2>
                <div class="table-filters">
                    <input type="text" class="search-box" placeholder="Rechercher un employé...">
                    <select class="filter-select">
                        <option>Tous les types</option>
                        <option>Congé normal</option>
                        <option>Congé exceptionnel</option>
                        <option>Congé maladie</option>
                    </select>
                </div>
            </div>

            <div class="table-wrapper">
                <?php if (empty($liste)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="fa-solid fa-inbox"></i></div>
                        <div class="empty-state-text">Aucune demande en attente</div>
                        <div class="empty-state-subtext">Toutes les demandes ont été traitées. Bon travail!</div>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Département</th>
                                <th>Employé</th>
                                <th>Motif</th>
                                <th>Période d'absence</th>
                                <th>Durée d'attente</th>
                                <th>Date de Validation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($liste as $l): ?>
                            
                            
                            <tr data-name="<?= strtolower($l['nom'] . ' ' . $l['prenom']) ?>" data-role="<?= strtolower($l['poste']) ?>">
                                <form action="<?= constant('BASE_URL') ?>valider_conge_rh" method="post" style="display: contents;">
                                <input type="hidden" value="<?= $l['id_absence'] ?>" name="id_absence">
                                <input type="hidden" value="<?= $l['poste'] ?>" name="poste">
                                <input type="hidden" value="<?= $l['id_employe'] ?>" name="id_employe">
                                <td>
                                    <span class="employee-role" title="<?= htmlspecialchars($l['departement']) ?>">
                                        <?= htmlspecialchars($l['departement']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="employee-cell">
                                        <div class="employee-avatar">
                                            <?= strtoupper(substr($l['prenom'], 0, 1) . substr($l['nom'], 0, 1)) ?>
                                        </div>
                                        <div class="employee-info">
                                            <span class="employee-name"><?= $l['nom'] . ' ' . $l['prenom'] ?></span>
                                            <span class="employee-role"><?= $l['poste'] ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="motif-text" title="<?= htmlspecialchars($l['motif']) ?>">
                                        <?= htmlspecialchars($l['motif']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="fa-regular fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($l['date_debut'])) ?> au <?= date('d/m/Y', strtotime($l['date_fin'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="days-badge <?= $l['jours_attente'] > 5 ? 'urgent' : '' ?>">
                                        <?= $l['jours_attente'] ?> jour<?= $l['jours_attente'] > 1 ? 's' : '' ?>
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        <input type="date" name="date_validation">
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="submit" name="button" value="0" class="btn btn-approve">
                                            <i class="fa-solid fa-check"></i> Valider
                                        </button>
                                        <button type="submit" name="button" value="1" class="btn btn-reject">
                                            <i class="fa-solid fa-xmark"></i> Refuser
                                        </button>
                                    </div>
                                </td>
                                </form>
                            </tr>
                            
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBox = document.querySelector('.search-box');
            const tableRows = document.querySelectorAll('.table-wrapper tbody tr');

            // Initialisation des champs de date
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[name="date_validation"]').forEach(input => {
                if (!input.value) {
                    input.value = today;
                }
            });

            // Recherche en temps réel
            searchBox?.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                
                tableRows.forEach(row => {
                    // Utiliser les attributs data pour la recherche (si la récupération échoue via JS)
                    const nameAttr = row.getAttribute('data-name') || '';
                    const roleAttr = row.getAttribute('data-role') || '';
                    
                    if (nameAttr.includes(searchTerm) || roleAttr.includes(searchTerm)) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Confirmation avant validation/refus
            const actionButtons = document.querySelectorAll('.btn');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const isApprove = btn.classList.contains('btn-approve');
                    const action = isApprove ? 'valider' : 'refuser';
                    // Trouver le nom de l'employé pour la confirmation
                    const employeeName = btn.closest('tr').querySelector('.employee-name').textContent;
                    
                    const confirmed = confirm(`Êtes-vous sûr de vouloir ${action} la demande de congé de ${employeeName} ?`);
                    
                    if (!confirmed) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>