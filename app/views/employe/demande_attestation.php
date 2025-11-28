<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d'Attestation - RH Manager</title>
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
            margin-bottom: 32px;
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
            font-size: 28px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 14px;
            margin-top: 8px;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 32px;
            max-width: 800px;
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .form-header-text h2 {
            font-size: 20px;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .form-header-text p {
            font-size: 13px;
            color: #64748b;
        }

        .form-info {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 14px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #92400e;
            display: flex;
            gap: 10px;
            align-items: start;
        }

        .form-info-icon {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .attestation-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .type-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .type-card:hover {
            border-color: #8b5cf6;
            background: #faf5ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.15);
        }

        .type-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .type-card input[type="radio"]:checked + .type-content {
            border-color: #8b5cf6;
        }

        .type-card input[type="radio"]:checked ~ .type-checkmark {
            opacity: 1;
            transform: scale(1);
        }

        .type-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
        }

        .type-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .type-card:hover .type-icon {
            background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
        }

        .type-label {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
        }

        .type-description {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
        }

        .type-checkmark {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 24px;
            height: 24px;
            background: #8b5cf6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            opacity: 0;
            transform: scale(0);
            transition: all 0.2s ease;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            color: #1e293b;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        input[type="date"]:focus,
        select:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        input[type="date"]:hover,
        select:hover {
            border-color: #cbd5e1;
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        .delivery-info {
            background: #eff6ff;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .delivery-title {
            font-size: 13px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .delivery-list {
            list-style: none;
            padding: 0;
        }

        .delivery-list li {
            font-size: 13px;
            color: #475569;
            padding: 6px 0;
            padding-left: 24px;
            position: relative;
        }

        .delivery-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #3b82f6;
            font-weight: 600;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
        }

        button {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            flex: 1;
        }

        button[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        button[type="button"] {
            background: white;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
        }

        button[type="button"]:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .form-container {
                padding: 24px;
            }

            .attestation-types {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            button {
                width: 100%;
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
                <a href="<?= constant('BASE_URL') ?>soumission">Soumission</a>
                <span class="breadcrumb-separator">›</span>
                <span>Demande d'attestation</span>
            </div>
            <h1 class="page-title">
                <span class="page-title-icon">📜</span>
                Demande d'Attestation
            </h1>
            <p class="page-subtitle">Obtenez votre attestation officielle en quelques clics</p>
        </div>

        <div class="form-container">
            <div class="form-header">
                <div class="form-icon">📜</div>
                <div class="form-header-text">
                    <h2>Nouvelle Demande d'Attestation</h2>
                    <p>Complétez le formulaire pour recevoir votre document certifié</p>
                </div>
            </div>

            <form action="#" method="post">
                <div class="form-group">
                    <label class="form-label">
                        Type d'attestation <span class="required">*</span>
                    </label>
                    <div class="attestation-types">
                        <label class="type-card">
                            <input type="radio" name="attestation" value="0" required checked>
                            <div class="type-content">
                                <div class="type-icon">💼</div>
                                <div class="type-label">Attestation de Travail</div>
                                <div class="type-description">Certifie votre emploi actuel</div>
                            </div>
                            <span class="type-checkmark">✓</span>
                        </label>

                        <label class="type-card">
                            <input type="radio" name="attestation" value="1" required>
                            <div class="type-content">
                                <div class="type-icon">💰</div>
                                <div class="type-label">Attestation de Salaire</div>
                                <div class="type-description">Détail de vos revenus</div>
                            </div>
                            <span class="type-checkmark">✓</span>
                        </label>

                        <label class="type-card">
                            <input type="radio" name="attestation" value="2" required>
                            <div class="type-content">
                                <div class="type-icon">✅</div>
                                <div class="type-label">Attestation de Présence</div>
                                <div class="type-description">Confirme votre assiduité</div>
                            </div>
                            <span class="type-checkmark">✓</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="date_demande">
                        Date de demande <span class="required">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="date_demande" 
                        name="date_demande" 
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="delivery-info">
                    <div class="delivery-title">
                        📧 Modalités de livraison
                    </div>
                    <ul class="delivery-list">
                        <li>Document certifié et signé électroniquement</li>
                        <li>Envoi par email à votre adresse professionnelle</li>
                        <li>Format PDF téléchargeable et imprimable</li>
                        <li>Notification de validation instantanée</li>
                    </ul>
                </div>

                <input type="hidden" name="id_employe" value="<?= $_SESSION['user_id'] ?? 1 ?>">

                <div class="form-actions">
                    <button type="button" onclick="window.history.back()">
                        ← Annuler
                    </button>
                    <button type="submit">
                        ✓ Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Définir la date par défaut à aujourd'hui
        const dateInput = document.getElementById('date_demande');
        if (!dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }

        // Animation de soumission
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '⏳ Envoi en cours...';
            submitBtn.disabled = true;
        });

        // Gérer la sélection des cartes radio
        const radioInputs = document.querySelectorAll('input[type="radio"]');
        radioInputs.forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.type-card').forEach(card => {
                    card.style.borderColor = '#e2e8f0';
                    card.style.background = '#f8fafc';
                });
                
                if (this.checked) {
                    const card = this.closest('.type-card');
                    card.style.borderColor = '#8b5cf6';
                    card.style.background = '#faf5ff';
                }
            });
        });

        // Initialiser la première carte comme sélectionnée
        const firstRadio = document.querySelector('input[type="radio"]:checked');
        if (firstRadio) {
            const firstCard = firstRadio.closest('.type-card');
            firstCard.style.borderColor = '#8b5cf6';
            firstCard.style.background = '#faf5ff';
        }
    </script>
</body>
</html>