<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Paie - IT University</title>
    <link rel="stylesheet" href="<?= constant('BASE_URL') ?>/public/assets/css/fiche_paie_web.css">

    <style>
        button {
            background-color: #2563eb;
            /* bleu vif */
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        }

        button:hover {
            background-color: #1e40af;
            /* bleu plus foncé au survol */
            box-shadow: 0 6px 12px rgba(30, 64, 175, 0.5);
        }

        button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.6);
        }
    </style>
</head>

<body>

    <button onclick="window.location.href=`<?= constant('BASE_URL') ?>/paie/fiche/export/<?= $emp['id_employe'] ?>`"> Exporter PDF </button>

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