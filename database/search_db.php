<?php

declare(strict_types=1);

function search_items_by_name(PDO $db, string $searchTerm) {
    $searchTerm = "%" . $searchTerm . "%";

    $query = 'SELECT i.*,
                      MIN(im.itemImageId) AS mainImageId,
                      im.itemId AS mainImageItemId
              FROM Item i
              LEFT JOIN ItemImage im ON i.itemId = im.itemId
              LEFT JOIN ItemBought ib ON i.itemId = ib.itemId
              WHERE (model LIKE :searchTerm OR brand LIKE :searchTerm) AND ib.itemId IS NULL
              GROUP BY i.itemId;';


    $stmt = $db->prepare($query);
    $stmt->bindParam(':searchTerm', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll();
}