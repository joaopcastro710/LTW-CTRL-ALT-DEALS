<?php
declare(strict_types=1);

require_once ("../templates/common.tpl.php");
require_once ("../templates/mainPage.tpl.php");
require_once ("../database/connection_db.php");
require_once ("../database/item_db.php");
require_once ("../database/filters_db.php");
require_once ("../utils/session.php");
require_once ("../database/user_db.php");
require_once ("../templates/seller.tpl.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$userId = getUserProfile($db, $_SESSION["user_id"]);
$categories = getAllCategories($db);
$sizes = getAllSizes($db);
$conditions = getAllConditions($db);
$items = getFilteredItems($db, 'all_categories', 'all_conditions', 'all_sizes', 'model_asc');

draw_header("Ctrl+Alt+Deals", ["/javascript/script.js", "../javascript/mainPage.js"]);
drawMainPage($items, $categories, $sizes, $conditions);
draw_footer();
