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
                <h1><i class="fas fa-plus-square"></i> Créer des Postes Libres</h1>
                <a href="/manager/postes-libres" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php if ($_GET['error'] === 'invalid_data'): ?>
                        Données invalides. Veuillez vérifier le formulaire.
                    <?php else: ?>
                        Une erreur s'est produite lors de la création.
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form action="/manager/postes-libres/create" method="POST" class="annonce-form">
                    
                    <div class="form-section">
                        <h2><i class="fas fa-briefcase"></i> Informations du Poste</h2>
                        
                        <div class="form-group">
                            <label for="label">Nom du poste *</label>
                            <input type="text" id="label" name="label" required 
                                   placeholder="Ex: Développeur Full Stack">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_categorie">Catégorie *</label>
                                <select id="id_categorie" name="id_categorie" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>">
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
                                    <option value="<?= $dep['id'] ?>">
                                        <?= htmlspecialchars($dep['libelle']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="valeur">Nombre de places par poste *</label>
                                <input type="number" id="valeur" name="valeur" min="1" max="100" required value="1">
                                <small class="form-text">
                                    Capacité maximale pour ce type de poste
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="nombre">Nombre de postes à créer *</label>
                                <input type="number" id="nombre" name="nombre" min="1" max="50" required value="1">
                                <small class="form-text">
                                    Vous pouvez créer plusieurs postes identiques en une fois
                                </small>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note :</strong> Si vous créez 3 postes avec une capacité de 2 places chacun, 
                            cela créera 3 entrées identiques permettant 6 employés au total (3 × 2).
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Créer les postes
                        </button>
                        <a href="/manager/postes-libres" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Aperçu en temps réel
        document.getElementById('nombre').addEventListener('input', function() {
            const nombre = parseInt(this.value) || 1;
            const valeur = parseInt(document.getElementById('valeur').value) || 1;
            const total = nombre * valeur;
            
            document.querySelector('.alert-info').innerHTML = `
                <i class="fas fa-info-circle"></i>
                <strong>Résumé :</strong> Vous allez créer <strong>${nombre}</strong> poste(s) 
                avec une capacité de <strong>${valeur}</strong> place(s) chacun = 
                <strong style="color: #22c55e;">${total}</strong> places disponibles au total.
            `;
        });
        
        document.getElementById('valeur').addEventListener('input', function() {
            document.getElementById('nombre').dispatchEvent(new Event('input'));
        });
    </script>
</body>
</html>