<style>
    /* VARIABLES */
    :root {
        --primary: #4f46e5;       /* Indigo */
        --accent-pay: #10b981;    /* Vert pour Net à payer */
        --bg-body: #f1f5f9;       /* Gris clair pour le fond */
        --bg-card: #ffffff;
        --text-main: #0f172a;     /* Noir foncé */
        --text-muted: #64748b;    /* Gris bleu */
        --border: #e2e8f0;        /* Gris très clair */
        --radius: 12px;
    }

    .container {
        width: 1450px;
        margin: 0 220px;
        background: var(--bg-card);
        padding: 40px;
        border-radius: var(--radius);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    

    /* --- HEADER (LOGO ET INFO ENTREPRISE) --- */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid var(--primary);
        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background-color: var(--primary);
        border-radius: 4px;
    }

    .logo-text {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.1;
        color: var(--text-main);
    }

    /* --- TITRE --- */
    .title-section {
        text-align: center;
        margin-bottom: 30px;
    }
    .title-section h1 {
        font-size: 2.5rem;
        color: var(--primary);
        margin: 0;
    }
    .title-section h2 {
        font-size: 1.1rem;
        color: var(--text-muted);
        font-weight: 500;
        margin: 5px 0 0 0;
    }

    /* --- GRILLE D'INFORMATIONS --- */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background-color: #f8fafc;
    }
    .info-column h3 {
        font-size: 1.25rem;
        border-bottom: 2px solid var(--border);
        padding-bottom: 5px;
        margin-bottom: 15px;
        color: var(--text-main);
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        font-size: 0.95rem;
    }
    .info-label {
        color: var(--text-muted);
        font-weight: 500;
    }
    .info-value {
        font-weight: 600;
        text-align: right;
    }
    .highlight {
        color: var(--primary);
    }

    /* --- TABLEAUX DE PAIE --- */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    table thead th {
        background-color: var(--primary);
        color: white;
        text-align: left;
        padding: 12px 15px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    table tbody td {
        padding: 10px 15px;
        border: 1px solid var(--border);
        font-size: 0.9rem;
        vertical-align: middle;
    }
    table tbody td:first-child {
        font-weight: 500;
    }
    
    .amount {
        text-align: right;
        font-weight: 600;
        font-family: monospace;
        width: 150px;
    }

    /* Ligne totale brute */
    .total-row td {
        background-color: #f0f4ff;
        font-size: 1rem;
        border-top: 2px solid var(--primary);
    }
    .amount-highlight {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
        text-align: right;
    }

    /* En-tête de section dans le tableau */
    .section-header td {
        background-color: #eef2ff;
        font-weight: 600;
        color: var(--text-main);
        padding: 8px 15px;
    }

    /* Ligne finale NET À PAYER */
    .final-total td {
        background-color: var(--accent-pay);
        color: white !important;
        padding: 15px;
    }
    .final-total td:last-child {
        text-align: right;
        font-size: larger;
    }

    /* --- FOOTER INFO (SIGNATURES) --- */
    .footer-info {
        margin-top: 50px;
        padding: 20px;
        border-top: 1px solid var(--border);
    }
    .footer-box h4 {
        text-align: center;
        color: var(--text-muted);
        margin-bottom: 10px;
    }
    .footer-box > div {
        max-width: 600px;
        margin: 0 auto;
    }
    .footer-box div > div {
        width: 40%;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        .info-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        table {
            display: block;
            overflow-x: auto;
        }
        table thead, table tbody {
            min-width: 600px; /* Assure le scroll horizontal */
        }
        .footer-box > div {
            flex-direction: column;
            gap: 50px;
        }
        .footer-box div > div {
            width: 100%;
        }
        body > button {
            position: static;
            display: block;
            width: calc(100% - 80px);
            margin: 20px auto;
        }
    }
</style>

<body>
    <button onclick="window.location.href=`<?= constant('BASE_URL') ?>paie/fiche/export/<?= $emp['id_employe'] ?? 0 ?>`">
        <i class="fa-solid fa-file-pdf"></i> Exporter PDF
    </button>

    <div class="container">
        <div class="header">
            <div class="logo">
                <div class="logo-icon"></div>
                <div class="logo-text">
                    <span>En</span>Treprise<br>
                    <small style="font-size: 12px; color: #888;">L'avenir est notre ambition</small>
                </div>
            </div>
        </div>

        <div class="title-section">
            <h1>FICHE DE PAIE</h1>
            <h2>ARRETE AU <?= function_exists('formatDate') ? formatDate(date('Y-m-d')) : date('d/m/Y') ?></h2>
        </div>

        <div class="info-grid">
            <div class="info-column">
                <h3><i class="fa-solid fa-user-tag"></i> Informations Employé</h3>
                <div class="info-item">
                    <span class="info-label">Nom et Prénoms :</span>
                    <span class="info-value"><?= $emp['nom'] . ' ' . $emp['prenom']  ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Matricule :</span>
                    <span class="info-value">627/TNR</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fonction :</span>
                    <span class="info-value"> <?= $emp['label'] ?? 'N/A' ?> </span>
                </div>
                <div class="info-item">
                    <span class="info-label">N° CNaPS :</span>
                    <span class="info-value">345670000</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date d'embauche :</span>
                    <span class="info-value highlight"><?= function_exists('formatDate') ? formatDate($emp['date_debut']) : ($emp['date_debut'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ancienneté :</span>
                    <span class="info-value"> <?= function_exists('calculerAnciennete') ? calculerAnciennete($emp['date_debut']) : 'N/A' ?></span>
                </div>
            </div>

            <div class="info-column">
                <h3><i class="fa-solid fa-money-check-dollar"></i> Informations Salariales</h3>
                <div class="info-item">
                    <span class="info-label">Salaire de base :</span>
                    <span class="info-value highlight"> <?= function_exists('moneyFormat') ? moneyFormat($emp['salaire']) : ($emp['salaire'] ?? '0 Ar') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Taux journaliers :</span>
                    <span class="info-value"> <?= function_exists('calculateTauxJournaliers') ? moneyFormat(calculateTauxJournaliers($emp['salaire'])) : 'N/A' ?> </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Taux horaires :</span>
                    <span class="info-value"> <?= function_exists('calculateTauxHoraire') ? moneyFormat(calculateTauxHoraire($emp['salaire'])) : 'N/A' ?> </span>
                </div>
                 <div class="info-item" style="margin-top: 15px;">
                    <span class="info-label"><strong>Total Avantages :</strong></span>
                    <span class="info-value highlight"><strong> <?= function_exists('moneyFormat') ? moneyFormat($emp['avantages'] ?? 0) : '0 Ar' ?></strong></span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Désignations</th>
                    <th style="width: 80px;">Nombre</th>
                    <th style="width: 120px;">Taux</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Salaire du 01/10/25 au 31/10/25</td>
                    <td>1 mois</td>
                    <td><?= function_exists('moneyFormat') ? moneyFormat($emp['salaire'] ?? 0) : '0,00' ?></td>
                    <td class="amount"><?= function_exists('moneyFormat') ? moneyFormat($emp['salaire']) : ($emp['salaire'] ?? '0,00') ?></td>
                </tr>

                <?php if (isset($emp['label_avantage']) && is_array($emp['label_avantage'])): ?>
                    <?php foreach ($emp['label_avantage'] as $av): ?>
                        <tr>
                            <td><?= $av['libelle'] ?></td>
                            <td>-</td>
                            <td>-</td>
                            <td class="amount"> <?= function_exists('moneyFormat') ? moneyFormat($av['montant']) : ($av['montant'] ?? '0,00') ?> </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <tr>
                    <td>Absences déductibles</td>
                    <td>0</td>
                    <td>-</td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Heures supplémentaires majorées de 30%</td>
                    <td>0h</td>
                    <td> <?= function_exists('calculMajorationHeureSup') ? moneyFormat(calculMajorationHeureSup(calculateTauxHoraire($emp['salaire'] ?? 0), 30)) : 'N/A' ?> </td>
                    <td class="amount">0,00</td>
                </tr>
                
                <tr>
                    <td>Primes diverses / Rappels</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="amount">-</td>
                </tr>
                
                <tr class="total-row">
                    <td colspan="3"><strong>Salaire brut</strong></td>
                    <td class="amount-highlight"> <?= function_exists('moneyFormat') ? moneyFormat($emp['salaire_brut']) : ($emp['salaire_brut'] ?? '0,00') ?> </td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th colspan="2">Retenues et Cotisations</th>
                    <th style="width: 100px;">Taux</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Retenue CNaPS (Salarié)</td>
                    <td>1%</td>
                    <td class="amount"> <?= function_exists('moneyFormat') ? moneyFormat($emp['cnaps_1']) : ($emp['cnaps_1'] ?? '0,00') ?></td>
                </tr>
                <tr>
                    <td colspan="2">Retenue Sanitaire (OSTIE)</td>
                    <td>1%</td>
                    <td class="amount"> <?= function_exists('moneyFormat') ? moneyFormat($emp['ostie_1']) : ($emp['ostie_1'] ?? '0,00') ?> </td>
                </tr>
                <tr class="section-header">
                    <td colspan="4">Impôt sur les Revenus Salariaux et Assimilés (IRSA)</td>
                </tr>

                <?php 
                $total_irsa = 0;
                if (isset($emp['irsa_details']) && is_array($emp['irsa_details'])) {
                    foreach ($emp['irsa_details'] as $t) {
                        $total_irsa += $t['montant'] ?? 0;
                        $money = function_exists('moneyFormat') ? 'moneyFormat' : function($v) { return number_format($v, 2, ',', ' '); };
                ?>
                    <tr>
                        <td colspan="2">
                            <?php if (($t['max'] ?? PHP_INT_MAX) === PHP_INT_MAX): ?>
                                Tranche IRSA PLUS DE <?= $money($t['min'] ?? 0) ?>
                            <?php elseif (($t['min'] ?? 0) == 0): ?>
                                Tranche IRSA INF <?= $money($t['max'] ?? 0) ?>
                            <?php else: ?>
                                Tranche IRSA DE <?= $money($t['min'] ?? 0) ?> à <?= $money($t['max'] ?? 0) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $t['taux'] ?? 0 ?> %</td>
                        <td class="amount"><?= $money($t['montant'] ?? 0) ?></td>
                    </tr>
                <?php }
                } ?>

                <tr class="total-row">
                    <td colspan="3"><strong>TOTAL IRSA</strong></td>
                    <td class="amount-highlight"><?= function_exists('moneyFormat') ? moneyFormat($emp['irsa']) : ($emp['irsa'] ?? '0,00') ?> </td>
                </tr>
                
                <tr class="total-row">
                    <td colspan="3"><strong>Total des retenues (Cotisations + IRSA)</strong></td>
                    <td class="amount-highlight"> 
                        <?= function_exists('moneyFormat') ? moneyFormat(($emp['total_ret'] ?? 0) + ($emp['irsa'] ?? 0)) : number_format((($emp['total_ret'] ?? 0) + ($emp['irsa'] ?? 0)), 2, ',', ' ') ?> 
                    </td>
                </tr>
                
                <tr>
                    <td colspan="3">Montant imposable avant abattement (Revenu imposable)</td>
                    <td class="amount"> <?= function_exists('moneyFormat') ? moneyFormat($emp['revenu_impo']) : ($emp['revenu_impo'] ?? '0,00') ?> </td>
                </tr>
                
                <tr class="final-total">
                    <td colspan="3"><strong>NET À PAYER</strong></td>
                    <td><strong> <?= function_exists('moneyFormat') ? moneyFormat($emp['salaire_net']) : ($emp['salaire_net'] ?? '0,00') ?> </strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer-info">
            <div class="footer-box">
                <h4>Signatures</h4>
                <div style="margin-top: 40px; display: flex; justify-content: space-between;">
                    <div style="text-align: center;">
                        <div style="border-top: 2px solid var(--border); padding-top: 5px; margin-top: 30px;">L'employeur</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="border-top: 2px solid var(--border); padding-top: 5px; margin-top: 30px;">L'employé(e)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>