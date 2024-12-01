<?php

declare(strict_types=1);

function getAllItems(PDO $db) {
    $stmt = $db->prepare('
        SELECT 
            i.*, 
            MIN(im.itemImageId) AS mainImageId
        FROM 
            Item i
        LEFT JOIN 
            ItemImage im ON i.itemId = im.itemId
        GROUP BY 
            i.itemId
    ');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getItemWithId(PDO $db, string $item_id) {
    $stmt = $db->prepare('SELECT * FROM Item WHERE itemId = ?');
    $stmt->bindParam(1, $item_id);
    $stmt->execute();
    return $stmt->fetch();
}

function getItemSeller(PDO $db, string $itemId) {
    $stmt = $db->prepare('SELECT seller FROM Item WHERE itemId = ?');
    $stmt->bindParam(1, $itemId);
    $stmt->execute();
    return $stmt->fetch();
}

function getAllItemImages(PDO $db, string $item_id) {
    $stmt = $db->prepare('SELECT * FROM ItemImage WHERE itemId = ?');
    $stmt->bindParam(1, $item_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

function createItem($db, $category, $brand, $model, $size, $condition, $price, $description, $seller) {
    $stmt = $db->prepare('INSERT INTO Item (categoryId, brand, model, size, condition, price, description, seller) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bindParam(1, $category);
    $stmt->bindParam(2, $brand);
    $stmt->bindParam(3, $model);
    $stmt->bindParam(4, $size);
    $stmt->bindParam(5, $condition);
    $stmt->bindParam(6, $price);
    $stmt->bindParam(7, $description);
    $stmt->bindParam(8, $seller);
    $stmt->execute();
    return $db->lastInsertId();
}

function deleteItem($db, $item_id): bool {
    $stmt = $db->prepare('DELETE FROM Item WHERE itemId = ?');
    $stmt->bindParam(1, $item_id);
    return $stmt->execute();
}

function updateItem($db, $itemId, $category, $brand, $model, $size, $condition, $price, $description): bool {
    $stmt = $db->prepare('UPDATE Item SET categoryId = ?, brand = ?, model = ?, size = ?, condition = ?, price = ?, description = ? WHERE itemId = ? ');
    $stmt->bindParam(1, $category);
    $stmt->bindParam(2, $brand);
    $stmt->bindParam(3, $model);
    $stmt->bindParam(4, $size);
    $stmt->bindParam(5, $condition);
    $stmt->bindParam(6, $price);
    $stmt->bindParam(7, $description);
    $stmt->bindParam(8, $itemId);

    return $stmt->execute();
}

function getAllItemsFromSeller($db, $seller) {
    $stmt = $db->prepare('
        SELECT
            i.itemId as iId,
            i.categoryId,
            i.brand,
            i.model,
            i.size,
            i.condition,
            i.price,
            i.description,
            i.seller,
            ib.*,
            s.sizeName,
            MIN(im.itemImageId) AS mainImageId,
            c.categoryName,
            cond.conditionName
        FROM
            Item i
        LEFT JOIN
            ItemImage im ON i.itemId = im.itemId
        LEFT JOIN
            Category c ON i.categoryId = c.categoryId
        LEFT JOIN
            Size s ON s.sizeId = s.sizeId
        LEFT JOIN
            Condition cond ON i.condition = cond.conditionId
        LEFT JOIN
            ItemBought ib ON i.itemId = ib.itemId
        WHERE
            i.seller = ?
        GROUP BY
            i.itemId
    ');
    $stmt->bindParam(1, $seller);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getItemsFiltered($db, $seller, $category, $condition, $size, $sort) {
    $query = '';
    $params = [];
    $bindIndex = 1;

    if ($seller === '') {
        $query = 'SELECT i.*,
                      MIN(im.itemImageId) AS mainImageId,
                      im.itemId AS mainImageItemId,
                      c.*,
                      s.*,
                      cat.*
              FROM Item i
              LEFT JOIN ItemImage im ON i.itemId = im.itemId
              JOIN main.Condition c ON i.condition = c.conditionId
              JOIN main.Size s ON i.size = s.sizeId
              JOIN main.Category cat ON i.categoryId = cat.categoryId
              LEFT JOIN ItemBought ib ON i.itemId = ib.itemId
              WHERE ib.itemId IS NULL';
    } else {
        $query = 'SELECT i.*,
                      MIN(im.itemImageId) AS mainImageId,
                      im.itemId AS mainImageItemId,
                      c.*,
                      s.*,
                      cat.*
              FROM Item i
              LEFT JOIN ItemImage im ON i.itemId = im.itemId
              JOIN main.Condition c ON i.condition = c.conditionId
              JOIN main.Size s ON i.size = s.sizeId
              JOIN main.Category cat ON i.categoryId = cat.categoryId
              LEFT JOIN ItemBought ib ON i.itemId = ib.itemId
              WHERE i.seller = ? AND ib.itemId IS NULL';
        $params[] = $seller;
    }

    if ($category !== 'all_categories') {
        $query .= ' AND i.categoryId = ?';
        $params[] = $category;
    }

    if ($condition !== 'all_conditions') {
        $query .= ' AND i.condition = ?';
        $params[] = $condition;
    }

    if ($size !== 'all_sizes') {
        $query .= ' AND i.size = ?';
        $params[] = $size;
    }

    $query .= ' GROUP BY i.itemId';

    switch ($sort) {
        case 'model_desc':
            $query .= ' ORDER BY i.model DESC';
            break;
        case 'brand_asc':
            $query .= ' ORDER BY i.brand ASC';
            break;
        case 'brand_desc':
            $query .= ' ORDER BY i.brand DESC';
            break;
        case 'price_asc':
            $query .= ' ORDER BY i.price ASC';
            break;
        case 'price_desc':
            $query .= ' ORDER BY i.price DESC';
            break;
        default:
            $query .= ' ORDER BY i.model ASC';
            break;
    }

    $stmt = $db->prepare($query);

    foreach ($params as $param) {
        $stmt->bindValue($bindIndex, $param);
        $bindIndex++;
    }

    $stmt->execute();

    return $stmt->fetchAll();
}

function getFilteredItemsFromSeller($db, $seller, $category, $condition, $size, $sort) {
    return getItemsFiltered($db, $seller, $category, $condition, $size, $sort);
}

function addItemImage($db, $itemId) {
    $stmt = $db->prepare('INSERT INTO ItemImage (itemId) VALUES (?)');
    $stmt->bindParam(1, $itemId);
    $stmt->execute();
}

function getFilteredItems($db, $category, $condition, $size, $sort) {
    return getItemsFiltered($db, '', $category, $condition, $size, $sort);
}