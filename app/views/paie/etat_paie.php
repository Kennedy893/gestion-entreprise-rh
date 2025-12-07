<style>

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
        --warning: #f59e0b;       /* Orange */
        
        --radius: 12px;
        --radius-sm: 6px;
    }
    .main-content {
        padding: 30px;
        max-width: 1400px;
        margin: 20px 350px;
    }

    /* --- HEADER DE PAGE --- */
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .company-info h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .company-info p {
        font-size: 1rem;
        color: var(--text-muted);
        margin-top: 5px;
    }

    .date-badge {
        background-color: var(--bg-card);
        border: 1px solid var(--border);
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-main);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    /* --- CONTROLES / BOUTONS --- */
    .controls {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }
    .export-btn {
        background-color: var(--success);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .export-btn:hover {
        background-color: #059669;
    }

    /* --- STATS GRID --- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border-left: 5px solid var(--primary);
    }
    .stat-card:nth-child(2) { border-left-color: var(--warning); }
    .stat-card:nth-child(3) { border-left-color: var(--success); }
    .stat-card:nth-child(4) { border-left-color: var(--danger); }
    
    .stat-label {
        font-size: 0.9rem;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 5px;
        font-weight: 500;
    }
    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-main);
    }
    
    /* --- TABLEAU DE DÉTAILS --- */
    .table-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
        width: 100%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1500px; /* Assure un débordement horizontal */
    }

    thead th {
        background-color: var(--bg-body);
        color: var(--text-main);
        text-align: left;
        padding: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        border-bottom: 2px solid var(--border);
        white-space: nowrap;
    }
    
    tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background-color 0.2s;
    }
    tbody tr:hover {
        background-color: #f8fafc;
    }
    
    tbody td {
        padding: 15px;
        font-size: 0.9rem;
        color: var(--text-main);
        white-space: nowrap;
    }

    /* Cellule Employé */
    .employee-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .employee-info {
        display: flex;
        flex-direction: column;
        line-height: 1.3;
    }
    .employee-name {
        font-weight: 600;
    }
    .employee-position {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    /* Montants et Retenues */
    .amount {
        text-align: right;
        font-family: monospace;
    }
    .amount-positive {
        color: var(--success);
    }
    .amount-negative {
        color: var(--danger);
    }
    
    .badge {
        padding: 4px 8px;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-success {
        background-color: #d1fae5;
        color: var(--success);
    }
    
    /* Bouton d'action */
    .download-btn {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        cursor: pointer;
        transition: background-color 0.2s;
        white-space: nowrap;
    }
    .download-btn:hover {
        background-color: var(--primary-dark);
    }

    /* --- PAGINATION --- */
    .pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-top: 1px solid var(--border);
    }

    .pagination-info {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .pagination-buttons {
        display: flex;
        gap: 5px;
    }
    .page-btn {
        background: var(--bg-body);
        border: 1px solid var(--border);
        padding: 8px 12px;
        border-radius: var(--radius-sm);
        font-size: 0.9rem;
        cursor: pointer;
        transition: background-color 0.2s, border-color 0.2s;
        color: var(--text-main);
    }
    .page-btn.active {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .page-btn:hover:not(.active) {
        background-color: #e5e7eb;
    }

    /* --- Responsive --- */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .header-top {
            flex-direction: column;
            align-items: flex-start;
        }
        .date-badge {
            margin-top: 15px;
        }
        .controls {
            justify-content: center;
        }
    }
</style>

    <div class="main-content">
        <div class="header">
            <div class="header-top">
                <div class="company-info">
                    <h1><i class="fa-solid fa-chart-area" style="color: var(--primary);"></i> Système de Gestion de Paie</h1>
                    <p>Tableau de bord des salaires - Période courante</p>
                </div>
                <div class="date-badge">
                    📅 <?= function_exists('formatDate') ? formatDate($data['resume']['date_generation']) : 'Date du rapport' ?>
                </div>
            </div>

            <div class="controls">
                <button class="export-btn" onclick="window.location.href=`<?= constant('BASE_URL') ?>paie/etats/export`">
                    <span><i class="fa-solid fa-file-excel"></i></span> Exporter Excel
                </button>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Employés</div>
                    <div class="stat-value"> <?= $data['resume']['total_employes'] ?? 'N/A' ?> </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Masse Salariale Brute</div>
                    <div class="stat-value"> <?= function_exists('moneyFormat') ? moneyFormat($data['resume']['masse_brut']) : ($data['resume']['masse_brut'] ?? '0 Ar') ?> </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Net à Payer</div>
                    <div class="stat-value"> <?= function_exists('moneyFormat') ? moneyFormat($data['resume']['total_net']) : ($data['resume']['total_net'] ?? '0 Ar') ?> </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Charges Sociales Employeur</div>
                    <div class="stat-value"> <?= function_exists('moneyFormat') ? moneyFormat($data['resume']['charges_sociales']) : ($data['resume']['charges_sociales'] ?? '0 Ar') ?> </div>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employé</th>
                            <th>Date d'embauche</th>
                            <th>Absence (h)</th>
                            <th>Salaire de base</th>
                            <th>Avantage</th>
                            <th>Heures sup.</th>
                            <th style="color: var(--warning);">Salaire Brut</th>
                            <th>CNAPS (1%)</th>
                            <th>OSTIE (1%)</th>
                            <th>Autres ret.</th>
                            <th style="color: var(--danger);">Total Retenues (Salarié)</th>
                            <th>Rev. imposable</th>
                            <th>IRSA</th>
                            <th style="color: var(--success);">Salaire Net</th>
                            <th>Charges Emp. (8%+5%)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                        if (isset($data['details']) && is_array($data['details'])) { 
                            foreach ($data['details'] as $d) { 
                                // Utiliser des valeurs par défaut si les fonctions PHP ne sont pas définies ou si les clés manquent
                                $moneyFormat = function_exists('moneyFormat') ? 'moneyFormat' : function($v) { return number_format($v, 0, ',', ' ') . ' Ar'; };
                                $salaire = $d['salaire'] ?? 0;
                                $avantages = $d['avantages'] ?? 0;
                                $heure_sup = $d['heure_sup'] ?? 0;
                                $salaire_brut = $d['salaire_brut'] ?? 0;
                                $cnaps_1 = $d['cnaps_1'] ?? 0;
                                $ostie_1 = $d['ostie_1'] ?? 0;
                                $total_ret = $d['total_ret'] ?? 0;
                                $revenu_impo = $d['revenu_impo'] ?? 0;
                                $irsa = $d['irsa'] ?? 0;
                                $salaire_net = $d['salaire_net'] ?? 0;
                                $autres_ret = $d['autres_ret'] ?? 0;
                                $charges_emp = ($d['cnaps_8'] ?? 0) + ($d['ostie_5'] ?? 0);
                        ?>
                            <tr>
                                <td># <?= $d['id_employe'] ?? 'N/A' ?> </td>
                                <td>
                                    <div class="employee-cell">
                                        <div class="avatar"><?= strtoupper(substr($d['nom'] ?? '', 0, 1) . substr($d['prenom'] ?? '', 0, 1)) ?></div>
                                        <div class="employee-info">
                                            <span class="employee-name"><?= $d['nom'] . ' ' . $d['prenom']  ?></span>
                                            <span class="employee-position"> <?= $d['label'] ?? 'N/A' ?> </span>
                                        </div>
                                    </div>
                                </td>
                                <td> <?= $d['date_debut'] ?? 'N/A' ?> </td>
                                <td><span class="badge <?= ($d['absence'] ?? 0) > 0 ? 'badge-danger' : 'badge-success' ?>"><?= $d['absence'] ?? 0 ?>h</span></td>
                                <td class="amount"> <?= $moneyFormat($salaire) ?> </td>
                                <td class="amount"> <?= $moneyFormat($avantages) ?> </td>
                                <td class="amount amount-positive"> <?= $moneyFormat($heure_sup) ?> </td>
                                <td class="amount" style="font-weight: 700;"> <?= $moneyFormat($salaire_brut) ?> </td>
                                <td class="amount amount-negative"> <?= $moneyFormat($cnaps_1) ?> </td>
                                <td class="amount amount-negative"> <?= $moneyFormat($ostie_1) ?> </td>
                                <td class="amount amount-negative"> <?= $moneyFormat($autres_ret) ?> </td>
                                <td class="amount amount-negative"> <?= $moneyFormat($total_ret) ?> </td>
                                <td class="amount"> <?= $moneyFormat($revenu_impo) ?> </td>
                                <td class="amount amount-negative"> <?= $moneyFormat($irsa) ?> </td>
                                <td class="amount" style="font-weight: 700; color: var(--success);"> <?= $moneyFormat($salaire_net) ?> </td>
                                <td class="amount"> <?= $moneyFormat($charges_emp) ?> </td>
                                <td>
                                    <button class="download-btn" onclick="window.location.href=`<?= constant('BASE_URL') ?>paie/fiche/<?= $d['id_employe'] ?? 'N/A' ?>`">
                                        <i class="fa-solid fa-file-invoice"></i> Bulletin
                                    </button>
                                </td>
                            </tr>
                        <?php } 
                        } else { ?>
                             <tr>
                                <td colspan="17" style="text-align: center; color: var(--text-muted); padding: 20px;">
                                    Aucun détail de paie disponible pour la période.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="pagination-info">
                    Affichage 1-<?= count($data['details'] ?? []) ?> sur <?= $data['resume']['total_employes'] ?? 0 ?> employés
                </div>
                <div class="pagination-buttons">
                    <button class="page-btn">‹ Précédent</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">Suivant ›</button>
                </div>
            </div>
        </div>
    </div>
