<?php

declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/user_db.php');
require_once ('../database/item_db.php');
require_once ('../utils/currency.php');
require_once ('../utils/shipping_calculator.php');

function draw_items_sold(PDO $db, array $itemsSold) {
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
    ?>
    <section id="items-sold">
        <h2>Your Sold Items</h2>
        <?php if (count($itemsSold) > 0): ?>
            <table class="items-sold-table">
                <thead>
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Buyer</th>
                    <th>Address</th>
                    <th>Postal Code</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemsSold as $item): ?>
                    <?php
                    $stmt = $db->prepare("SELECT address, postalCode FROM Address WHERE userId = :userId");
                    $stmt->execute(['userId' => $item['buyerId']]);
                    $buyerInfo = $stmt->fetch();
                    $shippingCost = calculateShippingCost($db, $item['buyerId'], $item['seller']);
                    $buyerAddress = is_array($buyerInfo) ? $buyerInfo['address'] : '';
                    $buyerPostalCode = is_array($buyerInfo) ? $buyerInfo['postalCode'] : '';
                    $images = getAllItemImages($db, (string)$item['itemId']);
                    ?>
                    <tr>
                        <td><?= htmlspecialchars((string)($item['brand'])) ?></td>
                        <td><?= htmlspecialchars((string)($item['model'])) ?></td>
                        <td><?= htmlspecialchars((number_format($item['price'] * $conversionAmount, 2)) . " " . $currencyName) ?></td>
                        <td><?= htmlspecialchars((string)($item['buyerName'])) ?></td>
                        <td><?= htmlspecialchars((string)($item['address'])) ?></td>
                        <td><?= htmlspecialchars((string)($item['postalCode'])) ?></td>
                        <td>
                            <button class="downloadPdf">Download Address Info</button>
                        </td>
                        <td class="shipping-cost" style="visibility: hidden;"><?= ((string)number_format($shippingCost * $conversionAmount, 2))  . " " . $currencyName; ?></td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div id="no-items-message" style="text-align: center; padding: 20px;">You haven't sold any items yet</div>
        <?php endif; ?>
    </section>
    <?php } ?>
