<?php
require_once("item_db.php");

function getSellerRating($db, $userId) {
    $stmt = $db->prepare("SELECT AVG(grade) AS average_rating FROM Review WHERE sellerId = ?");
    $stmt->bindParam(1, $userId);

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && isset($result['average_rating'])) {
        return $result['average_rating'];
    } else {
        return -1;
    }
}

function addUser($db, $userEmail, $username, $name, $userPassword, $continent, $isAdmin = 0) {
    $options = ['cost' => 12];
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT, $options);

    $stmt = $db->prepare('INSERT INTO User (email, password, username, name, favCurrency ,isAdmin, continentId) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->bindParam(1, $userEmail);
    $stmt->bindParam(2, $hashedPassword);
    $stmt->bindParam(3, $username);
    $stmt->bindParam(4, $name);
    $favCur = 0;
    $stmt->bindParam(5, $favCur);
    $stmt->bindParam(6, $isAdmin);
    $stmt->bindParam(7, $continent);
    $stmt->execute();
}

function checkIfUserIsValid($db, $uid) {
    $stmt = $db->prepare('SELECT userId FROM User WHERE userId = ?');
    $stmt->bindParam(1, $uid);
    $stmt->execute();
    return $stmt->fetch();
}

function userExists($db, $email, $userPassword) {
    $stmt = $db->prepare('SELECT userId, password FROM User WHERE email = ?');
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result && password_verify($userPassword, $result['password'])) {
        return $result['userId'];
    } else {
        return null;
    }
}

function getUserProfile($db, $userId) {
    $stmt = $db->prepare('SELECT username, email, password, name, address, postalCode, userImageId, continentName FROM User JOIN Address ON User.userId = Address.userId LEFT JOIN UserImage ON User.userId = UserImage.userId JOIN Continents ON User.continentId = Continents.continentId WHERE User.userId = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    return $stmt->fetch();
}

function updateUserProfile($db, $userId, $username, $email, $name, $address, $postalCode, $continent): bool {
    updateAddress($db, $userId, $address, $postalCode);
    $stmt = $db->prepare('UPDATE User SET email = ?, username = ?, name = ? , continentId = ? WHERE userId = ?');
    $stmt->bindParam(1, $email);
    $stmt->bindParam(2, $username);
    $stmt->bindParam(3, $name);
    $stmt->bindParam(4, $continent);
    $stmt->bindParam(5, $userId);
    return $stmt->execute();
}

function addUserImage($db, $userId) {
    $stmt = $db->prepare('INSERT INTO UserImage (userId) VALUES (?)');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
}

function replaceUserImage($db, $userId) {
    $stmt = $db->prepare('DELETE FROM UserImage WHERE userId = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    addUserImage($db, $userId);
}

function getAddress($db, $userID) {
    $stmt = $db->prepare('SELECT address, postalCode FROM Address WHERE userId = ?');
    $stmt->bindParam(1, $userID);
    $stmt->execute();
    return $stmt->fetch();
}

function setAddress($db, $userID, $address, $postalCode) {
    $stmt = $db->prepare('INSERT INTO Address (userId, address, postalCode) VALUES (?, ?, ?)');
    $stmt->bindParam(1, $userID);
    $stmt->bindParam(2, $address);
    $stmt->bindParam(3, $postalCode);
    $stmt->execute();
}

function updateAddress($db, $userID, $address, $postalCode) {
    $stmt = $db->prepare('UPDATE Address SET address = ?, postalCode = ? WHERE userId = ?');
    $stmt->bindParam(1, $address);
    $stmt->bindParam(2, $postalCode);
    $stmt->bindParam(3, $userID);
    $stmt->execute();
}

function getPayment($db, $userID) {
    $stmt = $db->prepare('SELECT dateHour, status, shoppingCart FROM Payment WHERE userId = ?');
    $stmt->bindParam(1, $userID);
    $stmt->execute();
    return $stmt->fetch();
}

function setPayment($db, $userID, $dateHour, $status, $shoppingCartID) {
    $stmt = $db->prepare('INSERT INTO Payment (userId, dateHour, status, shoppingCart) VALUES (?, ?, ?, ?)');
    $stmt->bindParam(1, $userID);
    $stmt->bindParam(2, $dateHour);
    $stmt->bindParam(3, $status);
    $stmt->bindParam(4, $shoppingCartID);
    $stmt->execute();
}

function checkUsernameAvailable($db, $username): bool {
    $stmt = $db->prepare('SELECT * FROM User WHERE username = ?');;
    $stmt->bindParam(1, $username);
    $stmt->execute();
    $result = $stmt->fetch();
    if (empty($result)) return TRUE;
    else return FALSE;
}

function checkEmailAvailable($db, $userEmail): bool {
    $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');;
    $stmt->bindParam(1, $userEmail);
    $stmt->execute();
    $result = $stmt->fetch();
    if (empty($result)) return TRUE;
    else return FALSE;
}

function checkUsernameAvailableExcludeId($db, $username, $userId): bool {
    $stmt = $db->prepare('SELECT * FROM User WHERE username = ? AND userId != ?');
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $userId);
    $stmt->execute();
    $result = $stmt->fetch();
    if (empty($result)) return TRUE;
    else return FALSE;
}

function checkEmailAvailableExcludeId($db, $userEmail, $userId): bool {
    $stmt = $db->prepare('SELECT * FROM User WHERE email = ? AND userId != ?');
    $stmt->bindParam(1, $userEmail);
    $stmt->bindParam(2, $userId);
    $stmt->execute();
    $result = $stmt->fetch();
    if (empty($result)) return TRUE;
    else return FALSE;
}

function updateUserPassword($db, $userId, $oldPassword, $newPassword) {
    $stmt = $db->prepare('SELECT password FROM User WHERE userId = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result && password_verify($oldPassword, $result['password'])) {
        $options = ['cost' => 12];
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT, $options);

        $updateStmt = $db->prepare('UPDATE User SET password = ? WHERE userId = ?');
        $updateStmt->bindParam(1, $hashedNewPassword);
        $updateStmt->bindParam(2, $userId);
        $updateStmt->execute();
        return true;
    } else {
        return false;
    }
}

