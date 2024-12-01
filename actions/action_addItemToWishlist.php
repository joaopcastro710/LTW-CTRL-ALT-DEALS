<?php

require_once ('../database/connection_db.php');
require_once ('../database/wishlist_shoppingCart_db.php');
require_once ('../templates/wishlist.tpl.php');
require_once ('../utils/session.php');

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['itemID']) && $session->isLoggedIn() && isset($_POST['type']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {
        $item_id = $_POST['itemID'];

        if ($_POST['type'] === 'remove') {
            removeWishlistItems(getDatabaseConnection(), $session->getUserId(), $item_id);
        } else {
            addToWishlist(getDatabaseConnection(), $session->getUserId(), $item_id);
        }

    } else {
        echo json_encode(array('User not logged in or itemID not provided' => false));
    }
} else {
    echo json_encode(array('Invalid request method' => false));
}