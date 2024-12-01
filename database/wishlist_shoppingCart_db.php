<?php

declare(strict_types=1);

require_once ("../utils/session.php");

function getWishlistItems(PDO $db, int $userID) {
    $stmt = $db->prepare('SELECT Item.itemID, Item.brand, Wishlist.wishlistID
                          FROM Wishlist JOIN WishlistItem JOIN Item
                          ON Wishlist.wishlistID = WishlistItem.wishlistID
                          AND WishlistItem.itemId = Item.itemId
                          WHERE Wishlist.uid = ?');
    $stmt->bindParam(1, $userID);
    $stmt->execute();
    return $stmt->fetchAll();
}


function removeWishlistItems(PDO $db, int $user_id, int $item_id) {
    $didDelete = false;
    $session = new Session();

    if (!$session->isLoggedIn()) {
        exit;
    }

    $wishlistItems = getWishlistItems($db, $user_id);

    foreach ($wishlistItems as $wishlistItem) {
        if ($wishlistItem['itemId'] === $item_id) {
            $stmt = $db->prepare('DELETE FROM WishlistItem WHERE wishlistID = ? AND itemId = ?');
            $stmt->bindParam(1, $wishlistItem['wishlistID']);
            $stmt->bindParam(2, $item_id);
            $stmt->execute();
            $didDelete = true;
        }
    }

    return $didDelete;
}

function checkIfItemInWishlist(PDO $db, int $itemID) {
    $session = new Session();
    $stmt = $db->prepare('SELECT wishlistID FROM Wishlist WHERE uid = ?');
    $userId = $session->getUserId();
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $res = $stmt->fetch();
    if ($res) {
        $stmt = $db->prepare('SELECT itemId FROM WishlistItem WHERE wishlistID = ? AND itemId = ?');
        $stmt->bindParam(1, $res['wishlistID']);
        $stmt->bindParam(2, $itemID);
        $stmt->execute();
        $itemId = $stmt->fetch();
        if ($itemId) {
            return true;
        }
    }
    return false;
}

function checkIfWishlistExists(PDO $db, string $userID) {
    $stmt = $db->prepare('SELECT wishlistID FROM Wishlist WHERE uid = ?');
    $stmt->bindParam(1, $userID);
    $stmt->execute();
    return $stmt->fetch();
}

function checkIfWishlistItemValid(PDO $db, int $wishlist_id, int $item_id) {
    $stmt = $db->prepare('SELECT itemId FROM WishlistItem WHERE wishlistID = ? AND itemId = ?');
    $stmt->bindParam(1, $wishlist_id);
    $stmt->bindParam(2, $item_id);
    $stmt->execute();
    try {
        return $stmt->fetch();
    } catch (PDOException $ex) {
        return false;
    }

}

function createWishlist(PDO $db, string $userID) {
    $stmt = $db->prepare('INSERT INTO Wishlist (wishlistID, uid) VALUES (?, ?)');
    $stmt->bindParam(1, $userID);
    $stmt->bindParam(2, $userID);
    $stmt->execute();
}

function addToWishlist(PDO $db, string $userID, int $itemId) {

    $res = checkIfWishlistExists($db, $userID);
    if (!$res) {
        createWishlist($db, $userID);
        $res = checkIfWishlistExists($db, $userID);
    }

    if (!checkIfWishlistItemValid($db, $res['wishlistID'], $itemId)) {
        $stmt = $db->prepare('INSERT INTO WishlistItem (wishlistID, itemId) VALUES (?,?)');
        $stmt->bindParam(1, $res['wishlistID']);
        $stmt->bindParam(2, $itemId);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
