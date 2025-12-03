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
                <h1><i class="fas fa-user"></i> Fiche Employé</h1>
                <div>
                    <?php if ($contrat): ?>
                    <a href="/rh/contrat/<?= $contrat['id'] ?>" 
                       class="btn btn-primary" 
                       style="margin-right: 10px;">
                        <i class="fas fa-file-contract"></i> Voir le contrat
                    </a>
                    <?php endif; ?>
                    <a href="/rh/employes" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
                <!-- Colonne gauche - Photo et infos rapides -->
                <div>
                    <div class="form-section">
                        <div style="text-align: center;">
                            <div style="width: 150px; height: 150px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem; font-weight: bold;">
                                <?= strtoupper(substr($employe['prenom'], 0, 1) . substr($employe['nom'], 0, 1)) ?>
                            </div>
                            <h2 style="margin: 0 0 5px 0; color: #1e293b;">
                                <?= htmlspecialchars($employe['prenom'] . ' ' . $employe['nom']) ?>
                            </h2>
                            <?php if ($contrat): ?>
                            <p style="color: #64748b; margin: 0;">
                                <?= htmlspecialchars($contrat['poste_nom'] ?? 'N/A') ?>
                            </p>
                            <?php endif; ?>
                        </div>

                        <?php if ($contrat): ?>
                        <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                            <div style="text-align: center; margin-bottom: 15px;">
                                <span class="badge badge-success" style="font-size: 1rem; padding: 10px 20px;">
                                    <i class="fas fa-check-circle"></i> 
                                    <?= htmlspecialchars($contrat['statut_contrat_nom'] ?? 'Actif') ?>
                                </span>
                            </div>
                            <div style="text-align: center;">
                                <label style="display: block; font-weight: 600; color: #64748b; font-size: 0.85rem; margin-bottom: 5px;">
                                    Date d'embauche
                                </label>
                                <p style="margin: 0; font-size: 1.1rem; color: #1e293b;">
                                    <?= isset($contrat['date_debut']) ? date('d/m/Y', strtotime($contrat['date_debut'])) : 'N/A' ?>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Colonne droite - Détails complets -->
                <div>
                    <!-- Informations personnelles -->
                    <div class="form-section">
                        <h2><i class="fas fa-id-card"></i> Informations Personnelles</h2>
                        <table class="info-table" style="width: 100%; margin-top: 20px;">
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b; width: 35%;">
                                    Email
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <a href="mailto:<?= htmlspecialchars($employe['email']) ?>" style="color: #2563eb;">
                                        <?= htmlspecialchars($employe['email']) ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Contact
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= htmlspecialchars($employe['contact']) ?>
                                </td>
                            </tr>
                            <?php if (!empty($employe['date_naissance'])): ?>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Date de naissance
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= date('d/m/Y', strtotime($employe['date_naissance'])) ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($employe['cin'])): ?>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    CIN
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= htmlspecialchars($employe['cin']) ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($employe['adresse'])): ?>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Adresse
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= htmlspecialchars($employe['adresse']) ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($employe['genre'])): ?>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Genre
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= $employe['genre'] == 1 ? 'Homme' : 'Femme' ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>

                    <!-- Informations contractuelles -->
                    <?php if ($contrat): ?>
                    <div class="form-section">
                        <h2><i class="fas fa-briefcase"></i> Informations Contractuelles</h2>
                        <table class="info-table" style="width: 100%; margin-top: 20px;">
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b; width: 35%;">
                                    Type de contrat
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <strong><?= htmlspecialchars($contrat['type_contrat_nom'] ?? 'N/A') ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Poste
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= htmlspecialchars($contrat['poste_nom'] ?? 'N/A') ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Catégorie
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= htmlspecialchars($contrat['categorie_nom'] ?? 'N/A') ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Département
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= htmlspecialchars($contrat['departement_nom'] ?? 'N/A') ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Salaire mensuel
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <strong style="font-size: 1.2rem; color: #2563eb;">
                                        <?= number_format($contrat['salaire'], 0, ',', ' ') ?> Ar
                                    </strong>
                                </td>
                            </tr>
                            <?php if (!empty($contrat['date_fin'])): ?>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Date de fin
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= date('d/m/Y', strtotime($contrat['date_fin'])) ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($contrat['duree'])): ?>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #64748b;">
                                    Durée
                                </td>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">
                                    <?= $contrat['duree'] ?> mois
                                </td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>