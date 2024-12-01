<?php
declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/item_db.php');
require_once ("../utils/session.php");
require_once ("../utils/currency.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['category'], $_GET['condition'], $_GET['size'], $_GET['sort'])) {
        $category = $_GET['category'];
        $condition = $_GET['condition'];
        $size = $_GET['size'];
        $sort = $_GET['sort'];

        $db = getDatabaseConnection();
        $filteredItems = getFilteredItemsFromSeller($db, $session->getUserId(), $category, $condition, $size, $sort);

        $currencyName = getCurrency();
        $conversionAmount = getCurrencyConversion($currencyName);

        foreach ($filteredItems as &$filteredItem) {
            $filteredItem['price'] = number_format(($filteredItem['price'] * $conversionAmount), 2) . " " . $currencyName;
        }

        header('Content-Type: application/json');
        echo json_encode($filteredItems);
    } else {
        echo json_encode(array('message' => 'Required parameters not provided', 'success' => false));
    }
} else {
    echo json_encode(array('message' => 'Invalid request method', 'success' => false));
}
