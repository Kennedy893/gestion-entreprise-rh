<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Qualité Employés - Performance</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .performance-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .performance-header h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }
        
        .performance-header p {
            margin: 0;
            opacity: 0.9;
        }
        
        .employee-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid #667eea;
            transition: all 0.3s;
        }
        
        .employee-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .employee-header {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            align-items: start;
        }
        
        .employee-info h3 {
            margin: 0 0 8px 0;
            color: #1e293b;
            font-size: 1.3rem;
        }
        
        .employee-info p {
            margin: 4px 0;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .employee-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            font-size: 0.9rem;
        }
        
        .meta-item {
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 6px;
            color: #334155;
        }
        
        .meta-label {
            font-weight: 600;
            color: #64748b;
            display: block;
            font-size: 0.8rem;
            margin-bottom: 2px;
        }
        
        .meta-value {
            color: #1e293b;
            font-weight: 600;
        }
        
        .performance-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .performance-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .score-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .score-display {
            min-width: 80px;
            text-align: center;
        }
        
        .score-value {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .score-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
        }
        
        .progress-bar-container {
            flex: 1;
            background: #e2e8f0;
            height: 30px;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ef4444 0%, #f97316 25%, #eab308 50%, #84cc16 75%, #22c55e 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            transition: width 0.3s ease;
            min-width: 40px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 10px;
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
        
        .threshold-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 25px;
            padding: 12px;
            background: #f0f9ff;
            border-radius: 6px;
            border-left: 4px solid #0284c7;
        }
        
        .threshold-item {
            text-align: center;
            font-size: 0.85rem;
        }
        
        .threshold-item strong {
            display: block;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .threshold-item em {
            color: #64748b;
            font-style: normal;
        }
        
        .actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        
        .btn-view {
            background-color: #e0e7ff;
            color: #3730a3;
        }
        
        .btn-view:hover {
            background-color: #c7d2fe;
        }
        
        .btn-formation {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .btn-formation:hover {
            background-color: #fcd34d;
        }
        
        .btn-reintegrate {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .btn-reintegrate:hover {
            background-color: #bbf7d0;
        }
        
        .btn-terminate {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .btn-terminate:hover {
            background-color: #fca5a5;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .empty-state h2 {
            color: #64748b;
        }
        
        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 16px;
            border-radius: 20px;
            border: 2px solid #e2e8f0;
            background: white;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .filter-btn.active {
            border-color: #667eea;
            background-color: #667eea;
            color: white;
        }
        
        .filter-btn:hover {
            border-color: #667eea;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border-left: 4px solid #22c55e;
        }
        
        @media (max-width: 768px) {
            .employee-header {
                grid-template-columns: 1fr;
            }
            
            .employee-meta {
                grid-template-columns: 1fr;
            }
            
            .score-container {
                flex-direction: column;
            }
            
            .threshold-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="performance-header">
                <h1><i class="fas fa-chart-line"></i> Qualité Employés - Performance</h1>
                <p>Gérez la performance et les formations de vos employés</p>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php
                        $success_messages = [
                            'employe_reintegre' => 'Employé réintégré avec succès',
                            'employe_licencie' => 'Employé licencié',
                            'remise_formation' => 'Employé mis en formation',
                            'score_updated' => 'Score de performance mis à jour'
                        ];
                        echo $success_messages[$_GET['success']] ?? 'Opération effectuée avec succès';
                    ?>
                </div>
            <?php endif; ?>

            <!-- Filtres -->
            <div class="filter-bar">
                <button class="filter-btn active" onclick="filterEmployees('tous')">
                    <i class="fas fa-users"></i> Tous (<?= count($employes) ?>)
                </button>
                <button class="filter-btn" onclick="filterEmployees('actif')">
                    <i class="fas fa-check-circle"></i> Actifs
                </button>
                <button class="filter-btn" onclick="filterEmployees('suspendu_formation')">
                    <i class="fas fa-pause-circle"></i> En formation
                </button>
                <button class="filter-btn" onclick="filterEmployees('licencie')">
                    <i class="fas fa-times-circle"></i> Licenciés
                </button>
            </div>

            <!-- Threshold Info -->
            <div class="threshold-info">
                <div class="threshold-item">
                    <strong style="color: #ef4444;">< 10%</strong>
                    <em>Licenciement</em>
                </div>
                <div class="threshold-item">
                    <strong style="color: #f59e0b;">10 - 25%</strong>
                    <em>Mise en formation</em>
                </div>
                <div class="threshold-item">
                    <strong style="color: #22c55e;">≥ 25%</strong>
                    <em>Employé actif</em>
                </div>
            </div>

            <?php if (empty($employes)): ?>
                <div class="employee-card">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h2>Aucun employé à gérer</h2>
                        <p>Les employés apparaîtront ici une fois ajoutés</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($employes as $emp): ?>
                    <div class="employee-card" data-status="<?= htmlspecialchars($emp['statut_performance'] ?? 'actif') ?>" data-employe-id="<?= $emp['id_employe'] ?>">
                        <div class="employee-header">
                            <div class="employee-info">
                                <h3>
                                    <i class="fas fa-user"></i> 
                                    <?= htmlspecialchars(($emp['prenom_employe'] ?? 'N/A') . ' ' . ($emp['nom_employe'] ?? 'N/A')) ?>
                                </h3>
                                <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($emp['email_employe'] ?? 'N/A') ?></p>
                                <p><i class="fas fa-phone"></i> <?= htmlspecialchars($emp['contact'] ?? 'N/A') ?></p>
                            </div>
                            <div class="employee-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Poste</span>
                                    <span class="meta-value"><?= htmlspecialchars($emp['poste_nom'] ?? 'N/A') ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Département</span>
                                    <span class="meta-value"><?= htmlspecialchars($emp['nom_departement'] ?? 'N/A') ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Date embauche</span>
                                    <span class="meta-value">
                                        <?= $emp['date_embauche'] ? date('d/m/Y', strtotime($emp['date_embauche'])) : 'N/A' ?>
                                    </span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Statut</span>
                                    <span class="status-badge status-<?= str_replace('_', '-', $emp['statut_performance'] ?? 'actif') ?>" id="status_badge_<?= $emp['id_employe'] ?>">
                                        <?php
                                            $status_labels = [
                                                'actif' => 'Actif', 
                                                'suspendu_formation' => 'En formation', 
                                                'licencie' => 'Licencié'
                                            ];
                                            echo $status_labels[$emp['statut_performance'] ?? 'actif'] ?? 'Inconnu';
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="performance-section">
                            <div class="performance-title">
                                <i class="fas fa-chart-bar"></i> Performance Qualité
                            </div>

                            <?php 
                                $score = isset($emp['score_performance']) ? (float)$emp['score_performance'] : 50;
                            ?>

                            <div class="score-container">
                                <div class="score-display">
                                    <div class="score-value" id="score_display_<?= $emp['id_employe'] ?>"><?= round($score, 1) ?>%</div>
                                    <div class="score-label">Score</div>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: <?= min(100, $score) ?>%;" id="progress_bar_<?= $emp['id_employe'] ?>">
                                        <span><?= round($score, 0) ?>%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Modifier le score avec AJAX -->
                            <form onsubmit="return updateScoreAjax(event, <?= $emp['id_employe'] ?>)" style="display: flex; gap: 10px; margin-top: 12px;">
                                <input type="range" name="score_performance" min="0" max="100" step="1" value="<?= $score ?>" 
                                       style="flex: 1; height: 6px; border-radius: 3px; background: #e2e8f0; outline: none; -webkit-appearance: none;"
                                       id="score_input_<?= $emp['id_employe'] ?>"
                                       oninput="updateScoreDisplay(this, <?= $emp['id_employe'] ?>)">
                                <button type="submit" class="btn-action" style="background-color: #667eea; color: white;" id="save_btn_<?= $emp['id_employe'] ?>">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </form>

                            <!-- Actions conditionnelles -->
                            <div class="actions" id="actions_<?= $emp['id_employe'] ?>">
                                <a href="/rh/competences/detail-employe/<?= $emp['id_employe'] ?>" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> Voir détails
                                </a>

                                <?php if ($score < 25 && ($emp['statut_performance'] ?? 'actif') === 'actif'): ?>
                                    <form action="/rh/competences/remettre-formation/<?= $emp['id_employe'] ?>" method="POST" style="display: inline;">
                                        <input type="hidden" name="raison" value="Score de performance insuffisant (<?= round($score, 1) ?>%)">
                                        <input type="hidden" name="frais_formation" value="0">
                                        <button type="submit" class="btn-action btn-formation" onclick="return confirm('Mettre cet employé en formation?');">
                                            <i class="fas fa-graduation-cap"></i> Remettre en formation
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if (($emp['statut_performance'] ?? 'actif') === 'suspendu_formation'): ?>
                                    <form action="/rh/competences/reintegrer/<?= $emp['id_employe'] ?>" method="POST" style="display: inline;">
                                        <button type="submit" class="btn-action btn-reintegrate" onclick="return confirm('Réintégrer cet employé?');">
                                            <i class="fas fa-check-circle"></i> Réintégrer
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if ($score < 10 && ($emp['statut_performance'] ?? 'actif') !== 'licencie'): ?>
                                    <form action="/rh/competences/licencier/<?= $emp['id_employe'] ?>" method="POST" style="display: inline;">
                                        <input type="hidden" name="raison" value="Performance insuffisante (<?= round($score, 1) ?>%)">
                                        <button type="submit" class="btn-action btn-terminate" onclick="return confirm('Êtes-vous sûr de vouloir licencier cet employé?');">
                                            <i class="fas fa-times-circle"></i> Licencier
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Historique formations si applicable -->
                        <?php if (!empty($emp['formations'])): ?>
                            <div class="performance-section">
                                <div class="performance-title">
                                    <i class="fas fa-history"></i> Historique formations
                                </div>
                                <div style="background: #f8fafc; padding: 12px; border-radius: 6px;">
                                    <ul style="margin: 0; padding-left: 20px; color: #64748b; font-size: 0.9rem;">
                                        <?php foreach ($emp['formations'] as $form): ?>
                                            <li style="margin-bottom: 8px;">
                                                <strong><?= date('d/m/Y', strtotime($form['date_remise_formation'])) ?></strong> - 
                                                <?= htmlspecialchars($form['raison'] ?? 'Formation') ?> 
                                                <?php if ($form['frais_formation'] > 0): ?>
                                                    - Coût: <strong><?= number_format($form['frais_formation'] ?? 0, 0, ',', ' ') ?> Ar</strong>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function filterEmployees(status) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            document.querySelectorAll('.employee-card').forEach(card => {
                if (status === 'tous' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function updateScoreDisplay(input, employeId) {
            const parentCard = input.closest('.employee-card');
            const bar = parentCard.querySelector('.progress-bar');
            const scoreValue = parentCard.querySelector('.score-value');
            const scoreSpan = bar.querySelector('span');
            
            bar.style.width = input.value + '%';
            scoreValue.textContent = Math.round(input.value) + '%';
            scoreSpan.textContent = Math.round(input.value) + '%';
        }

        // FONCTION AJAX POUR MISE À JOUR SCORE
        function updateScoreAjax(event, employeId) {
            event.preventDefault();
            
            const input = document.getElementById('score_input_' + employeId);
            const saveBtn = document.getElementById('save_btn_' + employeId);
            const newScore = input.value;
            
            // Désactiver le bouton pendant l'envoi
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            
            // Envoi AJAX
            fetch('/rh/competences/update-score/' + employeId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'score_performance=' + newScore
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Afficher message de succès temporaire
                    saveBtn.innerHTML = '<i class="fas fa-check"></i> Enregistré !';
                    
                    // Recharger la page immédiatement pour appliquer tous les changements
                    setTimeout(() => {
                        window.location.href = '/rh/competences/competence-employes?success=score_updated';
                    }, 500);
                } else {
                    alert('Erreur lors de la mise à jour: ' + (data.error || 'Erreur inconnue'));
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save"></i> Mettre à jour';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur de connexion au serveur');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Mettre à jour';
            });
            
            return false;
        }
    </script>
</body>
</html>