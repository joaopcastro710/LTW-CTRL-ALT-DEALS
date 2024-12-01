<?php

declare(strict_types=1);

function buyItems(PDO $db, array $cartItems, int $buyerId) {
    $db->beginTransaction();
    try {
        foreach ($cartItems as $item) {
            $stmt = $db->prepare("SELECT * FROM Item WHERE itemId = :itemId");
            $stmt->execute(['itemId' => $item['itemId']]);
            $itemDetails = $stmt->fetch();
            if (!$itemDetails) {
                throw new Exception("Item not found!");
            }

            if (isItemBought($db, $item['itemId'])) {
                continue;
            }

            $stmt = $db->prepare("INSERT INTO ItemBought (itemId, buyer) VALUES (:itemId, :buyer)");
            $stmt->execute([
                'itemId' => $item['itemId'],
                'buyer' => $buyerId
            ]);
            removeItemFromCart($db, $buyerId, $item['itemId']);
        }

        $db->commit();
    } catch (Exception $e) {
        $db->rollback();
        throw $e;
    }
}

function isItemBought(PDO $db, int $itemId) {
    $stmt = $db->prepare("SELECT * FROM ItemBought WHERE itemId = :itemId");
    $stmt->execute(['itemId' => $itemId]);
    $item = $stmt->fetch();

    if (empty($item)) {
        return false;
    }

    return true;
}