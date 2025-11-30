<?php

namespace app\models;

use Flight;
use PDO;
use Exception;
use DateTime;

class DemandeModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insererDemandeAtt($id_employe, $type_demande, $daty)
    {
        $stmt = $this->db->prepare("
            INSERT INTO demande_attestation (id_employe, type_demande, daty, statut)
            VALUES (?, ?, ?, 0)
        ");
        $stmt->execute([$id_employe, $type_demande, $daty]);
    }

    public function insererDemandeRemb($id_employe, $motif, $montant, $fichier, $daty)
    {
        $stmt = $this->db->prepare("
            INSERT INTO demande_remboursement (id_employe, motif, montant, fichier, daty, statut)
            VALUES (?, ?, ?, ?, ?, 0)
        ");
        $stmt->execute([$id_employe, $motif, $montant, $fichier, $daty]);
    }

}