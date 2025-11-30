<?php

namespace app\controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Flight;

class GenerationController {

    public function genererContratPdf($idEmploye) 
    {
        // Récupérer les données du contrat depuis le modèle
        $data = Flight::GenerationModel()->getContratData($idEmploye);

        if (!$data) {
            die("Contrat introuvable !");
        }

        // Charger le template PHP avec les données
        extract($data);
        ob_start();
        include __DIR__ . "/../views/Generation.php";
        $html = ob_get_clean();

        // Configurer Dompdf
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        // Forcer le téléchargement du PDF généré
        $filename = "contrat_employe_{$idEmploye}.pdf";
        $dompdf->stream($filename, [
            "Attachment" => true
        ]);

        exit;
    }
    public function genererAttestationTravailPdf($idEmploye) 
    {
        // Récupérer les données de l’employé (tu peux adapter la requête selon besoin)
        $data = Flight::GenerationModel()->getContratData($idEmploye);

        if (!$data) {
            die("Employé introuvable !");
        }

        // Charger le template PHP pour l’attestation
        extract($data);
        ob_start();
        include __DIR__ . "/../views/attestation_travail.php";
        $html = ob_get_clean();

        // Configurer Dompdf
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        // Forcer le téléchargement du PDF généré
        $filename = "attestation_travail_{$idEmploye}.pdf";
        $dompdf->stream($filename, [
            "Attachment" => true
        ]);

        exit;
    }
   public function homeGen()
    {
        Flight::render('Gen');
    }

}
