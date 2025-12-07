<style>
    .main-content {
        width: 1350px;
        padding: 30px;
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

    /* --- GRILLE DE CARTES --- */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .request-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid var(--border);
    }

    .request-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        border-color: transparent; /* Masquer la bordure claire au hover */
    }
    
    /* --- Card Structure --- */
    .card-header {
        display: flex;
        justify-content: space-between;
        padding: 20px 25px 0 25px;
    }
    .card-body {
        padding: 20px 25px;
        flex-grow: 1;
    }
    .card-footer {
        padding: 15px 25px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom-left-radius: var(--radius);
        border-bottom-right-radius: var(--radius);
    }

    /* --- Couleurs et Iconographie --- */
    .request-card.conge { border-left: 5px solid var(--color-conge); }
    .request-card.attestation { border-left: 5px solid var(--color-attestation); }
    .request-card.remboursement { border-left: 5px solid var(--color-remboursement); }

    .card-icon-wrapper {
        font-size: 2.2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-body);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .request-card.conge .card-icon-wrapper { color: var(--color-conge); background-color: rgba(16, 185, 129, 0.1); }
    .request-card.attestation .card-icon-wrapper { color: var(--color-attestation); background-color: rgba(59, 130, 246, 0.1); }
    .request-card.remboursement .card-icon-wrapper { color: var(--color-remboursement); background-color: rgba(245, 158, 11, 0.1); }


    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 10px 0;
    }

    .card-description {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    /* --- Info List --- */
    .card-info-list {
        margin-top: 15px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: var(--text-main);
    }
    
    .info-item-icon {
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    .request-card.conge .info-item-icon { color: var(--color-conge); }
    .request-card.attestation .info-item-icon { color: var(--color-attestation); }
    .request-card.remboursement .info-item-icon { color: var(--color-remboursement); }

    /* --- Footer --- */
    .footer-time {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .footer-arrow {
        font-size: 1.5rem;
        color: var(--primary-dark);
        transition: transform 0.3s ease;
    }

    .request-card:hover .footer-arrow {
        transform: translateX(5px);
        color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cards-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<body>
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fa-solid fa-file-circle-plus"></i></span>
                Soumettre une Demande
            </h1>
            <p class="page-subtitle">
                Sélectionnez le type de demande que vous souhaitez effectuer
            </p>
        </div>

        <div class="cards-grid">
            <a href="<?= constant('BASE_URL') ?>vers_demande_conge" class="request-card conge">
                <div class="card-header">
                    <div class="card-icon-wrapper">🏖️</div>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Demande de Congé</h3>
                    <p class="card-description">
                        Soumettez une demande de congé payé, exceptionnel ou pour maladie. Consultez votre solde en temps réel.
                    </p>
                    <div class="card-info-list">
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-solid fa-clock-rotate-left"></i></span>
                            <span>Traitement sous 48h</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-regular fa-file-lines"></i></span>
                            <span>Justificatifs optionnels</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-solid fa-chart-line"></i></span>
                            <span>Suivi en temps réel</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span class="footer-time">⏰ Toujours disponible</span>
                    <span class="footer-arrow">→</span>
                </div>
            </a>

            <a href="<?= constant('BASE_URL') ?>demande_attestation" class="request-card attestation">
                <div class="card-header">
                    <div class="card-icon-wrapper">📜</div>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Demande d'Attestation</h3>
                    <p class="card-description">
                        Obtenez vos attestations de travail, de salaire ou toute autre attestation administrative nécessaire.
                    </p>
                    <div class="card-info-list">
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-solid fa-bolt"></i></span>
                            <span>Délivrance sous 24h</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-solid fa-lock"></i></span>
                            <span>Documents certifiés</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-solid fa-envelope"></i></span>
                            <span>Envoi par email</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span class="footer-time">⏰ Service express</span>
                    <span class="footer-arrow">→</span>
                </div>
            </a>

            <a href="<?= constant('BASE_URL') ?>demande_remboursement" class="request-card remboursement">
                <div class="card-header">
                    <div class="card-icon-wrapper">💵</div>
                </div>
                <div class="card-body">
                    <h3 class="card-title">Demande de Remboursement</h3>
                    <p class="card-description">
                        Demandez le remboursement de vos frais professionnels : déplacements, repas, formation, etc.
                    </p>
                    <div class="card-info-list">
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-regular fa-credit-card"></i></span>
                            <span>Paiement sous 7 jours</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-solid fa-paperclip"></i></span>
                            <span>Joindre les justificatifs</span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-icon"><i class="fa-solid fa-square-check"></i></span>
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