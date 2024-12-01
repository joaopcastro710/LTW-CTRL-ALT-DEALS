<?php

declare(strict_types=1);

require_once ("../templates/common.tpl.php");
require_once ("../utils/session.php");
require_once ('../templates/users.tpl.php');
require_once ('../database/user_db.php');
require_once ('../database/connection_db.php');

$session = new Session();

if ($session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$continents = getContinents($db);

draw_header('Register', ["/javascript/register.js", "/javascript/forms_validator.js", "/javascript/userImage.js"]);
draw_register_form($continents);
draw_footer();
