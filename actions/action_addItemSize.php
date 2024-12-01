<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/admin_actions_db.php');
require_once ('../utils/session.php');

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['size_name']) && $session->isLoggedIn() && isAdmin(getDatabaseConnection(), $session->getUserId()) && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {

        $sizeName = $_POST['size_name'];

        $message = addItemSize(getDatabaseConnection(), $sizeName);

        if ($message) {
            header('Location: /pages/admin_page.php?message=' . urlencode($message));
            exit;
        } else {
            header('Location: /pages/admin_page.php?message=Failed to add item size');
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