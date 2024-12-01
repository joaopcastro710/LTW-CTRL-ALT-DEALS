<?php

declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/item_db.php');
require_once (__DIR__ . '/../utils/session.php');

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $itemId = $_POST['itemId'];

    if (!$itemId) {
        $response['delete-item-error'] = 'Item ID is missing.';
        echo json_encode($response);
        exit;
    }

    $db = getDatabaseConnection();
    $sellerId = getItemSeller($db, $itemId)["seller"];

    if ($sellerId === $session->getUserId()) {
        if (deleteItem($db, $itemId)) {
            $response = array('success' => true);
            echo json_encode($response);
            exit;
        } else {
            $response['delete-item-error'] = 'An error occurred while deleting the item.';
            echo json_encode($response);
            exit;
        }
    } else {
        $response['delete-item-error'] = 'You are not authorized to delete this item.';
        echo json_encode($response);
        exit;
    }
}

$response['delete-item-error'] = 'Invalid request.' . $_SESSION['priv_csrf'];
echo json_encode($response);
