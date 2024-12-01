<?php

declare(strict_types=1);

require_once "../templates/common.tpl.php";
require_once "../templates/mainPage.tpl.php";
require_once "../utils/session.php";
require_once('../database/connection_db.php');
require_once('../database/user_db.php');
require_once('../database/item_db.php');
require_once ('../database/shoppingCart_db.php');
require_once "../database/search_db.php";
require_once "../templates/search.tpl.php";

$session = new Session();

if (!$session->isLoggedIn()){
    header('Location: /pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$searchTerm =(string) $_GET['search'];
$matchingItems = search_items_by_name($db, $searchTerm);

draw_header('Ctrl+Alt+Deals', ["/javascript/script.js"]);
drawSearchPage($db, $matchingItems);
draw_footer();