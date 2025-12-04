<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Matching Automatique - Compétences</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="/public/assets/css/progress-bar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .matching-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .matching-header h2 {
            margin: 0 0 8px 0;
            font-size: 1.6rem;
            font-weight: 700;
        }
        
        .matching-header p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #667eea;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 5px;
        }
        
        .table-container {
            overflow-x: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .matching-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .matching-table thead {
            background: linear-gradient(135deg, #f8f9fa 0%, #f3f4f6 100%);
            border-bottom: 3px solid #e2e8f0;
        }
        
        .matching-table th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 700;
            color: #334155;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .matching-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        
        .matching-table tbody tr {
            transition: background-color 0.2s;
        }
        
        .matching-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .candidate-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .candidate-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            flex-shrink: 0;
        }

        .candidate-info {
            flex: 1;
        }
        
        .candidate-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
        }
        
        .candidate-email {
            font-size: 0.8rem;
            color: #64748b;
            display: block;
            margin-top: 4px;
        }

        .position-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
            display: block;
        }

        .position-category {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 4px;
        }
        
        .score-container {
            text-align: center;
        }

        .score-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 70px;
            padding: 10px 14px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            margin-bottom: 8px;
        }
        
        .score-excellent {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .score-good {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .score-fair {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        
        .score-poor {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .competences-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 0.9rem;
        }

        .competence-match {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .competence-ok {
            color: #166534;
            font-weight: 600;
        }

        .competence-ko {
            color: #991b1b;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            white-space: nowrap;
        }

        .status-candidat {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .status-proposee {
            background: linear-gradient(135deg, #fef3c7 0%, #fcd34d 100%);
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        .status-acceptee {
            background: linear-gradient(135deg, #c7d2fe 0%, #a5b4fc 100%);
            color: #3730a3;
            border: 1px solid #818cf8;
        }

        .status-en-formation {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .status-accepte {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border: 1px solid #86efac;
        }

        .status-rejete {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .btn-mini {
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        
        .btn-view:hover {
            background: linear-gradient(135deg, #93c5fd 0%, #60a5fa 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-eval {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border: 1px solid #6ee7b7;
        }
        
        .btn-eval:hover {
            background: linear-gradient(135deg, #6ee7b7 0%, #34d399 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-details {
            background: linear-gradient(135deg, #fef3c7 0%, #fcd34d 100%);
            color: #92400e;
            border: 1px solid #f59e0b;
            cursor: pointer;
        }
        
        .btn-details:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .filter-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .tab-btn {
            padding: 10px 18px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            background: white;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .tab-btn.active {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .tab-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .tab-count {
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .tab-btn.active .tab-count {
            background: rgba(255,255,255,0.3);
        }

        .matching-detail {
            display: none;
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin-top: 12px;
            border-left: 4px solid #667eea;
        }
        
        .matching-detail.show {
            display: block;
        }

        .detail-title {
            font-weight: 700;
            color: #334155;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }
        
        .detail-table {
            width: 100%;
            font-size: 0.85rem;
            border-collapse: collapse;
        }
        
        .detail-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-table tr:last-child td {
            border-bottom: none;
        }
        
        .detail-table th {
            padding: 10px;
            background: white;
            font-weight: 700;
            color: #334155;
            text-align: left;
            font-size: 0.8rem;
            border-bottom: 2px solid #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .detail-competence {
            font-weight: 600;
            color: #1e293b;
        }

        .level-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 35px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .level-ok {
            background: #dcfce7;
            color: #166534;
        }

        .level-ko {
            background: #fee2e2;
            color: #991b1b;
        }

        .score-match {
            font-weight: 700;
            color: #667eea;
        }

        .empty-state {
            background: white;
            padding: 80px 20px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #cbd5e1;
            display: block;
            margin-bottom: 20px;
        }

        .empty-state h2 {
            color: #64748b;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .empty-state p {
            color: #94a3b8;
            font-size: 0.95rem;
        }

        @media (max-width: 1200px) {
            .matching-table {
                font-size: 0.85rem;
            }

            .matching-table th,
            .matching-table td {
                padding: 12px 10px;
            }
        }

        @media (max-width: 768px) {
            .matching-table {
                font-size: 0.75rem;
            }

            .matching-header {
                padding: 20px;
            }

            .matching-header h2 {
                font-size: 1.3rem;
            }

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-tabs {
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }

            .btn-mini {
                width: 100%;
                justify-content: center;
                padding: 6px 10px;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <!-- Header -->
            <div class="matching-header">
                <h2><i class="fas fa-robot"></i> Matching Automatique des Candidats</h2>
                <p>Comparaison intelligente des compétences vs. les exigences des postes</p>
            </div>

            <!-- Statistiques -->
            <?php 
                $total = count($matchings ?? []);
                $excellent = count(array_filter($matchings ?? [], fn($m) => ($m['score_pondere_pct'] ?? 0) >= 80));
                $bon = count(array_filter($matchings ?? [], fn($m) => ($m['score_pondere_pct'] ?? 0) >= 70 && ($m['score_pondere_pct'] ?? 0) < 80));
                $moyen = count(array_filter($matchings ?? [], fn($m) => ($m['score_pondere_pct'] ?? 0) >= 50 && ($m['score_pondere_pct'] ?? 0) < 70));
                $faible = count(array_filter($matchings ?? [], fn($m) => ($m['score_pondere_pct'] ?? 0) < 50));
            ?>
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-value"><?= $total ?></div>
                    <div class="stat-label">Total candidats</div>
                </div>
                <div class="stat-card" style="border-left-color: #10b981;">
                    <div class="stat-value" style="color: #10b981;"><?= $excellent ?></div>
                    <div class="stat-label">Excellent (≥ 80%)</div>
                </div>
                <div class="stat-card" style="border-left-color: #3b82f6;">
                    <div class="stat-value" style="color: #3b82f6;"><?= $bon ?></div>
                    <div class="stat-label">Bon (70-80%)</div>
                </div>
                <div class="stat-card" style="border-left-color: #f59e0b;">
                    <div class="stat-value" style="color: #f59e0b;"><?= $moyen ?></div>
                    <div class="stat-label">Moyen (50-70%)</div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="filter-tabs">
                <button class="tab-btn active" onclick="filterMatching('tous')">
                    <i class="fas fa-list"></i> Tous
                    <span class="tab-count"><?= $total ?></span>
                </button>
                <button class="tab-btn" onclick="filterMatching('excellent')">
                    <i class="fas fa-star"></i> Excellent (≥ 80%)
                    <span class="tab-count"><?= $excellent ?></span>
                </button>
                <button class="tab-btn" onclick="filterMatching('bon')">
                    <i class="fas fa-thumbs-up"></i> Bon (70-80%)
                    <span class="tab-count"><?= $bon ?></span>
                </button>
                <button class="tab-btn" onclick="filterMatching('moyen')">
                    <i class="fas fa-minus-circle"></i> Moyen (50-70%)
                    <span class="tab-count"><?= $moyen ?></span>
                </button>
                <button class="tab-btn" onclick="filterMatching('faible')">
                    <i class="fas fa-exclamation-circle"></i> Faible (< 50%)
                    <span class="tab-count"><?= $faible ?></span>
                </button>
            </div>

            <?php if (empty($matchings)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox empty-state-icon"></i>
                    <h2>Aucun matching disponible</h2>
                    <p>Les candidats qui ont passé l'entretien d'embauche apparaîtront ici avec leur score de matching</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="matching-table">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Candidat</th>
                                <th style="width: 15%;">Poste visé</th>
                                <th style="width: 15%;">Score de Matching</th>
                                <th style="width: 18%;">Compétences</th>
                                <th style="width: 15%;">Statut</th>
                                <th style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matchings as $m): 
                                $score = round($m['score_pondere_pct'] ?? 0, 1);
                                $conformes = $m['nb_competences_conformes'] ?? 0;
                                $totales = $m['nb_competences_requises'] ?? 0;
                                $lacunes = $totales - $conformes;
                            ?>
                            <tr data-score="<?= $score ?>" data-matching-id="<?= $m['id_candidature'] ?? '' ?>">
                                <!-- Candidat -->
                                <td>
                                    <div class="candidate-card">
                                        <div class="candidate-avatar">
                                            <?= strtoupper(substr($m['candidat_prenom'] ?? 'C', 0, 1)) ?>
                                        </div>
                                        <div class="candidate-info">
                                            <div class="candidate-name">
                                                <?= htmlspecialchars($m['candidat_prenom'] . ' ' . $m['candidat_nom']) ?>
                                            </div>
                                            <span class="candidate-email">
                                                <?= htmlspecialchars($m['candidat_email']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Poste -->
                                <td>
                                    <div class="position-name">
                                        <?= htmlspecialchars($m['poste_nom'] ?? 'N/A') ?>
                                    </div>
                                    <div class="position-category">
                                        <?= htmlspecialchars($m['categorie_libelle'] ?? 'N/A') ?>
                                    </div>
                                </td>

                                <!-- Score -->
                                <td>
                                    <div class="score-container">
                                        <span class="score-badge <?php
                                            if ($score >= 80) echo 'score-excellent';
                                            elseif ($score >= 70) echo 'score-good';
                                            elseif ($score >= 50) echo 'score-fair';
                                            else echo 'score-poor';
                                        ?>">
                                            <?= $score ?>%
                                        </span>
                                        <div style="background: #e2e8f0; height: 6px; border-radius: 3px; overflow: hidden; width: 100%;">
                                            <div style="height: 100%; background: linear-gradient(90deg, #ef4444 0%, #f97316 25%, #eab308 50%, #84cc16 75%, #22c55e 100%); width: <?= min(100, $score) ?>%; transition: width 0.5s ease;"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Compétences -->
                                <td>
                                    <div class="competences-info">
                                        <div class="competence-match">
                                            <i class="fas fa-check-circle" style="color: #10b981;"></i>
                                            <span class="competence-ok"><?= $conformes ?>/<?= $totales ?> conformes</span>
                                        </div>
                                        <?php if ($lacunes > 0): ?>
                                            <div class="competence-match">
                                                <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
                                                <span class="competence-ko"><?= $lacunes ?> lacunes</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- Statut -->
                                <td>
                                    <span class="status-badge status-<?= str_replace('_', '-', strtolower($m['statut_evaluation'] ?? 'candidat')) ?>">
                                        <i class="fas fa-circle-dot" style="font-size: 0.6rem; margin-right: 4px;"></i>
                                        <?php
                                            $status_labels = [
                                                'candidat' => 'Candidat',
                                                'proposee' => 'Formation proposée',
                                                'acceptee' => 'Formation acceptée',
                                                'en_formation' => 'En formation',
                                                'accepte' => 'Accepté',
                                                'rejete' => 'Rejeté'
                                            ];
                                            echo $status_labels[strtolower($m['statut_evaluation'] ?? 'candidat')] ?? htmlspecialchars($m['statut_evaluation'] ?? 'Inconnu');
                                        ?>
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="action-buttons">
                                        <a href="/rh/candidature/<?= $m['id_candidature'] ?>" class="btn-mini btn-view" title="Voir le profil complet">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        
                                        <?php if ($score >= 70 && ($m['statut_evaluation'] ?? '') !== 'accepte'): ?>
                                            <a href="/rh/entretien/<?= $m['entretien_id'] ?>/evaluer" class="btn-mini btn-eval" title="Évaluer le candidat">
                                                <i class="fas fa-star"></i> Évaluer
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($lacunes > 0): ?>
                                            <button class="btn-mini btn-details" onclick="showDetail(event)" title="Voir les compétences manquantes">
                                                <i class="fas fa-list"></i> Détails
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Détail des compétences (caché par défaut) -->
                                    <div class="matching-detail">
                                        <div class="detail-title">
                                            <i class="fas fa-bars"></i> Analyse détaillée des compétences
                                        </div>
                                        <table class="detail-table">
                                            <thead>
                                                <tr>
                                                    <th>Compétence</th>
                                                    <th style="width: 80px;">Requise</th>
                                                    <th style="width: 80px;">Candidat</th>
                                                    <th style="width: 80px;">Match %</th>
                                                    <th style="width: 60px;">Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $detail = json_decode($m['detail_matching'] ?? '[]', true) ?? [];
                                                if (empty($detail)): 
                                                ?>
                                                    <tr>
                                                        <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">
                                                            Aucune donnée disponible
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($detail as $d): ?>
                                                        <tr>
                                                            <td class="detail-competence">
                                                                <?= htmlspecialchars($d['competence_nom'] ?? 'N/A') ?>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <span class="level-badge level-ok">
                                                                    <?= intval($d['niveau_requis'] ?? 0) ?>/5
                                                                </span>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <span class="level-badge <?= ($d['niveau_candidat'] ?? 0) >= ($d['niveau_requis'] ?? 0) ? 'level-ok' : 'level-ko' ?>">
                                                                    <?= intval($d['niveau_candidat'] ?? 0) ?>/5
                                                                </span>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <span class="score-match">
                                                                    <?= round($d['score_pct'] ?? 0, 0) ?>%
                                                                </span>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <?php 
                                                                    $candidat_level = intval($d['niveau_candidat'] ?? 0);
                                                                    $required_level = intval($d['niveau_requis'] ?? 0);
                                                                    if ($candidat_level >= $required_level):
                                                                ?>
                                                                    <span style="color: #10b981; font-weight: 700;">
                                                                        <i class="fas fa-check"></i> OK
                                                                    </span>
                                                                <?php else: ?>
                                                                    <span style="color: #ef4444; font-weight: 700;">
                                                                        <i class="fas fa-times"></i> Manquant
                                                                    </span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function filterMatching(type) {
            // Mettre à jour les boutons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.closest('.tab-btn').classList.add('active');

            // Filtrer les lignes
            document.querySelectorAll('.matching-table tbody tr').forEach(row => {
                const score = parseFloat(row.dataset.score) || 0;
                let show = false;

                switch(type) {
                    case 'excellent':
                        show = score >= 80;
                        break;
                    case 'bon':
                        show = score >= 70 && score < 80;
                        break;
                    case 'moyen':
                        show = score >= 50 && score < 70;
                        break;
                    case 'faible':
                        show = score < 50;
                        break;
                    default:
                        show = true;
                }

                row.style.display = show ? '' : 'none';
            });
        }

        function showDetail(e) {
            e.preventDefault();
            const btn = e.target.closest('.btn-details');
            const row = btn.closest('tr');
            const detail = row.querySelector('.matching-detail');
            
            if (detail) {
                detail.classList.toggle('show');
                
                // Animer le bouton
                if (detail.classList.contains('show')) {
                    btn.innerHTML = '<i class="fas fa-chevron-up"></i> Masquer';
                } else {
                    btn.innerHTML = '<i class="fas fa-list"></i> Détails';
                }
            }
        }
    </script>
</body>
</html>
