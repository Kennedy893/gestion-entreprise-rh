<?php

namespace app\controllers;

use Flight;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Dompdf\Dompdf;
use Dompdf\Options;



class PaieController
{
    public function __construct() {}

    public function etatDePaie()
    {
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByDate($date);
        Flight::render("paie/etat_paie", ['data' => $data], 'contenu');
        Flight::render('shared/home');
    }

    public function fichePaie($id_emp)
    {
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByIdEmploye($id_emp, $date);
        Flight::render("paie/fiche_paie", ['emp' => $data] , 'contenu');
        Flight::render('shared/home');
    }

    public function detailsEmp()
    {
        $id_emp = 1;
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByIdEmploye($id_emp, $date);
        Flight::json($data);
    }


    public function exportEtatDePaie()
    {
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByDate($date);

        $filename = 'etat_de_paie_' . date('Y_m_d') . '.xlsx';
        $tempPath = sys_get_temp_dir() . '/' . $filename;

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($tempPath);

        $headerStyle = (new StyleBuilder())
            ->setFontSize(11)
            ->setBackgroundColor("E2E8F0")
            ->build();

        // En-têtes du tableau
        $headers = [
            'ID',
            'Employé',
            'Date d\'embauche',
            'Absence (h)',
            'Salaire de base',
            'Avantage',
            'Heures sup.',
            'Salaire Brut',
            'CNAPS 1%',
            'CNAPS 8%',
            'OSTIE 1%',
            'OSTIE 5%',
            'Autres retenues',
            'Total retenues',
            'Revenu imposable',
            'IRSA',
            'Salaire Net'
        ];

        $headerRow = WriterEntityFactory::createRowFromArray($headers, $headerStyle);
        $writer->addRow($headerRow);

        // Données du tableau
        foreach ($data['details'] as $d) {
            $row = WriterEntityFactory::createRowFromArray([
                $d['id_employe'],
                $d['nom'] . ' ' . $d['prenom'] . ' (' . $d['label'] . ')',
                $d['date_debut'],
                '0h', // tu peux remplacer si tu calcules les absences
                $d['salaire'],
                $d['avantages'],
                $d['heure_sup'],
                $d['salaire_brut'],
                $d['cnaps_1'],
                $d['cnaps_8'],
                $d['ostie_1'],
                $d['ostie_5'],
                $d['autres_ret'],
                $d['total_ret'],
                $d['revenu_impo'],
                $d['irsa'],
                $d['salaire_net']
            ]);
            $writer->addRow($row);
        }

        $writer->close();

        // Envoi du fichier au navigateur
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        readfile($tempPath);
        unlink($tempPath); // Nettoyage
    }


    public function exportFichePaiePDF($id_emp)
    {
        $date = date('Y-m-d');
        $data = Flight::PaieModel()->getContratEmployeByIdEmploye($id_emp, $date);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        ob_start();

        $emp = $data;
        include __DIR__ . '/../views/paie/fiche_paie_pdf.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $filename = "fiche_paie_" . $id_emp . "_" . date('Y_m_d') . ".pdf";
        $dompdf->stream($filename, ["Attachment" => true]);
    }
}
