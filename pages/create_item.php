<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../templates/create_item.tpl.php');
require_once ('../templates/users.tpl.php');
require_once ('../database/filters_db.php');
require_once ('../utils/session.php');
require_once ("../templates/common.tpl.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$categories = getAllCategories($db);
$sizes = getAllSizes($db);
$conditions = getAllConditions($db);

draw_header('Create Item', ["/javascript/create_item.js", "/javascript/script.js", "/javascript/forms_validator.js"]);
draw_profile_tab();
draw_create_item_form($categories, $sizes, $conditions);
draw_footer();
