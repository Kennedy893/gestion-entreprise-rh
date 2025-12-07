<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?? 'Évaluation entretien' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .eval-container {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .candidat-info-card, .evaluation-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            margin-bottom: 10px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .competences-deficit {
            margin-top: 25px;
            padding: 20px;
            background: #fef3c7;
            border-radius: 10px;
            border-left: 4px solid #f59e0b;
        }

        .competences-deficit h3 {
            margin: 0 0 15px 0;
            color: #92400e;
            font-size: 1rem;
        }

        .competence-detail {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .comp-name {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 1.05rem;
        }

        .comp-stats {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
        }

        .stat-item {
            padding: 4px 8px;
            background: #f1f5f9;
            border-radius: 6px;
        }

        .stat-item.ecart {
            background: #fee2e2;
            color: #991b1b;
            font-weight: 700;
        }

        .note-group {
            margin-bottom: 25px;
        }

        .note-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }

        .note-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .note-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .recommendation-box {
            background: #eff6ff;
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .recommendation-box h3 {
            color: #1e40af;
            margin-bottom: 10px;
        }

        .note-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .note-section h2 {
            margin: 0 0 20px 0;
            color: #1e293b;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .note-display {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .note-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e40af;
        }

        .comments {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .comments h4 {
            margin: 0 0 10px 0;
            color: #334155;
            font-size: 0.95rem;
        }

        .comments p {
            color: #64748b;
            line-height: 1.6;
            margin: 8px 0;
        }

        .note-finale {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 25px;
        }

        .note-finale h2 {
            margin: 0 0 15px 0;
            color: #1e293b;
        }

        .stars {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }

        .stars i {
            font-size: 1.5rem;
            color: #fbbf24;
        }

        .decision-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .decision-section h2 {
            margin: 0 0 20px 0;
            color: #1e293b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group small {
            display: block;
            color: #64748b;
            margin-top: 5px;
            font-size: 0.85rem;
        }

        .radio-group {
            display: grid;
            gap: 12px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .radio-group label:hover {
            border-color: #667eea;
            background: #f8fafc;
        }

        .radio-group input[type="radio"] {
            margin-right: 12px;
            width: 20px;
            height: 20px;
        }

        .radio-group input[type="radio"]:checked + .radio-label {
            font-weight: 700;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            flex: 1;
        }

        .radio-label.accept {
            color: #166534;
        }

        .radio-label.formation {
            color: #92400e;
        }

        .radio-label.reject {
            color: #991b1b;
        }

        .contract-info {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
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
            text-decoration: none;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-lg {
            padding: 15px 30px;
            font-size: 1.05rem;
        }

        .alert-formation {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }

        .alert-formation.show {
            display: block;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 1200px) {
            .eval-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-star"></i> Évaluation Entretien RH - <?= htmlspecialchars($entretien['candidat_nom'] . ' ' . $entretien['candidat_prenom']) ?></h1>
                <a href="/rh/resultats-entretiens" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php 
            // Récupérer les compétences déficitaires pour ce candidat
            $db = Flight::db();
            $stmt = $db->prepare("SELECT * FROM v_candidature_competences_deficitaires WHERE id_candidature = ?");
            $stmt->execute([$entretien['id_candidature']]);
            $competencesDeficitaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <form action="/rh/entretien/<?= $entretien['id'] ?>/evaluer" method="POST" id="evaluation-form">
                <div class="eval-container">
                    <!-- Colonne gauche : Informations candidat -->
                    <div class="candidat-info-card">
                        <h2><i class="fas fa-user"></i> Candidat</h2>
                        <div class="info-item">
                            <strong>Nom:</strong>
                            <span><?= htmlspecialchars($entretien['candidat_nom'] . ' ' . $entretien['candidat_prenom']) ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong>
                            <span><?= htmlspecialchars($entretien['candidat_email']) ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Poste:</strong>
                            <span><?= htmlspecialchars($entretien['poste_nom']) ?></span>
                        </div>
                        
                        <!-- Note Manager -->
                        <div class="note-section" style="padding: 0; box-shadow: none; margin-top: 20px;">
                            <h2><i class="fas fa-user-tie"></i> Évaluation Manager</h2>
                            <div class="note-display">
                                <span class="note-value"><?= $entretien['note_manager'] ?>/20</span>
                            </div>
                            <div class="comments">
                                <h4><i class="fas fa-thumbs-up"></i> Qualités</h4>
                                <p><?= nl2br(htmlspecialchars($entretien['qualites_manager'])) ?></p>
                                <h4 style="margin-top: 15px;"><i class="fas fa-thumbs-down"></i> Défauts</h4>
                                <p><?= nl2br(htmlspecialchars($entretien['defauts_manager'])) ?></p>
                            </div>
                        </div>

                        <?php if (!empty($competencesDeficitaires)): ?>
                        <div class="competences-deficit">
                            <h3><i class="fas fa-exclamation-triangle"></i> Compétences à améliorer</h3>
                            <?php foreach ($competencesDeficitaires as $comp): ?>
                            <div class="competence-detail">
                                <div class="comp-name"><?= htmlspecialchars($comp['competence_nom']) ?></div>
                                <div class="comp-stats">
                                    <span class="stat-item">
                                        <strong>Requis:</strong> <?= $comp['niveau_requis'] ?>/5
                                    </span>
                                    <span class="stat-item">
                                        <strong>Candidat:</strong> <?= $comp['niveau_candidat'] ?>/5
                                    </span>
                                    <span class="stat-item ecart">
                                        <strong>Écart:</strong> -<?= $comp['ecart'] ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Colonne droite : Évaluation -->
                    <div class="evaluation-card">
                        <h2><i class="fas fa-clipboard-check"></i> Votre Évaluation (RH)</h2>
                        
                        <div class="note-group">
                            <label for="note_rh">Note RH sur 20 *</label>
                            <input type="number" id="note_rh" name="note_rh" 
                                   min="0" max="20" step="0.5" required 
                                   placeholder="Note sur 20">
                            <small>Évaluez le candidat globalement sur 20</small>
                        </div>

                        <div class="note-group">
                            <label for="note_competence">
                                Note Compétence sur 20 *
                                <small style="color: #f59e0b; font-weight: normal;">(Important pour la formation)</small>
                            </label>
                            <input type="number" id="note_competence" name="note_competence" 
                                   min="0" max="20" step="0.5" required
                                   placeholder="Note sur 20">
                            <small>Si < 10 mais note RH ≥ 10 → Mise en formation proposée automatiquement</small>
                        </div>

                        <div class="alert-formation" id="alert_formation">
                            <strong><i class="fas fa-exclamation-triangle"></i> Attention :</strong>
                            La note de compétence est inférieure à 10, mais la note RH est bonne.
                            Le candidat sera automatiquement proposé pour une formation.
                        </div>

                        <div class="form-group">
                            <label for="observation">Observations</label>
                            <textarea id="observation" name="observation" rows="5" placeholder="Vos observations sur le candidat..."></textarea>
                        </div>

                        <!-- Note finale -->
                        <div class="note-finale">
                            <h2><i class="fas fa-calculator"></i> Note finale</h2>
                            <div class="note-display">
                                <span class="note-value" id="note_finale">--/20</span>
                            </div>
                            <div class="stars" id="stars"></div>
                        </div>

                        <div class="recommendation-box">
                            <h3><i class="fas fa-lightbulb"></i> Logique automatique</h3>
                            <p id="recommendation">
                                • Si Note RH ≥ 10 ET Note Compétence < 10 → Mise en formation automatique<br>
                                • Si Note RH ≥ 10 ET Note Compétence ≥ 10 → Acceptation<br>
                                • Sinon → Rejet
                            </p>
                        </div>

                        <!-- Décision -->
                        <div class="decision-section">
                            <h2><i class="fas fa-gavel"></i> Décision</h2>
                            
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="decision" value="accepte" required>
                                    <span class="radio-label accept">
                                        <i class="fas fa-check-circle"></i> Accepter le candidat
                                    </span>
                                </label>
                                <label id="label_formation">
                                    <input type="radio" name="decision" value="formation" required>
                                    <span class="radio-label formation">
                                        <i class="fas fa-graduation-cap"></i> Mise en formation
                                    </span>
                                </label>
                                <label>
                                    <input type="radio" name="decision" value="rejete" required>
                                    <span class="radio-label reject">
                                        <i class="fas fa-times-circle"></i> Rejeter le candidat
                                    </span>
                                </label>
                            </div>

                            <div class="form-group" id="date_debut_group" style="display:none; margin-top: 20px;">
                                <label>Date de début souhaitée</label>
                                <input type="date" name="date_debut" min="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="contract-info" id="contract_info" style="display:none;">
                                <strong><i class="fas fa-file-contract"></i> Type de contrat accordé:</strong> 
                                <span id="type_contrat"></span>
                                <input type="hidden" name="type_contrat" id="type_contrat_input">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check"></i> Valider l'évaluation
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const noteManager = <?= $entretien['note_manager'] ?>;
        const demande_contrat_direct = <?= $entretien['demande_contrat_direct'] ?? 0 ?>;
        
        function updateEvaluation() {
            const noteRH = parseFloat(document.getElementById('note_rh').value) || 0;
            const noteCompetence = parseFloat(document.getElementById('note_competence').value) || 0;
            const noteFin = (noteManager + noteRH) / 2;
            
            // Afficher la note finale
            document.getElementById('note_finale').textContent = noteFin.toFixed(2) + '/20';
            
            // Étoiles
            const etoiles = Math.round(noteFin / 4);
            let starsHTML = '';
            for (let i = 0; i < 5; i++) {
                starsHTML += i < etoiles ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
            }
            document.getElementById('stars').innerHTML = starsHTML;
            
            // Logique auto mise en formation
            const alertFormation = document.getElementById('alert_formation');
            const labelFormation = document.getElementById('label_formation');
            
            if (noteRH >= 10 && noteCompetence < 10 && noteCompetence > 0) {
                // Proposer automatiquement la formation
                alertFormation.classList.add('show');
                labelFormation.style.borderColor = '#f59e0b';
                labelFormation.style.background = '#fef3c7';
                document.querySelector('input[value="formation"]').checked = true;
            } else {
                alertFormation.classList.remove('show');
                labelFormation.style.borderColor = '#e2e8f0';
                labelFormation.style.background = 'transparent';
            }
        }

        document.getElementById('note_rh').addEventListener('input', updateEvaluation);
        document.getElementById('note_competence').addEventListener('input', updateEvaluation);

        // Gestion des décisions
        document.querySelectorAll('input[name="decision"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const dateGroup = document.getElementById('date_debut_group');
                const contractInfo = document.getElementById('contract_info');
                const typeContratInput = document.getElementById('type_contrat_input');
                
                if (this.value === 'accepte') {
                    dateGroup.style.display = 'block';
                    contractInfo.style.display = 'block';
                    
                    const noteFin = parseFloat(document.getElementById('note_finale').textContent);
                    const typeContrat = (noteFin >= 18 && demande_contrat_direct) 
                        ? 'Contrat de Travail (CDT)' 
                        : 'Contrat d\'Essai (2-6 mois)';
                    
                    document.getElementById('type_contrat').textContent = typeContrat;
                    typeContratInput.value = typeContrat;
                } else {
                    dateGroup.style.display = 'none';
                    contractInfo.style.display = 'none';
                    typeContratInput.value = '';
                }
            });
        });

        // Initialiser l'évaluation
        updateEvaluation();
    </script>
</body>
</html>