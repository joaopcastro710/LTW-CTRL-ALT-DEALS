<?php

require_once '../database/connection_db.php';
require_once '../database/message_db.php';

require_once('../utils/session.php');

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['content']) && $session->isLoggedIn() && isset($_POST['chatID']) && isset($_POST['itemID']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {
        $content = htmlentities($_POST['content']);
        $content = trim($content);

        if ($content === '' || $content === ' ') {
            echo json_encode('Ignoring whitespaces.');
            exit;
        }

        $successful = addMessage(getDatabaseConnection(), $session->getUserId(), date('Y-m-d H:i:s', time()), $content, $_POST['chatID']);

        if (!$successful) {
            echo json_encode(array('Error in sending message.' => false));
        } else {
            echo json_encode('Message added successfully.');
        }

    } else {
        echo json_encode(array('User not logged, itemID/chatID/content not provided or invalid token' => false));
    }
} else {
    echo json_encode(array('Invalid request method' => false));
}
