<?php
require_once ("../database/user_db.php");
function calculateShippingCost($db, $buyerId, $sellerId): float {
    $buyerContinent = getUserContinent($db, $buyerId);
    $sellerContinent = getUserContinent($db, $sellerId);

    if ($buyerContinent === $sellerContinent) {
        return 5.00;
    } else {
        $continentDistances = [
            0 => [1 => 15, 2 => 25, 3 => 20, 4 => 10, 5 => 35, 6 => 15],
            1 => [0 => 15, 2 => 25, 3 => 20, 4 => 15, 5 => 40, 6 => 20],
            2 => [0 => 25, 1 => 25, 3 => 30, 4 => 40, 5 => 50, 6 => 45],
            3 => [0 => 20, 1 => 20, 2 => 30, 4 => 15, 5 => 45, 6 => 25],
            4 => [0 => 10, 1 => 15, 2 => 40, 3 => 15, 5 => 30, 6 => 5],
            5 => [0 => 35, 1 => 40, 2 => 50, 3 => 45, 4 => 30, 6 => 45],
            6 => [0 => 15, 1 => 20, 2 => 45, 3 => 25, 4 => 5, 5 => 45],
        ];
        return $continentDistances[$sellerContinent][$buyerContinent] ?? (float)$buyerContinent;
    }
}
