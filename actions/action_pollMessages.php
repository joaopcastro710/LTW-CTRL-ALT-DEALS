<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/message_db.php');
require_once ('../utils/session.php');

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['chatID']) && $session->isLoggedIn() && $_SESSION['priv_csrf'] === $_POST['token'] && isset($_POST['itemID']) && isset($_POST['currentNumberOfMessages'])) {
        $chatList = getChats(getDatabaseConnection(), $session->getUserId(), (int)$_POST['chatID'], (int)$_POST['itemID']);
        if (!$chatList) {
            echo json_encode(array('newChat' => true));
        } else {

            $res = array();
            $messageCounter = 0;
            foreach ($chatList as $chat_message) {
                $messageCounter++;
                if ($chat_message['sender'] !== $session->getUserId() && $messageCounter > $_POST['currentNumberOfMessages']) {
                    $res[] = array(
                        'sender' => $chat_message['sender'], 'timestamp' => $chat_message['timestamp'],
                        'content' => $chat_message['content'], 'chat' => $chat_message['chat'], 'buyer' => $chat_message['buyer'],
                        'item' => $chat_message['item'], 'currentUser' => $session->getUserId()
                    );
                }
            }

            if ($messageCounter !== (int)$_POST['currentNumberOfMessages']) {
                echo json_encode(array('new' => true, '' => $res));
            } else {
                echo json_encode(array('' => true));
            }
        }
    } else {
        echo json_encode(array('User not logged, type not provided or invalid token' => false));
    }
}
