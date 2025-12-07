<nav class="sidebar" id="sidebar-rh">
    <div class="sidebar-header">
        <h2><i class="fas fa-users-cog"></i> <span class="sidebar-text">RH</span></h2>
        <button class="sidebar-toggle" onclick="toggleSidebarRH()">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-item">
            <a href="/rh/dashboard">
                <i class="fas fa-home"></i>
                <span class="sidebar-text">Tableau de bord</span>
            </a>
        </li>

        <li class="menu-divider"></li>
        <li class="menu-section-title"><span class="sidebar-text">Recrutement</span></li>

        <li class="menu-item">
            <a href="/rh/annonces">
                <i class="fas fa-clipboard-list"></i>
                <span class="sidebar-text">Annonces publiées</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/candidatures">
                <i class="fas fa-inbox"></i>
                <span class="sidebar-text">Candidatures reçues</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/candidats-en-attente">
                <i class="fas fa-user-clock"></i>
                <span class="sidebar-text">Candidats en attente</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/resultats-entretiens">
                <i class="fas fa-clipboard-check"></i>
                <span class="sidebar-text">Résultats entretiens</span>
            </a>
        </li>

        <li class="menu-divider"></li>
        <li class="menu-section-title"><span class="sidebar-text">Résultats</span></li>

        <li class="menu-item">
            <a href="/rh/resultats-candidats">
                <i class="fas fa-chart-line"></i>
                <span class="sidebar-text">Résultats candidats</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/resultats-entretiens">
                <i class="fas fa-star"></i>
                <span class="sidebar-text">Évaluer entretiens</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/candidatures-acceptees">
                <i class="fas fa-user-check"></i>
                <span class="sidebar-text">Candidats acceptés</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/candidatures-rejetees">
                <i class="fas fa-user-times"></i>
                <span class="sidebar-text">Candidats rejetés</span>
            </a>
        </li>

        <li class="menu-divider"></li>
        <li class="menu-section-title"><span class="sidebar-text">Compétences</span></li>

        <li class="menu-item">
            <a href="/rh/matching-automatique">
                <i class="fas fa-robot"></i>
                <span class="sidebar-text">Matching automatique</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/competences/competence-employes">
                <i class="fas fa-chart-line"></i>
                <span class="sidebar-text">Qualité employés</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/competences/statistiques">
                <i class="fas fa-chart-pie"></i>
                <span class="sidebar-text">Statistiques</span>
            </a>
        </li>

        <li class="menu-divider"></li>
        <li class="menu-section-title"><span class="sidebar-text">Contrats & Employés</span></li>

        <li class="menu-item">
            <a href="/rh/contrats">
                <i class="fas fa-file-contract"></i>
                <span class="sidebar-text">Génération contrats</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="/rh/employes">
                <i class="fas fa-users"></i>
                <span class="sidebar-text">Liste des employés</span>
            </a>
        </li>

        <li class="menu-divider"></li>

        <li class="menu-item">
            <a href="/logout">
                <i class="fas fa-sign-out-alt"></i>
                <span class="sidebar-text">Déconnexion</span>
            </a>
        </li>
    </ul>

    <style>
        .sidebar {
            width: 260px;
            transition: width 0.3s ease;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        
        .sidebar.collapsed .menu-section-title {
            display: none;
        }
        
        .sidebar.collapsed .menu-divider {
            margin: 10px 15px;
        }
        
        .sidebar.collapsed .sidebar-header h2 {
            justify-content: center;
        }
        
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            font-size: 1.2rem;
            color: white;
        }
        
        .sidebar-toggle {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .sidebar.collapsed .sidebar-toggle {
            margin: 0 auto;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menu-section-title {
            padding: 15px 25px 10px;
            font-size: 0.75rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 15px 20px;
        }
        
        .menu-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        .menu-item a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 30px;
        }
        
        .menu-item a i {
            font-size: 1.1rem;
            min-width: 20px;
            text-align: center;
        }
        
        .sidebar.collapsed .menu-item a {
            justify-content: center;
            padding: 12px;
        }
        
        .sidebar.collapsed .menu-item a:hover {
            padding: 12px;
        }
        
        .main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            background: #f1f5f9;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }
    </style>

    <script>
        function toggleSidebarRH() {
            const sidebar = document.getElementById('sidebar-rh');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Sauvegarder l'état dans localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarRHCollapsed', isCollapsed);
        }
        
        // Restaurer l'état au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarRHCollapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('sidebar-rh').classList.add('collapsed');
                document.querySelector('.main-content').classList.add('expanded');
            }
        });
    </script>
</nav>