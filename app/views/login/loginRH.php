<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion RH</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #4878bbff 0%, #384da1ff 100%);
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
            background: linear-gradient(135deg, #2f5985ff 0%, #276749 100%);
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
            border-color: #487abbff;
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4889bbff 0%, #3881a1ff 100%);
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
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
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
            border-color: #4887bbff;
            color: #4885bbff;
        }

        .rh-features {
            background: #f0fff4;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .rh-features h4 {
            color: #2f5a85ff;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .rh-features ul {
            list-style: none;
            font-size: 12px;
            color: #4a5568;
        }

        .rh-features li {
            margin-bottom: 5px;
            padding-left: 15px;
            position: relative;
        }

        .rh-features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #486abbff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Espace RH</h1>
            <p>Ressources Humaines</p>
        </div>
        
        <div class="login-form">
            <form action="<?= constant('BASE_URL') ?>log" method="post">
                <div class="form-group">
                    <label for="email">Email RH</label>
                    <input type="email" id="email" name="email" class="form-control" value="email@entreprise.com" required>
                </div>
                
                <input type="hidden" name="role" value="3">

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Votre mot de passe" value="1234" required>
                </div>
                
                <button type="submit" class="login-btn">Se connecter</button>
            </form>
            
            <button class="back-btn" onclick="window.location.href='index.php'">
                ← Retour au choix du profil
            </button>
            
           
        </div>
    </div>
</body>
</html>