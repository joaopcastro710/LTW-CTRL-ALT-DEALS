<?php
declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/user_db.php');
require_once (__DIR__ . '/../utils/forms_validator.php');
require_once ("../utils/session.php");

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $response['login-email-error'] = 'Please provide your email address.';
        echo json_encode($response);
        exit;
    } else if (!regexEmail($email)) {
        $response['login-email-error'] = 'Invalid email format. Please use a valid email address.';
        echo json_encode($response);
        exit;
    }

    if (empty($password)) {
        $response['login-password-error'] = 'Please provide your password.';
        echo json_encode($response);
        exit;
    } else if (!regexPassword($password)) {
        $response['login-password-error'] = 'Invalid password format. Must contain at least one lowercase letter, one uppercase letter, one digit, one special character, and be at least 8 characters long.';
        echo json_encode($response);
        exit;
    }

    $db = getDatabaseConnection();
    $user = userExists($db, $email, $password);

    if (!$user) {
        $response['login-error'] = 'Invalid email or password. Please try again.';
        echo json_encode($response);
    } else {
        $session->setUserId((int)$user);
        $response['success'] = true;
        echo json_encode($response);
    }
} else {
    header('Location: ../pages/login.php');
    exit;
}
