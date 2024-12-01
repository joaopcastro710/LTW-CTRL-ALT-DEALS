<?php

declare(strict_types=1);

require_once ("../templates/common.tpl.php");
require_once ("../templates/itemsbought.tpl.php");
require_once ("../utils/session.php");
require_once ("../database/connection_db.php");
require_once ("../database/item_db.php");
require_once ("../database/user_db.php");
require_once ("../templates/users.tpl.php");
require_once ("../utils/shipping_calculator.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$itemsbought = getItemsBought($db, $session->getUserId());

foreach ($itemsbought as &$item) {
    $seller = $item['seller'];
    $shipping = calculateShippingCost($db, $seller, $session->getUserId());
    $item['shipping'] = $shipping;
}


draw_header("Bought Items", ["/javascript/script.js", "/javascript/itemsbought.js"]);
draw_profile_tab();
draw_items_bought($db, $itemsbought);
draw_footer();