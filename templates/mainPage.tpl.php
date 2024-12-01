<?php

declare(strict_types=1);

require_once '../utils/currency.php';

function drawItemForMainPage(array $dbItemData) {
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
    ?>
    <?php foreach ($dbItemData as $item) { ?>
    <article class="mainPageItem">
        <?php
        if ($item['mainImageId']) {
            $imagePath = "../assets/items/thumbs_small/" . $item['mainImageId'] . ".jpg";
            if (file_exists($imagePath)) {
                echo '<a class="responsiveImage" href="item.php/?id=' . $item['itemId'] . '"><img src="' . $imagePath . '" alt="Main Image"></a>';
            } else {
                echo '<a class="responsiveImage" href="item.php/?id=' . $item['itemId'] . '"><img src="https://picsum.photos/600/300?random" alt="Random Placeholder"></a>';
            }
        } else {
            echo '<a class="responsiveImage" href="item.php/?id=' . $item['itemId'] . '"><img src="https://picsum.photos/600/300?random" alt="Random Placeholder"></a>';
        }
        ?>

        <header>
            <h4 class="itemBrand"><a href="item.php/?id=<?= $item['itemId'] ?>"><?= $item['brand'] ?></a></h4>
            <h3 class="itemModel"><a href="item.php/?id=<?= $item['itemId'] ?>"><?= $item['model'] ?></a></h3>
            <h4 class="itemPriceValue"><a href="item.php/?id=<?= $item['itemId'] ?>"><?= (number_format($item['price'] * $conversionAmount, 2) . " " . $currencyName) ?></a></h4>
            <?php if ( isset($item['sizeName']) && $item['sizeName'] !== "") { ?>
                <h4 class="itemSize"><a href="item.php/?id=<?= $item['itemId'] ?>">Size: <?= $item['sizeName'] ?></a></h4>
            <?php } ?>
            <?php if ( isset($item['conditionName']) && $item['conditionName'] !== "") { ?>
                <h4 class="itemCondition"><a href="item.php/?id=<?= $item['itemId'] ?>">Condition: <?= $item['conditionName'] ?></a></h4>
            <?php } ?>
        </header>
    </article>
<?php } ?>
<?php }

function drawAsideForMainPage(array $categories, array $sizes, array $conditions)
{ ?>
    <aside class="filter-bar-main">
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
    </aside>
<?php }

function drawMainPage(array $dbItemData, array $categories, array $sizes, array $conditions)
{ ?>
    <?php $session = new Session() ?>
    <?php if (!$session->getPromotionPopUpShown()) { ?>
    <div class="popup" id="popup">
        <div class="popup-content">
            <span class="close" id="close" nonce="<?= $_SESSION['priv_csrf'] ?>">Close &times;</span>
            <p class="popupInfo">🎉 Special Offer! Enjoy exclusive discounts on all even days! 🎉</p>
            <p class="popupInfo">Use code EDAY-2212 for a special 25% off site-wide.</p>
        </div>
    </div>
<?php } ?>
    <main>
        <div class="content">
            <?php drawAsideForMainPage($categories, $sizes, $conditions); ?>
            <div id="item-container">
                <?php drawItemForMainPage($dbItemData); ?>
            </div>
        </div>
    </main>
<?php } ?>

