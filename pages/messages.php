<?php

declare(strict_types=1);

require_once ("../templates/common.tpl.php");
require_once ("../templates/messages.tpl.php");
require_once ("../utils/session.php");
require_once ("../templates/users.tpl.php");
$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

draw_header("Ctrl+Alt+Deals", ["/javascript/script.js", "/javascript/messages.js"]);
draw_profile_tab();
drawMessagePage();
draw_footer();
