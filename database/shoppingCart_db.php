<?php
declare(strict_types=1);

function getShoppingCartItems($db, $userId) {
    $stmt = $db->prepare('
        SELECT 
            CartItem.cartId,
            Item.itemId,
            Category.categoryName,
            Item.brand,
            Item.model,
            Size.sizeName,
            Condition.conditionName,
            Item.price,
            Item.seller
            /*,CartItem.quantity*/
        FROM ShoppingCart
        JOIN CartItem ON ShoppingCart.cartId = CartItem.cartId
        JOIN Item ON CartItem.itemId = Item.itemId
        LEFT JOIN Size ON Item.size = Size.sizeId
        LEFT JOIN Condition ON Item.condition = Condition.conditionId
        LEFT JOIN Category ON Item.categoryId = Category.categoryId 
        WHERE ShoppingCart.uid = ?
    ');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCartTotal(PDO $db, int $userId) {
    $stmt = $db->prepare('SELECT total FROM ShoppingCart WHERE uid = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

function removeDiscount(PDO $db, int $userId): bool {
    $stmt = $db->prepare('SELECT cartId, total, promo FROM ShoppingCart WHERE uid = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $shoppingCart = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$shoppingCart || !$shoppingCart['promo']) {
        return false;
    }

    $cartId = $shoppingCart['cartId'];

    $stmt = $db->prepare('SELECT itemId FROM CartItem WHERE cartId = ?');
    $stmt->bindParam(1, $cartId);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$cartItems) {
        return false;
    }

    $newTotal = 0.0;

    $stmt = $db->prepare('SELECT price FROM Item WHERE itemId = ?');
    foreach ($cartItems as $cartItem) {
        $stmt->bindParam(1, $cartItem['itemId']);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            $newTotal += $item['price'];
        }
    }

    $stmt = $db->prepare('UPDATE ShoppingCart SET total = ?, promo = ? WHERE cartId = ? AND uid = ?');
    $falseValue = false;
    $stmt->bindParam(1, $newTotal);
    $stmt->bindParam(2, $falseValue);
    $stmt->bindParam(3, $cartId);
    $stmt->bindParam(4, $userId);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

function applyPromoToCart($db, $userId, $percentage): bool {

    $stmt = $db->prepare('SELECT total, promo FROM ShoppingCart WHERE uid = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $tot = $stmt->fetch();

    if ($tot['promo']) {
        return false;
    }

    $promo = $tot['total'] * $percentage;

    $tot['total'] -= $promo;

    $stmt = $db->prepare('UPDATE ShoppingCart SET total = ?, promo = ? WHERE uid = ?');
    $stmt->bindParam(1, $tot['total']);
    $trueValue = true;
    $stmt->bindParam(2, $trueValue);
    $stmt->bindParam(3, $userId);
    $stmt->execute();

    return true;
}


function removeItemFromCart(PDO $db, int $user_id, int $itemId) {
    $cartId = getCartId($db, $user_id);
    if ($cartId) {
        $priceDecrease = 0.0;

        $stmt = $db->prepare('SELECT total, promo FROM ShoppingCart WHERE uid = ?');
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        $tot = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $db->prepare('SELECT price FROM Item WHERE itemId = ?');
        $stmt->bindParam(1, $itemId);
        $stmt->execute();
        $itemPrice = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($itemPrice) {
            if (!empty($tot['promo'])) {
                $priceDecrease = $itemPrice['price'] - ($itemPrice['price'] * 0.25);
            } else {
                $priceDecrease = $itemPrice['price'];
            }

            if (!empty($tot['total'])) {
                $newTotal = $tot['total'] - $priceDecrease;

                $stmt = $db->prepare('UPDATE ShoppingCart SET total = ? WHERE uid = ?');
                $stmt->bindParam(1, $newTotal);
                $stmt->bindParam(2, $user_id);
                $stmt->execute();
            }

            $stmt = $db->prepare('DELETE FROM CartItem WHERE cartId = ? AND itemId = ?');
            $stmt->bindParam(1, $cartId);
            $stmt->bindParam(2, $itemId);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        }
    }
    return false;
}


function getCartId($db, $userId) {
    $stmt = $db->prepare("SELECT cartId FROM ShoppingCart WHERE uid = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();

    return $result ? $result['cartId'] : null;
}

function checkIfAlreadyInShoppingCart(PDO $db, int $itemId): bool {

    $session = new Session();

    $stmt = $db->prepare('SELECT cartId, total FROM ShoppingCart WHERE uid = ?');
    $userId = $session->getUserId();
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $shoppingCart = $stmt->fetch();

    if (!$shoppingCart) {
        return false;
    }

    $itemsInCart = $db->prepare('SELECT 1 FROM CartItem WHERE cartId = ? AND itemId = ?');
    $itemsInCart->bindParam(1, $shoppingCart['cartId']);
    $itemsInCart->bindParam(2, $itemId);
    $itemsInCart->execute();
    $results = $itemsInCart->fetch();

    if ($results) {
        return true;
    } else {
        return false;
    }
}

function addEditShoppingCart(PDO $db, int $itemId) {
    $session = new Session();

    if (!$session->isLoggedIn()) {
        exit;
    }

    $item = getItemWithId($db, (string)$itemId);

    if (!$item) {
        exit;
    }

    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare('SELECT cartId, total FROM ShoppingCart WHERE uid = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $shoppingCart = $stmt->fetch(PDO::FETCH_ASSOC);

    $newItemPrice = $item['price'];

    if ($shoppingCart) {
        $cartId = $shoppingCart['cartId'];
        $shoppingCartTotal = $shoppingCart['total'];

        $itemsInCart = $db->prepare('SELECT itemId FROM CartItem WHERE cartId = ?');
        $itemsInCart->bindParam(1, $cartId);
        $itemsInCart->execute();
        $results = $itemsInCart->fetchAll(PDO::FETCH_ASSOC);

        $found = false;
        foreach ($results as $cartItem) {
            if ($cartItem['itemId'] === $itemId) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $stmt = $db->prepare('SELECT promo FROM ShoppingCart WHERE cartId = ? AND uid = ?');
            $stmt->bindParam(1, $cartId);
            $stmt->bindParam(2, $userId);
            $stmt->execute();
            $promo = $stmt->fetchColumn();

            if (!empty($promo) && $promo == 1) {
                $newItemPrice = $newItemPrice * 0.75;
            }

            $shoppingCartTotal += $newItemPrice;

            $stmt = $db->prepare('UPDATE ShoppingCart SET total = ? WHERE cartId = ? AND uid = ?');
            $stmt->bindParam(1, $shoppingCartTotal);
            $stmt->bindParam(2, $cartId);
            $stmt->bindParam(3, $userId);
            $stmt->execute();

            $stmt = $db->prepare('INSERT INTO CartItem (cartId, itemId) VALUES (?, ?)');
            $stmt->bindParam(1, $cartId);
            $stmt->bindParam(2, $itemId);
            $stmt->execute();
        }
    } else {
        $stmt = $db->prepare('INSERT INTO ShoppingCart (uid, total, promo) VALUES (?, ?, ?)');
        $promo = 0;
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $newItemPrice);
        $stmt->bindParam(3, $promo);
        $stmt->execute();

        $cartId = $db->lastInsertId();

        $stmt = $db->prepare('INSERT INTO CartItem (cartId, itemId) VALUES (?, ?)');
        $stmt->bindParam(1, $cartId);
        $stmt->bindParam(2, $itemId);
        $stmt->execute();
    }
}


function checkIfDiscountApplied(PDO $db, int $userId): bool {
    $stmt = $db->prepare('SELECT promo FROM ShoppingCart WHERE uid = ?');
    $stmt->bindParam(1, $userId);
    $stmt->execute();
    $promo = $stmt->fetchColumn();

    if (!empty($promo) && $promo == 1) {
        return true;
    }

    return false;
}

?>