<?php

require_once '../database/connection_db.php';
require_once '../database/message_db.php';
require_once '../database/user_db.php';
require_once "../utils/session.php";

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if (isset($_POST['messageContent']) && isset($_POST['chat_id'])
    && isset($_POST['seller_id']) && isset($_POST['item_id']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {

    $db = getDatabaseConnection();

    $messageContent = $_POST['messageContent'];
    $sender = $session->getUserId();
    $receiver = $_POST['seller_id'];
    $item_id = $_POST['item_id'];
    $chat_id = $_POST['chat_id'];

    if (checkIfUserIsValid($db, $sender) && checkIfUserIsValid($db, $receiver)) {
        if (checkIfChatExists($db, $chat_id)) {
            addMessage($db, $sender, date("Y-m-d H:i:s"), $messageContent, $chat_id);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}