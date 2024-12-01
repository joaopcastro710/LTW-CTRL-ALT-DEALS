<?php

declare(strict_types=1);

require_once ("../templates/common.tpl.php");
require_once ("../templates/itemPage.tpl.php");
require_once ("../utils/session.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: /pages/mainPage.php');
    exit;
}

draw_header("Ctrl+Alt+Deals", ["/javascript/script.js", "/javascript/item.js"]);
drawItemPage($_GET['id']);
draw_footer();
