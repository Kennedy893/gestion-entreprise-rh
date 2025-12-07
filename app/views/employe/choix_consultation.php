<style>
    .main-content {
        padding: 30px;
        width: 1400px;
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

    /* --- BANNIÈRE D'INFORMATION --- */
    .info-banner {
        display: flex;
        align-items: flex-start;
        padding: 20px 25px;
        border-radius: var(--radius);
        margin-bottom: 30px;
        background-color: var(--info-bg);
        border: 1px solid var(--info-text);
    }
    .info-banner-icon {
        font-size: 2rem;
        margin-right: 20px;
        flex-shrink: 0;
    }
    .info-banner-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--info-text);
        margin-bottom: 5px;
    }
    .info-banner-text {
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    
    /* --- GRILLE DE CARTES --- */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .consultation-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        padding: 30px;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid var(--border);
    }

    .consultation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    /* Bordure et icône de couleur thématique */
    .consultation-card.paie { border-top: 5px solid var(--color-paie); }
    .consultation-card.conges { border-top: 5px solid var(--color-conges); }

    .card-icon-wrapper {
        font-size: 2rem;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        background-color: var(--bg-body);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .consultation-card.paie .card-icon-wrapper { color: var(--color-paie); }
    .consultation-card.conges .card-icon-wrapper { color: var(--color-conges); }


    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .card-arrow {
        font-size: 1.5rem;
        color: var(--text-muted);
        transition: transform 0.3s ease;
    }

    .consultation-card:hover .card-arrow {
        transform: translateX(5px);
        color: var(--primary);
    }

    .card-description {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-bottom: 25px;
        flex-grow: 1; /* Pousse les features vers le bas */
    }

    /* --- Caractéristiques (Features) --- */
    .card-features {
        padding-top: 15px;
        border-top: 1px solid var(--border);
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: var(--text-main);
    }
    
    .feature-icon {
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    /* Couleur des icônes de feature */
    .consultation-card.paie .feature-icon { color: var(--color-paie); }
    .consultation-card.conges .feature-icon { color: var(--color-conges); }

    /* Responsive */
    @media (max-width: 768px) {
        .cards-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main-content">
    <div class="page-header">        
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fa-solid fa-book-open"></i></span>
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
        <a href="<?= constant('BASE_URL') ?>paie/fiche/1" class="consultation-card paie">
            <div class="card-icon-wrapper"><i class="fa-solid fa-money-check-dollar"></i></div>
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
                    <span>Accès sécurisé et personnel</span>
                </div>
            </div>
        </a>

        <a href="<?= constant('BASE_URL') ?>vers_solde_conge" class="consultation-card conges">
            <div class="card-icon-wrapper"><i class="fa-solid fa-umbrella-beach"></i></div>
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
                    <span>Historique annuel détaillé</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">✈️</span>
                    <span>Demande de congé rapide</span>
                </div>
            </div>
        </a>
    </div>
</div>