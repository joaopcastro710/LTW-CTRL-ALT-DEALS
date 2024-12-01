<?php
declare(strict_types=1);

require_once ("../database/item_db.php");
require_once ("../database/connection_db.php");
require_once ("../database/user_db.php");
require_once ("../database/shoppingCart_db.php");
require_once ("../database/buying_db.php");
require_once ('../utils/currency.php');

function drawCheckoutPage(int $userId) {
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
    $db = getDatabaseConnection();
    $shippingInfo = getAddress($db, $userId);
    $cartItems = getShoppingCartItems($db, $userId);
    $total = 0.0;
?>
    <main class="checkoutPage">
        <h1>Checkout</h1>
        <div id="shipping-info">
            <h3>Shipping Information</h3>
            <p><?php echo htmlspecialchars($shippingInfo['address']); ?></p>
            <p><?php echo htmlspecialchars($shippingInfo['postalCode']); ?></p>
        </div>
        <script src="/javascript/checkout.js"></script>
        <div id="items-checkout">
            <h3>Items to Purchase</h3>
            <?php foreach ($cartItems as $item):
                $itemTotal = $item['price'];
                $total += $itemTotal;
                ?>
                <div class="checkout-item">
                    <p><?php echo htmlspecialchars($item['brand']); ?>
                        - <?php echo htmlspecialchars($item['model']); ?></p>
                    <p>
                        Price: <?= htmlspecialchars((string)$item['price']) * $conversionAmount . " " . $currencyName ?></p>
                    <p>
                        Total: <?php echo number_format($item['price'] * $conversionAmount, 2) . " " . $currencyName; ?></p>
                </div>
            <?php endforeach; ?>
            <p>Total: <?php echo number_format((float)$total * $conversionAmount, 2) . " " . $currencyName; ?></p>
        </div>
        <button class="checkout-button" onclick="window.location.href='/pages/payment.php'">Proceed to Payment</button>
    </main>

<?php } ?>