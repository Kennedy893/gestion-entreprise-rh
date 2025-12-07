<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails candidature</title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1>Candidature de <?= htmlspecialchars($candidature['nom'] . ' ' . $candidature['prenom']) ?></h1>
                <a href="/rh/candidatures" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="detail-grid">
                <div class="main-details">
                    <!-- Informations personnelles -->
                    <div class="info-card">
                        <h2>Informations personnelles</h2>
                        <div class="info-grid">
                            
                            <div><strong>Email:</strong> <?= $candidature['email'] ?></div>
                            <div><strong>Téléphone:</strong> <?= $candidature['telephone'] ?></div>
                            <div><strong>Date naissance:</strong> <?= date('d/m/Y', strtotime($candidature['date_naissance'])) ?></div>
                            <div><strong>Adresse:</strong> <?= $candidature['adresse'] ?></div>
                        </div>
                    </div>

                    <!-- Formation -->
                    <div class="info-card">
                        <h2>Formation et expérience</h2>
                        <p><strong>Diplôme:</strong> <?= $candidature['dernier_diplome'] ?></p>
                        <p><strong>Établissement:</strong> <?= $candidature['etablissement'] ?></p>
                        <p><strong>Expérience:</strong> <?= $candidature['experience_annees'] ?> ans</p>
                        <p><strong>Langues:</strong> <?= $candidature['langue_parlee'] ?></p>
                    </div>

                    <!-- Qualifications -->
                    <div class="info-card">
                        <h2>Qualifications</h2>
                        <p><?= nl2br(htmlspecialchars($candidature['qualifications'])) ?></p>
                    </div>

                    <!-- Compétences -->
                    <div class="info-card">
                        <h2>Compétences</h2>
                        <p><?= nl2br(htmlspecialchars($candidature['competences'])) ?></p>
                    </div>
                </div>

                <!-- Sidebar avec documents et actions -->
                <div class="sidebar-details">
                    <div class="action-card">
                        <h3>Documents joints</h3>
                        <?php if (empty($documents)): ?>
                            <p style="color: #64748b; font-size: 0.9rem;">Aucun document</p>
                        <?php else: ?>
                            <?php foreach ($documents as $doc): ?>
                            <a href="<?= $doc['chemin_fichier'] ?>" target="_blank" class="doc-link">
                                <i class="fas fa-file-pdf"></i>
                                <?= ucfirst(str_replace('_', ' ', $doc['type_document'])) ?>
                            </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($candidature['statut'] === 'en_attente'): ?>
                    <div class="action-card highlight">
                        <h3><i class="fas fa-paper-plane"></i> Envoyer vers Manager</h3>
                        <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 15px;">
                            Sélectionnez un manager pour traiter cette candidature
                        </p>
                        
                        <form id="envoyerManagerForm">
                            <input type="hidden" name="candidature_id" value="<?= $candidature['id'] ?>">
                            <div class="form-group">
                                <label>Manager</label>
                                <select name="id_manager" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($managers as $manager): ?>
                                    <option value="<?= $manager['id'] ?>">
                                        <?= htmlspecialchars($manager['prenom'] . ' ' . $manager['nom']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-user-tie"></i> Envoyer au manager
                            </button>
                        </form>
                    </div>
                    <?php elseif ($candidature['statut'] === 'envoye_manager'): ?>
                    <div class="action-card success">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #22c55e; margin-bottom: 15px;"></i>
                        <h3>Candidature envoyée</h3>
                        <p style="color: #64748b;">Cette candidature a été transmise au manager</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .info-card h2 {
            font-size: 1.2rem;
            color: #1e293b;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }

        .info-grid {
            display: grid;
            gap: 12px;
        }

        .info-grid div {
            padding: 10px;
            background: #f8fafc;
            border-radius: 6px;
        }

        .sidebar-details {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .action-card h3 {
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 15px;
        }

        .action-card.highlight {
            border: 2px solid #3b82f6;
            background: #eff6ff;
        }

        .action-card.success {
            text-align: center;
            padding: 30px;
        }

        .doc-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #1e293b;
            transition: all 0.3s;
        }

        .doc-link:hover {
            background: #dbeafe;
            transform: translateX(5px);
        }

        .doc-link i {
            color: #ef4444;
            font-size: 1.3rem;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #1e293b;
        }

        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .form-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        @media (max-width: 1200px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.getElementById('envoyerManagerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('/rh/candidature/envoyer-manager', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Candidature envoyée au manager avec succès !');
                    window.location.reload();
                } else {
                    alert('Erreur lors de l\'envoi');
                }
            } catch (error) {
                alert('Erreur: ' + error.message);
            }
        });
    </script>
</body>
</html>