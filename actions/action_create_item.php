<?php

declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/item_db.php');
require_once (__DIR__ . '/../utils/forms_validator.php');
require_once ("../utils/session.php");
require_once ('../utils/currency.php');

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $size = $_POST['size'];
    $condition = $_POST['condition'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if ($category === '') {
        $response['create-item-category-error'] = 'Please enter the category.';
    }

    if ($brand === '') {
        $response['create-item-brand-error'] = 'Please enter the brand.';
    } elseif (containsUnexpectedChars($brand)) {
        $response['create-item-brand-error'] = 'Brand contains invalid characters.';
    }

    if ($model === '') {
        $response['create-item-model-error'] = 'Please enter the model.';
    } elseif (containsUnexpectedChars($model)) {
        $response['create-item-model-error'] = 'Model contains invalid characters.';
    }

    if ($size === '') {
        $response['create-item-size-error'] = 'Please enter the size.';
    }

    if ($condition === '') {
        $response['create-item-condition-error'] = 'Please enter the condition.';
    }

    if ($price === '') {
        $response['create-item-price-error'] = 'Please enter the price.';
    } elseif (!isValidNumericInput($price)) {
        $response['create-item-price-error'] = 'Price should be a numeric value.';
    }

    if ($description === '') {
        $response['create-item-model-error'] = 'Please enter the model.';
    } elseif (containsUnexpectedChars($description)) {
        $response['create-item-model-error'] = 'Model contains invalid characters.';
    }

    if (!empty($response)) {
        $response['error'] = "An error occurred while creating the item.";
        echo json_encode($response);
        exit;
    }

    $db = getDatabaseConnection();

    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);

    if ($currencyName !== "EUR") {
        $price = $price / $conversionAmount;
    }

    $itemId = createItem($db, $category, $brand, $model, $size, $condition, $price, $description, $session->getUserId());

    if ($itemId) {
        $response = array('success' => true, 'lastInsertedId' => $itemId);
        echo json_encode($response);
    } else {
        $response = array('create-item-image-error' => 'An error occurred while creating the item.');
        echo json_encode($response);
    }

} else {
    $response = array('create-item-image-error' => 'An error occurred while creating the item.');
    echo json_encode($response);
}