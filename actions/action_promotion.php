<?php

require_once ('../database/connection_db.php');
require_once ('../utils/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session = new Session();
    if ($session->isLoggedIn() && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {
        $session->setPromotionPopUpShown(true);
    } else {
        echo json_encode(array('User not logged, itemID not provided or invalid token' => false));
    }
} else {
    echo json_encode(array('Invalid request method' => false));
}
