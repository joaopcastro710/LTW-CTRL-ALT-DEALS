<?php

declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/item_db.php');
require_once (__DIR__ . '/../utils/forms_validator.php');
require_once ("../utils/session.php");
require_once ("../utils/currency.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $itemId = $_POST['itemId'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $size = $_POST['size'];
    $condition = $_POST['condition'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if ($category === '') {
        $response['edit-item-category-error'] = 'Please enter the category.';
    }

    if ($brand === '') {
        $response['edit-item-brand-error'] = 'Please enter the brand.';
    } elseif (containsUnexpectedChars($brand)) {
        $response['edit-item-brand-error'] = 'Brand contains invalid characters.';
    }

    if ($model === '') {
        $response['edit-item-model-error'] = 'Please enter the model.';
    } elseif (containsUnexpectedChars($model)) {
        $response['edit-item-model-error'] = 'Model contains invalid characters.';
    }

    if ($size === '') {
        $response['edit-item-size-error'] = 'Please enter the size.';
    }

    if ($condition === '') {
        $response['edit-item-condition-error'] = 'Please enter the condition.';
    }

    if ($price === '') {
        $response['edit-item-price-error'] = 'Please enter the price.';
    } elseif (!isValidNumericInput($price)) {
        $response['edit-item-price-error'] = 'Price should be a numeric value.';
    }

    if ($description === '') {
        $response['edit-item-description-error'] = 'Please enter the description.';
    } elseif (containsUnexpectedChars($price)) {
        $response['edit-item-description-error'] = 'Description contains invalid characters.';
    }

    if (!empty($response)) {
        $response['error'] = "An error occurred while creating the item.";
        echo json_encode($response);
        exit;
    }

    $db = getDatabaseConnection();
    if ($itemId) {
        $sellerId = getItemSeller($db, $itemId)["seller"];
        if ($sellerId === $session->getUserId()) {

            $currencyName = getCurrency();
            $conversionAmount = getCurrencyConversion($currencyName);

            if ($currencyName !== "EUR") {
                $price = $price / $conversionAmount;
            }

            if (updateItem($db, $itemId, $category, $brand, $model, $size, $condition, $price, $description)) {
                $response = array('success' => true);
                echo json_encode($response);
                exit;
            }
        } else {
            $response = array('edit-item-image-error' => $sellerId);
            echo json_encode($response);
            exit;
        }

    }
    $response = array('edit-item-image-error' => 'An error occurred while creating the item.');
    echo json_encode($response);
} else {
    echo "An error occurred while creating the item.";
}