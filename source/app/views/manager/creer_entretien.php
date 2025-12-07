<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_manager.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-calendar-plus"></i> Planifier l'entretien</h1>
                <a href="/manager/candidatures" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="entretien-layout">
                <!-- Informations candidat -->
                <div class="candidat-card">
                    <h2><i class="fas fa-user"></i> Candidat</h2>
                    <div class="candidat-info">
                        <div class="info-row">
                            <strong>Nom complet:</strong>
                            <span><?= htmlspecialchars($candidature['nom'] . ' ' . $candidature['prenom']) ?></span>
                        </div>
                        <div class="info-row">
                            <strong>Poste:</strong>
                            <span><?= htmlspecialchars($candidature['poste_nom']) ?></span>
                        </div>
                        <div class="info-row">
                            <strong>Email:</strong>
                            <span><?= htmlspecialchars($candidature['email']) ?></span>
                        </div>
                        
                        <div class="info-row">
                            <strong>Téléphone:</strong>
                            <span><?= htmlspecialchars($candidature['telephone']) ?></span>
                        </div>
                        <div class="info-row">
                            <strong>Expérience:</strong>
                            <span><?= $candidature['experience_annees'] ?> an(s)</span>
                        </div>
                    </div>

                    <div class="competences-block">
                        <h3>Compétences</h3>
                        <p><?= nl2br(htmlspecialchars($candidature['competences'])) ?></p>
                    </div>
                </div>

                <!-- Formulaire planification -->
                <div class="planning-card">
                    <h2><i class="fas fa-calendar-alt"></i> Date et lieu de l'entretien</h2>
                    
                    <form action="/manager/candidature/<?= $candidature['id'] ?>/entretien/creer" method="POST">
                        <div class="form-group">
                            <label for="date_entretien">Date et heure de l'entretien *</label>
                            <input type="datetime-local" 
                                   id="date_entretien" 
                                   name="date_entretien" 
                                   required
                                   min="<?= date('Y-m-d\TH:i') ?>"
                                   value="<?= $candidature['entretien_date'] ?? '' ?>">
                            <small class="help-text">
                                <i class="fas fa-info-circle"></i>
                                Choisissez une date et heure future
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="lieu">Lieu de l'entretien</label>
                            <input type="text" 
                                   id="lieu" 
                                   name="lieu" 
                                   placeholder="Ex: Salle de réunion A, Bureau RH, Visioconférence..."
                                   value="<?= $candidature['entretien_lieu'] ?? '' ?>">
                            <small class="help-text">
                                <i class="fas fa-map-marker-alt"></i>
                                Précisez le lieu exact de l'entretien
                            </small>
                        </div>

                        <div class="info-box">
                            <i class="fas fa-lightbulb"></i>
                            <div>
                                <strong>Note importante:</strong>
                                <p>Cette date ne sera visible par le candidat qu'après avoir cliqué sur "Publier" depuis la liste des candidatures.</p>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> 
                                <?= !empty($candidature['entretien_date']) ? 'Modifier' : 'Créer' ?> l'entretien
                            </button>
                            <a href="/manager/candidatures" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Annuler
                            </a>
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

        .candidat-card,
        .planning-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .candidat-card h2,
        .planning-card h2 {
            font-size: 1.3rem;
            color: #1e293b;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
        }

        .candidat-info {
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            margin-bottom: 8px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .info-row strong {
            color: #475569;
        }

        .info-row span {
            color: #1e293b;
            font-weight: 500;
        }

        .competences-block {
            padding: 20px;
            background: #eff6ff;
            border-radius: 10px;
            border-left: 4px solid #3b82f6;
        }

        .competences-block h3 {
            font-size: 1rem;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .competences-block p {
            color: #475569;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #1e293b;
            font-weight: 600;
            font-size: 1rem;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .help-text {
            display: block;
            margin-top: 8px;
            color: #64748b;
            font-size: 0.9rem;
        }

        .help-text i {
            margin-right: 5px;
            color: #3b82f6;
        }

        .info-box {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            display: flex;
            gap: 15px;
        }

        .info-box i {
            font-size: 1.5rem;
            color: #f59e0b;
            flex-shrink: 0;
        }

        .info-box strong {
            display: block;
            color: #92400e;
            margin-bottom: 5px;
        }

        .info-box p {
            color: #78350f;
            margin: 0;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid #f1f5f9;
        }

        @media (max-width: 1200px) {
            .entretien-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>