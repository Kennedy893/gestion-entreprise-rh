<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Créer une annonce' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }
    </style>
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

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php 
                        switch($_GET['error']) {
                            case 'missing_field':
                                echo "Erreur : Le champ " . htmlspecialchars($_GET['field'] ?? 'inconnu') . " est requis.";
                                break;
                            case 'invalid_date':
                                echo "Erreur : La date limite doit être une date future.";
                                break;
                            case 'creation_failed':
                                echo "Erreur : La création de l'annonce a échoué. Veuillez réessayer.";
                                break;
                            case 'exception':
                                echo "Erreur : Une erreur inattendue s'est produite. Veuillez contacter l'administrateur.";
                                break;
                            default:
                                echo "Une erreur s'est produite.";
                        }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($_SESSION['user']['id_employe']) && !isset($_SESSION['user']['email'])): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Attention : Aucun ID employé trouvé dans votre session. L'annonce sera créée sans manager associé.
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form action="/manager/annonce/create" method="POST" class="annonce-form" id="annonceForm">
                    
                    <div class="form-section">
                        <h2><i class="fas fa-info-circle"></i> Informations Générales</h2>
                        
                        <div class="form-group">
                            <label for="titre">Titre de l'annonce *</label>
                            <input type="text" id="titre" name="titre" required 
                                   placeholder="Ex: Ingénieur logiciel senior"
                                   value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="description">Description du poste *</label>
                            <textarea id="description" name="description" rows="6" required 
                                      placeholder="Décrivez les missions principales, l'environnement de travail..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_poste">Poste * <span id="poste-status"></span></label>
                                <select id="id_poste" name="id_poste" required onchange="checkPosteDisponibilite()">
                                    <option value="">-- Sélectionner un poste --</option>
                                    <?php foreach ($postes as $poste): ?>
                                    <option value="<?= $poste['id'] ?>" 
                                            <?= (isset($_POST['id_poste']) && $_POST['id_poste'] == $poste['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($poste['label']) ?> 
                                        (<?= htmlspecialchars($poste['categorie_nom']) ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text" id="poste-info"></small>
                            </div>

                            <div class="form-group">
                                <label for="id_type_contrat">Type de contrat *</label>
                                <select id="id_type_contrat" name="id_type_contrat" required>
                                    <option value="">-- Sélectionner un type --</option>
                                    <?php foreach ($typeContrats as $type): ?>
                                    <option value="<?= $type['id'] ?>"
                                            <?= (isset($_POST['id_type_contrat']) && $_POST['id_type_contrat'] == $type['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type['label']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date_limite">Date limite de candidature *</label>
                            <input type="date" id="date_limite" name="date_limite" required 
                                   min="<?= date('Y-m-d') ?>"
                                   value="<?= htmlspecialchars($_POST['date_limite'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-section">
                        <h2><i class="fas fa-graduation-cap"></i> Qualifications et Compétences</h2>
                        
                        <div class="form-group">
                            <label for="diplomes_requis">Diplômes et certifications requis *</label>
                            <textarea id="diplomes_requis" name="diplomes_requis" rows="4" required
                                      placeholder="Ex: Licence en Informatique, Master en Génie Logiciel, Certification AWS..."><?= htmlspecialchars($_POST['diplomes_requis'] ?? '') ?></textarea>
                            <small class="form-text">Listez les diplômes et certifications nécessaires</small>
                        </div>

                        <div class="form-group">
                            <label for="competences_requises">Compétences techniques requises *</label>
                            <textarea id="competences_requises" name="competences_requises" rows="4" required
                                      placeholder="Ex: PHP, MySQL, JavaScript, Vue.js, API REST..."><?= htmlspecialchars($_POST['competences_requises'] ?? '') ?></textarea>
                            <small class="form-text">Compétences techniques et générales attendues</small>
                        </div>

                        <div class="form-group">
                            <label for="experience_min">Expérience professionnelle minimale (années) *</label>
                            <input type="number" id="experience_min" name="experience_min" min="0" max="30" required
                                   value="<?= htmlspecialchars($_POST['experience_min'] ?? '0') ?>">
                        </div>
                    </div>

                    <div class="form-section">
                        <h2><i class="fas fa-layer-group"></i> Position Hiérarchique et Responsabilités</h2>
                        
                        <div class="form-group">
                            <label for="niveau_responsabilite">Niveau de responsabilité *</label>
                            <select id="niveau_responsabilite" name="niveau_responsabilite" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="Ouvrier" <?= (isset($_POST['niveau_responsabilite']) && $_POST['niveau_responsabilite'] == 'Ouvrier') ? 'selected' : '' ?>>
                                    Ouvrier (Exécution de tâches manuelles/techniques)
                                </option>
                                <option value="Employé" <?= (isset($_POST['niveau_responsabilite']) && $_POST['niveau_responsabilite'] == 'Employé') ? 'selected' : '' ?>>
                                    Employé (Tâches administratives, support)
                                </option>
                                <option value="Technicien" <?= (isset($_POST['niveau_responsabilite']) && $_POST['niveau_responsabilite'] == 'Technicien') ? 'selected' : '' ?>>
                                    Technicien/Agent de Maîtrise (Supervision d'équipe)
                                </option>
                                <option value="Cadre" <?= (isset($_POST['niveau_responsabilite']) && $_POST['niveau_responsabilite'] == 'Cadre') ? 'selected' : '' ?>>
                                    Cadre (Gestion et encadrement)
                                </option>
                                <option value="Dirigeant" <?= (isset($_POST['niveau_responsabilite']) && $_POST['niveau_responsabilite'] == 'Dirigeant') ? 'selected' : '' ?>>
                                    Dirigeant (Décisions stratégiques)
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="autonomie_requise">Niveau d'autonomie requis *</label>
                            <textarea id="autonomie_requise" name="autonomie_requise" rows="3" required
                                      placeholder="Décrivez le niveau d'autonomie attendu, la prise de décision, l'initiative..."><?= htmlspecialchars($_POST['autonomie_requise'] ?? '') ?></textarea>
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

    <script>
        // Vérifier la disponibilité du poste
        async function checkPosteDisponibilite() {
            const posteId = document.getElementById('id_poste').value;
            const statusEl = document.getElementById('poste-status');
            const infoEl = document.getElementById('poste-info');
            const submitBtn = document.querySelector('button[type="submit"]');
            
            if (!posteId) {
                statusEl.innerHTML = '';
                infoEl.innerHTML = '';
                submitBtn.disabled = false;
                return;
            }
            
            try {
                const response = await fetch(`/api/poste/${posteId}/disponibilite`);
                const data = await response.json();
                
                if (data.success && data.disponible) {
                    statusEl.innerHTML = `<span style="color: #22c55e; margin-left: 10px;">
                        <i class="fas fa-check-circle"></i> ${data.places} place(s) disponible(s)
                    </span>`;
                    infoEl.innerHTML = `<i class="fas fa-check-circle" style="color: #22c55e;"></i> 
                        Ce poste a des places disponibles. Vous pouvez créer l'annonce.`;
                    submitBtn.disabled = false;
                } else {
                    statusEl.innerHTML = `<span style="color: #ef4444; margin-left: 10px;">
                        <i class="fas fa-times-circle"></i> Aucune place disponible
                    </span>`;
                    infoEl.innerHTML = `<i class="fas fa-times-circle" style="color: #ef4444;"></i> 
                        Ce poste est complet. Impossible de créer une annonce.`;
                    submitBtn.disabled = true;
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }
        
        // Validation côté client
        document.getElementById('annonceForm').addEventListener('submit', function(e) {
            const dateLimite = new Date(document.getElementById('date_limite').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (dateLimite < today) {
                e.preventDefault();
                alert('La date limite doit être une date future.');
                return false;
            }
            
            const experienceMin = parseInt(document.getElementById('experience_min').value);
            if (experienceMin < 0 || experienceMin > 30) {
                e.preventDefault();
                alert('L\'expérience minimale doit être entre 0 et 30 ans.');
                return false;
            }
        });
        
        // Vérifier au chargement si un poste est déjà sélectionné
        window.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('id_poste').value) {
                checkPosteDisponibilite();
            }
        });
    </script>
</body>
</html>