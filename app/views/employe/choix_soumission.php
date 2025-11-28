<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumission de Demandes - RH Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 260px;
            padding: 40px;
            transition: margin-left 0.3s ease;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 16px;
        }

        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #1e40af;
        }

        .breadcrumb-separator {
            color: #cbd5e1;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .page-title-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 16px;
            line-height: 1.6;
        }

        .info-banner {
            background: white;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #10b981;
        }

        .info-banner-icon {
            font-size: 24px;
            flex-shrink: 0;
        }

        .info-banner-content {
            flex: 1;
        }

        .info-banner-title {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .info-banner-text {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            max-width: 1400px;
        }

        .request-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            border: 2px solid transparent;
        }

        .request-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            transition: transform 0.3s ease;
        }

        .request-card.conge::before {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        }

        .request-card.attestation::before {
            background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .request-card.remboursement::before {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }

        .request-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .request-card.conge:hover {
            border-color: #3b82f6;
        }

        .request-card.attestation:hover {
            border-color: #8b5cf6;
        }

        .request-card.remboursement:hover {
            border-color: #10b981;
        }

        .card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            position: relative;
            transition: transform 0.3s ease;
        }

        .request-card:hover .card-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .request-card.conge .card-icon-wrapper {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .request-card.attestation .card-icon-wrapper {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
        }

        .request-card.remboursement .card-icon-wrapper {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .card-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .request-card.conge .card-badge {
            background: #dbeafe;
            color: #1e40af;
        }

        .request-card.attestation .card-badge {
            background: #ede9fe;
            color: #6d28d9;
        }

        .request-card.remboursement .card-badge {
            background: #d1fae5;
            color: #065f46;
        }

        .card-body {
            flex: 1;
        }

        .card-title {
            font-size: 22px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .card-description {
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .card-info-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #475569;
        }

        .info-item-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .request-card.conge .info-item-icon {
            background: rgba(59, 130, 246, 0.1);
        }

        .request-card.attestation .info-item-icon {
            background: rgba(139, 92, 246, 0.1);
        }

        .request-card.remboursement .info-item-icon {
            background: rgba(16, 185, 129, 0.1);
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .footer-time {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #94a3b8;
        }

        .footer-arrow {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .request-card.conge .footer-arrow {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
        }

        .request-card.attestation .footer-arrow {
            background: rgba(139, 92, 246, 0.1);
            color: #6d28d9;
        }

        .request-card.remboursement .footer-arrow {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }

        .request-card:hover .footer-arrow {
            transform: translateX(6px) scale(1.1);
        }

        .request-card.conge:hover .footer-arrow {
            background: rgba(59, 130, 246, 0.2);
        }

        .request-card.attestation:hover .footer-arrow {
            background: rgba(139, 92, 246, 0.2);
        }

        .request-card.remboursement:hover .footer-arrow {
            background: rgba(16, 185, 129, 0.2);
        }

        .stats-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .page-title {
                font-size: 28px;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .info-banner {
                flex-direction: column;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .request-card {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }

        .request-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .request-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .request-card:nth-child(3) {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include('app/views/sidebar/sidebar.php') ?>

    <div class="main-content">
        <div class="page-header">
            <div class="breadcrumb">
                <a href="<?= constant('BASE_URL') ?>">Accueil</a>
                <span class="breadcrumb-separator">›</span>
                <span>Soumission de demandes</span>
            </div>
            
            <h1 class="page-title">
                <span class="page-title-icon">📝</span>
                Soumettre une Demande
            </h1>
            <p class="page-subtitle">
                Sélectionnez le type de demande que vous souhaitez effectuer
            </p>
        </div>

        <div class="cards-grid">
            <!-- Carte Demande de Congé -->
            <a href="<?= constant('BASE_URL') ?>vers_demande_conge" class="request-card conge">
                <div class="card-header">
                    <div class="card-icon-wrapper">🏖️</div>
                    <!-- <div class="card-badge">Populaire</div> -->
                </div>
                <div class="card-body">
                    <h3 class="card-title">Demande de Congé</h3>
                    <p class="card-description">
                        Soumettez une demande de congé payé, exceptionnel ou pour maladie. Consultez votre solde en temps réel.
                    </p>
                    <div class="card-info-list">
                        <div class="info-item">
                            <span class="info-item-icon">⏱️</span>
                            <span>Traitement sous 48h</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon">📄</span>
                            <span>Justificatifs optionnels</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon">📊</span>
                            <span>Suivi en temps réel</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span class="footer-time">⏰ Toujours disponible</span>
                    <span class="footer-arrow">→</span>
                </div>
            </a>

            <!-- Carte Demande d'Attestation -->
            <a href="<?= constant('BASE_URL') ?>demande_attestation" class="request-card attestation">
                <div class="card-header">
                    <div class="card-icon-wrapper">📜</div>
                    <!-- <div class="card-badge">Rapide</div> -->
                </div>
                <div class="card-body">
                    <h3 class="card-title">Demande d'Attestation</h3>
                    <p class="card-description">
                        Obtenez vos attestations de travail, de salaire ou toute autre attestation administrative nécessaire.
                    </p>
                    <div class="card-info-list">
                        <div class="info-item">
                            <span class="info-item-icon">⚡</span>
                            <span>Délivrance sous 24h</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon">🔐</span>
                            <span>Documents certifiés</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon">📧</span>
                            <span>Envoi par email</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span class="footer-time">⏰ Service express</span>
                    <span class="footer-arrow">→</span>
                </div>
            </a>

            <!-- Carte Demande de Remboursement -->
            <a href="<?= constant('BASE_URL') ?>demande_remboursement" class="request-card remboursement">
                <div class="card-header">
                    <div class="card-icon-wrapper">💵</div>
                    <!-- <div class="card-badge">Nouveau</div> -->
                </div>
                <div class="card-body">
                    <h3 class="card-title">Demande de Remboursement</h3>
                    <p class="card-description">
                        Demandez le remboursement de vos frais professionnels : déplacements, repas, formation, etc.
                    </p>
                    <div class="card-info-list">
                        <div class="info-item">
                            <span class="info-item-icon">💳</span>
                            <span>Paiement sous 7 jours</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon">📎</span>
                            <span>Joindre les justificatifs</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon">✅</span>
                            <span>Suivi de validation</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span class="footer-time">⏰ Processing rapide</span>
                    <span class="footer-arrow">→</span>
                </div>
            </a>
        </div>
    </div>
</body>
</html>