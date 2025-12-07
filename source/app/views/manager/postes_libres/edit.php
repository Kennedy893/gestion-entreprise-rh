<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../../partials/sidebar_manager.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-edit"></i> Modifier le Poste</h1>
                <a href="/manager/postes-libres" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    Une erreur s'est produite lors de la modification.
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form action="/manager/postes-libres/edit/<?= $poste['id'] ?>" method="POST" class="annonce-form">
                    
                    <div class="form-section">
                        <h2><i class="fas fa-briefcase"></i> Informations du Poste</h2>
                        
                        <div class="form-group">
                            <label for="label">Nom du poste *</label>
                            <input type="text" id="label" name="label" required 
                                   value="<?= htmlspecialchars($poste['label']) ?>"
                                   placeholder="Ex: Développeur Full Stack">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_categorie">Catégorie *</label>
                                <select id="id_categorie" name="id_categorie" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" 
                                            <?= $poste['id_categorie'] == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['libelle']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_departement">Département *</label>
                                <select id="id_departement" name="id_departement" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($departements as $dep): ?>
                                    <option value="<?= $dep['id'] ?>"
                                            <?= $poste['id_departement'] == $dep['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dep['libelle']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="valeur">Capacité (nombre de places) *</label>
                            <input type="number" id="valeur" name="valeur" min="1" max="100" required 
                                   value="<?= htmlspecialchars($poste['valeur']) ?>">
                            <small class="form-text">
                                <i class="fas fa-exclamation-triangle"></i>
                                Attention : Réduire la capacité en dessous du nombre d'employés/annonces actifs peut causer des problèmes.
                            </small>
                        </div>

                        <?php if (isset($poste['annonces_actives']) && isset($poste['employes_actifs'])): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>État actuel :</strong>
                            <ul style="margin: 10px 0 0 20px;">
                                <li>Annonces actives : <strong><?= $poste['annonces_actives'] ?></strong></li>
                                <li>Employés actifs : <strong><?= $poste['employes_actifs'] ?></strong></li>
                                <li>Places occupées : <strong><?= $poste['annonces_actives'] + $poste['employes_actifs'] ?></strong></li>
                                <li>Places libres : <strong style="color: <?= $poste['postes_disponibles'] > 0 ? '#22c55e' : '#ef4444' ?>;">
                                    <?= $poste['postes_disponibles'] ?>
                                </strong></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                        <a href="/manager/postes-libres" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>