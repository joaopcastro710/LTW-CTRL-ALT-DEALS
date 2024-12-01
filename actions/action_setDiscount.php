<?php

require_once ('../database/connection_db.php');
require_once ('../database/shoppingCart_db.php');
require_once ('../utils/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session = new Session();
    if ($session->isLoggedIn() && isset($_POST['dicountCode']) && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {

        $currentDay = date('j');

        if ($_POST['dicountCode'] === 'EDAY-2212' && $currentDay % 2 == 0) {
            if (applyPromoToCart(getDatabaseConnection(), $session->getUserId(), 0.25)) {
                echo json_encode(array('Applied' => true));
            } else {
                echo json_encode(array('Not applied' => false));
            }
        } else {
            echo json_encode(array('Not applied' => false));
        }

    } else {
        echo json_encode(array('Not applied' => false));
    }
} else {
    echo json_encode(array('Invalid request method' => false));
}
