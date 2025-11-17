<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Congé - RH Manager</title>
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

        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 14px;
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
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
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

        .form-group {
            margin-bottom: 24px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        input[type="date"],
        select,
        textarea {
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
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        input[type="date"]:hover,
        select:hover,
        textarea:hover {
            border-color: #cbd5e1;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
            line-height: 1.6;
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        .form-info {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #1e40af;
            display: flex;
            gap: 10px;
        }

        .form-info-icon {
            font-size: 16px;
            flex-shrink: 0;
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
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            flex: 1;
        }

        button[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
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

        .input-icon {
            position: relative;
        }

        .input-icon::before {
            content: attr(data-icon);
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
        }

        .input-icon input,
        .input-icon select {
            padding-left: 40px;
        }

        .char-count {
            text-align: right;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 6px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .form-container {
                padding: 24px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 24px;
            }

            .form-container {
                padding: 20px;
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
                <a href="<?= constant('BASE_URL') ?>conges">Congés</a>
                <span class="breadcrumb-separator">›</span>
                <span>Nouvelle demande</span>
            </div>
            <h1 class="page-title">Demande de Congé</h1>
            <p class="page-subtitle">Remplissez le formulaire ci-dessous pour soumettre votre demande</p>
        </div>

        <div class="form-container">
            <div class="form-header">
                <div class="form-icon">📅</div>
                <div class="form-header-text">
                    <h2>Nouvelle Demande</h2>
                    <p>Tous les champs marqués d'un astérisque (*) sont obligatoires</p>
                </div>
            </div>

            <div class="form-info">
                <span class="form-info-icon">ℹ️</span>
                <div>
                    <strong>Information importante :</strong> Votre demande sera envoyée à votre responsable pour validation. Vous recevrez une notification par email dès qu'elle sera traitée.
                </div>
            </div>

            <form action="<?= constant('BASE_URL') ?>demande_conge" method="post">
                <div class="form-group">
                    <label for="date_demande">
                        Date de demande <span class="required">*</span>
                    </label>
                    <input type="date" id="date_demande" name="date_demande " required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_debut">
                            Date de début <span class="required">*</span>
                        </label>
                        <input type="date" id="date_debut" name="date_debut" required>
                    </div>

                    <div class="form-group">
                        <label for="date_fin">
                            Date de fin <span class="required">*</span>
                        </label>
                        <input type="date" id="date_fin" name="date_fin" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="type_conge">
                        Type de congé <span class="required">*</span>
                    </label>
                    <select id="type_conge" name="type_conge" required>
                        <option value="">Sélectionnez un type de congé</option>
                        <option value="Conge normal">Congé normal</option>
                        <option value="Conge exceptionnel">Congé exceptionnel</option>
                        <option value="Conge maladie">Congé maladie</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="motif">
                        Motif <span class="required">*</span>
                    </label>
                    <textarea 
                        id="motif" 
                        name="motif" 
                        placeholder="Décrivez brièvement le motif de votre demande..."
                        maxlength="500"
                        required
                        oninput="updateCharCount(this)"
                    ></textarea>
                    <div class="char-count">
                        <span id="charCount">0</span> / 500 caractères
                    </div>
                </div>

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
        // Compteur de caractères pour le textarea
        function updateCharCount(textarea) {
            const count = textarea.value.length;
            document.getElementById('charCount').textContent = count;
        }

        // Validation des dates
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');

        // Définir la date minimale à aujourd'hui
        const today = new Date().toISOString().split('T')[0];
        dateDebut.setAttribute('min', today);

        dateDebut.addEventListener('change', function() {
            dateFin.setAttribute('min', this.value);
            if (dateFin.value && dateFin.value < this.value) {
                dateFin.value = this.value;
            }
        });

        // Animation de soumission
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '⏳ Envoi en cours...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>