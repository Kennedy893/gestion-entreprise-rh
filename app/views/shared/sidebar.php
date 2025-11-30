<link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/sidebar.css" />

<nav class="sidebar">
  <div class="brand"><i class="fa-solid fa-sack-dollar"></i> PayPro</div>
  <ul class="nav-menu">
    <li class="nav-item">
      <a href="#" class="nav-link active"><i class="fa-solid fa-chart-pie"></i> Tableau de bord</a>
    </li>
    <li class="nav-item has-submenu">
      <a href="#" class="nav-link dropdown-toggle" id="bulletins-toggle">
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
    const dropdownToggle = document.getElementById('bulletins-toggle');
    const parentItem = dropdownToggle.closest('.nav-item');

    dropdownToggle.addEventListener('click', (e) => {
      e.preventDefault(); // Empêche la navigation

      // Bascule la classe 'open' sur l'élément parent (<li>)
      parentItem.classList.toggle('open');
      dropdownToggle.classList.toggle('open');
    });
  });
</script>