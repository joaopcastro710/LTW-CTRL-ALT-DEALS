<?php

declare(strict_types=1);

require_once '../utils/currency.php';

function draw_seller_items(array $categories, array $sizes, array $conditions, $itemsBeingSold) {
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
?>
    <section id="seller-items">
        <h2>Your Selling Items</h2>
        <div class="filter-bar">
            <label for="category-filter">Filter by Category:</label>
            <select id="category-filter">
                <option value="all_categories">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['categoryId'] ?>"><?= htmlspecialchars($category['categoryName']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="condition-filter">Filter by Condition:</label>
            <select id="condition-filter">
                <option value="all_conditions">All Conditions</option>
                <?php foreach ($conditions as $condition): ?>
                    <option value="<?= $condition['conditionId'] ?>"><?= htmlspecialchars($condition['conditionName']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="size-filter">Filter by Size:</label>
            <select id="size-filter">
                <option value="all_sizes">All Sizes</option>
                <?php foreach ($sizes as $size): ?>
                    <option value="<?= $size['sizeId'] ?>"><?= htmlspecialchars($size['sizeName'] !== "" ? $size['sizeName'] : "No Size") ?></option>
                <?php endforeach; ?>
            </select>

            <label for="sort-by">Sort by:</label>
            <select id="sort-by">
                <option value="model_asc">Model (A-Z)</option>
                <option value="model_desc">Model (Z-A)</option>
                <option value="brand_asc">Brand (A-Z)</option>
                <option value="brand_desc">Brand (Z-A)</option>
                <option value="price_asc">Price (Low to High)</option>
                <option value="price_desc">Price (High to Low)</option>
            </select>
        </div>

        <div class="selling-item-list">
            <div id="no-items-message" style="text-align: center; padding: 20px;">There are no items being sold yet
            </div>
            <table>
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Category</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Condition</th>
                    <th>Edit Item</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itemsBeingSold as $item):
                    if (!$item["buyer"]):
                        $itemSize = !empty($item['sizeName']) ? $item['sizeName'] : "-";
                        $thumbnailUrl = isset($item['mainImageId']) ? "../assets/items/thumbs_small/{$item['mainImageId']}.jpg" : "https://picsum.photos/600/300?random";
                        $itemUrl = "item.php/?id=" . urlencode((string)$item['iId']);
                        ?>
                        <tr>
                            <td><a href="<?= $itemUrl ?>"><img src="<?= $thumbnailUrl ?>" alt="Item Image"></a></td>
                            <td><a href="<?= $itemUrl ?>"><?= htmlspecialchars($item['brand']) ?></a></td>
                            <td><a href="<?= $itemUrl ?>"><?= htmlspecialchars($item['model']) ?></a></td>
                            <td><a href="<?= $itemUrl ?>"><?= htmlspecialchars($item['categoryName']) ?></a></td>
                            <td><a href="<?= $itemUrl ?>"><?= htmlspecialchars($itemSize) ?></a></td>
                            <td>
                                <a href="<?= $itemUrl ?>"><?= htmlspecialchars(number_format(($item['price'] * $conversionAmount), 2) . ' ' . '$currencyName', ENT_QUOTES, 'UTF-8') ?></a>
                            </td>
                            <td><a href="<?= $itemUrl ?>"><?= htmlspecialchars($item['conditionName']) ?></a></td>
                            <td>
                                <form action="/pages/edit_item.php/?id=<?= urlencode((string)$item['iId']) ?>"
                                      method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="token"
                                           nonce="<?= urlencode((string)$_SESSION['priv_csrf']) ?>">
                                    <button type="submit" class="edit-button"><i class="fa fa-pencil"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    endif;
                endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php
}
