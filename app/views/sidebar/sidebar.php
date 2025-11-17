<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar RH</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
    }

    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100vh;
      width: 260px;
      background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
      padding: 24px 0;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      z-index: 1000;
    }

    .sidebar.collapsed {
      transform: translateX(-260px);
    }

    .sidebar-header {
      padding: 0 24px 24px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      margin-bottom: 24px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      color: white;
      text-decoration: none;
    }

    .logo-icon {
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }

    .logo-text {
      font-size: 20px;
      font-weight: 600;
      letter-spacing: -0.5px;
    }

    .nav-menu {
      flex: 1;
      overflow-y: auto;
      padding: 0 12px;
    }

    .nav-menu::-webkit-scrollbar {
      width: 6px;
    }

    .nav-menu::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 3px;
    }

    .nav-section {
      margin-bottom: 24px;
    }

    .nav-section-title {
      color: rgba(255, 255, 255, 0.5);
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      padding: 0 12px 8px;
      font-weight: 600;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 12px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      border-radius: 8px;
      margin-bottom: 4px;
      transition: all 0.2s ease;
      cursor: pointer;
      position: relative;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transform: translateX(2px);
    }

    .nav-item.active {
      background: rgba(255, 255, 255, 0.15);
      color: white;
      font-weight: 500;
    }

    .nav-item.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 3px;
      height: 20px;
      background: white;
      border-radius: 0 2px 2px 0;
    }

    .nav-icon {
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    .nav-text {
      font-size: 14px;
      flex: 1;
    }

    .badge {
      background: #ef4444;
      color: white;
      font-size: 11px;
      padding: 2px 6px;
      border-radius: 10px;
      font-weight: 600;
    }

    /* Styles pour les sous-menus */
    .nav-item.has-submenu {
      position: relative;
    }

    .submenu-arrow {
      width: 16px;
      height: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255, 255, 255, 0.6);
      transition: transform 0.3s ease;
      font-size: 12px;
    }

    .nav-item.has-submenu.open .submenu-arrow {
      transform: rotate(180deg);
    }

    .submenu {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      margin-left: 20px;
      margin-top: 4px;
      margin-bottom: 4px;
    }

    .submenu.open {
      max-height: 500px;
    }

    .submenu-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      border-radius: 6px;
      margin-bottom: 2px;
      font-size: 13px;
      transition: all 0.2s ease;
      position: relative;
      padding-left: 32px;
    }

    .submenu-item::before {
      content: '';
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 4px;
      background: rgba(255, 255, 255, 0.4);
      border-radius: 50%;
      transition: all 0.2s ease;
    }

    .submenu-item:hover {
      background: rgba(255, 255, 255, 0.08);
      color: white;
      transform: translateX(2px);
    }

    .submenu-item:hover::before {
      background: white;
      width: 6px;
      height: 6px;
    }

    .submenu-item.active {
      background: rgba(255, 255, 255, 0.12);
      color: white;
      font-weight: 500;
    }

    .submenu-item.active::before {
      background: white;
      width: 6px;
      height: 6px;
    }

    .sidebar-footer {
      padding: 16px 24px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .user-profile:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 14px;
    }

    .user-info {
      flex: 1;
    }

    .user-name {
      color: white;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 2px;
    }

    .user-role {
      color: rgba(255, 255, 255, 0.5);
      font-size: 11px;
    }

    .toggle-btn {
      position: fixed;
      left: 270px;
      top: 20px;
      width: 40px;
      height: 40px;
      background: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      transition: all 0.3s ease;
      z-index: 999;
    }

    .toggle-btn:hover {
      background: #f3f4f6;
      transform: scale(1.05);
    }

    .toggle-btn.collapsed {
      left: 10px;
    }

    .main-content {
      margin-left: 260px;
      padding: 40px;
      transition: margin-left 0.3s ease;
    }

    .main-content.expanded {
      margin-left: 0;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-260px);
      }

      .sidebar.mobile-open {
        transform: translateX(0);
      }

      .toggle-btn {
        left: 10px;
      }

      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <a href="#" class="logo">
        <div class="logo-icon">👥</div>
        <div class="logo-text">RH Manager</div>
      </a>
    </div>

    <nav class="nav-menu">
      <div class="nav-section">
        <div class="nav-section-title">Principal</div>
        <a href="#" class="nav-item active">
          <span class="nav-icon">📊</span>
          <span class="nav-text">Tableau de bord</span>
        </a>
        <a href="#" class="nav-item">
          <span class="nav-icon">👤</span>
          <span class="nav-text">Employés</span>
        </a>
        <a href="#" class="nav-item">
          <span class="nav-icon">📝</span>
          <span class="nav-text">Recrutement</span>
          <span class="badge">3</span>
        </a>
        
        <!-- Menu Congés avec sous-menu -->
        <div class="nav-item has-submenu" data-submenu="conges">
          <span class="nav-icon">📅</span>
          <span class="nav-text">Congés</span>
          <span class="submenu-arrow">▼</span>
        </div>
        <div class="submenu" id="submenu-conges">
          <a href="<?= constant('BASE_URL') ?>vers_demande_conge" class="submenu-item">Demander un congé</a>
          <a href="<?= constant('BASE_URL') ?>vers_liste_conge" class="submenu-item">Liste des congés</a>
          <a href="<?= constant('BASE_URL') ?>vers_solde_conge" class="submenu-item">Solde de congés</a>
        </div>
      </div>

      <div class="nav-section">
        <div class="nav-section-title">Gestion</div>
        <a href="#" class="nav-item">
          <span class="nav-icon">⏰</span>
          <span class="nav-text">Présence</span>
        </a>
        
        <!-- Menu Paie avec sous-menu -->
        <div class="nav-item has-submenu" data-submenu="paie">
          <span class="nav-icon">💰</span>
          <span class="nav-text">Paie</span>
          <span class="submenu-arrow">▼</span>
        </div>
        <div class="submenu" id="submenu-paie">
          <a href="#" class="submenu-item">Mes bulletins</a>
          <a href="#" class="submenu-item">Historique des paies</a>
          <a href="#" class="submenu-item">Documents fiscaux</a>
        </div>
        
        <a href="#" class="nav-item">
          <span class="nav-icon">📈</span>
          <span class="nav-text">Performance</span>
        </a>
        <a href="#" class="nav-item">
          <span class="nav-icon">🎓</span>
          <span class="nav-text">Formation</span>
        </a>
      </div>

      <div class="nav-section">
        <div class="nav-section-title">Système</div>
        <a href="#" class="nav-item">
          <span class="nav-icon">⚙️</span>
          <span class="nav-text">Paramètres</span>
        </a>
        <a href="#" class="nav-item">
          <span class="nav-icon">📄</span>
          <span class="nav-text">Rapports</span>
        </a>
      </div>
    </nav>

    <div class="sidebar-footer">
      <div class="user-profile">
        <div class="user-avatar">JD</div>
        <div class="user-info">
          <div class="user-name">Jean Dupont</div>
          <div class="user-role">Administrateur</div>
        </div>
      </div>
    </div>
  </div>

  <button class="toggle-btn" id="toggleBtn">
    <span id="toggleIcon">☰</span>
  </button>

  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const toggleIcon = document.getElementById('toggleIcon');
    const mainContent = document.getElementById('mainContent');
    let isCollapsed = false;

    toggleBtn.addEventListener('click', () => {
      isCollapsed = !isCollapsed;
      
      if (isCollapsed) {
        sidebar.classList.add('collapsed');
        toggleBtn.classList.add('collapsed');
        if (mainContent) mainContent.classList.add('expanded');
        toggleIcon.textContent = '☰';
      } else {
        sidebar.classList.remove('collapsed');
        toggleBtn.classList.remove('collapsed');
        if (mainContent) mainContent.classList.remove('expanded');
        toggleIcon.textContent = '✕';
      }
    });

    // Gestion des sous-menus
    const menuItemsWithSubmenu = document.querySelectorAll('.nav-item.has-submenu');
    
    menuItemsWithSubmenu.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        const submenuId = 'submenu-' + item.getAttribute('data-submenu');
        const submenu = document.getElementById(submenuId);
        
        // Fermer les autres sous-menus
        document.querySelectorAll('.submenu').forEach(sub => {
          if (sub.id !== submenuId) {
            sub.classList.remove('open');
          }
        });
        
        document.querySelectorAll('.nav-item.has-submenu').forEach(navItem => {
          if (navItem !== item) {
            navItem.classList.remove('open');
          }
        });
        
        // Toggle du sous-menu actuel
        item.classList.toggle('open');
        submenu.classList.toggle('open');
      });
    });

    // Gestion du menu actif pour les items sans sous-menu
    const navItems = document.querySelectorAll('.nav-item:not(.has-submenu)');
    navItems.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        navItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        
        // Fermer tous les sous-menus
        document.querySelectorAll('.submenu').forEach(sub => sub.classList.remove('open'));
        document.querySelectorAll('.nav-item.has-submenu').forEach(navItem => {
          navItem.classList.remove('open');
        });
      });
    });

    // Gestion des sous-items
    // const submenuItems = document.querySelectorAll('.submenu-item');
    // submenuItems.forEach(item => {
    //   item.addEventListener('click', (e) => {
    //     e.preventDefault();
        
    //     // Retirer l'état actif des items principaux
    //     navItems.forEach(i => i.classList.remove('active'));
        
    //     // Retirer l'état actif des autres sous-items
    //     submenuItems.forEach(i => i.classList.remove('active'));
        
    //     // Ajouter l'état actif au sous-item cliqué
    //     item.classList.add('active');
    //   });
    // });

    // Responsive: fermer la sidebar sur mobile au clic sur un lien
    if (window.innerWidth <= 768) {
      submenuItems.forEach(item => {
        item.addEventListener('click', () => {
          sidebar.classList.remove('mobile-open');
        });
      });
    }
  </script>
</body>
</html>