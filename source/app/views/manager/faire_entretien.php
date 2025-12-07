<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Entretien - <?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_manager.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-user-check"></i> Entretien de <?= htmlspecialchars($entretien['candidat_nom'] . ' ' . $entretien['candidat_prenom']) ?></h1>
                <a href="/manager/entretiens" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="entretien-layout">
                <!-- Informations candidat -->
                <div class="candidat-info-card">
                    <h2><i class="fas fa-user"></i> Informations du candidat</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Poste:</strong>
                            <span><?= htmlspecialchars($entretien['poste_nom']) ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong>
                            <span><?= htmlspecialchars($entretien['candidat_email']) ?></span>
                        </div>
                    
                        <div class="info-item">
                            <strong>Téléphone:</strong>
                            <span><?= htmlspecialchars($entretien['telephone']) ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Expérience:</strong>
                            <span><?= $entretien['experience_annees'] ?> an(s)</span>
                        </div>
                    </div>

                    <div class="detail-block">
                        <h3>Qualifications</h3>
                        <p><?= nl2br(htmlspecialchars($entretien['qualifications'])) ?></p>
                    </div>

                    <div class="detail-block">
                        <h3>Compétences</h3>
                        <p><?= nl2br(htmlspecialchars($entretien['competences'])) ?></p>
                    </div>

                    <div class="entretien-meta">
                        <div><i class="fas fa-calendar"></i> <?= date('d/m/Y à H:i', strtotime($entretien['date_entretien'])) ?></div>
                        <?php if ($entretien['lieu']): ?>
                        <div><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($entretien['lieu']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Formulaire d'évaluation -->
                <div class="evaluation-card">
                    <h2><i class="fas fa-clipboard-check"></i> Évaluation de l'entretien</h2>
                    
                    <form action="/manager/entretien/<?= $entretien['id'] ?>/noter" method="POST" class="entretien-form">
                        <div class="note-group">
                            <label for="note_manager">Note sur 20 *</label>
                            <div class="note-input-wrapper">
                                <input type="number" id="note_manager" name="note_manager" 
                                       min="0" max="20" step="0.5" required>
                                <span class="note-suffix">/ 20</span>
                            </div>
                            <small class="note-hint">
                                Évaluez les compétences techniques, l'attitude, la motivation, etc.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="qualites">Points forts / Qualités *</label>
                            <textarea id="qualites" name="qualites" rows="5" required
                                      placeholder="Décrivez les qualités remarquées lors de l'entretien..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="defauts">Points d'amélioration / Faiblesses *</label>
                            <textarea id="defauts" name="defauts" rows="5" required
                                      placeholder="Notez les points d'amélioration ou faiblesses constatées..."></textarea>
                        </div>

                        <div class="recommendation-box">
                            <h3><i class="fas fa-lightbulb"></i> Recommandation automatique</h3>
                            <p id="recommendation">Saisissez une note pour voir la recommandation</p>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check"></i> Valider et envoyer à RH
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .entretien-layout {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 30px;
        }

        .candidat-info-card,
        .evaluation-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .candidat-info-card h2,
        .evaluation-card h2 {
            font-size: 1.3rem;
            color: #1e293b;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .detail-block {
            margin: 20px 0;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }

        .detail-block h3 {
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .entretien-meta {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 10px;
            color: #64748b;
        }

        .note-group {
            margin-bottom: 30px;
        }

        .note-input-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .note-input-wrapper input {
            flex: 0 0 150px;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1.3rem;
            font-weight: 600;
            text-align: center;
        }

        .note-suffix {
            font-size: 1.2rem;
            color: #64748b;
            font-weight: 600;
        }

        .note-hint {
            display: block;
            margin-top: 8px;
            color: #64748b;
            font-size: 0.9rem;
        }

        .recommendation-box {
            background: #eff6ff;
            border: 2px solid #2563eb;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .recommendation-box h3 {
            font-size: 1.1rem;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .recommendation-box p {
            color: #1e293b;
            line-height: 1.6;
        }

        @media (max-width: 1200px) {
            .entretien-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.getElementById('note_manager').addEventListener('input', function() {
            const note = parseFloat(this.value) || 0;
            const recBox = document.getElementById('recommendation');
            
            if (note >= 16) {
                recBox.innerHTML = '<strong style="color: #16a34a;">Excellent candidat</strong> - Hautement recommandé pour le poste. Profil très solide.';
                recBox.style.background = '#dcfce7';
                recBox.style.borderColor = '#16a34a';
            } else if (note >= 12) {
                recBox.innerHTML = '<strong style="color: #0ea5e9;">Bon candidat</strong> - Profil intéressant avec un potentiel à confirmer.';
                recBox.style.background = '#e0f2fe';
                recBox.style.borderColor = '#0ea5e9';
            } else if (note >= 8) {
                recBox.innerHTML = '<strong style="color: #f59e0b;">Candidat moyen</strong> - Nécessite réflexion approfondie.';
                recBox.style.background = '#fef3c7';
                recBox.style.borderColor = '#f59e0b';
            } else {
                recBox.innerHTML = '<strong style="color: #dc2626;">Candidat faible</strong> - Ne répond pas aux exigences du poste.';
                recBox.style.background = '#fee2e2';
                recBox.style.borderColor = '#dc2626';
            }
        });
    </script>
</body>
</html>