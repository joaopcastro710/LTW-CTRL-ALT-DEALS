<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/message_db.php');
require_once ('../utils/session.php');

function formatedMessageList(array $messages): array {
    $res = array();
    foreach ($messages as $message) {
        $res[] = array('item' => $message['item'], 'model' => $message['model'], 'chatID' => $message['chatId']);
    }
    return $res;
}

function formatedChatsSeller(array $chats): array {
    $session = new Session();
    $res = array();
    foreach ($chats as $chat_message) {
        $res[] = array(
            'sender' => $chat_message['sender'], 'timestamp' => $chat_message['timestamp'],
            'content' => $chat_message['content'], 'chat' => $chat_message['chat'], 'buyer' => $chat_message['buyer'],
            'item' => $chat_message['item'], 'currentUser' => $session->getUserId()
        );
    }
    return $res;
}

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type']) && $session->isLoggedIn() && $_SESSION['priv_csrf'] === $_POST['token']) {
        $message_type = $_POST['type'];
        $db = getDatabaseConnection();

        if ($message_type === 'as_seller') {
            $messageList = getSellersMessageList($db, $session->getUserId());

            if (!$messageList) {
                echo json_encode(array('No messages available.' => false));
            } else {
                echo json_encode(formatedMessageList($messageList));
            }

        } else if ($message_type === 'fetch_messages') {

            if (!isset($_POST['chatID']) || !isset($_POST['itemID'])) {
                echo json_encode(array('No chatID or itemID provided.' => false));
            } else {
                $chatList = getChats($db, $session->getUserId(), (int)$_POST['chatID'], (int)$_POST['itemID']);

                if (!$chatList) {
                    echo json_encode("");
                } else {
                    echo json_encode(formatedChatsSeller($chatList));
                }
            }

        } else {
            $messageList = getBuyersMessageList($db, $session->getUserId());

            if (!$messageList) {
                echo json_encode(array('No messages available.' => false));
            } else {
                echo json_encode(formatedMessageList($messageList));
            }
        }

    } else {
        echo json_encode(array('User not logged, type not provided or invalid token' => false));
    }
} else {
    echo json_encode(array('Invalid request method' => false));
}
