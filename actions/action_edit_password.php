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
    $userId = $session->getUserId();
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    $response = [];

    if ($currentPassword === '' || $newPassword === '') {
        $response['reset-password-error'] = 'Please fill in both current and new password fields.';
    } else {
        $db = getDatabaseConnection();

        if (!regexPassword($currentPassword)) {
            $response['profile-currentPassword-error'] = 'Invalid password format. Must contain at least one lowercase letter, one uppercase letter, one digit, one special character, and be at least 8 characters long';
        }

        if (!regexPassword($newPassword)) {
            $response['profile-newPassword-error'] = 'Invalid new password format. Must contain at least one lowercase letter, one uppercase letter, one digit, one special character, and be at least 8 characters long';
        }

        if (empty($response)) {
            if (updateUserPassword($db, $userId, $currentPassword, $newPassword)) {
                $response['success'] = true;
                $response['message'] = 'Password updated successfully.';
            } else {
                $response['reset-password-error'] = 'Failed to update password. Current password is incorrect.';
            }
        }
    }

    echo json_encode($response);
    exit;
} else {
    $response = array('error' => 'An error occurred while resetting the password.');
    echo json_encode($response);
}
