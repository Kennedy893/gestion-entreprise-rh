<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Remboursement - RH Manager</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
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
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 14px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #065f46;
            display: flex;
            gap: 10px;
            align-items: start;
        }

        .form-info-icon {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
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

        .form-label .hint {
            font-weight: 400;
            color: #94a3b8;
            font-size: 12px;
            margin-left: 4px;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
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

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        input[type="text"]:hover,
        input[type="date"]:hover,
        input[type="number"]:hover,
        textarea:hover {
            border-color: #cbd5e1;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
            line-height: 1.6;
        }

        .char-count {
            text-align: right;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 6px;
        }

        /* Styles pour l'upload de fichier */
        .file-upload-container {
            margin-top: 8px;
        }

        .file-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 32px;
            text-align: center;
            background: #f8fafc;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .file-upload-area:hover {
            border-color: #10b981;
            background: #ecfdf5;
        }

        .file-upload-area.dragover {
            border-color: #10b981;
            background: #d1fae5;
        }

        .file-upload-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        .file-upload-text {
            font-size: 14px;
            color: #475569;
            margin-bottom: 8px;
        }

        .file-upload-text strong {
            color: #10b981;
            font-weight: 600;
        }

        .file-upload-hint {
            font-size: 12px;
            color: #94a3b8;
        }

        .file-list {
            margin-top: 16px;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }

        .file-item:hover {
            background: #f1f5f9;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .file-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .file-details {
            flex: 1;
        }

        .file-name {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .file-size {
            font-size: 11px;
            color: #94a3b8;
        }

        .file-remove {
            width: 28px;
            height: 28px;
            border: none;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .file-remove:hover {
            background: #fecaca;
            transform: scale(1.1);
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #94a3b8;
        }

        .input-with-icon input {
            padding-left: 40px;
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            flex: 1;
        }

        button[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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
                <span>Demande de remboursement</span>
            </div>
            <h1 class="page-title">
                <span class="page-title-icon">💵</span>
                Demande de Remboursement
            </h1>
            <p class="page-subtitle">Soumettez votre demande de remboursement de frais professionnels</p>
            <!-- affichage message -->
            <?php if (isset($message)) : ?>
                <div class="form-info" style="margin-top: 16px; <?php echo strpos($message, 'Erreur') !== false ? 'border-color: #ef4444; background: #fee2e2; color: #b91c1c;' : ''; ?>">
                    <span class="form-info-icon">ℹ️</span>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-container">
            <div class="form-header">
                <div class="form-icon">💵</div>
                <div class="form-header-text">
                    <h2>Nouveau Remboursement</h2>
                    <p>Complétez tous les champs et joignez vos justificatifs</p>
                </div>
            </div>

            <div class="form-info">
                <span class="form-info-icon">ℹ️</span>
                <div>
                    <strong>Important :</strong> Les justificatifs sont obligatoires (factures, reçus, tickets). Le remboursement sera effectué sous 7 jours après validation.
                </div>
            </div>

            <form action="<?= constant('BASE_URL') ?>demande_remboursement" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="montant">
                            Montant demandé <span class="required">*</span>
                            <span class="hint">(en Ar)</span>
                        </label>
                        <div class="input-with-icon">
                            <span class="input-icon">💰</span>
                            <input 
                                type="number" 
                                id="montant" 
                                name="montant" 
                                placeholder="Ex: 150000" 
                                step="0.01"
                                min="0"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="date">
                            Date de la dépense <span class="required">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="date" 
                            name="date" 
                            value="<?= date('Y-m-d') ?>"
                            max="<?= date('Y-m-d') ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label" for="raison">
                        Motif du remboursement <span class="required">*</span>
                    </label>
                    <textarea 
                        id="raison" 
                        name="raison" 
                        placeholder="Décrivez précisément le motif de votre demande (déplacement, repas, matériel, formation, etc.)"
                        maxlength="500"
                        required
                        oninput="updateCharCount(this)"
                    ></textarea>
                    <div class="char-count">
                        <span id="charCount">0</span> / 500 caractères
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">
                        Justificatifs <span class="required">*</span>
                        <span class="hint">(Format: PDF, JPG, PNG - Max: 5 Mo par fichier)</span>
                    </label>
                    <div class="file-upload-container">
                        <div class="file-upload-area" id="fileUploadArea">
                            <input 
                                type="file" 
                                id="justificatifs" 
                                name="fichier[]" 
                                class="file-upload-input"
                                accept=".pdf,.jpg,.jpeg,.png"
                                multiple
                                required
                            >
                            <div class="file-upload-icon">📎</div>
                            <div class="file-upload-text">
                                <strong>Cliquez pour parcourir</strong> ou glissez-déposez vos fichiers
                            </div>
                            <div class="file-upload-hint">
                                PDF, JPG ou PNG - Maximum 5 Mo par fichier
                            </div>
                        </div>
                        <div class="file-list" id="fileList"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="window.history.back()">
                        ← Annuler
                    </button>
                    <button type="submit" id="submitBtn">
                        ✓ Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Compteur de caractères
        function updateCharCount(textarea) {
            const count = textarea.value.length;
            document.getElementById('charCount').textContent = count;
        }

        // Gestion de l'upload de fichiers
        const fileInput = document.getElementById('justificatifs');
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileList = document.getElementById('fileList');
        let selectedFiles = [];

        // Empêcher le comportement par défaut du drag & drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Ajouter les effets visuels au drag
        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, () => {
                fileUploadArea.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, () => {
                fileUploadArea.classList.remove('dragover');
            });
        });

        // Gérer le drop
        fileUploadArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            handleFiles(files);
        });

        // Gérer la sélection de fichiers
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            const maxSize = 5 * 1024 * 1024; // 5 Mo
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];

            Array.from(files).forEach(file => {
                // Vérifier la taille
                if (file.size > maxSize) {
                    alert(`Le fichier "${file.name}" est trop volumineux (max 5 Mo)`);
                    return;
                }

                // Vérifier le type
                if (!allowedTypes.includes(file.type)) {
                    alert(`Le fichier "${file.name}" n'est pas au bon format (PDF, JPG, PNG uniquement)`);
                    return;
                }

                // Ajouter le fichier à la liste
                selectedFiles.push(file);
                displayFile(file);
            });

            updateFileInput();
        }

        function displayFile(file) {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            
            const fileSize = (file.size / 1024).toFixed(1);
            const fileExtension = file.name.split('.').pop().toUpperCase();
            
            fileItem.innerHTML = `
                <div class="file-info">
                    <div class="file-icon">📄</div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${fileSize} Ko • ${fileExtension}</div>
                    </div>
                </div>
                <button type="button" class="file-remove" onclick="removeFile('${file.name}')">
                    ✕
                </button>
            `;
            
            fileList.appendChild(fileItem);
        }

        function removeFile(fileName) {
            selectedFiles = selectedFiles.filter(file => file.name !== fileName);
            updateFileInput();
            renderFileList();
        }

        function renderFileList() {
            fileList.innerHTML = '';
            selectedFiles.forEach(file => displayFile(file));
        }

        function updateFileInput() {
            // Créer un nouveau DataTransfer pour mettre à jour l'input
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        // Validation du formulaire
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            if (selectedFiles.length === 0) {
                e.preventDefault();
                alert('Veuillez joindre au moins un justificatif.');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '⏳ Envoi en cours...';
            submitBtn.disabled = true;
        });

        // Définir la date max à aujourd'hui
        const dateInput = document.getElementById('date');
        dateInput.setAttribute('max', new Date().toISOString().split('T')[0]);
    </script>
</body>
</html>