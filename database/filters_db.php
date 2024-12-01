<?php

declare(strict_types=1);

function getAllCategories($db) {
    $stmt = $db->prepare('SELECT * FROM Category');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getSize($db, $size) {
    $stmt = $db->prepare('SELECT sizeName FROM Size WHERE sizeId = ?');
    $stmt->bindParam(1, $size);
    $stmt->execute();
    return $stmt->fetch();
}

function getCondition($db, $condition) {
    $stmt = $db->prepare('SELECT conditionName FROM Condition WHERE conditionId = ?');
    $stmt->bindParam(1, $condition);
    $stmt->execute();
    return $stmt->fetch();
}


function getCategoryName($db, $category_id) {
    $stmt = $db->prepare('SELECT categoryName FROM Category WHERE categoryId = ?');
    $stmt->bindParam(1, $category_id);
    $stmt->execute();
    return $stmt->fetch();
}

function getAllConditions($db) {
    $stmt = $db->prepare('SELECT * FROM Condition');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAllSizes($db) {
    $stmt = $db->prepare('SELECT * FROM Size');
    $stmt->execute();
    return $stmt->fetchAll();
}