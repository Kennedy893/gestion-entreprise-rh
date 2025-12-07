<header class="top-header">
    <div class="header-left">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="header-title">Système de Gestion RH</h1>
    </div>
    
    <div class="header-right">
        <div class="header-search">
            <form action="" method="GET" id="searchForm">
                <input type="text" name="search" placeholder="Rechercher..." class="search-input" 
                       value="<?= $_GET['search'] ?? '' ?>"
                       onchange="document.getElementById('searchForm').submit()">
                <button type="submit" style="background:none;border:none;position:absolute;right:15px;top:50%;transform:translateY(-50%);cursor:pointer;color:#94a3b8;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <div class="header-notifications">
            <button class="notif-btn">
                <i class="fas fa-bell"></i>
                <span class="notif-badge">3</span>
            </button>
        </div>
        
        <div class="header-user">
            <img src="/assets/img/user-avatar.png" alt="User" class="user-avatar" 
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2240%22 height=%2240%22%3E%3Ccircle cx=%2220%22 cy=%2220%22 r=%2220%22 fill=%22%234CAF50%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22white%22 font-size=%2218%22 font-family=%22Arial%22%3E<?= strtoupper(substr($_SESSION['user']['nom'] ?? 'U', 0, 1)) ?><?= strtoupper(substr($_SESSION['user']['prenom'] ?? '', 0, 1)) ?></text%3E%3C/svg%3E'">
            <div class="user-info">
                <span class="user-name"><?= $_SESSION['user']['prenom'] ?? 'User' ?> <?= $_SESSION['user']['nom'] ?? '' ?></span>
                <span class="user-role"><?= ucfirst($_SESSION['user']['role'] ?? 'User') ?></span>
            </div>
        </div>
    </div>
</header>

<script>
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('expanded');
}
</script>