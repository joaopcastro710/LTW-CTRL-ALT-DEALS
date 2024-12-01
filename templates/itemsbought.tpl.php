<?php

declare(strict_types=1);

require_once('../database/connection_db.php');
require_once('../database/user_db.php');
require_once('../database/item_db.php');
require_once '../utils/currency.php';

function draw_items_bought(PDO $db, array $itemsbought) {
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
?>
    <section id="items-bought">
        <h2>Your Bought Items</h2>
        <?php if (count($itemsbought) > 0): ?>
            <table class="items-bought-table">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Shipping</th>
                    <th>Condition</th>
                    <th>Seller</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($itemsbought as $item) {
                    $itemSize = !empty($item['sizeName']) ? $item['sizeName'] : "-";
                    $delivered = $item["delivered"] == 0 ? "Delivering" : "Delivered";
                    $thumbnailUrl = isset($item['mainImageId']) ? "../assets/items/thumbs_small/{$item['mainImageId']}.jpg" : "https://picsum.photos/600/300?random";
                    ?>
                    <tr>
                        <td><a href="item.php/?id=<?php echo urlencode((string)$item['itemId']); ?>"><img src="<?php echo $thumbnailUrl; ?>" alt="Item Image"></a></td>
                        <td><a href="item.php/?id=<?php echo urlencode((string)$item['itemId']); ?>"><?php echo htmlspecialchars($item['brand']); ?></a></td>
                        <td><a href="item.php/?id=<?php echo urlencode((string)$item['itemId']); ?>"><?php echo htmlspecialchars($item['model']); ?></a></td>
                        <td><a href="item.php/?id=<?php echo urlencode((string)$item['itemId']); ?>"><?php echo htmlspecialchars($itemSize); ?></a></td>
                        <td><a href="item.php/?id=<?php echo urlencode((string)$item['itemId']); ?>"><?php echo htmlspecialchars((string)(number_format(($item['price'] * $conversionAmount),2))) . " " . $currencyName; ?></a></td>
                        <td><a href="item.php/?id=<?php echo urlencode((string)$item['itemId']); ?>"><?php echo htmlspecialchars((string)(number_format(($item['shipping'] * $conversionAmount),2))) . " " . $currencyName; ?></a></td>
                        <td><a href="item.php/?id=<?php echo urlencode((string)$item['itemId']); ?>"><?php echo htmlspecialchars($item['conditionName']); ?></a></td>
                        <td><a href="item.php/?id=<?php echo urlencode((string)$item['itemId']); ?>"><?php echo htmlspecialchars($item['sellerName']); ?></a></td>
                        <td>
                            <?php if ($delivered == "Delivering") { ?>
                                <form method="POST">
                                    <input type="hidden" name="seller"
                                           value="<?php echo urlencode((string)$item['seller']); ?>">
                                    <input type="hidden" name="sellerName"
                                           value="<?php echo htmlspecialchars($item['sellerName']); ?>">
                                    <input type="hidden" name="itemId"
                                           value="<?php echo urlencode((string)$item['itemId']); ?>">
                                    <input type="hidden" name="token"
                                           nonce="<?php echo urlencode((string)$_SESSION['priv_csrf']); ?>">
                                    <button class="accept-delivery-button" type="submit">Accept Delivery</button>
                                </form>
                            <?php } else { ?>
                                <span><?php echo htmlspecialchars($delivered); ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        <?php else: ?>
            <div id="no-items-message" style="text-align: center; padding: 20px;">You haven't purchased any items yet
            </div>
        <?php endif; ?>

        <div id="rating-popup" class="rating-popup">
            <div class="popup-content">
                <button class="close-popup-btn">&times;</button>
                <h2>Rate Seller:</h2>
                <div id="rating-stars">
                    <?php
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<button class="star" data-rating="' . $i . '">&#9733;</button>';
                        }
                    ?>
                </div>
                <input type="hidden" name="token" nonce="<?php echo urlencode((string)$_SESSION['priv_csrf']); ?>">
                <button id="submit-ratings">Submit Ratings</button>
            </div>
        </div>
    </section>
    <?php
}

?>
