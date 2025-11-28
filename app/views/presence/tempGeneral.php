<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Hebdomadaires</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 16px; color: #222; }
        h2 { margin-bottom: 12px; }
        form { margin-top: 12px; }
        input, button { padding: 6px 8px; }
        input[type="date"] {
            border: 1px solid #ddd; border-radius: 4px; outline: none;
        }
        input[type="date"]:focus {
            border-color: #1976d2; box-shadow: 0 0 0 2px rgba(25,118,210,0.15);
        }
        button {
            background: #1976d2; color: #fff; border: 1px solid #1976d2; border-radius: 4px; cursor: pointer;
        }
        button:hover { background: #155fa7; border-color: #155fa7; }
    </style>
</head>
<body>
    <h2>Feuille de temps - Sélection de la date</h2>

    <form method="get" action="<?php echo constant('BASE_URL'); ?>/time/timecards">
        <label for="date">Date :</label>
        <input type="date" id="date" name="date" required>
        <button type="submit">Voir les détails</button>
    </form>
</body>
</html>