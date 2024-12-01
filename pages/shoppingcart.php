<?php

declare(strict_types=1);

require_once ('../templates/common.tpl.php');
require_once ('../templates/shoppingcart.tpl.php');
require_once ("../utils/session.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

draw_header('Ctrl+Alt+Deals', ["/javascript/script.js", "/javascript/shoppingcart.js"]);
drawShoppingCartPage($session->getUserId());
draw_footer();
