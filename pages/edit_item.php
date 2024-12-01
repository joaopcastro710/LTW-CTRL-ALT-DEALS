<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../templates/create_item.tpl.php');
require_once ('../templates/users.tpl.php');
require_once ('../database/filters_db.php');
require_once ('../database/item_db.php');
require_once ('../utils/session.php');
require_once ("../templates/common.tpl.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}
if (!isset($_GET['id'])) {
    header('Location: /pages/mainPage.php');
    exit;
}

$db = getDatabaseConnection();
$categories = getAllCategories($db);
$sizes = getAllSizes($db);
$conditions = getAllConditions($db);
$item = getItemWithId($db, $_GET['id']);

draw_header('Edit Item', ["/javascript/edit_item.js", "/javascript/forms_validator.js"]);
draw_edit_item_form($item, $categories, $sizes, $conditions);
draw_footer();
