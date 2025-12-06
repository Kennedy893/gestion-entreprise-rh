<style>
    .main-content {
        padding: 30px;
        max-width: 1400px;
        margin: 20px auto;
    }

    /* --- EN-TÊTE DE PAGE --- */
    .page-header {
        margin-bottom: 30px;
    }
    .breadcrumb {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 10px;
    }
    .breadcrumb a {
        color: var(--text-muted);
        text-decoration: none;
    }
    .breadcrumb-separator {
        margin: 0 5px;
    }
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
        display: flex;
        align-items: center;
    }
    .page-title-icon {
        font-size: 1.5em;
        margin-right: 10px;
        color: var(--accent);
    }
    .page-subtitle {
        font-size: 1rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* --- CONTENEUR DE FORMULAIRE --- */
    .form-container {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border-top: 5px solid var(--accent);
        padding: 30px;
    }

    .form-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }
    .form-icon {
        font-size: 2rem;
        color: var(--accent);
    }
    .form-header-text h2 {
        font-size: 1.5rem;
        margin: 0;
        color: var(--text-main);
    }
    .form-header-text p {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin: 5px 0 0 0;
    }
    
    /* --- INFO BANNER --- */
    .form-info {
        display: flex;
        align-items: center;
        padding: 15px;
        background-color: var(--info-bg);
        color: var(--info-text);
        border-radius: var(--radius-sm);
        margin-bottom: 25px;
        font-size: 0.9rem;
    }
    .form-info-icon {
        font-size: 1.5rem;
        margin-right: 10px;
        flex-shrink: 0;
    }

    /* --- ÉLÉMENTS DE FORMULAIRE --- */
    .form-row {
        display: flex;
        gap: 30px;
        margin-bottom: 25px;
    }
    .form-group {
        flex: 1;
    }
    .form-group.full-width {
        flex: 0 0 100%;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--text-main);
        font-size: 1rem;
    }
    .required {
        color: var(--danger);
    }
    .hint {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-left: 5px;
    }

    input[type="number"],
    input[type="date"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 1rem;
        background-color: var(--bg-body);
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    input:focus, textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Input avec icône */
    .input-with-icon {
        position: relative;
    }
    .input-with-icon input {
        padding-left: 40px;
    }
    .input-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
        font-size: 1.2rem;
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .char-count {
        text-align: right;
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 5px;
    }
    #charCount {
        font-weight: 600;
        color: var(--text-main);
    }

    /* --- UPLOAD DE FICHIERS --- */
    .file-upload-container {
        margin-top: 10px;
    }

    .file-upload-area {
        border: 2px dashed var(--border);
        border-radius: var(--radius-sm);
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        background-color: var(--bg-body);
    }

    .file-upload-area:hover, .file-upload-area.dragover {
        border-color: var(--primary);
        background-color: #f8fafc;
    }

    .file-upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-upload-icon {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .file-upload-text {
        font-weight: 500;
        color: var(--text-main);
        margin-bottom: 5px;
    }
    .file-upload-hint {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* Liste des fichiers */
    .file-list {
        margin-top: 20px;
        border-top: 1px solid var(--border);
        padding-top: 15px;
    }

    .file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        margin-bottom: 10px;
        background-color: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
    }
    .file-icon {
        font-size: 1.2rem;
        color: var(--accent);
        flex-shrink: 0;
    }
    .file-name {
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 250px;
    }
    .file-size {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    .file-remove {
        background: none;
        border: none;
        color: var(--danger);
        cursor: pointer;
        font-size: 1rem;
        padding: 5px;
        line-height: 1;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .file-remove:hover {
        opacity: 1;
    }

    /* --- BOUTONS D'ACTION --- */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding-top: 20px;
        margin-top: 30px;
        border-top: 1px solid var(--border);
    }

    .form-actions button {
        padding: 12px 20px;
        border: none;
        border-radius: var(--radius-sm);
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-actions button:first-child { /* Annuler */
        background-color: var(--border);
        color: var(--text-main);
    }
    .form-actions button:first-child:hover {
        background-color: #cbd5e1;
    }

    .form-actions button[type="submit"] {
        background-color: var(--primary);
        color: white;
    }
    .form-actions button[type="submit"]:hover:not(:disabled) {
        background-color: var(--primary-dark);
    }
    .form-actions button[type="submit"]:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 600px) {
        .form-row {
            flex-direction: column;
            gap: 25px;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions button {
            width: 100%;
        }
    }
</style>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                Demande de Remboursement
            </h1>
            <p class="page-subtitle">Soumettez votre demande de remboursement de frais professionnels</p>
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
                    <strong>Important :</strong> Les justificatifs (factures, reçus, tickets) sont **obligatoires**. Le remboursement sera effectué sous 7 jours après validation.
                </div>
            </div>

            <form action="#" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="montant">
                            Montant demandé <span class="required">*</span>
                            <span class="hint">(en Ar)</span>
                        </label>
                        <div class="input-with-icon">
                            <span class="input-icon"><i class="fa-solid fa-money-bill-wave"></i></span>
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
                                name="justificatifs[]" 
                                class="file-upload-input"
                                accept=".pdf,.jpg,.jpeg,.png"
                                multiple
                                required
                            >
                            <div class="file-upload-icon"><i class="fa-solid fa-file-arrow-up"></i></div>
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
                        <i class="fa-solid fa-arrow-left"></i> Annuler
                    </button>
                    <button type="submit" id="submitBtn">
                        <i class="fa-solid fa-check"></i> Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- LOGIQUE JS ---

        // 1. Compteur de caractères
        function updateCharCount(textarea) {
            const count = textarea.value.length;
            document.getElementById('charCount').textContent = count;
        }

        // 2. Gestion de l'upload de fichiers (Drag & Drop et Sélection)
        const fileInput = document.getElementById('justificatifs');
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileList = document.getElementById('fileList');
        let selectedFiles = []; // Array pour stocker les fichiers valides

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

            // Vider l'ancienne sélection de l'input et reconstruire entièrement
            // (méthode simple pour éviter les doublons et gérer l'ajout/suppression)
            selectedFiles = [];

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

                // Ajouter le fichier à la liste des fichiers valides
                selectedFiles.push(file);
            });

            updateFileInput();
            renderFileList();
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
                <button type="button" class="file-remove" data-file-name="${file.name}">
                    ✕
                </button>
            `;
            
            fileList.appendChild(fileItem);
        }

        function removeFile(fileName) {
            // Filtrer le fichier à supprimer de l'array
            selectedFiles = selectedFiles.filter(file => file.name !== fileName);
            
            // Reconstruire l'input file et la liste visuelle
            updateFileInput();
            renderFileList();
        }

        function renderFileList() {
            fileList.innerHTML = '';
            selectedFiles.forEach(file => displayFile(file));
        }

        function updateFileInput() {
            // Reconstruire un DataTransfer pour mettre à jour l'input file
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
            
            // Mettre à jour l'attribut required en fonction de la présence de fichiers
            if (selectedFiles.length > 0) {
                fileInput.removeAttribute('required');
            } else {
                fileInput.setAttribute('required', 'required');
            }
        }
        
        // Écouteur pour la suppression des fichiers (utilise la délégation d'événements)
        fileList.addEventListener('click', (e) => {
            if (e.target.classList.contains('file-remove')) {
                const fileName = e.target.getAttribute('data-file-name');
                removeFile(fileName);
            }
        });


        // 3. Validation du formulaire et soumission
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            // La validation des fichiers est gérée par l'attribut required et la fonction updateFileInput,
            // mais on peut ajouter une double vérification ici:
            if (selectedFiles.length === 0) {
                e.preventDefault();
                alert('Veuillez joindre au moins un justificatif.');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Envoi en cours...';
            submitBtn.disabled = true;
        });

        // 4. Initialisations au chargement
        document.addEventListener('DOMContentLoaded', () => {
             // Définir la date max à aujourd'hui (utile si le PHP échoue)
            const dateInput = document.getElementById('date');
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('max', today);
            if (!dateInput.value) {
                dateInput.value = today;
            }
            
            // Initialisation du compteur de caractères
            updateCharCount(document.getElementById('raison'));
        });
    </script>