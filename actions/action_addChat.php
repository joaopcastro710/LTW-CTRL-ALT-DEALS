<?php

require_once ('../database/connection_db.php');
require_once ('../database/message_db.php');

require_once ('../utils/session.php');

$session = new Session();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($session->isLoggedIn() && isset($_POST['itemID']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {

        $successful = addChat(getDatabaseConnection(), $session->getUserId(), $_POST['itemID']);

        if (!$successful) {
            echo json_encode(array('Error while adding chat.' => false));
        } else {
            echo json_encode(array('Chat added.' => true));
        }

    } else {
        echo json_encode(array('User not logged, itemID not provided or invalid token' => false));
    }
} else {
    echo json_encode(array('Invalid request method' => false));
}
