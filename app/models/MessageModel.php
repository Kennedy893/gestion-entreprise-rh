<?php

namespace app\models;

use Flight;
use PDO;
use Exception;
use DateTime;

class MessageModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getMessages($id_employe) 
    {
        $stmt = $this->db->prepare("
            SELECT * FROM messages
            WHERE id_employe = ?
            ORDER BY date_envoi ASC
        ");
        $stmt->execute([$id_employe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function envoyerMessage($id_employe, $contenu, $sender) 
    {
        $stmt = $this->db->prepare("
            INSERT INTO messages (id_employe, contenu, sender)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$id_employe, $contenu, $sender]);
    }


}