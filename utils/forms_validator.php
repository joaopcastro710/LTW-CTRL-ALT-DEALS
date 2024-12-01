<?php

function regexUsername($username) {
    $regex = '/^[a-zA-Z0-9]+$/';
    return preg_match($regex, $username);
}

function regexEmail($email) {
    $regex = '/\S+@\S+\.\S+/';
    return preg_match($regex, $email);
}

function regexPassword($password) {
    $regex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/';
    return preg_match($regex, $password);
}

function containsUnexpectedChars($input) {
    $allowedChars = '/^[\p{L}\p{N}\s.,-]*$/u';
    return !preg_match($allowedChars, $input);
}

function isValidNumericInput($input) {
    return preg_match('/^\d+(\.\d+)?$/', $input);
}
