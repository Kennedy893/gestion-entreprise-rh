<style>
    .main-content {
        padding: 30px;
        width: 1350px;
        margin: 20px 350px;
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
        color: var(--primary);
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
        border-top: 5px solid var(--primary);
        padding: 30px;
        max-width: 1400px;
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
        color: var(--primary);
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

    /* --- ÉLÉMENTS DE FORMULAIRE --- */
    .form-group {
        margin-bottom: 25px;
    }
    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-main);
        font-size: 1rem;
    }
    .required {
        color: #ef4444; /* Rouge */
    }

    input[type="date"] {
        width: 100%;
        max-width: 300px;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 1rem;
        background-color: var(--bg-body);
        transition: border-color 0.2s;
    }
    input[type="date"]:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* --- CHOIX DU TYPE D'ATTESTATION (Cartes Radio) --- */
    .attestation-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .type-card {
        display: block;
        cursor: pointer;
        padding: 20px;
        border: 2px solid var(--border);
        border-radius: var(--radius);
        background-color: #f8fafc;
        transition: all 0.2s ease;
        position: relative;
    }

    .type-card:hover {
        border-color: var(--secondary);
    }

    .type-card input[type="radio"] {
        /* Masquer le radio bouton natif */
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* Style du contenu de la carte */
    .type-content {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .type-icon {
        font-size: 1.8rem;
        padding: 5px;
        background-color: rgba(79, 70, 229, 0.1);
        border-radius: var(--radius-sm);
        margin-bottom: 5px;
    }
    .type-label {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--text-main);
    }
    .type-description {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    
    /* Indicateur de sélection */
    .type-checkmark {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 20px;
        height: 20px;
        line-height: 20px;
        text-align: center;
        border-radius: 50%;
        background-color: var(--border);
        color: white;
        font-size: 0.9rem;
        display: none;
    }

    /* Style lorsque le radio est coché */
    .type-card input[type="radio"]:checked + .type-content {
        color: var(--secondary);
    }
    .type-card input[type="radio"]:checked ~ .type-checkmark {
        display: block;
        background-color: var(--secondary);
    }
    .type-card input[type="radio"]:checked {
        /* Le style de la carte au check est appliqué via JS pour les couleurs dynamiques */
    }
    
    /* --- INFORMATIONS DE LIVRAISON --- */
    .delivery-info {
        background-color: #f0fdf4; /* Vert très clair */
        border: 1px solid #dcfce7;
        padding: 20px;
        border-radius: var(--radius-sm);
        margin-top: 30px;
        margin-bottom: 30px;
    }
    .delivery-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--success);
        margin-bottom: 10px;
    }
    .delivery-list {
        list-style: none;
        padding-left: 0;
        font-size: 0.9rem;
        color: var(--text-main);
    }
    .delivery-list li {
        margin-bottom: 5px;
        position: relative;
        padding-left: 20px;
    }
    .delivery-list li::before {
        content: '✓';
        color: var(--success);
        font-weight: 700;
        position: absolute;
        left: 0;
    }

    /* --- BOUTONS D'ACTION --- */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .form-actions button {
        padding: 12px 20px;
        border: none;
        border-radius: var(--radius-sm);
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
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
    @media (max-width: 768px) {
        .attestation-types {
            grid-template-columns: 1fr;
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
            <div class="breadcrumb">
                <span>Demande d'attestation</span>
            </div>
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fa-solid fa-file-contract"></i></span>
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

            <form action="<?= constant('BASE_URL') ?>attes" method="post">
                <div class="form-group">
                    <label class="form-label">
                        Type d'attestation <span class="required">*</span>
                    </label>
                    <div class="attestation-types">
                        <label class="type-card" data-card-type="travail">
                            <input type="radio" name="attestation" value="0" required checked>
                            <div class="type-content">
                                <div class="type-icon">💼</div>
                                <div class="type-label">Attestation de Travail</div>
                                <div class="type-description">Certifie votre emploi actuel</div>
                            </div>
                            <span class="type-checkmark">✓</span>
                        </label>

                        <label class="type-card" data-card-type="salaire">
                            <input type="radio" name="attestation" value="1" required>
                            <div class="type-content">
                                <div class="type-icon">💰</div>
                                <div class="type-label">Attestation de Salaire</div>
                                <div class="type-description">Détail de vos revenus</div>
                            </div>
                            <span class="type-checkmark">✓</span>
                        </label>

                        <label class="type-card" data-card-type="presence">
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
                        <li>Délai de délivrance : **Moins de 24 heures**</li>
                    </ul>
                </div>

                <input type="hidden" name="id_employe" value="<?= $_SESSION['user_id'] ?? 1 ?>">

                <div class="form-actions">
                    <button type="button" onclick="window.history.back()">
                        ← Annuler
                    </button>
                    <button type="submit">
                        <i class="fa-solid fa-check"></i> Soumettre la demande
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
        // const form = document.querySelector('form');
        // form.addEventListener('submit', function(e) {
        //     const submitBtn = this.querySelector('button[type="submit"]');
        //     submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Envoi en cours...';
        //     submitBtn.disabled = true;
        // });

        // Gérer la sélection des cartes radio (Couleur et bordure)
        const radioInputs = document.querySelectorAll('input[type="radio"]');
        
        function updateCardStyles() {
            document.querySelectorAll('.type-card').forEach(card => {
                // Réinitialiser les styles
                card.style.borderColor = 'var(--border)';
                card.style.background = '#f8fafc';
            });
            
            const checkedRadio = document.querySelector('input[type="radio"]:checked');
            if (checkedRadio) {
                const card = checkedRadio.closest('.type-card');
                // Appliquer les styles de sélection
                card.style.borderColor = 'var(--secondary)';
                card.style.background = '#faf5ff';
            }
        }

        radioInputs.forEach(radio => {
            radio.addEventListener('change', updateCardStyles);
        });

        // Initialiser la première carte comme sélectionnée au chargement
        document.addEventListener('DOMContentLoaded', updateCardStyles);
    </script>
