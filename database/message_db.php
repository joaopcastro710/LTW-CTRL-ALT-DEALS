<?php

declare(strict_types=1);

function getSellersMessageList(PDO $db, int $userID): false|array {
    $stmt = $db->prepare("SELECT Chat.item, Item.model, Chat.chatId FROM Chat JOIN Item ON Chat.item = Item.itemId
                                 WHERE Item.seller = ?");
    $stmt->bindParam(1, $userID);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getBuyersMessageList(PDO $db, int $userID): false|array {
    $stmt = $db->prepare("SELECT Chat.item, Item.model, Chat.chatId FROM Chat JOIN Item ON Chat.item = Item.itemId
                                WHERE Chat.buyer = ?");
    $stmt->bindParam(1, $userID);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getChats(PDO $db, int $userID, int $chatID, int $itemID): false|array {
    $isSeller = checkIfItemBelongsToSeller($db, $itemID, $userID);
    $isBuyer = checkIfItemBelongsToBuyer($db, $chatID, $itemID, $userID);

    if (!$isSeller && !$isBuyer) {
        return false;
    }

    $stmt = $db->prepare("SELECT Messages.sender, Messages.timestamp, Messages.content, Messages.chat, Chat.buyer, Chat.item
                                FROM Chat JOIN Messages ON Chat.chatId = Messages.chat WHERE Chat.chatId = ? AND Chat.item = ?");
    $stmt->bindParam(1, $chatID);
    $stmt->bindParam(2, $itemID);
    $stmt->execute();
    return $stmt->fetchAll();
}

function checkIfItemBelongsToSeller(PDO $db, int $itemID, int $userID) {
    $stmt = $db->prepare("SELECT 1 FROM Item WHERE seller = ? and itemId = ?");
    $stmt->bindParam(1, $userID);
    $stmt->bindParam(2, $itemID);
    $stmt->execute();
    return $stmt->fetch();
}

function checkIfItemBelongsToBuyer(PDO $db, int $chatID, int $itemID, int $userID) {
    $stmt = $db->prepare("SELECT 1 FROM Chat WHERE chatId = ? AND item = ? AND buyer = ?");
    $stmt->bindParam(1, $chatID);
    $stmt->bindParam(2, $itemID);
    $stmt->bindParam(3, $userID);
    $stmt->execute();
    return $stmt->fetch();
}

function checkIfChatExists(PDO $db, int $chat_id) {
    $stmt = $db->prepare("SELECT * FROM Chat WHERE chatId = ?");
    $stmt->bindParam(1, $chat_id);
    $stmt->execute();
    return $stmt->fetch();
}

function addMessage(PDO $db, int $user_id, string $timestamp, string $content, int $chat_id): bool {
    $stmt = $db->prepare("INSERT INTO Messages (sender, timestamp, content, chat) 
                                 VALUES (?,?,?,?)");
    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $timestamp);
    $stmt->bindParam(3, $content);
    $stmt->bindParam(4, $chat_id);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
    return true;
}

function addChat(PDO $db, int $user_id, int $itemID): bool {
    $stmt = $db->prepare("INSERT INTO Chat (buyer, item) 
                                 VALUES (?,?)");
    $stmt->bindParam(1, $user_id);
    $stmt->bindParam(2, $itemID);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
    return true;
}