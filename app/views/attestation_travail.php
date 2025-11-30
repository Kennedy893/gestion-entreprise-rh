<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        .section { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>ATTESTATION DE TRAVAIL</h2>

    <p>Je soussigné(e), responsable de l’entreprise, atteste que :</p>

    <p><strong>Nom et prénom :</strong> <?= $nom . " " . $prenom ?></p>
    <p><strong>CIN :</strong> <?= $cin ?></p>
    <p><strong>Poste :</strong> <?= $poste ?></p>
    <p><strong>Département :</strong> <?= $departement ?></p>
    <p><strong>Date d’entrée :</strong> <?= $date_debut ?></p>
    <p><strong>Statut du contrat :</strong> <?= $statut_contrat ?></p>

    <div class="section">
        <p>Cette attestation est délivrée pour servir et valoir ce que de droit.</p>
    </div>

    <div class="section">
        <p>Fait à Antananarivo, le <?= date('d/m/Y') ?></p>
    </div>
</body>
</html>
