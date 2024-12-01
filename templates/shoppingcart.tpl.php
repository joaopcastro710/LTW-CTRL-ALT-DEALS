<?php
declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/user_db.php');
require_once ('../database/item_db.php');
require_once ('../database/shoppingCart_db.php');
require_once ('../utils/session.php');
require_once ("../templates/common.tpl.php");
require_once ("../templates/mainPage.tpl.php");
require_once ('../utils/currency.php');
require_once ("../database/connection_db.php");
require_once ("../database/item_db.php");

function fetchShoppingCartItems(int $userID) {
    $db = getDatabaseConnection();
    return getShoppingCartItems($db, $userID);
}

function drawShoppingCartPage(int $userId) {
    $session = new Session();
    $db = getDatabaseConnection();
    $cartItems = getShoppingCartItems($db, $userId);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['remove_item_id'])) {
            removeItemFromCart($db, $userId, (int)$_POST['remove_item_id']);
            header('Location: shoppingcart.php'); // Refresh the page to update the cart display
            exit();
        }
        header('Location: checkout.php');
        exit();
    }
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
    ?>

    <main class="shoppingcartPage">
        <h1>Your Shopping Cart</h1>
        <section id="cart-items">
            <?php if (!($cartItems)) { ?>
                <p>Your cart is empty.</p>
            <?php } else { ?>
                <?php foreach ($cartItems as $item) {
                    $images = getAllItemImages($db, (string)$item['itemId']);
                    $firstImage = $images[0];
                    ?>
                    <article class="cart-item">
                        <div class="item-details">
                        <span class="itemImages">
                            <?php if (empty($images)): ?>
                                <img src="https://picsum.photos/600/300?random" alt="Placeholder">
                            <?php else: ?>
                                <img src="/assets/items/originals/<?= $firstImage['itemImageId'] ?>.jpg"
                                     alt="Item Image">
                            <?php endif; ?>
                        </span>
                            <div>
                                <h3 class="itemModel"><a
                                            href="item.php/?id=<?= $item['itemId'] ?>"><?= $item['model'] ?></a></h3>
                                <a href="/pages/itemPage.php?itemId=<?php echo htmlspecialchars((string)$item['itemId']); ?>"></a>
                                <h3 class="itemBrand"><a
                                            href="item.php/?id=<?= $item['itemId'] ?>"><?= $item['brand'] ?></a></h3>
                                Price: <?= htmlspecialchars((string)(number_format($item['price'] * $conversionAmount, 2))) . " " . $currencyName ?>

                            </div>
                        </div>
                        <button class="<?= $item['cartId'] ?>" id="<?= $item['itemId'] ?>"
                                nonce="<?= $_SESSION['priv_csrf'] ?>">Remove Item
                        </button>
                    </article>
                <?php } ?>
                <form action="/pages/shoppingcart.php" method="post">
                    <button type="submit" name="checkout">Proceed to Checkout</button>
                </form>
            <?php } ?>
        </section>
    </main>

<?php } ?>

