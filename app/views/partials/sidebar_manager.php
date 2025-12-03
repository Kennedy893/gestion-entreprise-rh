<nav class="sidebar" id="sidebar-manager">
    <div class="sidebar-header">
        <h2><i class="fas fa-user-tie"></i> <span class="sidebar-text">Manager</span></h2>
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-item">
            <a href="/manager/dashboard">
                <i class="fas fa-home"></i>
                <span class="sidebar-text">Tableau de bord</span>
            </a>
        </li>
        
        <li class="menu-divider"></li>
        <li class="menu-section-title"><span class="sidebar-text">Gestion des Annonces</span></li>
        
        <li class="menu-item">
            <a href="/manager/create-annonce">
                <i class="fas fa-plus-circle"></i>
                <span class="sidebar-text">Créer une annonce</span>
            </a>
        </li>
        
        <li class="menu-divider"></li>
        <li class="menu-section-title"><span class="sidebar-text">Postes</span></li>
        
        <li class="menu-item">
            <a href="/manager/postes-libres">
                <i class="fas fa-briefcase"></i>
                <span class="sidebar-text">Liste postes libres</span>
            </a>
        </li>
        
        <li class="menu-item">
            <a href="/manager/postes-libres/create">
                <i class="fas fa-plus-square"></i>
                <span class="sidebar-text">Créer des postes</span>
            </a>
        </li>
        
        <li class="menu-divider"></li>
        <li class="menu-section-title"><span class="sidebar-text">Recrutement</span></li>
        
        <li class="menu-item">
            <a href="/manager/candidatures">
                <i class="fas fa-users"></i>
                <span class="sidebar-text">Candidatures reçues</span>
            </a>
        </li>
        
        <li class="menu-divider"></li>
        <li class="menu-section-title"><span class="sidebar-text">Accès RH</span></li>
        
        <li class="menu-item">
            <a href="/rh/dashboard">
                <i class="fas fa-users-cog"></i>
                <span class="sidebar-text">Espace RH</span>
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
        
        .sidebar.collapsed .sidebar-header h2 {
            justify-content: center;
        }
        
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }
        
        .sidebar-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            font-size: 1.2rem;
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
        
        .menu-section-title {
            padding: 15px 25px 10px;
            font-size: 0.85rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
    </style>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar-manager');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Sauvegarder l'état dans localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }
        
        // Restaurer l'état au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('sidebar-manager').classList.add('collapsed');
                document.querySelector('.main-content').classList.add('expanded');
            }
        });
    </script>
</nav>