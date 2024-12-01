<?php
declare(strict_types=1);

require_once ('../templates/users.tpl.php');
require_once ('../database/user_db.php');
require_once ('../database/connection_db.php');
require_once ('../database/item_db.php');
require_once ("../templates/common.tpl.php");
require_once ('../database/filters_db.php');
require_once ('../templates/seller.tpl.php');

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$userProfile = getUserProfile($db, $session->getUserId());
$itemsBeingSold = getAllItemsFromSeller($db, $session->getUserId());
$categories = getAllCategories($db);
$sizes = getAllSizes($db);
$conditions = getAllConditions($db);

draw_header('Items being sold', ["../javascript/seller.js", "../javascript/script.js"]);
draw_profile_tab();
draw_seller_items($categories, $sizes, $conditions, $itemsBeingSold);
draw_footer();

