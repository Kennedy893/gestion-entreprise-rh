<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Paie - IT University</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1e40af;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #059669, #047857);
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }

        .logo-text span {
            color: #1e40af;
        }

        .title-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .title-section h1 {
            font-size: 22px;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .title-section h2 {
            font-size: 18px;
            color: #1e40af;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .info-column h3 {
            font-size: 14px;
            color: #1e40af;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
        }

        .info-value {
            color: #1f2937;
        }

        .highlight {
            background: #1e40af;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        thead {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        th:last-child,
        td:last-child {
            text-align: right;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        td {
            padding: 12px 15px;
            color: #1f2937;
        }

        .section-header {
            background: #f3f4f6;
            font-weight: 600;
            color: #1e40af;
        }

        .total-row {
            background: #dbeafe;
            font-weight: 600;
            font-size: 16px;
        }

        .final-total {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            color: white;
            font-size: 18px;
        }

        .amount {
            font-weight: 600;
            color: #1f2937;
        }

        .amount-highlight {
            color: #1e40af;
            font-weight: 700;
            font-size: 16px;
        }

        .footer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .footer-box {
            padding: 15px;
            background: white;
            border-radius: 6px;
            border-left: 4px solid #1e40af;
        }

        .footer-box h4 {
            color: #1e40af;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .footer-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .payment-mode {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #ecfdf5;
            border-radius: 6px;
            color: #047857;
            font-weight: 600;
            border: 1px solid #a7f3d0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include("app/views/bar/sidebar.php")?>

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
                <div style="margin-top: 40px; display: flex; justify-content: space-between;">
                    <div style="text-align: center;">
                        <div style="border-top: 2px solid #ddd; padding-top: 5px; margin-top: 30px;">L'employeur</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="border-top: 2px solid #ddd; padding-top: 5px; margin-top: 30px;">L'employé(e)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>