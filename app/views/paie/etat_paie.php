<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion de Paie</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .company-info h1 {
            color: #2d3748;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .company-info p {
            color: #718096;
            font-size: 14px;
        }

        .date-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f6f8fb 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }

        .stat-label {
            color: #718096;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            color: #2d3748;
            font-size: 24px;
            font-weight: 700;
        }

        .controls {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .filter-btn, .export-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-btn {
            background: white;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .filter-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .export-btn {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
        }

        .table-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1800px;
        }

        thead {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            color: white;
        }

        th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        tbody tr:hover {
            background-color: #f7fafc;
            transform: scale(1.001);
        }

        td {
            padding: 16px 15px;
            font-size: 14px;
            color: #4a5568;
            white-space: nowrap;
        }

        .employee-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }

        .employee-info {
            display: flex;
            flex-direction: column;
        }

        .employee-name {
            font-weight: 600;
            color: #2d3748;
        }

        .employee-position {
            font-size: 12px;
            color: #a0aec0;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge-warning {
            background: #feebc8;
            color: #744210;
        }

        .amount {
            font-weight: 600;
            color: #2d3748;
        }

        .amount-positive {
            color: #38a169;
        }

        .amount-negative {
            color: #e53e3e;
        }

        .download-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            background: #f7fafc;
        }

        .pagination-info {
            color: #718096;
            font-size: 14px;
        }

        .pagination-buttons {
            display: flex;
            gap: 10px;
        }

        .page-btn {
            padding: 8px 16px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            color: #4a5568;
        }

        .page-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .page-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .controls {
                flex-direction: column;
            }

            .search-box {
                width: 100%;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 12px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <div class="company-info">
                    <h1>📊 Système de Gestion de Paie</h1>
                    <p>Tableau de bord des salaires - Période courante</p>
                </div>
                <div class="date-badge">
                    📅 <?= formatDate($data['resume']['date_generation']) ?>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Employés</div>
                    <div class="stat-value"> <?= $data['resume']['total_employes'] ?> </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Masse Salariale Brute</div>
                    <div class="stat-value"> <?= moneyFormat($data['resume']['masse_brut']) ?> </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Net à Payer</div>
                    <div class="stat-value"> <?= moneyFormat($data['resume']['total_net']) ?> </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Charges Sociales</div>
                    <div class="stat-value"> <?= moneyFormat($data['resume']['charges_sociales']) ?> </div>
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
                            <th>Salaire Brut</th>
                            <th>CNAPS 1%</th>
                            <th>CNAPS 8%</th>
                            <th>OSTIE 1%</th>
                            <th>OSTIE 5%</th>
                            <th>Autres retenues</th>
                            <th>Total retenues</th>
                            <th>Rev. imposable</th>
                            <th>IRSA</th>
                            <th>Salaire Net</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($data['details'] as $d ) { ?>
                        <tr>
                            <td># <?= $d['id_employe'] ?> </td>
                            <td>
                                <div class="employee-cell">
                                    <div class="avatar">EMP</div>
                                    <div class="employee-info">
                                        <span class="employee-name"><?= $d['nom'] . ' ' . $d['prenom']  ?></span>
                                        <span class="employee-position"> <?= $d['label'] ?> </span>
                                    </div>
                                </div>
                            </td>
                            <td> <?= $d['date_debut'] ?> </td>
                            <td><span class="badge badge-success">0h</span></td>
                            <td class="amount"> <?= moneyFormat($d['salaire']) ?> </td>
                            <td class="amount"> <?= moneyFormat($d['avantages']) ?> </td>
                            <td class="amount amount-positive"> <?= moneyFormat($d['heure_sup']) ?> </td>
                            <td class="amount" style="font-weight: 700;"> <?= moneyFormat($d['salaire_brut']) ?> </td>
                            <td> <?= moneyFormat($d['cnaps_1']) ?> </td>
                            <td> <?= moneyFormat($d['cnaps_8']) ?> </td>
                            <td> <?= moneyFormat($d['ostie_1']) ?> </td>
                            <td> <?= moneyFormat($d['ostie_5']) ?> </td>
                            <td> <?= moneyFormat($d['autres_ret']) ?> </td>
                            <td class="amount amount-negative"> <?= moneyFormat($d['total_ret']) ?> </td>
                            <td class="amount"> <?= moneyFormat($d['revenu_impo']) ?> </td>
                            <td class="amount amount-negative"> <?= moneyFormat($d['irsa']) ?> </td>
                            <td class="amount" style="font-weight: 700; color: #38a169;"> <?= moneyFormat($d['salaire_net']) ?> </td>
                            <td>
                                <button class="download-btn">
                                    📄 Bulletin
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                        
                        
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="pagination-info">
                    Affichage 1-2 sur 2 employés
                </div>
                <div class="pagination-buttons">
                    <button class="page-btn">‹ Précédent</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">Suivant ›</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 