<?php
// Fichier : app/views/public/depot_dossier.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Dépôt de candidature' ?></title>
    <link rel="stylesheet" href="/public/assets/css/public.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="public-header">
        <h1><i class="fas fa-building"></i> Système de Recrutement</h1>
        <a href="/" class="btn-back"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
    </div>

    <div class="depot-container">
        <!-- Liste des annonces à gauche -->
        <div class="annonces-panel">
            <h2><i class="fas fa-clipboard-list"></i> Offres d'emploi disponibles</h2>
            
            <?php if (empty($annonces)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Aucune offre disponible pour le moment</p>
            </div>
            <?php else: ?>
            <div class="annonces-list">
                <?php foreach ($annonces as $annonce): ?>
                <div class="annonce-card" onclick="selectAnnonce(<?= $annonce['id'] ?>)" id="annonce-<?= $annonce['id'] ?>">
                    <h3><?= htmlspecialchars($annonce['titre']) ?></h3>
                    <div class="annonce-meta">
                        <span class="badge"><?= htmlspecialchars($annonce['type_contrat_nom']) ?></span>
                        <span class="badge"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
                    </div>
                    <p class="annonce-desc"><?= substr(htmlspecialchars($annonce['description']), 0, 150) ?>...</p>
                    <div class="annonce-footer">
                        <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($annonce['departement_nom']) ?></span>
                        <span><i class="fas fa-clock"></i> <?= $annonce['experience_min'] ?> an(s)</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Formulaire à droite -->
        <div class="formulaire-panel">
            <h2><i class="fas fa-user-plus"></i> Formulaire de candidature</h2>
            
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Votre candidature a été soumise avec succès !
            </div>
            <?php endif; ?>

            <form action="/depot-dossier/submit" method="POST" enctype="multipart/form-data" id="candidatureForm">
                <input type="hidden" name="id_annonce" id="id_annonce" required>

                <div class="form-section">
                    <h3>Informations personnelles</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nom *</label>
                            <input type="text" name="nom" required>
                        </div>
                        <div class="form-group">
                            <label>Prénom *</label>
                            <input type="text" name="prenom" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Téléphone *</label>
                            <input type="tel" name="telephone" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Date de naissance *</label>
                            <input type="date" name="date_naissance" required>
                        </div>
                        <div class="form-group">
                            <label>Adresse *</label>
                            <input type="text" name="adresse" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Genre</label>
                            <select name="genre" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Formation et expérience</h3>
                    
                    <div class="form-group">
                        <label>Dernier diplôme obtenu *</label>
                        <input type="text" name="dernier_diplome" required placeholder="Ex: Master en Informatique">
                    </div>

                    <div class="form-group">
                        <label>Établissement *</label>
                        <input type="text" name="etablissement" required placeholder="Ex: Université d'Antananarivo">
                    </div>

                    <div class="form-group">
                        <label>Expérience professionnelle (années) *</label>
                        <input type="number" name="experience_annees" min="0" max="50" required>
                    </div>

                    <div class="form-group">
                        <label>Qualifications et certifications *</label>
                        <textarea name="qualifications" rows="3" required placeholder="Listez vos qualifications et certifications..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Compétences techniques *</label>
                        <textarea name="competences" rows="3" required placeholder="Listez vos compétences techniques..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Langues parlées *</label>
                        <input type="text" name="langue_parlee" required placeholder="Ex: Français, Anglais, Malgache">
                    </div>
                </div>

                <div class="form-section">
                    <h3>Documents justificatifs</h3>
                    
                    <div class="form-group">
                        <label>CV * (PDF, DOC, DOCX)</label>
                        <input type="file" name="cv" required accept=".pdf,.doc,.docx">
                    </div>

                    <div class="form-group">
                        <label>Lettre de motivation (PDF, DOC, DOCX)</label>
                        <input type="file" name="lettre_motivation" accept=".pdf,.doc,.docx">
                    </div>

                    <div class="form-group">
                        <label>Diplômes (PDF, JPG, PNG - plusieurs fichiers possibles)</label>
                        <input type="file" name="diplomes[]" multiple accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>

                <div class="form-section highlight">
                    <h3>Type de contrat souhaité</h3>
                    
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="demande_contrat_direct" value="1">
                            <span>
                                <strong>Je demande un contrat de travail direct</strong>
                                <small>Si votre dossier est excellent (note ≥ 18/20) et que vous le demandez, 
                                vous pouvez obtenir directement un contrat de travail sans période d'essai.</small>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Soumettre ma candidature
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectAnnonce(id) {
            document.querySelectorAll('.annonce-card').forEach(card => card.classList.remove('selected'));
            document.getElementById('annonce-' + id).classList.add('selected');
            document.getElementById('id_annonce').value = id;
        }

        document.getElementById('candidatureForm').addEventListener('submit', function(e) {
            if (!document.getElementById('id_annonce').value) {
                e.preventDefault();
                alert('Veuillez sélectionner une offre d\'emploi avant de soumettre votre candidature.');
            }
        });
    </script>
</body>
</html>
