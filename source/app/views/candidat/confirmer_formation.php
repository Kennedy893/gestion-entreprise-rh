<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation Formulaire Formation</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: #fff; max-width: 900px; width: 100%; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 25px; text-align: center; }
        .body { padding: 30px; }
        .info-box { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .info-box p { margin: 8px 0; color: #334155; }
        .field-row { margin-bottom: 15px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; }
        .field-row:last-child { border-bottom: none; }
        .field-label { font-weight: 600; color: #1e293b; display: block; margin-bottom: 4px; }
        .field-value { color: #475569; font-size: 0.95rem; }
        .actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px; padding-top: 20px; border-top: 2px solid #e2e8f0; }
        .btn { padding: 10px 20px; border-radius: 8px; border: 0; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4); }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2><i class="fas fa-check-double"></i> Confirmation du Formulaire de Formation</h2>
        <p>Veuillez vérifier les informations avant de continuer</p>
    </div>
    <div class="body">
        <?php if (empty($data)): ?>
            <div style="background: #fee2e2; padding: 15px; border-radius: 8px; color: #991b1b; text-align: center;">
                <i class="fas fa-exclamation-circle"></i> Aucune donnée à afficher. Veuillez remplir le formulaire d'abord.
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="/entretiens" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
        <?php else: ?>
            <div style="background: #dcfce7; border-left: 4px solid #22c55e; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #166534;">
                <i class="fas fa-check-circle"></i> <strong>Formulaire reçu et enregistré !</strong><br>
                <small>Vos données ont été sauvegardées. Veuillez maintenant compléter le questionnaire d'évaluation.</small>
            </div>
            <div class="field-row">
                <span class="field-label">Contenu de la formation</span>
                <span class="field-value"><?= htmlspecialchars($data['contenu_formation'] ?? '—') ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Durée (heures)</span>
                <span class="field-value"><?= htmlspecialchars($data['duree_heures'] ?? '—') ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Méthodologie</span>
                <span class="field-value"><?= htmlspecialchars($data['methodologie'] ?? '—') ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Formateur / Organisme</span>
                <span class="field-value"><?= htmlspecialchars($data['formateur'] ?? '—') ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Date de début</span>
                <span class="field-value"><?= htmlspecialchars($data['date_debut_formation'] ?? '—') ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Date de fin</span>
                <span class="field-value"><?= htmlspecialchars($data['date_fin_formation'] ?? '—') ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Résultats obtenus</span>
                <span class="field-value"><?= htmlspecialchars($data['resultats'] ?? '—') ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Observations personnelles</span>
                <span class="field-value"><?= htmlspecialchars($data['observations'] ?? '—') ?></span>
            </div>
            <div class="field-row">
                <span class="field-label">Certificat / Attestation</span>
                <span class="field-value"><?= htmlspecialchars($data['certificat'] ?? '—') ?></span>
            </div>
            
            <?php if (!empty($data['documents']) && is_array($data['documents'])): ?>
            <div class="field-row">
                <span class="field-label">Documents uploadés</span>
                <div class="field-value">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($data['documents'] as $doc): ?>
                            <li><?= htmlspecialchars($doc) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <div class="actions">
                <a href="/entretiens" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <form action="/candidat/questionnaire-start" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-primary" title="Répondre au questionnaire de formation">
                        <i class="fas fa-check-double"></i> Continuer vers questionnaire
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
