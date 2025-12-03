<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion RH - Connexion</title>
    <link rel="stylesheet" href="/public/assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-split">
            <!-- Section Admin -->
            <div class="login-section admin-section">
                <div class="section-content">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h2>Espace Administration</h2>
                    <p>Connectez-vous en tant que Manager ou RH</p>
                    
                    <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        Identifiants incorrects ou accès refusé
                    </div>
                    <?php endif; ?>

                    <form action="/login" method="POST" class="login-form">
                        <div class="form-group">
                            <label>Rôle</label>
                            <select name="role" required class="form-select">
                                <option value="">-- Sélectionner --</option>
                                <option value="manager">Manager</option>
                                <option value="rh">Ressources Humaines</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nom d'utilisateur</label>
                            <input type="text" name="username" required placeholder="Votre identifiant">
                        </div>

                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" name="password" required placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </button>
                    </form>

                    <div class="help-text">
                        <p><strong>Comptes de test :</strong></p>
                        <p><small>Manager: manager1 / password</small></p>
                        <p><small>RH: rh1 / password</small></p>
                    </div>
                </div>
            </div>

            <!-- Section Candidats -->
            <div class="login-section candidate-section">
                <div class="section-content">
                    <div class="icon-wrapper">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h2>Espace Candidats</h2>
                    <p>Déposez votre candidature en ligne</p>
                    
                    <div class="features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Consultez les offres d'emploi</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Déposez votre dossier</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Suivez votre candidature</span>
                        </div>
                    </div>

                    <a href="/depot-dossier" class="btn btn-secondary btn-block">
                        <i class="fas fa-file-upload"></i> Accéder au dépôt de dossier
                    </a>

                    <a href="/entretiens" class="btn btn-outline btn-block">
                        <i class="fas fa-calendar-alt"></i> Voir les dates d'entretien et candidatures acceptés
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>