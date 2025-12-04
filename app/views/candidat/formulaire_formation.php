<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de Formation</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: white;
            max-width: 800px;
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .form-header h1 {
            margin: 0 0 10px 0;
            font-size: 1.8rem;
        }

        .form-header p {
            margin: 0;
            opacity: 0.9;
        }

        .form-body {
            padding: 40px;
        }

        .info-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .info-box h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
        }

        .info-box p {
            margin: 5px 0;
            color: #334155;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-group label span.required {
            color: #ef4444;
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
            transition: border-color 0.3s;
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
            min-height: 100px;
        }

        .form-group small {
            display: block;
            color: #64748b;
            margin-top: 5px;
            font-size: 0.85rem;
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
            text-decoration: none;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
            font-size: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .success-message {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #22c55e;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .form-body {
                padding: 25px;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1><i class="fas fa-graduation-cap"></i> Formulaire de Formation</h1>
            <p>Veuillez remplir ce formulaire après avoir terminé votre formation</p>
        </div>

        <div class="form-body">
            <?php if (isset($_GET['success'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>Formulaire envoyé avec succès ! Vous serez contacté pour l'évaluation.</span>
                </div>
            <?php endif; ?>

            <div class="info-box">
                <h3><i class="fas fa-user"></i> Information Candidat</h3>
                <p><strong>Nom:</strong> <?= htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($candidature['email']) ?></p>
                <?php if (!empty($mise_formation['competence_nom'])): ?>
                    <p><strong>Compétence à acquérir:</strong> <?= htmlspecialchars($mise_formation['competence_nom']) ?></p>
                <?php endif; ?>
            </div>

            <form action="/rh/competences/submit-formation" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_candidat_mise_formation" value="<?= $mise_formation['id'] ?>">
                <input type="hidden" name="id_candidature" value="<?= htmlspecialchars($candidature['id'] ?? '') ?>">

                <div class="form-group">
                    <label>
                        Contenu de la formation <span class="required">*</span>
                    </label>
                    <textarea name="contenu_formation" required placeholder="Décrivez le contenu de la formation suivie..."></textarea>
                    <small>Détaillez les sujets abordés, les modules suivis, etc.</small>
                </div>

                <div class="form-group">
                    <label>
                        Durée de la formation (en heures) <span class="required">*</span>
                    </label>
                    <input type="number" name="duree_heures" min="1" required placeholder="Ex: 40">
                </div>

                <div class="form-group">
                    <label>
                        Méthodologie <span class="required">*</span>
                    </label>
                    <select name="methodologie" required>
                        <option value="">Sélectionnez...</option>
                        <option value="presentiel">Présentiel</option>
                        <option value="en_ligne">En ligne</option>
                        <option value="hybride">Hybride</option>
                        <option value="autodidacte">Autodidacte</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Formateur / Organisme <span class="required">*</span>
                    </label>
                    <input type="text" name="formateur" required placeholder="Nom du formateur ou de l'organisme">
                </div>

                <div class="form-group">
                    <label>
                        Date de début <span class="required">*</span>
                    </label>
                    <input type="date" name="date_debut_formation" required max="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label>
                        Date de fin <span class="required">*</span>
                    </label>
                    <input type="date" name="date_fin_formation" required max="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label>
                        Résultats obtenus <span class="required">*</span>
                    </label>
                    <textarea name="resultats" required placeholder="Décrivez les compétences acquises et les résultats obtenus..."></textarea>
                    <small>Mentionnez les certificats obtenus, les projets réalisés, etc.</small>
                </div>

                <div class="form-group">
                    <label>
                        Observations personnelles
                    </label>
                    <textarea name="observations" placeholder="Vos commentaires sur la formation (optionnel)..."></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Certificat / Attestation
                    </label>
                    <select name="certificat">
                        <option value="">Aucun</option>
                        <option value="certificat">Certificat obtenu</option>
                        <option value="attestation">Attestation de participation</option>
                        <option value="diplome">Diplôme</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Documents à lire / Supports de formation
                    </label>
                    <input type="file" name="documents_formation[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip">
                    <small>Vous pouvez télécharger plusieurs fichiers (PDF, Word, Excel, PowerPoint, etc.)</small>
                </div>

                <div class="form-actions">
                    <a href="/entretiens" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Envoyer le formulaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>