<?php

function isAdmin($db, $userId) {
    $stmt = $db->prepare("SELECT isAdmin FROM User WHERE userId = :userId");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if ($user) {
        return (bool)$user['isAdmin'];
    }
    return false;
}

function addAdmin($db, $username) {
    try {
        $user = getUserByUsername($db, $username);
        if ($user && !$user['isAdmin']) {
            // User exists and is not an admin
            $stmt = $db->prepare('UPDATE User SET isAdmin = :isAdmin WHERE username = :username');
            $stmt->execute([':isAdmin' => 1, ':username' => $username]);
            return "User $username is now an admin.";
        } elseif ($user && $user['isAdmin']) {
            return "User $username is already an admin.";
        } else {
            return "User $username does not exist.";
        }
    } catch (PDOException $e) {
        error_log('Failed to update admin status: ' . $e->getMessage());
        return "Failed to update admin status due to a system error.";
    }
}


function getUserByUsername($db, $username) {
    $stmt = $db->prepare('SELECT * FROM User WHERE username = :username');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
    if ($user) {
        return $user;
    }
    return null;
}

function doesCategoryExist($db, $categoryName) {
    $stmt = $db->prepare('SELECT * FROM Category WHERE CategoryName = ?');
    $stmt->execute([$categoryName]);
    return $stmt->fetch() !== false;
}

function addCategory($db, $categoryName) {
    if (!doesCategoryExist($db, $categoryName)) {
        $stmt = $db->prepare('INSERT INTO Category (CategoryName) VALUES (?)');
        $stmt->bindParam(1, $categoryName);
        $stmt->execute();
        return "Category $categoryName added successfully.";
    } else {
        return "Category $categoryName already exists.";
    }
}

function doesItemSizeExist($db, $sizeName) {
    $stmt = $db->prepare('SELECT * FROM Size WHERE SizeName = ?');
    $stmt->execute([$sizeName]);
    return $stmt->fetch() !== false;
}

function addItemSize($db, $sizeName) {
    if (!doesItemSizeExist($db, $sizeName)) {
        $stmt = $db->prepare('INSERT INTO Size (SizeName) VALUES (?)');
        $stmt->bindParam(1, $sizeName);
        $stmt->execute();
        return "Size $sizeName added successfully.";
    } else {
        return "Size $sizeName already exists.";
    }
}

function doesItemConditionExist($db, $conditionName) {
    $stmt = $db->prepare('SELECT * FROM Condition WHERE conditionName = ?');
    $stmt->execute([$conditionName]);
    return $stmt->fetch() !== false;
}

function addItemCondition($db, $conditionName) {
    if (!doesItemConditionExist($db, $conditionName)) {
        $stmt = $db->prepare('INSERT INTO Condition (conditionName) VALUES (?)');
        $stmt->bindParam(1, $conditionName);
        $stmt->execute();
        return "Condition $conditionName added successfully.";
    } else {
        return "Condition $conditionName already exists.";
    }
}
