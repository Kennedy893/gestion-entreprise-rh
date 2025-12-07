<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-file-signature"></i> Générer un Contrat</h1>
                <a href="/rh/candidatures-acceptees" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php 
                        switch($_GET['error']) {
                            case 'candidature_not_found':
                                echo "Candidature introuvable.";
                                break;
                            case 'candidat_not_accepted':
                                echo "Le candidat n'a pas été accepté.";
                                break;
                            case 'contrat_exists':
                                echo "Un contrat existe déjà pour ce candidat.";
                                break;
                            default:
                                echo "Une erreur s'est produite.";
                        }
                    ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <!-- Informations candidat -->
                <div class="form-section" style="background: #f8fafc; padding: 25px; border-radius: 12px; margin-bottom: 25px;">
                    <h2><i class="fas fa-user"></i> Informations du Candidat</h2>
                    <div class="info-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 20px;">
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.9rem;">Nom complet</label>
                            <p style="margin: 5px 0 0; font-size: 1.1rem; color: #1e293b;">
                                <?= htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']) ?>
                            </p>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.9rem;">Email</label>
                            <p style="margin: 5px 0 0; font-size: 1.1rem; color: #1e293b;">
                                <?= htmlspecialchars($candidature['email']) ?>
                            </p>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.9rem;">Poste</label>
                            <p style="margin: 5px 0 0; font-size: 1.1rem; color: #1e293b;">
                                <?= htmlspecialchars($candidature['poste_nom'] ?? 'N/A') ?>
                            </p>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: #64748b; font-size: 0.9rem;">Téléphone</label>
                            <p style="margin: 5px 0 0; font-size: 1.1rem; color: #1e293b;">
                                <?= htmlspecialchars($candidature['telephone']) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <form action="/rh/contrat/create/<?= $candidature['id'] ?>" method="POST" class="annonce-form">
                    
                    <div class="form-section">
                        <h2><i class="fas fa-file-contract"></i> Détails du Contrat</h2>
                        
                        <div class="form-group">
                            <label for="id_type_contrat">Type de contrat *</label>
                            <select id="id_type_contrat" name="id_type_contrat" required>
                                <option value="">-- Sélectionner le type --</option>
                                <?php foreach ($typeContrats as $type): ?>
                                <option value="<?= $type['id'] ?>"
                                        <?= (isset($candidature['type_contrat_accorde']) && 
                                             strpos($candidature['type_contrat_accorde'], $type['label']) !== false) 
                                             ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($type['label']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($candidature['type_contrat_accorde'])): ?>
                            <small class="form-text">
                                <i class="fas fa-info-circle"></i>
                                Type suggéré : <?= htmlspecialchars($candidature['type_contrat_accorde']) ?>
                            </small>
                            <?php endif; ?>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_debut">Date de début *</label>
                                <input type="date" id="date_debut" name="date_debut" required 
                                       min="<?= date('Y-m-d') ?>"
                                       value="<?= htmlspecialchars($candidature['date_debut_travail'] ?? '') ?>">
                            </div>

                            <div class="form-group">
                                <label for="date_fin">Date de fin (optionnelle)</label>
                                <input type="date" id="date_fin" name="date_fin"
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                <small class="form-text">
                                    Laisser vide pour un contrat à durée indéterminée
                                </small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="duree">Durée (en mois, optionnel)</label>
                            <input type="number" id="duree" name="duree" min="1" max="120" 
                                   placeholder="Ex: 6 pour un essai de 6 mois">
                            <small class="form-text">
                                Utile pour les contrats d'essai ou à durée déterminée
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="salaire">Salaire mensuel brut (Ar) *</label>
                            <input type="number" id="salaire" name="salaire" required 
                                   min="0" step="1000"
                                   placeholder="Ex: 2500000">
                            <small class="form-text">
                                Montant en Ariary avant déductions
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information :</strong> Une fois le contrat généré, l'employé sera automatiquement 
                            ajouté à la liste des employés et le poste sera occupé.
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-signature"></i> Générer le contrat
                        </button>
                        <a href="/rh/candidatures-acceptees" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Calcul automatique de la date de fin selon la durée
        document.getElementById('duree').addEventListener('input', function() {
            const duree = parseInt(this.value);
            const dateDebut = document.getElementById('date_debut').value;
            
            if (duree && dateDebut) {
                const debut = new Date(dateDebut);
                debut.setMonth(debut.getMonth() + duree);
                document.getElementById('date_fin').value = debut.toISOString().split('T')[0];
            }
        });
        
        // Si on change la date de début, recalculer
        document.getElementById('date_debut').addEventListener('change', function() {
            const dureeInput = document.getElementById('duree');
            if (dureeInput.value) {
                dureeInput.dispatchEvent(new Event('input'));
            }
        });
    </script>
</body>
</html>