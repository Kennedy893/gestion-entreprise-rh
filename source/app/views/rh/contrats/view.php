<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .contrat-document {
            background: white;
            padding: 60px;
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
        .contrat-header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 30px;
            margin-bottom: 40px;
        }
        .contrat-section {
            margin: 30px 0;
            line-height: 1.8;
        }
        .contrat-section h3 {
            color: #2563eb;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #64748b;
            width: 40%;
        }
        .signature-section {
            margin-top: 60px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
        .signature-box {
            text-align: center;
            padding-top: 80px;
            border-top: 2px solid #1e293b;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-file-contract"></i> Détails du Contrat</h1>
                <div>
                    <a href="/rh/contrat/<?= $contrat['id'] ?>/pdf" 
                       class="btn btn-danger" 
                       style="margin-right: 10px;">
                        <i class="fas fa-file-pdf"></i> Télécharger PDF
                    </a>
                    <a href="/rh/contrats" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <?php if (isset($_GET['info']) && $_GET['info'] === 'pdf_not_yet_implemented'): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    La génération PDF sera implémentée prochainement. Vous pouvez imprimer cette page (Ctrl+P) en attendant.
                </div>
            <?php endif; ?>

            <div class="contrat-document">
                <!-- En-tête -->
                <div class="contrat-header">
                    <h1 style="color: #2563eb; margin: 0 0 10px 0;">CONTRAT DE TRAVAIL</h1>
                    <p style="color: #64748b; margin: 0;">N° <?= str_pad($contrat['id'], 6, '0', STR_PAD_LEFT) ?></p>
                </div>

                <!-- Informations employeur -->
                <div class="contrat-section">
                    <h3>Entre les soussignés :</h3>
                    <p><strong>L'EMPLOYEUR :</strong></p>
                    <p style="margin-left: 20px;">
                        [NOM DE L'ENTREPRISE]<br>
                        Représentée par [NOM DU REPRÉSENTANT]<br>
                        Adresse : [ADRESSE DE L'ENTREPRISE]<br>
                        NIF : [NUMÉRO NIF]
                    </p>
                </div>

                <!-- Informations employé -->
                <div class="contrat-section">
                    <p><strong>ET L'EMPLOYÉ :</strong></p>
                    <table class="info-table">
                        <tr>
                            <td>Nom et Prénom</td>
                            <td><strong><?= htmlspecialchars($contrat['employe_prenom'] . ' ' . $contrat['employe_nom']) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?= htmlspecialchars($contrat['employe_email']) ?></td>
                        </tr>
                        <tr>
                            <td>Contact</td>
                            <td><?= htmlspecialchars($contrat['employe_contact']) ?></td>
                        </tr>
                        <?php if (!empty($contrat['employe_cin'])): ?>
                        <tr>
                            <td>CIN</td>
                            <td><?= htmlspecialchars($contrat['employe_cin']) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>

                <!-- Termes du contrat -->
                <div class="contrat-section">
                    <h3>Article 1 - Objet du contrat</h3>
                    <table class="info-table">
                        <tr>
                            <td>Type de contrat</td>
                            <td><strong><?= htmlspecialchars($contrat['type_contrat_nom']) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Poste</td>
                            <td><strong><?= htmlspecialchars($contrat['poste_nom']) ?></strong></td>
                        </tr>
                       <tr>
                                <td>Poste</td>
                                <td><strong><?= htmlspecialchars($contrat['poste_nom']) ?></strong></td>
                            </tr>
                            <tr>
                                <td>Salaire</td>
                                <td><strong><?= number_format($contrat['salaire'], 0, ',', ' ') ?> Ar</strong></td>
                            </tr>
                            <tr>
                                <td>Statut</td>
                                <td>
                                    <span class="badge badge-success">
                                        <?= htmlspecialchars($contrat['statut_contrat_nom']) ?>
                                    </span>
                                </td>
                               </tr>
                    </table>
                </div>

                <div class="contrat-section">
                    <h3>Article 2 - Durée et période d'essai</h3>
                    <table class="info-table">
                        <tr>
                            <td>Date de début</td>
                            <td><strong><?= date('d/m/Y', strtotime($contrat['date_debut'])) ?></strong></td>
                        </tr>
                        <?php if (!empty($contrat['date_fin'])): ?>
                        <tr>
                            <td>Date de fin</td>
                            <td><strong><?= date('d/m/Y', strtotime($contrat['date_fin'])) ?></strong></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($contrat['duree'])): ?>
                        <tr>
                            <td>Durée</td>
                            <td><strong><?= $contrat['duree'] ?> mois</strong></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Statut</td>
                            <td>
                                <span class="badge badge-success">
                                    <?= htmlspecialchars($contrat['statut_contrat_nom']) ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="contrat-section">
                    <h3>Article 3 - Rémunération</h3>
                    <table class="info-table">
                        <tr>
                            <td>Salaire mensuel brut</td>
                            <td><strong style="font-size: 1.2rem; color: #2563eb;">
                                <?= number_format($contrat['salaire'], 0, ',', ' ') ?> Ar
                            </strong></td>
                        </tr>
                    </table>
                    <p style="margin-top: 10px; color: #64748b; font-size: 0.9rem;">
                        Ce salaire est versé mensuellement, sous déduction des charges sociales et fiscales légales.
                    </p>
                </div>

                <div class="contrat-section">
                    <h3>Article 4 - Obligations</h3>
                    <p>
                        L'employé s'engage à exercer ses fonctions avec professionnalisme, à respecter le règlement 
                        intérieur de l'entreprise, et à préserver la confidentialité des informations auxquelles il 
                        aura accès dans le cadre de ses fonctions.
                    </p>
                </div>

                <!-- Signatures -->
                <div class="signature-section">
                    <div class="signature-box">
                        <strong>L'EMPLOYEUR</strong><br>
                        <small>Date et signature</small>
                    </div>
                    <div class="signature-box">
                        <strong>L'EMPLOYÉ</strong><br>
                        <small>Date et signature</small>
                    </div>
                </div>

                <div style="margin-top: 40px; text-align: center; color: #64748b; font-size: 0.85rem;">
                    <p>Fait à Antananarivo, le <?= date('d/m/Y') ?></p>
                    <p>En deux exemplaires originaux, dont un remis à chaque partie.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction d'impression
        window.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>