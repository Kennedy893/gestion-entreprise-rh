<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
        }
        .info-box h3 {
            color: #1e40af;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .data-row {
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 3px solid #667eea;
        }
        .data-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
        }
        .data-value {
            color: #64748b;
            line-height: 1.6;
        }
        .actions {
            background: #f8fafc;
            padding: 25px 30px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            gap: 15px;
        }
        .btn {
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            justify-content: center;
            font-size: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            flex: 1;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }
        .btn-secondary:hover {
            background: #cbd5e1;
        }
        .alert {
            padding: 15px 20px;
            margin: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-file-alt"></i> Formulaire de Formation</h1>
            <p>Détails de votre formation complétée</p>
        </div>

        <div class="info-box">
            <h3><i class="fas fa-user"></i> Information Candidat</h3>
            <p><strong>Nom :</strong> <?= htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($candidature['email']) ?></p>
        </div>

        <?php if (!empty($formation_data['formulaire_id'])): ?>
            <div class="content">
                <h2 style="margin-bottom: 20px; color: #1e293b;">
                    <i class="fas fa-graduation-cap"></i> Détails de la Formation
                </h2>

                <div class="data-row">
                    <div class="data-label">Contenu de la formation</div>
                    <div class="data-value"><?= nl2br(htmlspecialchars($formation_data['contenu_formation'] ?? 'Non spécifié')) ?></div>
                </div>

                <div class="data-row">
                    <div class="data-label">Durée</div>
                    <div class="data-value"><?= htmlspecialchars($formation_data['duree_heures'] ?? '0') ?> heures</div>
                </div>

                <div class="data-row">
                    <div class="data-label">Méthodologie</div>
                    <div class="data-value"><?= htmlspecialchars($formation_data['methodes_enseignement'] ?? 'Non spécifié') ?></div>
                </div>

                <div class="data-row">
                    <div class="data-label">Formateur / Organisme</div>
                    <div class="data-value"><?= htmlspecialchars($formation_data['formateur'] ?? 'Non spécifié') ?></div>
                </div>

                <div class="data-row">
                    <div class="data-label">Période</div>
                    <div class="data-value">
                        Du <?= !empty($formation_data['date_debut']) ? date('d/m/Y', strtotime($formation_data['date_debut'])) : 'N/A' ?>
                        au <?= !empty($formation_data['date_fin']) ? date('d/m/Y', strtotime($formation_data['date_fin'])) : 'N/A' ?>
                    </div>
                </div>

                <div class="data-row">
                    <div class="data-label">Résultats obtenus</div>
                    <div class="data-value"><?= nl2br(htmlspecialchars($formation_data['evaluations_intermediaires'] ?? 'Non spécifié')) ?></div>
                </div>

                <?php if (!$formation_data['questionnaire_complete']): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Veuillez compléter le questionnaire ci-dessous pour finaliser votre formation.</span>
                    </div>
                <?php else: ?>
                    <div class="alert" style="background: #dcfce7; color: #166534; border-left: 4px solid #22c55e;">
                        <i class="fas fa-check-circle"></i>
                        <span>Questionnaire complété ! Le QCM sera disponible prochainement.</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="actions">
                <a href="/entretiens" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour aux entretiens
                </a>
                <?php if (!$formation_data['questionnaire_complete']): ?>
                    <a href="/candidat/questionnaire-formation/<?= $formation_data['formulaire_id'] ?>" class="btn btn-primary">
                        <i class="fas fa-clipboard-check"></i> Commencer le questionnaire
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="content">
                <div class="alert" style="background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Aucun formulaire de formation trouvé.</span>
                </div>
            </div>

            <div class="actions">
                <a href="/entretiens" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour aux entretiens
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>