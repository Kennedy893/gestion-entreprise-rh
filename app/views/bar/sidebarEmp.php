<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Navigation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f7fa;
            color: #333;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            height: 100vh;
            position: fixed;
            overflow-y: auto;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 14px 25px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #4CAF50;
        }

        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #4CAF50;
        }

        .menu-item i {
            margin-right: 12px;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .menu-item span {
            font-size: 1rem;
            font-weight: 500;
        }

        .content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .content-header {
            margin-bottom: 30px;
        }

        .content-header h1 {
            font-size: 2rem;
            color: #2a5298;
            margin-bottom: 10px;
        }

        .content-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #2a5298;
        }

        .card p {
            color: #666;
            line-height: 1.6;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #2a5298;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: visible;
            }

            .sidebar-header h2,
            .sidebar-header p,
            .menu-item span {
                display: none;
            }

            .menu-item {
                justify-content: center;
                padding: 18px 0;
            }

            .menu-item i {
                margin-right: 0;
                font-size: 1.4rem;
            }

            .content {
                margin-left: 70px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Espace Employé</h2>
            <p>Gestion RH</p>
        </div>

        <div class="sidebar-menu">
            
            <div class="menu-item" data-url="<?= constant('BASE_URL') ?>/paie/fiche/1">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Fiche de Paie</span>
            </div>
            <div class="menu-item" data-url="<?= constant('BASE_URL') ?>/time/presences">
                <i class="fas fa-user-check"></i>
                <span>Présence</span>
            </div>
            <div class="menu-item" data-url="<?= constant('BASE_URL') ?>/demande_conge">
                <i class="fas fa-umbrella-beach"></i>
                <span>Congé</span>
            </div>
        </div>

        <script>
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    if (url) {
                        window.location.href = url;
                    }
                });
            });
        </script>
    </div>

    <!-- Contenu principal -->

    <script>
        // Gestion des clics sur les éléments du menu
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                // Retirer la classe active de tous les éléments
                document.querySelectorAll('.menu-item').forEach(el => {
                    el.classList.remove('active');
                });

                // Ajouter la classe active à l'élément cliqué
                this.classList.add('active');

                // Mettre à jour le titre de la page
                const pageTitle = this.querySelector('span').textContent;
                document.querySelector('.content-header h1').textContent = pageTitle;
            });
        });
    </script>
</body>

</html>