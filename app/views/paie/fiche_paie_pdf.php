<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Paie - IT University</title>
    <style>
        /* Réglages globaux pour l’impression */
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10.5px;
            color: #111;
            background: white;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 15px 20px;
        }

        /* ======= En-tête ======= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #1e40af;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #059669, #047857);
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        }

        .logo-text {
            font-size: 13px;
            font-weight: bold;
            color: #1f2937;
            line-height: 1.1;
        }

        .logo-text span {
            color: #1e40af;
        }

        /* ======= Titre ======= */
        .title-section {
            text-align: center;
            margin-bottom: 10px;
        }

        .title-section h1 {
            font-size: 14px;
            margin-bottom: 2px;
            color: #1f2937;
        }

        .title-section h2 {
            font-size: 11px;
            color: #1e40af;
        }

        /* ======= Informations Employé ======= */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            background: #f9fafb;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px 15px;
            margin-bottom: 10px;
        }

        .info-column h3 {
            font-size: 10px;
            color: #1e40af;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 0.5px solid #ddd;
            padding: 3px 0;
        }

        .info-label {
            font-weight: bold;
            color: #444;
        }

        .info-value {
            color: #000;
        }

        .highlight {
            background: #1e40af;
            color: white;
            padding: 1px 4px;
            border-radius: 3px;
        }

        /* ======= Tableaux ======= */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        thead {
            background: #1e40af;
            color: white;
        }

        th,
        td {
            padding: 4px 6px;
            border: 0.5px solid #ccc;
            font-size: 9.5px;
        }

        th {
            text-align: left;
            font-weight: bold;
        }

        td.amount,
        td.amount-highlight {
            text-align: right;
            font-weight: 600;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        /* Sections et totaux */
        .section-header {
            background: #f3f4f6;
            color: #1e40af;
            font-weight: bold;
        }

        .total-row {
            background: #dbeafe;
            font-weight: bold;
        }

        .final-total {
            background: #1e40af;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        /* ======= Pied de page ======= */
        .footer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            border-top: 1.5px solid #cbd5e1;
            padding-top: 15px;
            margin-top: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .footer-box {
            background: #f3f4f6;
            border-left: 4px solid #2563eb;
            /* bleu un peu plus vif */
            padding: 15px 20px;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(37, 99, 235, 0.15);
            /* légère ombre */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80px;
        }

        .footer-box h4 {
            font-size: 12px;
            color: #2563eb;
            margin-bottom: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-signature {
            margin-top: 30px;
            width: 80%;
            border-top: 2px solid #94a3b8;
            /* ligne de signature */
        }

        .footer-signature-text {
            margin-top: 8px;
            font-size: 11px;
            color: #475569;
            font-weight: 600;
            text-align: center;
        }

        /* ======= Supprimer les ombres, marges inutiles ======= */
        .box-shadow,
        .shadow,
        .container,
        .header,
        .table {
            box-shadow: none !important;
        }

        /* ======= Impression (Dompdf) ======= */
        @media print {
            body {
                background: white !important;
            }
        }
    </style>
</head>

<body>
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
            <h2>ARRETE AU <?= formatDate(date('Y-m-d')) ?></h2>
        </div>

        <div class="info-grid">
            <div class="info-column">
                <h3>Informations Employé</h3>
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
                    <span class="info-value"> <?= $emp['label'] ?> </span>
                </div>
                <div class="info-item">
                    <span class="info-label">N° CNaPS :</span>
                    <span class="info-value">345670000</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date d'embauche :</span>
                    <span class="info-value highlight"><?= formatDate($emp['date_debut']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ancienneté :</span>
                    <span class="info-value"> <?= calculerAnciennete($emp['date_debut']) ?></span>
                </div>
            </div>

            <div class="info-column">
                <h3>Informations Salariales</h3>
                <div class="info-item">
                    <span class="info-label">Salaire de base :</span>
                    <span class="info-value highlight"> <?= moneyFormat($emp['salaire']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Taux journaliers :</span>
                    <span class="info-value"> <?= moneyFormat(calculateTauxJournaliers($emp['salaire'])) ?> </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Taux horaires :</span>
                    <span class="info-value"> <?= moneyFormat(calculateTauxHoraire($emp['salaire'])) ?> </span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Désignations</th>
                    <th>Nombre</th>
                    <th>Taux</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Salaire du 01/10/25 au 31/10/25</td>
                    <td>1 mois</td>
                    <td>260 000,00</td>
                    <td class="amount"><?= moneyFormat($emp['salaire']) ?></td>
                </tr>

                <?php foreach ($emp['label_avantage'] as $av) { ?>
                    <tr>
                        <td><?= $av['libelle'] ?></td>
                        <td></td>
                        <td></td>
                        <td class="amount"> <?= moneyFormat($av['montant']) ?> </td>
                    </tr>
                <?php } ?>

                <tr>
                    <td>Absences déductibles</td>
                    <td></td>
                    <td>260 000,00</td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Primes de rendement</td>
                    <td></td>
                    <td></td>
                    <td class="amount">-</td>
                </tr>
                <tr>
                    <td>Primes d'ancienneté</td>
                    <td></td>
                    <td></td>
                    <td class="amount">-</td>
                </tr>
                <tr>
                    <td>Heures supplémentaires majorées de 30%</td>
                    <td></td>
                    <td> <?= moneyFormat(calculMajorationHeureSup(calculateTauxHoraire($emp['salaire']), 30)) ?> </td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Heures supplémentaires majorées de 40%</td>
                    <td></td>
                    <td> <?= moneyFormat(calculMajorationHeureSup(calculateTauxHoraire($emp['salaire']), 40)) ?></td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Heures supplémentaires majorées de 50%</td>
                    <td></td>
                    <td> <?= moneyFormat(calculMajorationHeureSup(calculateTauxHoraire($emp['salaire']), 50)) ?> </td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Heures supplémentaires majorées de 100%</td>
                    <td></td>
                    <td> <?= moneyFormat(calculMajorationHeureSup(calculateTauxHoraire($emp['salaire']), 100)) ?> </td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Majoration pour heures de nuit</td>
                    <td></td>
                    <td>13 500,00</td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Primes diverses</td>
                    <td></td>
                    <td></td>
                    <td class="amount">-</td>
                </tr>
                <tr>
                    <td>Rappels sur période antérieure</td>
                    <td></td>
                    <td></td>
                    <td class="amount">-</td>
                </tr>
                <tr>
                    <td>Droits de congés</td>
                    <td></td>
                    <td>260 000,00</td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Droits de préavis</td>
                    <td></td>
                    <td>260 000,00</td>
                    <td class="amount">0,00</td>
                </tr>
                <tr>
                    <td>Indemnités de licenciement</td>
                    <td></td>
                    <td>260 000,00</td>
                    <td class="amount">0,00</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3"><strong>Salaire brut</strong></td>
                    <td class="amount-highlight"> <?= moneyFormat($emp['salaire_brut']) ?> </td>
                </tr>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th colspan="2">Retenues et Cotisations</th>
                    <th>Taux</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">Retenue CNaPS 1%</td>
                    <td></td>
                    <td class="amount"> <?= moneyFormat($emp['cnaps_1']) ?></td>
                </tr>
                <tr>
                    <td colspan="2">Retenue sanitaire</td>
                    <td></td>
                    <td class="amount"> <?= moneyFormat($emp['ostie_1']) ?> </td>
                </tr>
                <tr class="section-header">
                    <td colspan="4">Tranches IRSA</td>
                </tr>

                <?php foreach ($emp['irsa_details'] as $t): ?>
                    <tr>
                        <td colspan="2">
                            <?php if ($t['max'] === PHP_INT_MAX): ?>
                                Tranche IRSA PLUS DE <?= moneyFormat($t['min']) ?>
                            <?php elseif ($t['min'] == 0): ?>
                                Tranche IRSA INF <?= moneyFormat($t['max']) ?>
                            <?php else: ?>
                                Tranche IRSA DE <?= moneyFormat($t['min']) ?> à <?= moneyFormat($t['max']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $t['taux'] ?> %</td>
                        <td class="amount"><?= moneyFormat($t['montant']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr class="total-row">
                    <td colspan="3"><strong>TOTAL IRSA</strong></td>
                    <td class="amount-highlight"><?= moneyFormat($emp['irsa']) ?> </td>
                </tr>
                <tr class="total-row">
                    <td colspan="3"><strong>Total des retenues</strong></td>
                    <td class="amount-highlight"> <?= moneyFormat($emp['total_ret'] + $emp['irsa']) ?> </td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Montant imposable</td>
                    <td class="amount"> <?= moneyFormat($emp['revenu_impo']) ?> </td>
                </tr>
                <tr class="final-total">
                    <td colspan="3"><strong>Net à payer</strong></td>
                    <td><strong> <?= moneyFormat($emp['salaire_net']) ?> </strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer-info">
            <div class="footer-box">
                <h4>Signatures</h4>
                <div class="footer-signature"></div>
                <div class="footer-signature-text">L'employeur</div>
            </div>
            <div class="footer-box">
                <h4>&nbsp;</h4> <!-- Garde la hauteur uniforme -->
                <div class="footer-signature"></div>
                <div class="footer-signature-text">L'employé(e)</div>
            </div>
        </div>

    </div>
</body>

</html>