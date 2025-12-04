<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        .section { margin-top: 20px; }
    </style>
</head>
<body>

<h2>CONTRAT DE TRAVAIL</h2>

<p><strong>Employé :</strong> <?= $nom . " " . $prenom ?></p>
<p><strong>CIN :</strong> <?= $cin ?></p>
<p><strong>Date de naissance :</strong> <?= $date_naissance ?></p>
<p><strong>Email :</strong> <?= $email ?></p>
<p><strong>Adresse :</strong> <?= $adresse ?></p>

<div class="section">
    <h3>Informations du contrat :</h3>
    <p><strong>Type de contrat :</strong> <?= $type_contrat ?></p>
    <p><strong>Statut :</strong> <?= $statut_contrat ?></p>
    <p><strong>Poste :</strong> <?= $poste ?></p>
    <p><strong>Catégorie :</strong> <?= $categorie ?></p>
    <p><strong>Département :</strong> <?= $departement ?></p>
</div>

<div class="section">
    <p><strong>Date début :</strong> <?= $date_debut ?></p>
    <p><strong>Date fin :</strong> <?= $date_fin ?></p>
    <p><strong>Durée :</strong> <?= $duree ?> mois</p>
    <p><strong>Salaire :</strong> <?= number_format($salaire, 2, ',', ' ') ?> Ar</p>
</div>

<div class="section">
    <p>Fait à Antananarivo, le <?= date('d/m/Y') ?></p>
</div>

</body>
</html>
