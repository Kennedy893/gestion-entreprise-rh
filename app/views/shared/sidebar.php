<link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/sidebar.css" />

<nav class="sidebar">
  <div class="brand"><i class="fa-solid fa-sack-dollar"></i> PayPro</div>
  <ul class="nav-menu">
    <li class="nav-item">
      <a href="<?= constant('BASE_URL') ?>" class="nav-link active"><i class="fa-solid fa-chart-pie"></i> Tableau de bord</a>
    </li>
    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle" id="employes-toggle">
        <i class="fa-solid fa-users"></i>Employés
        <i class="fa-solid fa-chevron-right arrow-icon"></i>
      </a>
      <ul class="submenu">
        <li><a href="<?= constant('BASE_URL') ?>time/employees"> Liste Employes </a></li>
        <li><a href="<?= constant('BASE_URL') ?>time/presences"> Pointage / Présences </a></li>
        <li><a href="<?= constant('BASE_URL') ?>time/timecards"> Timecards </a></li>
        <li><a href="<?= constant('BASE_URL') ?>time/form-sheet"> TimeGeneral </a></li>
      </ul>
    </li>

    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle" id="bulletins-toggle">
        <i class="fa-solid fa-file-invoice-dollar"></i>Bulletins
        <i class="fa-solid fa-chevron-right arrow-icon"></i>
      </a>
      <ul class="submenu">
        <li><a href="#">Fiches de Paie</a></li>
        <li><a href="#">Pointage / Présences</a></li>
        <li><a href="#">Avantages & Primes</a></li>
      </ul>
    </li>

    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle" id="performance-toggle">
        <i class="fa-solid fa-file-invoice-dollar"></i>Performance
        <i class="fa-solid fa-chevron-right arrow-icon"></i>
      </a>
      <ul class="submenu">
        <li><a href="<?= constant('BASE_URL') ?>performance/calendar">Calendrier</a></li>
        <li><a href="<?= constant('BASE_URL') ?>performance/dashboard">Dashboard</a></li>
      </ul>
    </li>

    <li class="nav-item">
      <a href="#" class="nav-link"><i class="fa-solid fa-chart-line"></i> Rapports</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="fa-solid fa-gear"></i> Paramètres</a>
    </li>
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