<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include("app/views/bar/sidebar.php")?>

    <form action="" method="post">
        <label for="date_debut">Date début</label>
        <input type="date" name="date_debut">

        <label for="date_fin">Date fin</label>
        <input type="date" name="date_fin">

        <label for="motif">Motif</label>
        <textarea name="motif"></textarea>

        <label for="type_conge">Type de congé</label>
        <select name="type_conge">
            <option value="0">Congé normal</option>
            <option value="1">Congeé exceptionnel</option>
            <option value="2">Congé maladie</option>
        </select>

        <button type="submit">Soumettre</button>
    </form>
</body>
</html>