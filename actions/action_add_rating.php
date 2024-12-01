<?php

declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/user_db.php');
require_once (__DIR__ . '/../utils/forms_validator.php');
require_once ("../utils/session.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $db = getDatabaseConnection();
    $userId = $session->getUserId();
    if ($userId) {
        $sellerId = $_POST['sellerId'];
        $grade = $_POST['grade'];
        addSellerReview($db, $session->getUserId(), $grade, $sellerId);
        $response = array('success' => 'Review successfully added');
        echo json_encode($response);
        exit;
    }
}
$response = array('error' => 'An error occurred while making a review.');
echo json_encode($response);
exit;