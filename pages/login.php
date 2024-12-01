<?php

declare(strict_types=1);

require_once ('../templates/users.tpl.php');
require_once ("../utils/session.php");
require_once ("../templates/common.tpl.php");

$session = new Session();

if ($session->isLoggedIn()) {
    header('Location: /pages/mainPage.php');
    exit;
}

draw_header('Login', ["/javascript/login.js", "/javascript/forms_validator.js"]);
draw_login_form();
draw_footer();
