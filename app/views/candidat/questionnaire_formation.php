<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title ?? 'Questionnaire de Formation') ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .container { background:#fff; width:100%; max-width:900px; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,0.15); overflow:hidden; }
        .header { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#fff; padding:20px; text-align:center; }
        .body { padding:28px; max-height: 80vh; overflow-y: auto; }
        .info-box { background:#eff6ff; border-left:4px solid #3b82f6; padding:15px; border-radius:8px; margin-bottom:15px; }
        .info-box p { margin:4px 0; color:#334155; font-size:0.9rem; }
        .form-group { margin-bottom:22px; }
        label { font-weight:600; display:block; margin-bottom:8px; color:#334155; }
        select, textarea { width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; box-sizing:border-box; }
        .qcm-options { margin-top: 8px; }
        .option { margin: 8px 0; display: flex; gap: 8px; align-items: flex-start; }
        .option input[type="radio"] { margin-top: 4px; }
        .option label { margin: 0; display: inline; font-weight: 400; }
        .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:25px; padding-top:20px; border-top:2px solid #e2e8f0; }
        .btn { padding:10px 16px; border-radius:8px; border:0; cursor:pointer; font-weight:600; }
        .btn-primary { background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#fff; }
        .btn-secondary { background:#eef2ff; color:#1e293b; }
        .debug-box { background:#fef3c7; border:2px solid #f59e0b; padding:12px; border-radius:6px; margin-bottom:15px; font-family:monospace; font-size:0.85rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2><i class="fas fa-question-circle"></i> Questionnaire d'Évaluation de Formation</h2>
        <p>Dernière étape : Évaluez votre expérience de formation</p>
    </div>
    <div class="body">
        <?php if (!empty($_GET['success'])): ?>
            <div style="background:#dcfce7;padding:12px;border-radius:6px;margin-bottom:12px;color:#166534;border-left:4px solid #22c55e;">
                <i class="fas fa-check-circle"></i> <strong>Questionnaire envoyé avec succès !</strong><br>
                <small>Votre évaluation a été transmise au service RH. Merci de votre participation.</small>
            </div>
        <?php endif; ?>

        <?php if (!empty($_GET['error'])): ?>
            <div style="background:#fee2e2;padding:12px;border-radius:6px;margin-bottom:12px;color:#991b1b;border-left:4px solid #ef4444;">
                <i class="fas fa-exclamation-circle"></i> <strong>Erreur !</strong> 
                <?php 
                    $errors = [
                        'questionnaire_no_id' => 'Identifiant candidature manquant',
                        'no_formation_data' => 'Données de formation introuvables',
                        'missing_id' => 'ID candidature manquant',
                        'directory_error' => 'Erreur de création du dossier',
                        'file_write_failed' => 'Échec de sauvegarde du fichier'
                    ];
                    echo $errors[$_GET['error']] ?? 'Une erreur est survenue';
                ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($data)): ?>
            <!-- DEBUG INFO (visible pour le développement) -->
            <?php if (!empty($data['id_candidature'])): ?>
                <div class="debug-box">
                    <strong>✓ DEBUG:</strong> ID Candidature chargé = <code><?= htmlspecialchars($data['id_candidature']) ?></code>
                </div>
            <?php else: ?>
                <div style="background:#fee2e2;border:2px solid #ef4444;padding:12px;border-radius:6px;margin-bottom:12px;">
                    <strong>⚠️ ERREUR CRITIQUE:</strong> id_candidature est VIDE dans $data!
                </div>
            <?php endif; ?>

            <div class="info-box">
                <p><strong>Formation complétée :</strong> <?= date('d/m/Y à H:i') ?></p>
                <p><strong>Formateur :</strong> <?= htmlspecialchars($data['formateur'] ?? '—') ?></p>
                <p><strong>Durée :</strong> <?= htmlspecialchars($data['duree_heures'] ?? '—') ?> heures</p>
                <p style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #bfdbfe;"><small><i class="fas fa-info-circle"></i> Vos réponses aideront le service RH à évaluer la qualité de cette formation.</small></p>
            </div>
        <?php else: ?>
            <div style="background:#fee2e2;padding:15px;border-radius:8px;color:#991b1b;text-align:center;">
                <i class="fas fa-exclamation-triangle"></i> <strong>Erreur :</strong> Aucune donnée de formation disponible. Veuillez recommencer depuis le formulaire de formation.
            </div>
            <div style="text-align:center;margin-top:15px;">
                <a href="/entretiens" class="btn btn-secondary">Retour aux entretiens</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($data) && !empty($data['id_candidature'])): ?>
        <form action="/candidat/questionnaire/submit" method="POST" id="questionnaireForm">
            <!-- HIDDEN FIELD - ID CANDIDATURE -->
            <input type="hidden" name="id_candidature" value="<?= htmlspecialchars($data['id_candidature']) ?>" id="hiddenIdCandidature">

            <div class="form-group">
                <label for="q_1">1) Le contenu de la formation correspond-il à vos attentes ?</label>
                <select name="q_1" id="q_1" required>
                    <option value="">Sélectionnez...</option>
                    <option value="oui">Oui</option>
                    <option value="partiellement">Partiellement</option>
                    <option value="non">Non</option>
                </select>
            </div>

            <div class="form-group">
                <label for="q_2">2) Évaluez la qualité pédagogique (1 = faible, 5 = excellent)</label>
                <select name="q_2" id="q_2" required>
                    <option value="">Sélectionnez...</option>
                    <option value="1">1 - Faible</option>
                    <option value="2">2 - Insuffisant</option>
                    <option value="3">3 - Moyen</option>
                    <option value="4">4 - Bon</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>

            <div class="form-group">
                <label for="q_3">3) Les objectifs de la formation ont-ils été atteints ?</label>
                <select name="q_3" id="q_3" required>
                    <option value="">Sélectionnez...</option>
                    <option value="oui">Oui</option>
                    <option value="partiellement">Partiellement</option>
                    <option value="non">Non</option>
                </select>
            </div>

            <div class="form-group">
                <label for="q_4">4) Que faudrait-il améliorer ? (Optionnel)</label>
                <textarea name="q_4" id="q_4" placeholder="Vos suggestions..." rows="3" style="width:100%;padding:10px;border:1px solid #e2e8f0;border-radius:6px;"></textarea>
            </div>

            <div class="form-group">
                <label for="q_5">5) Souhaitez-vous une formation complémentaire ?</label>
                <select name="q_5" id="q_5" required>
                    <option value="">Sélectionnez...</option>
                    <option value="oui">Oui</option>
                    <option value="non">Non</option>
                </select>
            </div>

            <hr style="margin: 25px 0; border: none; border-top: 2px solid #e2e8f0;">

            <h3 style="margin: 20px 0 15px 0; color: #1e293b;"><i class="fas fa-briefcase"></i> Évaluation par Poste</h3>
            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 15px;">Évaluez les compétences pour chaque poste qui vous concerne. Si vous n'êtes pas concerné(e), sélectionnez "Ne pas répondre".</p>

            <?php if (!empty($postes) && is_array($postes)): ?>
                <?php foreach ($postes as $index => $poste): ?>
                    <div class="form-group">
                        <label style="color: #1e40af; font-weight: 700;"><i class="fas fa-bookmark"></i> Poste: <?= htmlspecialchars($poste['label'] ?? 'Poste') ?></label>
                        <div class="qcm-options">
                            <div class="option">
                                <input type="radio" id="p<?= $index ?>_0" name="poste_<?= $index ?>" value="ne_pas_repondre" required>
                                <label for="p<?= $index ?>_0">Ne pas répondre (non concerné)</label>
                            </div>
                            <div class="option">
                                <input type="radio" id="p<?= $index ?>_1" name="poste_<?= $index ?>" value="tres_competent">
                                <label for="p<?= $index ?>_1">Très compétent(e) pour ce poste</label>
                            </div>
                            <div class="option">
                                <input type="radio" id="p<?= $index ?>_2" name="poste_<?= $index ?>" value="competent">
                                <label for="p<?= $index ?>_2">Compétent(e)</label>
                            </div>
                            <div class="option">
                                <input type="radio" id="p<?= $index ?>_3" name="poste_<?= $index ?>" value="partiellement">
                                <label for="p<?= $index ?>_3">Partiellement compétent(e)</label>
                            </div>
                            <div class="option">
                                <input type="radio" id="p<?= $index ?>_4" name="poste_<?= $index ?>" value="peu_competent">
                                <label for="p<?= $index ?>_4">Peu compétent(e)</label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="actions">
                <a href="/entretiens" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Envoyer le questionnaire
                </button>
            </div>
        </form>

        <script>
        // Vérification avant soumission
        document.getElementById('questionnaireForm').addEventListener('submit', function(e) {
            const idCandidature = document.getElementById('hiddenIdCandidature').value;
            console.log('Form submitting with id_candidature:', idCandidature);
            
            if (!idCandidature || idCandidature === '') {
                e.preventDefault();
                alert('ERREUR: ID Candidature manquant! Impossible de soumettre le formulaire.');
                return false;
            }
            
            // Log tous les champs pour debug
            const formData = new FormData(this);
            console.log('All form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
            
            return true;
        });
        </script>
        <?php endif; ?>
    </div>
</div>
</body>
</html>