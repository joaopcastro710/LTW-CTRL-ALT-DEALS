<?php
declare(strict_types=1);

require_once ("../utils/session.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

$session->logout();
header('Location: ../pages/login.php');
