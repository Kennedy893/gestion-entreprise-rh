<link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/sidebar.css" />

<nav class="sidebar">
  <div class="brand"><i class="fa-solid fa-sack-dollar"></i> GESTION rh</div>
  <ul class="nav-menu">
    <li class="nav-item">
      <a href="<?= constant('BASE_URL') ?>" class="nav-link"><i class="fa-solid fa-chart-pie"></i> Tableau de bord</a>
    </li>
    
    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle">
        <i class="fa-solid fa-users"></i> Employés
        <i class="fa-solid fa-chevron-right arrow-icon"></i>
      </a>
      <ul class="submenu">
        <li><a href="<?= constant('BASE_URL') ?>time/employees"> <i class="fa-solid fa-list"></i> Liste Employés</a></li>
        <li><a href="<?= constant('BASE_URL') ?>time/presences"> <i class="fa-solid fa-fingerprint"></i> Pointage / Présences</a></li>
        <li><a href="<?= constant('BASE_URL') ?>time/form-sheet"> <i class="fa-solid fa-calendar-alt"></i> Feuille de temps </a></li>
      </ul>
    </li>

    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle">
        <i class="fa-solid fa-chart-line"></i> Performance
        <i class="fa-solid fa-chevron-right arrow-icon"></i>
      </a>
      <ul class="submenu">
        <li><a href="<?= constant('BASE_URL') ?>performance/calendar"> <i class="fa-solid fa-calendar"></i> Calendrier</a></li>
        <li><a href="<?= constant('BASE_URL') ?>performance/dashboard"> <i class="fa-solid fa-chart-bar"></i> Dashboard</a></li>
      </ul>
    </li>

    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle">
        <i class="fa-solid fa-umbrella-beach"></i> Congés
        <i class="fa-solid fa-chevron-right arrow-icon"></i>
      </a>
      <ul class="submenu">
        <li><a href="<?= constant('BASE_URL') ?>vers_demande_conge"> <i class="fa-solid fa-file-import"></i> Demande congé</a></li>
        <li><a href="<?= constant('BASE_URL') ?>liste_conge"> <i class="fa-solid fa-list-check"></i> Liste congés</a></li>
        <li><a href="<?= constant('BASE_URL') ?>validation_rh"> <i class="fa-solid fa-user-check"></i> Liste congés (RH)</a></li>
        <li><a href="<?= constant('BASE_URL') ?>vers_solde_conge"> <i class="fa-solid fa-coins"></i> Solde de congé</a></li>
      </ul>
    </li>

    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle">
        <i class="fa-solid fa-user-tie"></i> Services RH
        <i class="fa-solid fa-chevron-right arrow-icon"></i>
      </a>
      <ul class="submenu">
        <li><a href="<?= constant('BASE_URL') ?>choose_consultation"> <i class="fa-solid fa-search"></i> Consultation</a></li>
        <li><a href="<?= constant('BASE_URL') ?>choose_soumission"> <i class="fa-solid fa-paper-plane"></i> Soumission</a></li>
        <li><a href="<?= constant('BASE_URL') ?>demande_attestation"> <i class="fa-solid fa-file-certificate"></i> Demande d'attestation</a></li>
        <li><a href="<?= constant('BASE_URL') ?>demande_remboursement"> <i class="fa-solid fa-money-bill-wave"></i> Demande de remboursement</a></li>
      </ul>
    </li>

    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle">
        <i class="fa-solid fa-calculator"></i> Gestion de paie
        <i class="fa-solid fa-chevron-right arrow-icon"></i>
      </a>
      <ul class="submenu">
        <li><a href="<?= constant('BASE_URL') ?>paie"> <i class="fa-solid fa-file-invoice"></i> État de paie</a></li>
      </ul>
    </li>
<!-- 
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="fa-solid fa-file-chart-column"></i> Rapports</a>
    </li>
    
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="fa-solid fa-gear"></i> Paramètres</a>
    </li> -->
  </ul>
  
  <div class="user-profile">
    <div class="user-avatar">DR</div>
    <div class="emp-details" style="text-align: left">
      <div style="color: white; font-size: 0.9rem">Dir. RH</div>
      <div style="color: #94a3b8; font-size: 0.75rem">Admin</div>
    </div>
  </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne tous les éléments avec la classe 'dropdown-toggle'
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', (e) => {
        e.preventDefault(); // Empêche la navigation

        // Trouve l'élément parent <li> qui contient le sous-menu
        const parentItem = toggle.closest('.nav-item.has-submenu');

        // Bascule la classe 'open' sur l'élément parent
        parentItem.classList.toggle('open');

        // Bascule la rotation de l'icône flèche
        const arrowIcon = toggle.querySelector('.arrow-icon');
        if (arrowIcon) {
          arrowIcon.classList.toggle('rotated');
        }

        // Optionnel: Fermer les autres menus ouverts
        dropdownToggles.forEach(otherToggle => {
          if (otherToggle !== toggle) {
            const otherParent = otherToggle.closest('.nav-item.has-submenu');
            const otherArrow = otherToggle.querySelector('.arrow-icon');
            if (otherParent && otherParent.classList.contains('open')) {
              otherParent.classList.remove('open');
              if (otherArrow) {
                otherArrow.classList.remove('rotated');
              }
            }
          }
        });
      });
    });

    // Fermer le menu si on clique en dehors
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.nav-item.has-submenu')) {
        dropdownToggles.forEach(toggle => {
          const parentItem = toggle.closest('.nav-item.has-submenu');
          const arrowIcon = toggle.querySelector('.arrow-icon');
          if (parentItem && parentItem.classList.contains('open')) {
            parentItem.classList.remove('open');
            if (arrowIcon) {
              arrowIcon.classList.remove('rotated');
            }
          }
        });
      }
    });
  });
</script>