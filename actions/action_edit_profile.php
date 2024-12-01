<?php
declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/user_db.php');
require_once (__DIR__ . '/../utils/forms_validator.php');
require_once ("../utils/session.php");
require_once ("../utils/currency.php");

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: /pages/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $postalCode = $_POST['postalCode'];
    $continent = $_POST['continent'];
    $currency = $_POST['currency'];

    $currencyExists = false;

    $currencies = CurrencyType::cases();
    for ($i = 0; $i < count($currencies); $i++) {
        if ($currencies[$i]->value === $currency) {
            $currencyExists = true;
            break;
        }
    }

    if (!$currencyExists) {
        $response = array('profile-currency-error' => 'Invalid currency.');
        echo json_encode($response);
        exit;
    }

    if (!regexUsername($username)) {
        $response = array('profile-username-error' => 'Invalid username format. Please use only letters and numbers.');
        echo json_encode($response);
        exit;
    }

    if (!regexEmail($email)) {
        $response = array('profile-email-error' => 'Invalid email format. Please use a valid email address.');
        echo json_encode($response);
        exit;
    }

    if (empty($name)) {
        $response = array('profile-name-error' => 'Please enter your name.');
        echo json_encode($response);
        exit;
    }

    if (empty($address)) {
        $response = array('profile-address-error' => 'Please enter your address.');
        echo json_encode($response);
        exit;
    }

    if (empty($postalCode)) {
        $response = array('profile-postalCode-error' => 'Please enter your postal code.');
        echo json_encode($response);
        exit;
    }

    if (containsUnexpectedChars($name)) {
        $response = array('profile-name-error' => 'Name contains invalid characters.');
        echo json_encode($response);
        exit;
    }

    if (containsUnexpectedChars($address)) {
        $response = array('profile-address-error' => 'Address contains invalid characters.');
        echo json_encode($response);
        exit;
    }

    if (containsUnexpectedChars($postalCode)) {
        $response = array('profile-postalCode-error' => 'Postal code should contain only numeric characters.');
        echo json_encode($response);
        exit;
    }

    if ($continent === '') {
        $response = array('profile-continent-error' => 'Please enter the Continent.');
        echo json_encode($response);
        exit;
    }

    if (containsUnexpectedChars($currency)) {
        $response = array('profile-currency-error' => 'Invalid currency.');
        echo json_encode($response);
        exit;
    }

    $db = getDatabaseConnection();
    $userId = $session->getUserId();

    $userAvailable = checkUsernameAvailableExcludeId($db, $username, $userId);
    $emailAvailable = checkEmailAvailableExcludeId($db, $email, $userId);

    if (!$userAvailable || !$emailAvailable || $username === '' || $email === '' || $name === '' || $address === '' || $postalCode === '' || $currency === '' || $continent === '') {
        if (!$userAvailable) {
            $response = array('profile-username-error' => 'Username not available. Please choose a different one.');
        } else if (!$emailAvailable) {
            $response = array('profile-email-error' => 'Email already in use. Please use a different one or login.');
        } else {
            $response = array('profile-error' => 'Credentials not available. Please choose a different one.');
        }
    } else {
        $message = updateUserProfile($db, $userId, $username, $email, $name, $address, $postalCode, $continent);
        setCurrency($currency);
        if ($message) {
            $response = array('success' => true, 'message' => 'Profile updated successfully.', "userId" => $userId);
        } else {
            $response = array('success' => false, 'profile-error' => 'Failed to update profile.');
        }
    }
    echo json_encode($response);
    exit;
} else {
    $response = array('success' => false, 'profile-error' => 'Failed to update profile.');
    echo json_encode($response);
    exit;
}
