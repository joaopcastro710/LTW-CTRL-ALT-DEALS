<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/admin_actions_db.php');
require_once ('../utils/session.php');

$session = new Session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['condition_name']) && $session->isLoggedIn() && isAdmin(getDatabaseConnection(), $session->getUserId()) && isset($_POST['token']) && $_POST['token'] === $_SESSION['priv_csrf']) {

        $conditionName = $_POST['condition_name'];

        $message = addItemCondition(getDatabaseConnection(), $conditionName);

        if ($message) {
            header('Location: /pages/admin_page.php?message=' . urlencode($message));
            exit;
        } else {
            header('Location: /pages/admin_page.php?message=Failed to add item condition');
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