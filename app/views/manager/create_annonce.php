<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Créer une annonce' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_manager.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-plus-circle"></i> Créer une annonce d'emploi</h1>
                <a href="/manager/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="form-container">
                <form action="/manager/annonce/create" method="POST" class="annonce-form">
                    
                    <div class="form-section">
                        <h2><i class="fas fa-info-circle"></i> Informations Générales</h2>
                        
                        <div class="form-group">
                            <label for="titre">Titre de l'annonce *</label>
                            <input type="text" id="titre" name="titre" required 
                                   placeholder="Ex: Ingénieur logiciel senior">
                        </div>

                        <div class="form-group">
                            <label for="description">Description du poste *</label>
                            <textarea id="description" name="description" rows="6" required 
                                      placeholder="Décrivez les missions principales, l'environnement de travail..."></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_poste">Poste *</label>
                                <select id="id_poste" name="id_poste" required>
                                    <option value="">-- Sélectionner un poste --</option>
                                    <?php foreach ($postes as $poste): ?>
                                    <option value="<?= $poste['id'] ?>">
                                        <?= htmlspecialchars($poste['label']) ?> 
                                        (<?= htmlspecialchars($poste['categorie_nom']) ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_type_contrat">Type de contrat *</label>
                                <select id="id_type_contrat" name="id_type_contrat" required>
                                    <option value="">-- Sélectionner un type --</option>
                                    <?php foreach ($typeContrats as $type): ?>
                                    <option value="<?= $type['id'] ?>">
                                        <?= htmlspecialchars($type['label']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date_limite">Date limite de candidature *</label>
                            <input type="date" id="date_limite" name="date_limite" required 
                                   min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="form-section">
                        <h2><i class="fas fa-graduation-cap"></i> Qualifications et Compétences</h2>
                        
                        <div class="form-group">
                            <label for="diplomes_requis">Diplômes et certifications requis *</label>
                            <textarea id="diplomes_requis" name="diplomes_requis" rows="4" required
                                      placeholder="Ex: Licence en Informatique, Master en Génie Logiciel, Certification AWS..."></textarea>
                            <small class="form-text">Listez les diplômes et certifications nécessaires</small>
                        </div>

                        <div class="form-group">
                            <label for="competences_requises">Compétences techniques requises *</label>
                            <textarea id="competences_requises" name="competences_requises" rows="4" required
                                      placeholder="Ex: PHP, MySQL, JavaScript, Vue.js, API REST..."></textarea>
                            <small class="form-text">Compétences techniques et générales attendues</small>
                        </div>

                        <div class="form-group">
                            <label for="experience_min">Expérience professionnelle minimale (années) *</label>
                            <input type="number" id="experience_min" name="experience_min" min="0" max="30" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2><i class="fas fa-layer-group"></i> Position Hiérarchique et Responsabilités</h2>
                        
                        <div class="form-group">
                            <label for="niveau_responsabilite">Niveau de responsabilité *</label>
                            <select id="niveau_responsabilite" name="niveau_responsabilite" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="Ouvrier">Ouvrier (Exécution de tâches manuelles/techniques)</option>
                                <option value="Employé">Employé (Tâches administratives, support)</option>
                                <option value="Technicien">Technicien/Agent de Maîtrise (Supervision d'équipe)</option>
                                <option value="Cadre">Cadre (Gestion et encadrement)</option>
                                <option value="Dirigeant">Dirigeant (Décisions stratégiques)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="autonomie_requise">Niveau d'autonomie requis *</label>
                            <textarea id="autonomie_requise" name="autonomie_requise" rows="3" required
                                      placeholder="Décrivez le niveau d'autonomie attendu, la prise de décision, l'initiative..."></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Publier l'annonce
                        </button>
                        <a href="/manager/dashboard" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>