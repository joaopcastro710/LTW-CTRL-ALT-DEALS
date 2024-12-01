<?php
declare(strict_types=1);

require_once ("../database/item_db.php");
require_once ("../database/connection_db.php");
require_once ("../database/user_db.php");
require_once ("../database/shoppingCart_db.php");
require_once ("../database/buying_db.php");
require_once ('../utils/currency.php');
require_once ("../utils/shipping_calculator.php");

function drawPaymentPage(int $userId) {

    $db = getDatabaseConnection();
    $cartItems = getShoppingCartItems($db, $userId);

    $total = 0.0;
    $shippingCost = 0.0;
    foreach ($cartItems as $item) {
        $total += $item['price'];
        $shippingCost += calculateShippingCost($db, $item['seller'], $userId);
    }

    if ($total == 0.0) {
        header('Location: /pages/mainPage.php');
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy'])) {
        buyItems($db, $cartItems, $userId);
        header('Location: /pages/mainPage.php');
        exit();
    }
    $session = new Session();
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
?>
    <main class="paymentPage">
        <h1>Payment</h1>
        <form id="payment-form" method="post">
            <h3>Payment Options</h3>
            <table>
                <tr>
                    <td><label for="in-person">In Person</label></td>
                    <td><input type="radio" id="in-person" name="payment" value="in-person"></td>
                </tr>
                <tr>
                    <td><label for="atm">ATM</label></td>
                    <td><input type="radio" id="atm" name="payment" value="atm"></td>
                </tr>
                <tr>
                    <td><label for="paypal">PayPal</label></td>
                    <td><input type="radio" id="paypal" name="payment" value="paypal"></td>
                </tr>
                <tr>
                    <td><label for="mbway">MBWay</label></td>
                    <td><input type="radio" id="mbway" name="payment" value="mbway"></td>
                </tr>
            </table>

            <div class="discount-code-section">
                <label for="discount-code">Promo code</label>
                <input type="text" id="discount-code" name="discount" placeholder="Discount code">
                <button id="discount-code-button" nonce="<?= $_SESSION['priv_csrf'] ?>">Apply</button>

                <?php if (checkIfDiscountApplied($db, (int)$session->getUserId())) { ?>
                    <button id="remove-discount-button" nonce="<?= $_SESSION['priv_csrf'] ?>">Remove discount</button>
                <?php } ?>

            </div>

            <?php
            $cartTotal = number_format((float)getCartTotal($db, $session->getUserId()), 2);
            $totalWithShipping = $total + $shippingCost;
            ?>
            <p>Total: <?php echo number_format($totalWithShipping * $conversionAmount, 2) . " " . $currencyName; ?></p>
            <p>Shipping: <?php echo number_format($shippingCost * $conversionAmount, 2) . " " . $currencyName; ?></p>
            <button class="buy-button" type="submit" name="buy">Buy</button>
        </form>
    </main>

<?php } ?>