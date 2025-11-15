<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pointage</title>
</head>
<body>
    <h2>Formulaire de Pointage</h2>
    <form method="post" action="<?php echo constant('BASE_URL'); ?>/time/presences">

        <label for="date">Date :</label>
        <input type="date" id="date" name="date" required><br><br>

        <label for="type">Type :</label>
        <select id="type" name="type" required>
            <option value=1>Entrée</option>
            <option value=2>Sortie</option>
        </select><br><br>

        <label for="heure">Heure :</label>
        <input type="time" id="heure" name="heure" required><br><br>

        <h3>Liste des employés</h3>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Sélectionner</th>
                    <th>Nom & Prenom</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $employees = $data['employees'];
                for ($i=0 ; $i < count($employees); $i++) 
                {
            ?>
                <!-- Exemple statique, à remplacer par une boucle PHP -->
                <tr>
                    <td><input type="checkbox" name="employes[]" value="<?php echo $employees[$i]['id']; ?>"></td>
                    <td><?php echo $employees[$i]['nom'] . " " . $employees[$i]['prenom']; ?></td>
                </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
        <br>
        <input type="submit" value="Valider la sélection">
    </form>
</body>
</html>