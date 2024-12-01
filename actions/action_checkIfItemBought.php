<?php

require_once ("../database/connection_db.php");
require_once ("../database/buying_db.php");
require_once ("../utils/session.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemId'])) {
    if (isItemBought(getDatabaseConnection(), (int)$_POST['itemId'])) {
        echo json_encode(array('response' => true));
    } else {
        echo json_encode(array('response' => false));
    }
} else {
    echo json_encode(array('response' => false));
}
