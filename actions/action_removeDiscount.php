<?php

require_once ('../database/connection_db.php');
require_once ('../database/shoppingCart_db.php');
require_once ('../utils/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session = new Session();
    if ($session->isLoggedIn() && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {

        if (removeDiscount(getDatabaseConnection(), $session->getUserId())) {
            echo json_encode(array('Removed' => true));
        } else {
            echo json_encode(array('Not removed' => false));
        }

    } else {
        echo json_encode(array('Not applied' => false));
    }
} else {
    echo json_encode(array('Invalid request method' => false));
}