function addSellerReview($db, $userId, $grade, $sellerId) {
    $stmt = $db->prepare('INSERT INTO Review (userId, grade, sellerId) VALUES (?, ?, ?)');
    $stmt->bindParam(1, $userId);
    $stmt->bindParam(2, $grade);
    $stmt->bindParam(3, $sellerId);
    $stmt->execute();
}

function getItemsSoldBySeller(PDO $db, int $sellerId): array {
    $stmt = $db->prepare("
        SELECT Item.*, User.name as buyerName, ItemBought.buyer as buyerId, Address.address, Address.postalCode
        FROM Item
        INNER JOIN ItemBought ON Item.itemId = ItemBought.itemId
        INNER JOIN User ON ItemBought.buyer = User.userId
        INNER JOIN Address ON User.userId = Address.userId
        WHERE Item.seller = :sellerId
    ");
    $stmt->execute(['sellerId' => $sellerId]);
    return $stmt->fetchAll();
}

function getItemsBought($db, $buyerId) {
    $stmt = $db->prepare('SELECT ItemBought.*, MIN(im.itemImageId) AS mainImageId, Item.*, Size.sizeName, Condition.conditionName, Category.categoryName, User.name as sellerName 
                          FROM ItemBought
                          JOIN Item ON ItemBought.itemId = Item.itemId
                          JOIN Size ON Item.size = Size.sizeId
                          JOIN Condition ON Item.condition = Condition.conditionId
                          JOIN Category ON Item.categoryId = Category.categoryId
                          JOIN User ON Item.seller = User.userId
                          LEFT JOIN ItemImage im ON Item.itemId = im.itemId
                          WHERE ItemBought.buyer = ?
                          GROUP BY ItemBought.itemId');
    $stmt->bindParam(1, $buyerId);
    $stmt->execute();
    return $stmt->fetchAll();
}

function acceptDelivery($db, $itemId, $userId) {
    $stmt = $db->prepare('UPDATE ItemBought SET delivered = 1 WHERE itemId = ? AND buyer = ?');
    $stmt->bindParam(1, $itemId);
    $stmt->bindParam(2, $userId);
    $stmt->execute();
}

function getFavoriteCurrency($db, $userId) {
    $stmt = $db->prepare('SELECT favCurrency FROM User WHERE userId = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $tot = $stmt->fetch();

    if (empty($tot) || $tot['favCurrency'] === null) {
        return 0;
    } else {
        return $tot['favCurrency'];
    }
}

function setFavoriteCurrency($db, $favCurrency, $userId) {
    $stmt = $db->prepare('UPDATE User SET favCurrency = ? WHERE userId = ?');
    $stmt->bindParam(1, $favCurrency);
    $stmt->bindParam(2, $userId);
    $stmt->execute();
}


function getContinents($db) {
    $stmt = $db->prepare('SELECT * FROM Continents');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserContinent($db, $userId) {
    $stmt = $db->prepare('SELECT continentId FROM USER WHERE userId = ?');
    $stmt->bindParam(1, $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && isset($result['continentId'])) {
        return $result['continentId'];
    } else {
        return null;
    }
}
