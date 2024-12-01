<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/wishlist_shoppingCart_db.php');
require_once ('../templates/wishlist.tpl.php');
require_once ('../utils/session.php');

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['itemID']) && $session->isLoggedIn() && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {
        $itemID = $_POST['itemID'];

        $itemDeleted = removeWishlistItems(getDatabaseConnection(), $session->getUserId(), (int)$itemID);
        $wishlistItems = fetchWishlistItems((int)$session->getUserId());

        if (!$wishlistItems) {
            echo json_encode(array('No more items' => true));
        } else {
            if ($itemDeleted) {
                echo json_encode(array('Success' => true));
            } else {
                echo json_encode(array('Item does not exist' => false));
            }
        }

    } else {
        echo json_encode(array('User not logged in or itemID not provided' => false));
    }
} else {
    echo json_encode(array('Invalid request method' => false));
}
