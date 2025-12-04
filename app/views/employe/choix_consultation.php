<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation - RH Manager</title>
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

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            max-width: 1200px;
        }

        .consultation-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
        }

        .consultation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .consultation-card:hover::before {
            transform: scaleX(1);
        }

        .consultation-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .consultation-card.paie {
            background: linear-gradient(135deg, #ffffff 0%, #fef3c7 100%);
        }

        .consultation-card.paie::before {
            background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        }

        .consultation-card.conges {
            background: linear-gradient(135deg, #ffffff 0%, #dbeafe 100%);
        }

        .consultation-card.conges::before {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        }

        .card-icon-wrapper {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin-bottom: 24px;
            position: relative;
        }

        .consultation-card.paie .card-icon-wrapper {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .consultation-card.conges .card-icon-wrapper {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .card-icon-wrapper::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 18px;
            padding: 2px;
            background: linear-gradient(135deg, rgba(255,255,255,0.8), rgba(255,255,255,0.2));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .consultation-card:hover .card-icon-wrapper::after {
            opacity: 1;
        }

        .card-title {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-arrow {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(30, 58, 138, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .consultation-card:hover .card-arrow {
            background: rgba(30, 58, 138, 0.2);
            transform: translateX(4px);
        }

        .card-description {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .card-features {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #475569;
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(30, 58, 138, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .consultation-card.paie .feature-icon {
            background: rgba(245, 158, 11, 0.2);
        }

        .consultation-card.conges .feature-icon {
            background: rgba(59, 130, 246, 0.2);
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
            border-left: 4px solid #3b82f6;
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
                <span>Consultation</span>
            </div>
            
            <h1 class="page-title">
                <span class="page-title-icon">📋</span>
                Espace de Consultation
            </h1>
            <p class="page-subtitle">
                Accédez à vos documents et informations personnelles en toute simplicité
            </p>
        </div>

        <div class="info-banner">
            <span class="info-banner-icon">💡</span>
            <div class="info-banner-content">
                <div class="info-banner-title">Accès rapide à vos documents</div>
                <div class="info-banner-text">
                    Consultez vos bulletins de paie, votre solde de congés et bien plus encore depuis cet espace centralisé.
                </div>
            </div>
        </div>

        <div class="cards-grid">
            <!-- Carte Bulletins de paie -->
            <a href="<?= constant('BASE_URL') ?>paie/fiche/1" class="consultation-card paie">
                <div class="card-icon-wrapper">💰</div>
                <div class="card-title">
                    Bulletins de Paie
                    <span class="card-arrow">→</span>
                </div>
                <p class="card-description">
                    Consultez et téléchargez tous vos bulletins de paie mensuels en un seul endroit.
                </p>
                <div class="card-features">
                    <div class="feature-item">
                        <span class="feature-icon">📄</span>
                        <span>Historique complet disponible</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">⬇️</span>
                        <span>Téléchargement en PDF</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">🔒</span>
                        <span>Accès sécurisé</span>
                    </div>
                </div>
            </a>

            <!-- Carte Solde de congés -->
            <a href="<?= constant('BASE_URL') ?>vers_solde_conge" class="consultation-card conges">
                <div class="card-icon-wrapper">🏖️</div>
                <div class="card-title">
                    Solde de Congés
                    <span class="card-arrow">→</span>
                </div>
                <p class="card-description">
                    Visualisez votre solde de congés, l'historique et planifiez vos prochaines absences.
                </p>
                <div class="card-features">
                    <div class="feature-item">
                        <span class="feature-icon">📊</span>
                        <span>Solde en temps réel</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">📅</span>
                        <span>Historique détaillé</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">✈️</span>
                        <span>Demande de congé rapide</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script>
        // Animation au scroll pour les cartes
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.consultation-card').forEach((card) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>