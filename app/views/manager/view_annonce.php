<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Détails annonce' ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_manager.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-file-alt"></i> <?= htmlspecialchars($annonce['titre']) ?></h1>
                <a href="/manager/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="annonce-detail-card">
                <div class="annonce-header">
                    <div class="annonce-badge-group">
                        <span class="badge badge-primary"><?= htmlspecialchars($annonce['type_contrat_nom']) ?></span>
                        <span class="badge badge-info"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
                        <?php 
                        $statusClass = $annonce['statut'] === 'active' ? 'badge-success' : 'badge-danger';
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($annonce['statut']) ?></span>
                    </div>
                    <div class="annonce-dates">
                        <p><i class="fas fa-calendar"></i> Publié le <?= date('d/m/Y', strtotime($annonce['date_publication'])) ?></p>
                        <p><i class="fas fa-calendar-times"></i> Date limite: <?= date('d/m/Y', strtotime($annonce['date_limite'])) ?></p>
                    </div>
                </div>

                <div class="detail-section">
                    <h2><i class="fas fa-info-circle"></i> Informations du poste</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Poste:</span>
                            <span class="info-value"><?= htmlspecialchars($annonce['poste_nom']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Département:</span>
                            <span class="info-value"><?= htmlspecialchars($annonce['departement_nom']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Catégorie:</span>
                            <span class="info-value"><?= htmlspecialchars($annonce['categorie_nom']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Expérience minimale:</span>
                            <span class="info-value"><?= $annonce['experience_min'] ?> an(s)</span>
                        </div>
                        <?php if (!empty($annonce['duree_travail'])): ?>
                        <div class="info-item">
                            <span class="info-label">Durée de travail:</span>
                            <span class="info-value"><?= $annonce['duree_travail'] ?>h</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Horaires:</span>
                            <span class="info-value"><?= $annonce['entree'] ?> - <?= $annonce['sortie'] ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="detail-section">
                    <h2><i class="fas fa-align-left"></i> Description du poste</h2>
                    <p class="description-text"><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>
                </div>

                <div class="detail-section">
                    <h2><i class="fas fa-graduation-cap"></i> Diplômes requis</h2>
                    <p class="description-text"><?= nl2br(htmlspecialchars($annonce['diplomes_requis'])) ?></p>
                </div>

                <div class="detail-section">
                    <h2><i class="fas fa-tools"></i> Compétences requises</h2>
                    <p class="description-text"><?= nl2br(htmlspecialchars($annonce['competences_requises'])) ?></p>
                </div>

                <div class="detail-section">
                    <h2><i class="fas fa-layer-group"></i> Responsabilités et Autonomie</h2>
                    <div class="info-grid">
                        <div class="info-item full-width">
                            <span class="info-label">Niveau de responsabilité:</span>
                            <span class="info-value badge badge-warning">
                                <?= htmlspecialchars($annonce['niveau_responsabilite']) ?>
                            </span>
                        </div>
                        <div class="info-item full-width">
                            <span class="info-label">Autonomie requise:</span>
                            <p class="description-text"><?= nl2br(htmlspecialchars($annonce['autonomie_requise'])) ?></p>
                        </div>
                    </div>
                </div>

                <div class="action-footer">
                    <button onclick="printAnnonce()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                    <button onclick="shareAnnonce()" class="btn btn-info">
                        <i class="fas fa-share-alt"></i> Partager
                    </button>
                    <button onclick="deleteAnnonce(<?= $annonce['id'] ?>)" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printAnnonce() {
            window.print();
        }
        
        function shareAnnonce() {
            const url = window.location.href.replace('/manager/', '/public/');
            navigator.clipboard.writeText(url);
            alert('Lien copié dans le presse-papier !');
        }
        
        function deleteAnnonce(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')) {
                window.location.href = '/manager/annonce/delete/' + id;
            }
        }
    </script>
</body>
</html>