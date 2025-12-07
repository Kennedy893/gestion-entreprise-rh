<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?? 'Candidats en Attente de Formation' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 1.8rem; display: flex; align-items: center; gap: 10px; }
        .candidats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(500px, 1fr)); gap: 25px; margin-top: 20px; }
        .candidat-card { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; border-left: 4px solid #667eea; }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; display: flex; justify-content: space-between; align-items: flex-start; }
        .card-header h3 { margin: 0 0 5px 0; font-size: 1.3rem; }
        .card-body { padding: 25px; }
        .candidat-info { margin-bottom: 20px; }
        .candidat-info p { margin: 4px 0; color: #64748b; font-size: 0.9rem; display: flex; align-items: center; gap: 6px; }
        .competences-section { margin: 20px 0; }
        .competence-item { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin-bottom: 10px; }
        .comp-levels { display: flex; gap: 10px; flex-wrap: wrap; }
        .level-badge { padding: 4px 10px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; }
        .level-badge.requis { background: #dbeafe; color: #1e40af; }
        .level-badge.candidat { background: #e0f2fe; color: #075985; }
        .level-badge.ecart { background: #fee2e2; color: #991b1b; }
        .competence-detail { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 10px; }
        .detail-item { text-align: center; padding: 8px; background: white; border-radius: 6px; }
        .detail-label { font-size: 0.75rem; color: #64748b; display: block; margin-bottom: 4px; }
        .detail-value { font-size: 1.1rem; font-weight: 700; color: #1e293b; }
        .raison-section { background: #f1f5f9; padding: 12px; border-radius: 8px; margin: 15px 0; }
        .meta-info { padding-top: 12px; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; }
        .card-actions { padding: 15px 25px; background: #f8fafc; display: flex; gap: 10px; border-top: 1px solid #e2e8f0; }
        .status-badge { padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: white; }
        .btn { padding: 10px 18px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; text-decoration: none; }
        .btn-success { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4); }
        .btn-danger { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); }
        .btn-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4); }
        .btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .btn-warning:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4); }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
        .alert-success { background: #dcfce7; color: #166534; border-left: 4px solid #22c55e; }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .empty-state { text-align: center; padding: 80px 20px; color: #64748b; }
        .empty-state i { font-size: 4rem; color: #cbd5e1; margin-bottom: 20px; }
        .questionnaire-badge { background: #22c55e; color: white; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; margin-left: 8px; }
        @media (max-width: 768px) {
            .candidats-grid { grid-template-columns: 1fr; }
            .card-header { flex-direction: column; gap: 10px; }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="header">
                <h1><i class="fas fa-user-clock"></i> Candidats en Attente de Formation</h1>
                <p style="margin: 10px 0 0 0; opacity: 0.9;">Gérez les candidats qui ont été proposés pour une mise en formation</p>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php
                        $messages = [
                            'acceptee' => 'Formation acceptée avec succès',
                            'echouee' => 'Formation rejetée',
                            'en_formation' => 'Formation démarrée',
                            'mise_formation' => 'Candidat mis en formation avec succès'
                        ];
                        echo $messages[$_GET['success']] ?? 'Opération effectuée avec succès';
                    ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php
                        $errors = [
                            'not_found' => 'Candidat introuvable',
                            'update_failed' => 'Échec de la mise à jour',
                            'exception' => 'Une erreur est survenue',
                            'candidat_not_found' => 'Candidat introuvable',
                            'questionnaire_not_found' => 'Questionnaire introuvable',
                            'questionnaire_invalid' => 'Questionnaire invalide'
                        ];
                        echo $errors[$_GET['error']] ?? 'Une erreur est survenue';
                    ?>
                </div>
            <?php endif; ?>

            <?php 
            // Regrouper les candidats par id_candidature
            $candidatsGroupes = [];
            foreach ($candidats as $c) {
                $idCand = $c['id_candidature'];
                if (!isset($candidatsGroupes[$idCand])) {
                    $candidatsGroupes[$idCand] = [
                        'info' => $c,
                        'competences' => []
                    ];
                }
                if (!empty($c['competence_nom'])) {
                    $candidatsGroupes[$idCand]['competences'][] = $c;
                }
            }
            ?>

            <?php if (empty($candidatsGroupes)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h2>Aucun candidat en attente</h2>
                    <p>Les candidats proposés pour une formation apparaîtront ici</p>
                </div>
            <?php else: ?>
                <div class="candidats-grid">
                    <?php foreach ($candidatsGroupes as $candidat): 
                        $info = $candidat['info'];
                        $competences = $candidat['competences'];
                        
                        // Déterminer le statut global
                        $statut = $info['statut'] ?? 'proposee';
                        $statutLabels = [
                            'proposee' => ['icon' => 'clock', 'label' => 'Formation proposée'],
                            'acceptee' => ['icon' => 'check', 'label' => 'Formation acceptée'],
                            'en_formation' => ['icon' => 'graduation-cap', 'label' => 'En formation'],
                            'questionnaire_soumis' => ['icon' => 'file-alt', 'label' => 'Questionnaire Soumis'],
                            'terminee' => ['icon' => 'check-circle', 'label' => 'Formation terminée'],
                            'echouee' => ['icon' => 'times-circle', 'label' => 'Échouée']
                        ];
                        $statutInfo = $statutLabels[$statut] ?? ['icon' => 'question', 'label' => ucfirst($statut)];
                    ?>
                        <div class="candidat-card">
                            <div class="card-header">
                                <div>
                                    <h3>
                                        <?= htmlspecialchars($info['candidat_prenom'] . ' ' . $info['candidat_nom']) ?>
                                        <?php if ($statut === 'questionnaire_soumis' || $statut === 'terminee'): ?>
                                            <span class="questionnaire-badge">
                                                <i class="fas fa-check-circle"></i> Questionnaire reçu
                                            </span>
                                        <?php endif; ?>
                                    </h3>
                                    <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;">
                                        <i class="fas fa-envelope"></i> <?= htmlspecialchars($info['candidat_email']) ?>
                                    </p>
                                </div>
                                <span class="status-badge">
                                    <i class="fas fa-<?= $statutInfo['icon'] ?>"></i>
                                    <?= $statutInfo['label'] ?>
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="candidat-info">
                                    <p><i class="fas fa-briefcase"></i> <strong>Poste:</strong> <?= htmlspecialchars($info['poste_nom']) ?></p>
                                    <?php if (!empty($info['annonce_titre'])): ?>
                                        <p><i class="fas fa-file-alt"></i> <strong>Annonce:</strong> <?= htmlspecialchars($info['annonce_titre']) ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($competences)): ?>
                                    <div class="competences-section">
                                        <h4 style="color: #f59e0b; font-size: 1rem; margin-bottom: 12px;">
                                            <i class="fas fa-exclamation-triangle"></i> Compétences à améliorer
                                        </h4>
                                        <?php foreach ($competences as $comp): ?>
                                        <div class="competence-item">
                                            <div style="font-weight: 700; color: #78350f; margin-bottom: 8px; font-size: 1.05rem;">
                                                <?= htmlspecialchars($comp['competence_nom']) ?>
                                            </div>
                                            <div class="comp-levels">
                                                <span class="level-badge requis">Requis: <?= $comp['niveau_requis'] ?>/5</span>
                                                <span class="level-badge candidat">Candidat: <?= $comp['niveau_candidat'] ?>/5</span>
                                                <span class="level-badge ecart">Écart: -<?= $comp['niveau_requis'] - $comp['niveau_candidat'] ?></span>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>

                                        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-top: 15px;">
                                            <h4 style="margin: 0 0 10px 0; color: #334155; font-size: 0.95rem;">
                                                <i class="fas fa-graduation-cap"></i> Compétence principale
                                            </h4>
                                            <div class="competence-detail">
                                                <div class="detail-item">
                                                    <span class="detail-label">Niveau requis</span>
                                                    <span class="detail-value"><?= $competences[0]['niveau_requis'] ?? 'N/A' ?>/5</span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Niveau candidat</span>
                                                    <span class="detail-value"><?= $competences[0]['niveau_candidat'] ?? 'N/A' ?>/5</span>
                                                </div>
                                                <div class="detail-item">
                                                    <span class="detail-label">Écart</span>
                                                    <span class="detail-value" style="color: #ef4444;">
                                                        -<?= ($competences[0]['niveau_requis'] ?? 0) - ($competences[0]['niveau_candidat'] ?? 0) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($info['raison'])): ?>
                                <div class="raison-section">
                                    <strong>Raison:</strong>
                                    <p><?= nl2br(htmlspecialchars($info['raison'])) ?></p>
                                </div>
                                <?php endif; ?>

                                <div class="meta-info">
                                    <i class="fas fa-calendar"></i>
                                    Mise en formation le <?= date('d/m/Y', strtotime($info['date_mise_en_formation'])) ?>
                                </div>
                            </div>

                            <div class="card-actions">
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <?php if ($statut === 'proposee'): ?>
                                        <form action="/rh/competences/accepter-formation/<?= $info['id_mise_formation'] ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Accepter cette mise en formation ?')">
                                                <i class="fas fa-check"></i> Accepter
                                            </button>
                                        </form>
                                        <form action="/rh/competences/rejeter-formation/<?= $info['id_mise_formation'] ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Rejeter cette mise en formation ?')">
                                                <i class="fas fa-times"></i> Rejeter
                                            </button>
                                        </form>
                                    <?php elseif ($statut === 'acceptee'): ?>
                                        <div style="background: #dbeafe; color: #1e40af; padding: 10px 15px; border-radius: 6px; font-size: 0.9rem;">
                                            <i class="fas fa-info-circle"></i>
                                            En attente que le candidat accepte la formation
                                        </div>
                                        <form action="/rh/competences/demarrer-formation/<?= $info['id_mise_formation'] ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-play"></i> Démarrer la formation
                                            </button>
                                        </form>
                                    <?php elseif ($statut === 'en_formation'): ?>
                                        <a href="/candidat/formation/<?= $info['id_candidature'] ?>" class="btn btn-warning" style="text-decoration: none;">
                                            <i class="fas fa-file-alt"></i> Accéder au formulaire
                                        </a>
                                        <div style="margin-top: 8px; padding: 12px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px; width: 100%;">
                                            <p style="color: #92400e; margin: 0;">
                                                <i class="fas fa-clock"></i> <small>En attente du questionnaire du candidat...</small>
                                            </p>
                                        </div>
                                    <?php elseif ($statut === 'questionnaire_soumis'): ?>
                                        <a href="/rh/competences/evaluer-questionnaire/<?= $info['id_candidature'] ?>" 
                                           class="btn btn-warning">
                                            <i class="fas fa-star"></i> Évaluer la formation
                                        </a>
                                    <?php elseif ($statut === 'terminee'): ?>
                                        <div style="color: #166534; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-check-circle"></i> Formation terminée
                                            <?php if (!empty($info['evaluation_decision'])): ?>
                                                <span style="color: <?= $info['evaluation_decision'] === 'accepte' ? '#166534' : '#991b1b' ?>;">
                                                    (<?= $info['evaluation_decision'] === 'accepte' ? 'Accepté' : 'Rejeté' ?>)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif ($statut === 'echouee'): ?>
                                        <div style="background:#fee2e2;padding:10px 15px;border-radius:6px;color:#991b1b;display:flex;align-items:center;gap:8px;">
                                            <i class="fas fa-times-circle"></i>
                                            <span>Formation échouée</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>