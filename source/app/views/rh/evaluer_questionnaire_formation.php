<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Évaluation Formation - RH</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 25px; text-align: center; }
        .header h2 { margin: 0; font-size: 1.5rem; }
        .body { padding: 30px; }
        .info-box { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .info-box h3 { margin: 0 0 12px 0; color: #1e40af; }
        .info-box p { margin: 6px 0; color: #334155; }
        .section { margin-bottom: 30px; }
        .section h3 { color: #1e293b; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; }
        .question { background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 12px; }
        .question strong { color: #1e40af; }
        .question-answer { color: #475569; margin-top: 8px; padding: 10px; background: #fff; border-left: 3px solid #3b82f6; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: #334155; margin-bottom: 8px; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        textarea { resize: vertical; min-height: 80px; }
        .actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e2e8f0; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: #fff; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4); }
        .btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4); }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
        .note-display { font-size: 1.2rem; font-weight: 700; padding: 10px; background: #fef3c7; border-radius: 6px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2><i class="fas fa-clipboard-check"></i> Évaluation de Formation - RH</h2>
        <p>Analysez les réponses et attribuez une note finale (0-20)</p>
    </div>
    <div class="body">
        <?php if (!empty($_GET['success'])): ?>
            <div style="background: #dcfce7; border-left: 4px solid #22c55e; padding: 15px; border-radius: 8px; margin-bottom: 15px; color: #166534;">
                <i class="fas fa-check-circle"></i> <strong>Évaluation enregistrée !</strong><br>
                <small>La note a été attribuée et le candidat a été classé automatiquement.</small>
            </div>
        <?php endif; ?>
        
        <?php if (empty($questionnaire)): ?>
            <div style="background: #fee2e2; padding: 15px; border-radius: 8px; color: #991b1b; text-align: center; border-left: 4px solid #ef4444;">
                <i class="fas fa-exclamation-circle"></i> <strong>Erreur !</strong> Aucun questionnaire trouvé pour ce candidat.
            </div>
        <?php else: ?>
            <div class="info-box" style="background: #dbeafe; border-left-color: #3b82f6;">
                <h3 style="color: #1e40af;"><i class="fas fa-user"></i> Candidat</h3>
                <p><strong>Nom :</strong> <?= htmlspecialchars($candidat['prenom'] ?? '') ?> <?= htmlspecialchars($candidat['nom'] ?? '') ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($candidat['email'] ?? '—') ?></p>
                <p><strong>Annonce :</strong> <?= htmlspecialchars($candidat['annonce_titre'] ?? '—') ?></p>
            </div>

            <div class="section">
                <h3><i class="fas fa-list"></i> Réponses au Questionnaire</h3>
                
                <?php if (!empty($questionnaire['answers'])): ?>
                    <?php 
                    $answers = is_array($questionnaire['answers']) ? $questionnaire['answers'] : json_decode($questionnaire['answers'], true) ?? [];
                    ?>
                    
                    <?php if (!empty($answers['q_1'])): ?>
                        <div class="question">
                            <strong>Q1 : Le contenu correspond-il à vos attentes ?</strong>
                            <div class="question-answer"><?= htmlspecialchars($answers['q_1']) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($answers['q_2'])): ?>
                        <div class="question">
                            <strong>Q2 : Qualité pédagogique</strong>
                            <div class="question-answer"><?= htmlspecialchars($answers['q_2']) ?>/5</div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($answers['q_3'])): ?>
                        <div class="question">
                            <strong>Q3 : Objectifs atteints ?</strong>
                            <div class="question-answer"><?= htmlspecialchars($answers['q_3']) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($answers['q_4'])): ?>
                        <div class="question">
                            <strong>Q4 : Améliorations suggérées</strong>
                            <div class="question-answer"><?= htmlspecialchars($answers['q_4']) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($answers['q_5'])): ?>
                        <div class="question">
                            <strong>Q5 : Formation complémentaire ?</strong>
                            <div class="question-answer"><?= htmlspecialchars($answers['q_5']) ?></div>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                        <h4 style="color: #1e40af; margin-bottom: 12px;"><i class="fas fa-briefcase"></i> Évaluation par Poste</h4>
                        <?php 
                        $poste_answers = array_filter($answers, function($k) { return strpos($k, 'poste_') === 0; }, ARRAY_FILTER_USE_KEY);
                        if (!empty($poste_answers)):
                            foreach ($poste_answers as $key => $value):
                                $poste_num = explode('_', $key)[1] ?? '';
                                $poste_label = isset($postes[$poste_num - 1]) ? htmlspecialchars($postes[$poste_num - 1]['label']) : "Poste " . $poste_num;
                        ?>
                            <div class="question">
                                <strong><?= $poste_label ?></strong>
                                <div class="question-answer"><?= htmlspecialchars($value) ?></div>
                            </div>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <form action="/rh/competences/evaluer-questionnaire-submit" method="POST">
                <input type="hidden" name="id_candidature" value="<?= htmlspecialchars($id_candidature ?? '') ?>">

                <div class="section" style="background: #f0f9ff; border: 2px solid #3b82f6; border-radius: 8px; padding: 20px;">
                    <h3 style="color: #1e40af;"><i class="fas fa-star"></i> Attribution de la Note Finale</h3>

                    <div class="form-group">
                        <label for="note_rh" style="color: #1e40af; font-weight: 700;">Note (0 - 20) <span style="color: #ef4444;">*</span></label>
                        <input type="number" id="note_rh" name="note_rh" min="0" max="20" step="0.5" required placeholder="Ex: 15" style="font-size: 1.1rem; font-weight: 600;">
                        <div style="background: white; padding: 12px; border-radius: 6px; margin-top: 8px; border-left: 4px solid #3b82f6;">
                            <p style="margin: 0; color: #334155;"><strong>Système de notation :</strong></p>
                            <ul style="margin: 8px 0 0 0; padding-left: 20px; color: #475569; font-size: 0.9rem;">
                                <li><strong style="color: #22c55e;">> 10/20 :</strong> Candidat accepté ✓</li>
                                <li><strong style="color: #ef4444;">≤ 10/20 :</strong> Candidat rejeté ✗</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="observations_rh" style="color: #1e40af; font-weight: 600;">Observations / Commentaires</label>
                        <textarea id="observations_rh" name="observations_rh" placeholder="Justifiez votre évaluation..." style="min-height: 120px;"></textarea>
                    </div>
                </div>

                <div class="actions" style="gap: 15px;">
                    <a href="/rh/candidats-en-attente" class="btn btn-secondary" style="padding: 12px 24px;">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 700; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);" onclick="return confirm('Êtes-vous sûr de votre évaluation ? Cette action est définitive.')">
                        <i class="fas fa-check-circle"></i> Valider et Enregistrer
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
