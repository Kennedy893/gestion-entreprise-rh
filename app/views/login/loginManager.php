<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #2f5985ff 0%, #2f5985ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            background: linear-gradient(135deg, #2f5985ff 0%, #2f5985ff 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-header p {
            opacity: 0.8;
            font-size: 14px;
        }

        .login-form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #ed8936;
            box-shadow: 0 0 0 3px rgba(237, 137, 54, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #2f5985ff 0%, #2f5985ff 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.4);
        }

        .back-btn {
            width: 100%;
            padding: 12px;
            background: white;
            color: #4a5568;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .back-btn:hover {
            border-color: #ed8936;
            color: #ed8936;
        }

        .manager-features {
            background: #fffaf0;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .manager-features h4 {
            color: #c05621;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .manager-features ul {
            list-style: none;
            font-size: 12px;
            color: #4a5568;
        }

        .manager-features li {
            margin-bottom: 5px;
            padding-left: 15px;
            position: relative;
        }

        .manager-features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #48bb78;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Espace Manager</h1>
            <p>Accès supervision</p>
        </div>
        
        <div class="login-form">
            <form action="<?= constant('BASE_URL') ?>/time/employees" method="GET">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Votre identifiant" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Votre mot de passe" required>
                </div>
                
                <div class="form-group">
                    <label for="department">Département</label>
                    <select id="department" name="department" class="form-control" required>
                        <option value="">Sélectionnez un département</option>
                        <option value="commercial">Commercial</option>
                        <option value="technique">Technique</option>
                        <option value="production">Production</option>
                        <option value="administratif">Administratif</option>
                    </select>
                </div>
                
                <button type="submit" class="login-btn">Se connecter</button>
            </form>
            
            <button class="back-btn" onclick="window.location.href='index.php'">
                ← Retour au choix du profil
            </button>
            
            <div class="manager-features">
                <h4>👔 Accès Manager :</h4>
                <ul>
                    <li>Supervision de votre équipe</li>
                    <li>Validation des congés</li>
                    <li>Rapports de performance</li>
                    <li>Tableaux de bord départementaux</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>