<?php
declare(strict_types=1);

require_once ("../templates/common.tpl.php");
require_once ("../templates/itemsold.tpl.php");
require_once ("../utils/session.php");
require_once ("../database/connection_db.php");
require_once ("../database/item_db.php");
require_once ("../database/user_db.php");
require_once ("../templates/users.tpl.php");
$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

$db = getDatabaseConnection();

$itemSold = getItemsSoldBySeller($db, $session->getUserId());

draw_header("Sold Items", ["/javascript/script.js", "/javascript/itemsold.js"]);
draw_profile_tab();
draw_items_sold($db, $itemSold);
draw_footer();
