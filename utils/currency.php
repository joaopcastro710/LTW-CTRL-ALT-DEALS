<?php

require_once ('../database/user_db.php');
require_once ('../database/connection_db.php');

enum CurrencyType: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case CNY = 'CNY';
    case JPY = 'JPY';
}

function getCurrency(): string {
    $session = new Session();
    $currencies = CurrencyType::cases();
    $userId = $session->getUserId();
    $curr = getFavoriteCurrency(getDatabaseConnection(), $userId);
    if ($curr < 0 || $curr >= count($currencies)) {
        $curr = 0;
    }
    return $currencies[$curr]->value;
}

function setCurrency(string $currency): void {
    $session = new Session();
    $userId = $session->getUserId();
    $currencyIndex = getCurrencyIndex($currency);

    if ($currencyIndex !== null) {
        setFavoriteCurrency(getDatabaseConnection(), $currencyIndex, $userId);
    } else {
        setFavoriteCurrency(getDatabaseConnection(), 0, $userId);
    }
}

function getCurrencyIndex(string $currency): ?int {
    $currencies = CurrencyType::cases();
    foreach ($currencies as $index => $currencyType) {
        if ($currencyType->value === $currency) {
            return $index;
        }
    }
    return null;
}

function getCurrencyConversion(string $currency): float {
    $currencyConversion = ['EUR' => 1.0, 'USD' => 1.09, 'CNY' => 7.87, 'JPY' => 169.57];
    $currencies = CurrencyType::cases();
    $index = 0;
    foreach ($currencies as $idx => $currencyType) {
        if ($currencyType->value === $currency) {
            $index = $idx;
        }
    }
    return $currencyConversion[$currencies[$index]->value];
}
