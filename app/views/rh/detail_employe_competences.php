<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail Employé - Compétences</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: start;
        }
        
        .header-info h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }
        
        .header-info p {
            margin: 4px 0;
            opacity: 0.9;
        }
        
        .header-action {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: white;
            color: #667eea;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background-color: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
        }
        
        .btn-secondary:hover {
            background-color: rgba(255,255,255,0.3);
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .card-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .card-title h2 {
            margin: 0;
            color: #1e293b;
            font-size: 1.2rem;
        }
        
        .card-title i {
            color: #667eea;
            font-size: 1.3rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #64748b;
        }
        
        .info-value {
            color: #1e293b;
            font-weight: 500;
        }
        
        .competence-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 4px solid #667eea;
        }
        
        .competence-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .competence-level {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .formation-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 4px solid #10b981;
        }
        
        .formation-date {
            font-weight: 600;
            color: #166534;
        }
        
        .formation-cost {
            background: #dcfce7;
            color: #166534;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .status-actif {
            background-color: #dcfce7;
            color: #166534;
            border: 2px solid #22c55e;
        }
        
        .status-suspendu-formation {
            background-color: #fef3c7;
            color: #92400e;
            border: 2px solid #fbbf24;
        }
        
        .status-licencie {
            background-color: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }
        
        .score-display {
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .score-value {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .score-label {
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 8px;
        }
        
        .progress-bar-container {
            background: #e2e8f0;
            height: 40px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ef4444 0%, #f97316 25%, #eab308 50%, #84cc16 75%, #22c55e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .section-empty {
            background: #f8fafc;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            color: #64748b;
        }
        
        .section-empty i {
            font-size: 2rem;
            color: #cbd5e1;
            margin-bottom: 10px;
        }
        
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }
        
        .action-btn {
            padding: 12px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }
        
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            
            .detail-header {
                flex-direction: column;
            }
            
            .header-action {
                width: 100%;
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper" style="max-width: 1200px;">
            <!-- Header -->
            <div class="detail-header">
                <div class="header-info">
                    <h1>
                        <i class="fas fa-user-circle"></i> 
                        <?= htmlspecialchars(($employe['prenom'] ?? 'N/A') . ' ' . ($employe['nom'] ?? 'N/A')) ?>
                    </h1>
                    <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($employe['email'] ?? 'N/A') ?></p>
                    <p><i class="fas fa-phone"></i> <?= htmlspecialchars($employe['contact'] ?? 'N/A') ?></p>
                    <p style="margin-top: 10px;">
                        <span class="status-badge status-<?= str_replace('_', '-', $employe['statut_employe'] ?? 'actif') ?>">
                            <?php
                                $status_labels = [
                                    'actif' => 'Actif', 
                                    'suspendu_formation' => 'En formation', 
                                    'licencie' => 'Licencié'
                                ];
                                echo $status_labels[$employe['statut_employe'] ?? 'actif'] ?? 'Inconnu';
                            ?>
                        </span>
                    </p>
                </div>
                <div class="header-action">
                    <a href="/rh/competences/competence-employes" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid-2">
                <!-- Colonne gauche: Performance et Infos -->
                <div>
                    <!-- Performance Score -->
                    <div class="card">
                        <div class="card-title">
                            <i class="fas fa-chart-line"></i>
                            <h2>Performance</h2>
                        </div>

                        <?php 
                            $score = isset($employe['score_performance']) ? (float)$employe['score_performance'] : 50;
                        ?>

                        <div class="score-display">
                            <div class="score-value"><?= round($score, 1) ?>%</div>
                            <div class="score-label">Score de qualité</div>
                        </div>

                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?= min(100, $score) ?>%;">
                                <span><?= round($score, 0) ?>%</span>
                            </div>
                        </div>

                        <div style="background: #f0f9ff; padding: 12px; border-radius: 6px; font-size: 0.85rem; color: #0284c7;">
                            <strong style="display: block; margin-bottom: 4px;">Seuils de décision:</strong>
                            < 10%: Licenciement | 10-25%: Formation | ≥ 25%: Actif
                        </div>
                    </div>

                    <!-- Informations Personnelles -->
                    <div class="card" style="margin-top: 20px;">
                        <div class="card-title">
                            <i class="fas fa-id-card"></i>
                            <h2>Informations</h2>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Poste actuel</span>
                            <span class="info-value"><?= htmlspecialchars($employe['poste_nom'] ?? 'N/A') ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Département</span>
                            <span class="info-value"><?= htmlspecialchars($employe['nom_departement'] ?? 'N/A') ?></span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Date d'embauche</span>
                            <span class="info-value">
                                <?= isset($employe['date_embauche']) ? date('d/m/Y', strtotime($employe['date_embauche'])) : 'N/A' ?>
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">CIN</span>
                            <span class="info-value"><?= htmlspecialchars($employe['cin'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite: Compétences et Formations -->
                <div>
                    <!-- Compétences -->
                    <div class="card">
                        <div class="card-title">
                            <i class="fas fa-graduation-cap"></i>
                            <h2>Compétences Acquises</h2>
                        </div>

                        <?php if (empty($competences)): ?>
                            <div class="section-empty">
                                <i class="fas fa-inbox"></i>
                                <p>Aucune compétence enregistrée</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($competences as $comp): ?>
                                <div class="competence-item">
                                    <div class="competence-name"><?= htmlspecialchars($comp['competence'] ?? $comp['competence_nom'] ?? 'N/A') ?></div>
                                    <span class="competence-level">
                                        Niveau <?= $comp['niveau_employe'] ?? $comp['niveau'] ?? 0 ?>/5
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Historique Formations -->
                    <div class="card" style="margin-top: 20px;">
                        <div class="card-title">
                            <i class="fas fa-book"></i>
                            <h2>Historique Formations</h2>
                        </div>

                        <?php if (empty($historique)): ?>
                            <div class="section-empty">
                                <i class="fas fa-inbox"></i>
                                <p>Aucune formation enregistrée</p>
                            </div>
                        <?php else: ?>
                            <div style="background: white;">
                                <?php 
                                $total_frais = 0;
                                foreach ($historique as $form): 
                                    $total_frais += $form['frais_formation'] ?? 0;
                                ?>
                                    <div class="formation-item">
                                        <div class="formation-date">
                                            <i class="fas fa-calendar"></i> 
                                            <?= date('d/m/Y', strtotime($form['date_formation'] ?? $form['date_remise_formation'] ?? 'now')) ?>
                                        </div>
                                        <p style="margin: 8px 0 0 0; color: #64748b; font-size: 0.9rem;">
                                            Raison: <?= htmlspecialchars($form['raison'] ?? $form['raison_formation'] ?? 'N/A') ?>
                                        </p>
                                        <?php if (isset($form['frais_formation']) && $form['frais_formation'] > 0): ?>
                                            <p style="margin: 4px 0; font-size: 0.9rem;">
                                                Frais: <span class="formation-cost">
                                                    <?= number_format($form['frais_formation'], 0, ',', ' ') ?> Ar
                                                </span>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                                
                                <?php if ($total_frais > 0): ?>
                                    <div style="background: #f0fdf4; padding: 12px; border-radius: 6px; margin-top: 12px; border-left: 4px solid #22c55e;">
                                        <strong style="color: #166534;">
                                            Frais totaux: <?= number_format($total_frais, 0, ',', ' ') ?> Ar
                                        </strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions section -->
            <div class="card" style="margin-top: 30px;">
                <div class="card-title">
                    <i class="fas fa-cogs"></i>
                    <h2>Actions</h2>
                </div>

                <div class="action-grid">
                    <a href="/rh/competences/competence-employes" class="action-btn" style="background: #e0e7ff; color: #3730a3;">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>

                    <?php if ($score < 25 && ($employe['statut_employe'] ?? 'actif') === 'actif'): ?>
                        <form action="/rh/competences/remettre-formation/<?= $employe['id'] ?>" method="POST" style="display: contents;">
                            <input type="hidden" name="raison" value="Score de performance insuffisant (<?= round($score, 1) ?>%)">
                            <input type="hidden" name="frais_formation" value="0">
                            <button type="submit" class="action-btn" style="background: #fef3c7; color: #92400e;" onclick="return confirm('Mettre cet employé en formation?');">
                                <i class="fas fa-graduation-cap"></i> Mettre en formation
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if (($employe['statut_employe'] ?? 'actif') === 'suspendu_formation'): ?>
                        <form action="/rh/competences/reintegrer/<?= $employe['id'] ?>" method="POST" style="display: contents;">
                            <button type="submit" class="action-btn" style="background: #dcfce7; color: #166534;" onclick="return confirm('Réintégrer cet employé?');">
                                <i class="fas fa-check-circle"></i> Réintégrer
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($score < 10 && ($employe['statut_employe'] ?? 'actif') !== 'licencie'): ?>
                        <form action="/rh/competences/licencier/<?= $employe['id'] ?>" method="POST" style="display: contents;">
                            <input type="hidden" name="raison" value="Performance insuffisante (<?= round($score, 1) ?>%)">
                            <button type="submit" class="action-btn" style="background: #fee2e2; color: #991b1b;" onclick="return confirm('Êtes-vous sûr de vouloir licencier cet employé?');">
                                <i class="fas fa-times-circle"></i> Licencier
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>