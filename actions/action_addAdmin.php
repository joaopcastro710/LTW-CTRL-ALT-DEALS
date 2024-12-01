<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/user_db.php');
require_once ('../utils/session.php');
require_once ('../database/admin_actions_db.php');

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && $session->isLoggedIn() && isAdmin(getDatabaseConnection(), $session->getUserId()) && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {

        $username = $_POST['username'];

        $message = addAdmin(getDatabaseConnection(), $username);

        if ($message) {
            header('Location: /pages/admin_page.php?message=' . urlencode($message));
            exit;
        } else {
            header('Location: /pages/admin_page.php?message=Failed to add admin');
            exit;
        }

    } else {
        header('Location: /pages/admin_page.php?message=Invalid request');
        exit;
    }
} else {
    header('Location: /pages/admin_page.php?message=Invalid request method');
    exit;
}