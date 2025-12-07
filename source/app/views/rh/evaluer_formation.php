<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Évaluer Formation</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .eval-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .eval-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .eval-header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        
        .eval-candidate-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .candidate-avatar {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        .eval-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
        }
        
        .eval-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        
        .eval-section h3 {
            margin-top: 0;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-section-title {
            font-weight: 600;
            color: #334155;
            margin-top: 20px;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }
        
        .note-input-group {
            margin-bottom: 20px;
        }
        
        .note-input-group label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }
        
        .note-display {
            font-size: 1.4rem;
            color: #3b82f6;
            font-weight: 700;
        }
        
        .rating-stars {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }
        
        .rating-stars i {
            font-size: 1.5rem;
            color: #cbd5e1;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .rating-stars i.filled {
            color: #fbbf24;
        }
        
        .rating-stars i:hover,
        .rating-stars i.hover {
            color: #f59e0b;
        }
        
        .note-slider {
            width: 100%;
            height: 8px;
            border-radius: 5px;
            background: #e2e8f0;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }
        
        .note-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }
        
        .note-slider::-moz-range-thumb {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }
        
        .note-value-display {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
        }
        
        .decision-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .decision-btn {
            padding: 15px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
        }
        
        .decision-btn.accept {
            border-color: #10b981;
            color: #10b981;
            background: #f0fdf4;
        }
        
        .decision-btn.accept:hover {
            background: #10b981;
            color: white;
        }
        
        .decision-btn.accept.selected {
            background: #10b981;
            color: white;
        }
        
        .decision-btn.reject {
            border-color: #ef4444;
            color: #ef4444;
            background: #fef2f2;
        }
        
        .decision-btn.reject:hover {
            background: #ef4444;
            color: white;
        }
        
        .decision-btn.reject.selected {
            background: #ef4444;
            color: white;
        }
        
        .eval-details {
            background: #eff6ff;
            border: 2px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .eval-details h4 {
            margin-top: 0;
            color: #1e40af;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dbeafe;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #334155;
        }
        
        .detail-value {
            color: #64748b;
        }
        
        .final-note {
            font-size: 1.5rem;
            color: #1e40af;
            font-weight: 700;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            flex: 1;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
        }
        
        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper" style="max-width: 1200px;">
            <form action="/rh/competences/submit-evaluation-formation" method="POST">
                <input type="hidden" name="id_candidature" value="<?= $candidature['id'] ?>">
                <input type="hidden" name="id_formulaire" value="<?= $formulaire['id'] ?>">

                <div class="eval-container">
                    <!-- En-tête -->
                    <div class="eval-header">
                        <div>
                            <h1><i class="fas fa-star"></i> Évaluation de Formation</h1>
                            <p style="margin: 8px 0 0 0; opacity: 0.9;">
                                Évaluez la formation suivie par le candidat
                            </p>
                        </div>
                        <div class="eval-candidate-info">
                            <div>
                                <div style="font-size: 0.9rem; opacity: 0.9;">Candidat</div>
                                <div style="font-weight: 600; font-size: 1.1rem;">
                                    <?= htmlspecialchars($candidature['candidat_prenom'] . ' ' . $candidature['candidat_nom']) ?>
                                </div>
                            </div>
                            <div class="candidate-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu évaluation -->
                    <div class="eval-body">
                        <!-- Détails de la formation -->
                        <div>
                            <div class="eval-section">
                                <h3><i class="fas fa-info-circle"></i> Détails de la Formation</h3>
                                
                                <div class="detail-row">
                                    <span class="detail-label">Poste visé:</span>
                                    <span class="detail-value"><strong><?= htmlspecialchars($candidature['poste_nom']) ?></strong></span>
                                </div>
                                
                                <div class="detail-row">
                                    <span class="detail-label">Durée:</span>
                                    <span class="detail-value"><?= $formulaire['duree'] ?> heures</span>
                                </div>
                                
                                <div class="detail-row">
                                    <span class="detail-label">Méthodologie:</span>
                                    <span class="detail-value"><?= ucfirst(str_replace('_', ' ', $formulaire['methodologie'])) ?></span>
                                </div>
                                
                                <div class="detail-row">
                                    <span class="detail-label">Formateur:</span>
                                    <span class="detail-value"><?= htmlspecialchars($formulaire['formateur']) ?></span>
                                </div>
                                
                                <div class="detail-row">
                                    <span class="detail-label">Périodes:</span>
                                    <span class="detail-value">
                                        <?= date('d/m/Y', strtotime($formulaire['date_debut_formation'])) ?> 
                                        → 
                                        <?= date('d/m/Y', strtotime($formulaire['date_fin_formation'])) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="eval-section" style="border-left-color: #10b981; margin-top: 20px;">
                                <h3 style="color: #166534;"><i class="fas fa-list-check"></i> Contenu et Résultats</h3>
                                
                                <div class="form-section-title">Contenu suivi:</div>
                                <div style="background: white; padding: 12px; border-radius: 6px; border-left: 3px solid #10b981; color: #334155; line-height: 1.6;">
                                    <?= nl2br(htmlspecialchars($formulaire['contenu'])) ?>
                                </div>
                                
                                <div class="form-section-title" style="margin-top: 15px;">Résultats:</div>
                                <div style="background: white; padding: 12px; border-radius: 6px; border-left: 3px solid #10b981; color: #334155; line-height: 1.6;">
                                    <?= nl2br(htmlspecialchars($formulaire['resultats'])) ?>
                                </div>
                                
                                <?php if (!empty($formulaire['observations'])): ?>
                                    <div class="form-section-title" style="margin-top: 15px;">Observations du candidat:</div>
                                    <div style="background: white; padding: 12px; border-radius: 6px; border-left: 3px solid #8b5cf6; color: #334155; line-height: 1.6;">
                                        <?= nl2br(htmlspecialchars($formulaire['observations'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Formulaire d'évaluation -->
                        <div>
                            <div class="eval-section" style="border-left-color: #f59e0b;">
                                <h3 style="color: #92400e;"><i class="fas fa-pen-fancy"></i> Votre Évaluation RH</h3>

                                <div class="note-input-group">
                                    <label>
                                        Note de Formation <span style="color: #ef4444;">*</span>
                                        <span class="note-value-display" id="display_note_formation">--/20</span>
                                    </label>
                                    <input type="range" name="note_formation" id="note_formation" 
                                           min="0" max="20" step="0.5" value="10"
                                           class="note-slider"
                                           oninput="updateNoteFormation(this.value)">
                                    <div class="rating-stars" id="stars_formation"></div>
                                </div>

                                <div style="background: white; padding: 12px; border-radius: 6px; color: #64748b; font-size: 0.9rem; margin-bottom: 20px;">
                                    Évaluez la qualité de la formation suivie
                                </div>

                                <div class="note-input-group">
                                    <label>
                                        Note de Compétence <span style="color: #ef4444;">*</span>
                                        <span class="note-value-display" id="display_note_competence">--/20</span>
                                    </label>
                                    <input type="range" name="note_competence" id="note_competence" 
                                           min="0" max="20" step="0.5" value="10"
                                           class="note-slider"
                                           oninput="updateNoteCompetence(this.value)">
                                    <div class="rating-stars" id="stars_competence"></div>
                                </div>

                                <div style="background: white; padding: 12px; border-radius: 6px; color: #64748b; font-size: 0.9rem; margin-bottom: 20px;">
                                    Évaluez le niveau de compétence atteint par le candidat
                                </div>

                                <div class="form-group">
                                    <label for="observation" style="font-weight: 600;">Observations RH</label>
                                    <textarea name="observation" id="observation" placeholder="Vos observations sur la formation et le candidat..."></textarea>
                                </div>

                                <!-- Résumé -->
                                <div class="eval-details">
                                    <h4>Résumé d'Évaluation</h4>
                                    <div class="detail-row">
                                        <span class="detail-label">Moyenne:</span>
                                        <span class="detail-value final-note" id="moyenne_note">--/20</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Décision proposée:</span>
                                        <span class="detail-value" id="decision_proposee">
                                            <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-weight: 600;">En attente</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Sélection de la décision -->
                                <div class="form-section-title">Votre Décision:</div>
                                <div class="decision-buttons">
                                    <button type="button" class="decision-btn accept" onclick="selectDecision('accepte', this)">
                                        <i class="fas fa-check-circle"></i> Accepter
                                    </button>
                                    <button type="button" class="decision-btn reject" onclick="selectDecision('rejete', this)">
                                        <i class="fas fa-times-circle"></i> Rejeter
                                    </button>
                                </div>
                                <input type="hidden" name="decision" id="decision" value="">
                            </div>
                        </div>
                    </div>

                    <!-- Actions de formulaire -->
                    <div style="padding: 30px; border-top: 2px solid #e2e8f0; display: flex; gap: 12px;">
                        <a href="/rh/candidats-en-attente" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Valider l'Évaluation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function createStars(containerId, rating) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            const stars = Math.round(rating / 4);
            for (let i = 0; i < 5; i++) {
                const icon = document.createElement('i');
                icon.className = i < stars ? 'fas fa-star filled' : 'far fa-star';
                container.appendChild(icon);
            }
        }

        function updateNoteFormation(value) {
            document.getElementById('display_note_formation').textContent = value + '/20';
            createStars('stars_formation', value);
            updateMoyenne();
        }

        function updateNoteCompetence(value) {
            document.getElementById('display_note_competence').textContent = value + '/20';
            createStars('stars_competence', value);
            updateMoyenne();
        }

        function updateMoyenne() {
            const noteFormation = parseFloat(document.getElementById('note_formation').value) || 0;
            const noteCompetence = parseFloat(document.getElementById('note_competence').value) || 0;
            const moyenne = (noteFormation + noteCompetence) / 2;
            
            document.getElementById('moyenne_note').textContent = moyenne.toFixed(1) + '/20';
            
            const proposee = document.getElementById('decision_proposee');
            if (noteFormation >= 10 && noteCompetence >= 10) {
                proposee.innerHTML = '<span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 12px; font-weight: 600;">✓ Acceptation</span>';
            } else {
                proposee.innerHTML = '<span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 12px; font-weight: 600;">✗ Rejet</span>';
            }
        }

        function selectDecision(decision, btn) {
            document.querySelectorAll('.decision-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById('decision').value = decision;
        }

        // Initialiser
        updateNoteFormation(10);
        updateNoteCompetence(10);
    </script>
</body>
</html>
