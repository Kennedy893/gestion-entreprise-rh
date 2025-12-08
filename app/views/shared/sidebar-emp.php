<link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/home.css">

<style>
    /* --- SIDEBAR EMPLOYÉ --- */
    :root {
        --primary: #3b82f6;
        /* Bleu vif */
        --primary-dark: #2563eb;
        --secondary: #64748b;
        /* Gris ardoise */
        --bg-sidebar: #1e293b;
        /* Slate foncé */
        --bg-hover: rgba(255, 255, 255, 0.08);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --submenu-bg: #273548;
        --submenu-hover: rgba(255, 255, 255, 0.1);

        --sidebar-width: 260px;
        --header-height: 70px;
    }

    /* --- Structure de base --- */
    .sidebar.employe {
        width: var(--sidebar-width);
        background-color: var(--bg-sidebar);
        color: var(--text-main);
        position: fixed;
        height: 100vh;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    /* --- En-tête / marque --- */
    .sidebar.employe .brand {
        height: var(--header-height);
        display: flex;
        align-items: center;
        padding: 0 24px;
        font-size: 1.25rem;
        font-weight: 700;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar.employe .brand i {
        margin-right: 10px;
        color: var(--primary);
    }

    /* --- Liens principaux --- */
    .sidebar.employe .nav-menu {
        list-style: none;
        padding: 20px 10px;
        margin: 0;
        flex-grow: 1;
    }

    .sidebar.employe .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: var(--text-muted);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .sidebar.employe .nav-link i {
        width: 25px;
        margin-right: 10px;
        text-align: center;
    }

    .sidebar.employe .nav-link:hover {
        background-color: var(--bg-hover);
        color: #fff;
    }

    .sidebar.employe .nav-link.active {
        background-color: var(--primary);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4);
    }

    /* --- Sous-menus --- */
    .sidebar.employe .arrow-icon {
        transition: transform 0.3s ease;
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .sidebar.employe .nav-item.has-submenu.open .arrow-icon {
        transform: rotate(90deg);
    }

    .sidebar.employe .submenu {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }

    .sidebar.employe .submenu li a {
        display: block;
        padding: 8px 20px 8px 55px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.9rem;
        background-color: var(--submenu-bg);
    }

    .sidebar.employe .submenu li a:hover {
        background-color: var(--submenu-hover);
        color: white;
    }

    .sidebar.employe .nav-item.has-submenu.open .submenu {
        max-height: 500px;
    }
</style>

<nav class="sidebar-employe">
    <div class="brand">
        <i class="fa-solid fa-sack-dollar"></i>
    </div>

    <ul class="nav-menu">

        <li class="nav-item">
            <a href="<?= constant('BASE_URL') ?>" class="nav-link">
                <i class="fa-solid fa-chart-pie"></i> Tableau de bord
            </a>
        </li>

        <li class="nav-item has-submenu">
            <a href="#" class="nav-link dropdown-toggle">
                <i class="fa-solid fa-users"></i> Gestion Employés
                <i class="fa-solid fa-chevron-right arrow-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?= constant('BASE_URL') ?>time/employees"> <i class="fa-solid fa-list"></i> Liste Employés</a></li>
                <li><a href="<?= constant('BASE_URL') ?>time/presences"> <i class="fa-solid fa-fingerprint"></i> Pointages</a></li>
                <li><a href="<?= constant('BASE_URL') ?>time/form-sheet"> <i class="fa-solid fa-calendar-alt"></i> Feuilles de temps</a></li>
            </ul>
        </li>

        <li class="nav-item has-submenu">
            <a href="#" class="nav-link dropdown-toggle">
                <i class="fa-solid fa-user-tie"></i> Services RH
                <i class="fa-solid fa-chevron-right arrow-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?= constant('BASE_URL') ?>vers_messagerie"> <i class="fa-solid fa-comments"></i> Messagerie interne</a></li>
                <li><a href="<?= constant('BASE_URL') ?>choose_consultation"> <i class="fa-solid fa-magnifying-glass"></i> Consultation</a></li>
                <li><a href="<?= constant('BASE_URL') ?>choose_soumission"> <i class="fa-solid fa-paper-plane"></i> Soumission</a></li>
                <li><a href="<?= constant('BASE_URL') ?>validation_rh"> <i class="fa-solid fa-user-check"></i> Validation congés</a></li>
            </ul>
        </li>

        <li class="nav-item has-submenu">
            <a href="#" class="nav-link dropdown-toggle">
                <i class="fa-solid fa-calculator"></i> Paie
                <i class="fa-solid fa-chevron-right arrow-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?= constant('BASE_URL') ?>paie"> <i class="fa-solid fa-file-invoice"></i> État de paie</a></li>
            </ul>
        </li>

        <!-- Menu spécifique Employé -->
        <li class="nav-item has-submenu">
            <a href="#" class="nav-link dropdown-toggle">
                <i class="fa-solid fa-umbrella-beach"></i> Congés
                <i class="fa-solid fa-chevron-right arrow-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?= constant('BASE_URL') ?>vers_demande_conge"><i class="fa-solid fa-file-import"></i> Demande congé</a></li>
                <li><a href="<?= constant('BASE_URL') ?>liste_conge"><i class="fa-solid fa-list-check"></i> Mes congés</a></li>
                <li><a href="<?= constant('BASE_URL') ?>vers_solde_conge"><i class="fa-solid fa-coins"></i> Mon solde</a></li>
            </ul>
        </li>

        <li class="nav-item has-submenu">
            <a href="#" class="nav-link dropdown-toggle">
                <i class="fa-solid fa-headset"></i> Services
                <i class="fa-solid fa-chevron-right arrow-icon"></i>
            </a>
            <ul class="submenu">
                <li><a href="<?= constant('BASE_URL') ?>demande_attestation"><i class="fa-solid fa-file-certificate"></i> Attestation</a></li>
                <li><a href="<?= constant('BASE_URL') ?>demande_remboursement"><i class="fa-solid fa-money-bill-wave"></i> Remboursement</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="<?= constant('BASE_URL') ?>profil" class="nav-link">
                <i class="fa-solid fa-user"></i> Mon Profil
            </a>
        </li>

    </ul>
</nav>

<script>
    // même JavaScript que dans ta version actuelle
    document.addEventListener('DOMContentLoaded', () => {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const parentItem = toggle.closest('.nav-item.has-submenu');
                parentItem.classList.toggle('open');
                const arrowIcon = toggle.querySelector('.arrow-icon');
                if (arrowIcon) arrowIcon.classList.toggle('rotated');
            });
        });
    });
</script>