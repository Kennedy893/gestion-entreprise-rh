<?php

namespace app\controllers;

use DateTime;
use Flight;

class DemandeController 
{
	public function __construct() {

	}

    public function insererDemandeAttestation()
    {
        $attestation = $_POST['attestation'];
        // $id_employe = $_GET['id_employe'];
        $id_employe = 1;
        $date_demande = $_POST['date_demande'];

        $soumettre = Flight::DemandeModel()->insererDemandeAtt($id_employe, $attestation, $date_demande);
        if(!$soumettre)
        {
            Flight::render('employe/demande_attestation', ['message' => 'Demande soumise avec succes.']);
        }
        else
        {
            Flight::render('employe/demande_attestation', ['message' => 'Erreur lors de la soumission de la demande.']);
        }
    }

    public function insererDemandeRemboursement()
    {
        $motif = $_POST['raison'];
        $montant = $_POST['montant'];

        $files = $_FILES['fichier'];
        $nbFiles = count($files['name']);
        $uploadedPaths = [];

        for ($i = 0; $i < $nbFiles; $i++) {
            if ($files['error'][$i] === 0) {
                $originalName = $files['name'][$i];
                $tmpPath = $files['tmp_name'][$i];

                // Dossier de destination
                $dest = "uploads/justificatifs/" . uniqid() . "_" . $originalName;

                // Déplacer le fichier
                move_uploaded_file($tmpPath, $dest);

                // Stocker le chemin final
                $uploadedPaths[] = $dest;
            }
        }
        $fichiers = json_encode($uploadedPaths);


        // $id_employe = $_GET['id_employe'];
        $id_employe = 1;
        $date_demande = $_POST['date'];

        $soumettre = Flight::DemandeModel()->insererDemandeRemb($id_employe, $motif, $montant, $fichiers, $date_demande);
        if(!$soumettre)
        {
            Flight::render('employe/remboursement', ['message' => 'Demande soumise avec succes.']);
        }
        else
        {
            Flight::render('employe/remboursement', ['message' => 'Erreur lors de la soumission de la demande.']);
        }
    }

}