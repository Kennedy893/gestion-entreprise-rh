<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Paie - Entreprise</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
            gap: 15px;
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
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .title-section h1 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .title-section h2 {
            font-size: 18px;
            color: #1e40af;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
            padding: 25px;
            background: #f9fafb;
            border-radius: 10px;
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
            padding: 10px 0;
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
            font-weight: 500;
        }

        .highlight {
            background: #1e40af;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            color: white;
        }

        th {
            padding: 16px;
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
            padding: 14px 16px;
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
            font-size: 15px;
        }

        .final-total {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            color: white;
            font-size: 16px;
        }

        .amount {
            font-weight: 600;
            color: #1f2937;
        }

        .amount-highlight {
            color: #1e40af;
            font-weight: 700;
            font-size: 15px;
        }

        .footer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-top: 30px;
            padding: 25px;
            background: #f9fafb;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .footer-box {
            padding: 20px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .footer-box h4 {
            color: #1e40af;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .footer-item {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }

        .payment-mode {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #ecfdf5;
            border-radius: 8px;
            color: #047857;
            font-weight: 600;
            border: 1px solid #a7f3d0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 70px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .container {
                padding: 25px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 20px;
            }

            .footer-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 12px 10px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 15px;
            }

            .container {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .title-section h1 {
                font-size: 20px;
            }

            .title-section h2 {
                font-size: 16px;
            }

            th, td {
                padding: 10px 8px;
                font-size: 0.85rem;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 20px;
                border: none;
            }
        }

        .print-btn {
            position: fixed;
            top: 30px;
            right: 30px;
            padding: 12px 20px;
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php include("app/views/bar/sidebarEmp.php")?>

    <div class="main-content">
        <button class="print-btn" onclick="window.print()">🖨️ Imprimer</button>
        
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

            <!-- Le reste du contenu de la fiche de paie reste identique -->
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

                    <!-- ... Le reste du tableau ... -->
                </tbody>
            </table>

            <!-- Le reste du code HTML reste identique -->
        </div>
    </div>
</body>
</html>