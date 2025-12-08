<?php

namespace app\controllers;

use DateTime;
use Flight;

class MessageController {

	public function __construct() {

	}

    public function messagerie()
    {
        // $id_employe = $_GET['id_employe'];
        $id_employe = 1;

        // Liste des messages
        $messages = Flight::MessageModel()->getMessages($id_employe);

        Flight::render('employe/messagerie', [
            'messages' => $messages,
            'id_employe' => $id_employe
        ], 'contenu');
        Flight::render('shared/home');
    }

    public function envoyer()
    {
        $contenu = $_POST['contenu'];
        $id_employe = $_POST['id_employe'];

        Flight::MessageModel()->envoyerMessage($id_employe, $contenu, 0);

        Flight::redirect(constant('BASE_URL').'employe/messagerie?id_employe=' . $id_employe);
    }


}