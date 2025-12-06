<style>
    .main-content {
        padding: 30px;
        margin: auto;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
    }

    /* --- EN-TÊTE DE PAGE (Breadcrumb & Titres) --- */
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
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: var(--primary);
    }

    .breadcrumb-separator {
        margin: 0 5px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
    }

    .page-subtitle {
        font-size: 1rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* --- FORMULAIRE CONTENANT LA CARTE --- */
    .form-container {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-top: 5px solid var(--primary);
        padding: 30px;
    }

    .form-header {
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .form-icon {
        font-size: 2rem;
    }

    .form-header-text h2 {
        font-size: 1.5rem;
        color: var(--text-main);
        margin: 0;
    }

    .form-header-text p {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin: 0;
    }
    
    /* --- BOÎTE D'INFORMATION --- */
    .form-info {
        display: flex;
        align-items: flex-start;
        background-color: var(--info-bg);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 30px;
        border: 1px solid var(--info-border);
        color: var(--info-text);
        font-size: 0.95rem;
    }

    .form-info-icon {
        font-size: 1.2rem;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .form-info strong {
        font-weight: 600;
    }

    /* --- ÉLÉMENTS DU FORMULAIRE --- */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-main);
        font-size: 0.95rem;
    }

    .required {
        color: var(--primary);
    }

    input[type="date"],
    select,
    textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s, box-shadow 0.3s;
        box-sizing: border-box;
        font-family: 'Outfit', sans-serif;
        background-color: var(--bg-body);
    }

    input[type="date"]:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        background-color: var(--bg-card);
    }

    /* --- LIGNE DE FORMULAIRE (pour côte à côte) --- */
    .form-row {
        display: flex;
        gap: 20px;
    }

    .form-row .form-group {
        flex: 1;
    }
    
    /* --- TEXTAREA et COMPTEUR --- */
    textarea {
        resize: vertical;
        min-height: 120px;
    }

    .char-count {
        text-align: right;
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 5px;
    }

    .char-count #charCount {
        font-weight: 600;
        color: var(--primary);
    }

    /* --- BOUTONS D'ACTION --- */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        margin-top: 30px;
    }

    .form-actions button {
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: background-color 0.2s, transform 0.2s;
    }

    .form-actions button[type="submit"] {
        background-color: var(--primary);
        color: white;
        border: none;
    }

    .form-actions button[type="submit"]:hover:not(:disabled) {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
    }
    
    .form-actions button[type="submit"]:disabled {
        background-color: var(--text-muted);
        cursor: not-allowed;
    }

    .form-actions button[type="button"] { /* Annuler */
        background-color: var(--border);
        color: var(--text-main);
        border: 1px solid var(--text-muted);
    }

    .form-actions button[type="button"]:hover {
        background-color: #e5e7eb;
    }

    /* --- Responsive --- */
    @media (max-width: 600px) {
        .form-row {
            flex-direction: column;
        }
        .form-actions {
            flex-direction: column-reverse; /* Soumettre en haut */
            gap: 10px;
        }
        .form-actions button {
            width: 100%;
        }
    }
</style>

<div class="main-content">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Demande de Congé</h1>
            <p class="page-subtitle">Remplissez le formulaire ci-dessous pour soumettre votre demande</p>
        </div>
        
        <div class="form-container">
            <div class="form-header">
                <div class="form-icon">📅</div>
                <div class="form-header-text">
                    <h2>Nouvelle Demande</h2>
                    <p>Tous les champs marqués d'un astérisque (<span class="required">*</span>) sont obligatoires</p>
                </div>
            </div>

            <div class="form-info">
                <span class="form-info-icon">ℹ️</span>
                <div>
                    <strong>Information importante :</strong> Votre demande sera envoyée à votre responsable pour validation. Vous recevrez une notification par email dès qu'elle sera traitée.
                </div>
            </div>

            <form action="<?= constant('BASE_URL') ?>demande_conge" method="post" id="congeForm">
                <div class="form-group">
                    <label for="date_demande">
                        Date de demande <span class="required">*</span>
                    </label>
                    <input type="date" id="date_demande" name="date_demande" required value="<?= date('Y-m-d') ?>" readonly>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateDemande = document.getElementById('date_demande');
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');
        const form = document.getElementById('congeForm');

        // Initialisation de la date de demande (Lecture seule et définie en PHP)
        if (dateDemande) {
            // S'assurer que la date de demande est définie sur aujourd'hui si elle ne l'est pas
            if (!dateDemande.value) {
                dateDemande.value = new Date().toISOString().split('T')[0];
            }
            // Date de demande toujours désactivée visuellement/lecture seule
            dateDemande.setAttribute('readonly', true);
            dateDemande.style.backgroundColor = 'var(--bg-body)';
            dateDemande.style.cursor = 'not-allowed';
        }

        // --- Logique de validation des dates ---
        const today = new Date().toISOString().split('T')[0];
        
        // La date de début ne peut pas être antérieure à aujourd'hui
        if (dateDebut) {
            dateDebut.setAttribute('min', today);
        }

        if (dateDebut && dateFin) {
            dateDebut.addEventListener('change', function() {
                // La date de fin ne peut pas être antérieure à la date de début
                dateFin.setAttribute('min', this.value);
                if (dateFin.value && dateFin.value < this.value) {
                    dateFin.value = this.value;
                }
            });
        }
        
        // --- Compteur de caractères pour le textarea ---
        const charCountElement = document.getElementById('charCount');
        const motifTextarea = document.getElementById('motif');
        
        window.updateCharCount = function(textarea) {
            charCountElement.textContent = textarea.value.length;
        }

        // --- Animation de soumission ---
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Vérification de base avant animation
            if (form.checkValidity()) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Envoi en cours...';
                submitBtn.disabled = true;
            }
        });
    });
</script>