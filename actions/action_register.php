<?php

declare(strict_types=1);

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/user_db.php');
require_once (__DIR__ . '/../utils/forms_validator.php');
require_once ("../utils/session.php");
require_once ("../utils/forms_validator.php");

$session = new Session();

if ($session->isLoggedIn()) {
    header('Location: /pages/mainPage.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['priv_csrf'] === $_POST['token']) {
    $username = $_POST['username'];
    $userPassword = $_POST['password'];
    $userEmail = $_POST['email'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $postcode = $_POST['postcode'];
    $continent = $_POST['continent'];

    if (!regexUsername($username)) {
        $response = array('username_error' => 'Invalid username format. Please use only letters and numbers.');
        echo json_encode($response);
        exit;
    }

    if (!regexEmail($userEmail)) {
        $response = array('email_error' => 'Invalid email format. Please use a valid email address.');
        echo json_encode($response);
        exit;
    }

    if (!regexPassword($userPassword)) {
        $response = array('password_error' => 'Invalid password format. Must contain at least one lowercase letter, one uppercase letter, one digit, one special character, and be at least 8 characters long.');
        echo json_encode($response);
        exit;
    }

    if (empty($name)) {
        $response = array('name_error' => 'Please enter your name.');
        echo json_encode($response);
        exit;
    }

    if (empty($address)) {
        $response = array('address_error' => 'Please enter your address.');
        echo json_encode($response);
        exit;
    }

    if (empty($postcode)) {
        $response = array('postcode_error' => 'Please enter your postal code.');
        echo json_encode($response);
        exit;
    }

    if (containsUnexpectedChars($name)) {
        $response = array('name_error' => 'Name contains invalid characters.');
        echo json_encode($response);
        exit;
    }

    if (containsUnexpectedChars($address)) {
        $response = array('address_error' => 'Address contains invalid characters.');
        echo json_encode($response);
        exit;
    }

    if (containsUnexpectedChars($postcode)) {
        $response = array('postcode_error' => 'Postal code contains invalid characters.');
        echo json_encode($response);
        exit;
    }

    if ($continent === '') {
        $response = array('continent_error' => 'Please enter the Continent.');
        echo json_encode($response);
        exit;
    }

    $db = getDatabaseConnection();
    $userAvailable = checkUsernameAvailable($db, $username);
    $emailAvailable = checkEmailAvailable($db, $userEmail);

    if (!$userAvailable || !$emailAvailable || $name === '' || $address === '' || $postcode === '' || $continent === '') {
        if (!$userAvailable) {
            $response = array('username_error' => 'Username not available. Please choose a different one.');
            echo json_encode($response);
        } else if (!$emailAvailable) {
            $response = array('email_error' => 'Email already in use. Please use a different one or login.');
            echo json_encode($response);
        } else {
            $response = array('general_error' => 'Credentials not available. Please choose a different one.');
            echo json_encode($response);
        }
        exit;
    }

    try {
        $db->beginTransaction();

        addUser($db, $userEmail, $username, $name, $userPassword, $continent);

        $userId = userExists($db, $userEmail, $userPassword);

        if ($userId !== null) {
            setAddress($db, $userId, $address, $postcode);
        } else {
            throw new Exception('Failed to create user. Please try again later.');
        }

        $db->commit(); // Commit transaction if all operations succeeded
        $response = array('success' => true, 'userId' => $userId);
        echo json_encode($response);
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        $response = array('error' => $e->getMessage());
        echo json_encode($response);
        exit;
    }
} else {
    $response = array('error' => "Error registering. Try again");
    echo json_encode($response);
    exit;
}
