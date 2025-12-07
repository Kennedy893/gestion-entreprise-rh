<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques - Compétences</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stats-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .stats-header h1 {
            margin: 0 0 5px 0;
            font-size: 2rem;
        }
        
        .stats-header p {
            margin: 0;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid #667eea;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .stat-card.formation {
            border-top-color: #f59e0b;
        }
        
        .stat-card.success {
            border-top-color: #10b981;
        }
        
        .stat-card.warning {
            border-top-color: #ef4444;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        .stat-card.formation .stat-number {
            color: #f59e0b;
        }
        
        .stat-card.success .stat-number {
            color: #10b981;
        }
        
        .stat-card.warning .stat-number {
            color: #ef4444;
        }
        
        .stat-label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 8px;
        }
        
        .stat-subtext {
            font-size: 0.85rem;
            color: #94a3b8;
        }
        
        .chart-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .chart-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
        }
        
        .chart-title i {
            color: #667eea;
        }
        
        .distribution-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .distribution-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .distribution-item.formation {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        
        .distribution-item.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .distribution-item.warning {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .distribution-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .distribution-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .table-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .data-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
        }
        
        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .badge-primary {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            display: inline-block;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
        }
        
        .empty-state i {
            font-size: 2.5rem;
            color: #cbd5e1;
            margin-bottom: 15px;
            display: block;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="stats-header">
                <h1><i class="fas fa-chart-pie"></i> Statistiques Compétences</h1>
                <p>Tableau de bord d'analyse du système de gestion des compétences</p>
            </div>

            <!-- Cartes statistiques principales -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['total_competences'] ?? 0 ?></div>
                    <div class="stat-label">Compétences</div>
                    <div class="stat-subtext">Définies dans le système</div>
                </div>

                <div class="stat-card formation">
                    <div class="stat-number"><?= $stats['formations_actives'] ?? 0 ?></div>
                    <div class="stat-label">Formations actives</div>
                    <div class="stat-subtext">En cours de suivi</div>
                </div>

                <div class="stat-card success">
                    <div class="stat-number"><?= $stats['formations_reussies'] ?? 0 ?></div>
                    <div class="stat-label">Formations réussies</div>
                    <div class="stat-subtext">Candidats acceptés</div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-number"><?= $stats['formations_echouees'] ?? 0 ?></div>
                    <div class="stat-label">Formations échouées</div>
                    <div class="stat-subtext">Candidats rejetés</div>
                </div>
            </div>

            <!-- Distribution des employés -->
            <div class="chart-section">
                <div class="chart-title">
                    <i class="fas fa-users"></i> Distribution des Employés
                </div>
                <div class="distribution-grid">
                    <div class="distribution-item">
                        <div class="distribution-number"><?= $stats['employes_actifs'] ?? 0 ?></div>
                        <div class="distribution-label">Actifs</div>
                    </div>
                    <div class="distribution-item formation">
                        <div class="distribution-number"><?= $stats['employes_formation'] ?? 0 ?></div>
                        <div class="distribution-label">En formation</div>
                    </div>
                    <div class="distribution-item warning">
                        <div class="distribution-number"><?= $stats['employes_licencies'] ?? 0 ?></div>
                        <div class="distribution-label">Licenciés</div>
                    </div>
                </div>
            </div>

            <!-- Statistiques candidats -->
            <div class="chart-section">
                <div class="chart-title">
                    <i class="fas fa-file-alt"></i> Candidatures Traitées
                </div>
                <div class="distribution-grid">
                    <div class="distribution-item success">
                        <div class="distribution-number"><?= $stats['candidats_acceptes'] ?? 0 ?></div>
                        <div class="distribution-label">Acceptés</div>
                    </div>
                    <div class="distribution-item formation">
                        <div class="distribution-number"><?= $stats['candidats_formation'] ?? 0 ?></div>
                        <div class="distribution-label">En formation</div>
                    </div>
                    <div class="distribution-item warning">
                        <div class="distribution-number"><?= $stats['candidats_rejetes'] ?? 0 ?></div>
                        <div class="distribution-label">Rejetés</div>
                    </div>
                </div>
            </div>

            <!-- Top 10 compétences -->
            <div class="table-section">
                <div class="chart-title">
                    <i class="fas fa-star"></i> Top 10 Compétences Requises
                </div>

                <?php if (empty($stats['top_competences'])): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Aucune donnée disponible</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Compétence</th>
                                <th>Catégorie</th>
                                <th>Postes</th>
                                <th>% Couverture</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['top_competences'] as $idx => $comp): ?>
                            <tr>
                                <td><strong>#<?= $idx + 1 ?></strong></td>
                                <td><?= htmlspecialchars($comp['nom'] ?? '') ?></td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?= htmlspecialchars($comp['categorie'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td><?= $comp['nb_postes'] ?? 0 ?></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="progress-bar" style="width: 80px;">
                                            <div class="progress-fill" style="width: <?= $comp['couverture_pct'] ?? 0 ?>%"></div>
                                        </div>
                                        <span><?= round($comp['couverture_pct'] ?? 0) ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Formations récentes -->
            <div class="table-section" style="margin-top: 30px;">
                <div class="chart-title">
                    <i class="fas fa-history"></i> Formations Récentes
                </div>

                <?php if (empty($stats['formations_recentes'])): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Aucune formation enregistrée</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Candidat</th>
                                <th>Date</th>
                                <th>Durée</th>
                                <th>Formateur</th>
                                <th>Note Formation</th>
                                <th>Note Compétence</th>
                                <th>Résultat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['formations_recentes'] as $form): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($form['candidat_nom'] ?? 'N/A') ?></strong></td>
                                <td><?= $form['date'] ? date('d/m/Y', strtotime($form['date'])) : 'N/A' ?></td>
                                <td><?= ($form['duree'] ?? 0) ?> h</td>
                                <td><?= htmlspecialchars($form['formateur'] ?? 'N/A') ?></td>
                                <td><?= $form['note_formation'] ? number_format($form['note_formation'], 1) . '/20' : '--' ?></td>
                                <td><?= $form['note_competence_finale'] ? number_format($form['note_competence_finale'], 1) . '/20' : '--' ?></td>
                                <td>
                                    <?php if (($form['decision'] ?? '') === 'accepte'): ?>
                                        <span class="badge badge-success">✓ Accepté</span>
                                    <?php elseif (($form['decision'] ?? '') === 'rejete'): ?>
                                        <span class="badge badge-danger">✗ Rejeté</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">⏳ En attente</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Employés avec performance faible -->
            <div class="table-section" style="margin-top: 30px;">
                <div class="chart-title">
                    <i class="fas fa-exclamation-triangle"></i> Employés - Attention Requise (< 25%)
                </div>

                <?php if (empty($stats['employes_faible_performance'])): ?>
                    <div class="empty-state">
                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                        <p style="color: #166534; font-weight: 600;">Tous les employés ont une bonne performance ✓</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Poste</th>
                                <th>Performance</th>
                                <th>Statut</th>
                                <th>Action requise</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['employes_faible_performance'] as $emp): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($emp['nom'] ?? 'N/A') ?></strong></td>
                                <td><?= htmlspecialchars($emp['poste'] ?? 'N/A') ?></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="progress-bar" style="width: 80px;">
                                            <div class="progress-fill" style="width: <?= $emp['score'] ?? 0 ?>%; background: <?= ($emp['score'] ?? 0) < 10 ? 'linear-gradient(90deg, #ef4444 0%, #dc2626 100%)' : 'linear-gradient(90deg, #f59e0b 0%, #d97706 100%)'; ?>"></div>
                                        </div>
                                        <span><strong><?= round($emp['score'] ?? 0) ?>%</strong></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if (($emp['score'] ?? 0) < 10): ?>
                                        <span class="badge badge-danger">Licenciement imminent</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Formation requise</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/rh/competences/detail-employe/<?= $emp['id'] ?>" style="color: #3b82f6; text-decoration: none; font-weight: 600;">
                                        Voir détails →
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
</body>
</html>