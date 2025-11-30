<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prédiction des Notes sur 6 Mois</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #007BFF; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Prédiction des Notes sur 6 Mois</h1>

        <?php if (!empty($data) && is_array($data)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Critère</th>
                        <th>Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ponctualité</td>
                        <td><?= number_format($data['ponctualite'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>Gestion du temps</td>
                        <td><?= number_format($data['gestion_temps'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>Productivité</td>
                        <td><?= number_format($data['productivite'], 2) ?></td>
                    </tr>
                    <tr style="font-weight:bold; background-color:#e0e0e0;">
                        <td>Moyenne Générale</td>
                        <td><?= number_format($data['moyenne_generale'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune donnée disponible pour afficher la prédiction.</p>
        <?php endif; ?>
    </div>
</body>
</html>
