<?php
declare(strict_types=1);

require_once ('../templates/users.tpl.php');
require_once ('../database/user_db.php');
require_once ('../database/connection_db.php');
require_once ('../database/item_db.php');
require_once ("../templates/common.tpl.php");
require_once ("../utils/session.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

$db = getDatabaseConnection();
$userProfile = getUserProfile($db, $_SESSION["user_id"]);
$userId = $session->getUserId();
$continents = getContinents($db);

draw_header('Profile Page', ["/javascript/profile.js", "/javascript/script.js", "/javascript/forms_validator.js", "/javascript/userImage.js"]);
draw_profile_tab();
draw_profile_page($db, $userProfile, $userId, $continents);
draw_footer();
