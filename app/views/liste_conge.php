<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Congés - RH Manager</title>
    
</head>
<body>
    
    <h1>Liste des congés</h1>

    <div class="main-content">
        <?php
            // Récupération des messages depuis l'URL
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
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>


        <div class="page-header">
            <div class="breadcrumb">
                <a href="<?= constant('BASE_URL') ?>">Accueil</a>
                <span class="breadcrumb-separator">›</span>
                <a href="<?= constant('BASE_URL') ?>conges">Congés</a>
                <span class="breadcrumb-separator">›</span>
                <span>Demandes en attente</span>
            </div>
              
            <div class="page-title-wrapper">
                <h1 class="page-title">
                    <span class="page-title-icon">📋</span>
                    Gestion des Demandes de Congés
                </h1>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-label">En attente</div>
                <div class="stat-value"><?= count($liste) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✓</div>
                <div class="stat-label">Validées ce mois</div>
                <div class="stat-value">24</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✕</div>
                <div class="stat-label">Refusées ce mois</div>
                <div class="stat-value">3</div>
            </div>
        </div>

        <!-- Tableau -->
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
                        <div class="empty-state-icon">📭</div>
                        <div class="empty-state-text">Aucune demande en attente</div>
                        <div class="empty-state-subtext">Toutes les demandes ont été traitées</div>
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
                                <th>Date du jour</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($liste as $l): ?>
                            
                            
                            <tr>
                                <form action="<?= constant('BASE_URL') ?>valider_conge" method="post" style="display: table-row;">
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
                                        📅 <?= date('d/m/Y', strtotime($l['date_debut'])) ?> - <?= date('d/m/Y', strtotime($l['date_fin'])) ?>
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
                                            ✓ Valider
                                        </button>
                                        <button type="submit" name="button" value="1" class="btn btn-reject">
                                            ✕ Refuser
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
        // Recherche en temps réel
        const searchBox = document.querySelector('.search-box');
        const tableRows = document.querySelectorAll('tbody tr');

        searchBox?.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            
            tableRows.forEach(row => {
                const employeeName = row.querySelector('.employee-name')?.textContent.toLowerCase() || '';
                const employeeRole = row.querySelector('.employee-role')?.textContent.toLowerCase() || '';
                
                if (employeeName.includes(searchTerm) || employeeRole.includes(searchTerm)) {
                    row.style.display = '';
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
                const employeeName = btn.closest('tr').querySelector('.employee-name').textContent;
                
                const confirmed = confirm(`Êtes-vous sûr de vouloir ${action} la demande de congé de ${employeeName} ?`);
                
                if (!confirmed) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>