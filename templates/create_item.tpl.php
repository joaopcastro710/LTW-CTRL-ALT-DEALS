<?php

declare(strict_types=1);

require_once ('../utils/currency.php');

function draw_create_item_form(array $categories, array $sizes, array $conditions) {
    $currencyName = getCurrency();
?>
    <section id="create-item">
        <h2>Add an item</h2>
        <form id="create-item-form" method="post" enctype="multipart/form-data">
            <label>
                Category:
                <select name="category">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['categoryId']; ?>"><?php echo htmlspecialchars($category['categoryName']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <aside id="create-item-category-error" class="error-message"></aside>

            <label>
                Brand: <input type="text" name="brand" placeholder="Brand">
            </label>
            <aside id="create-item-brand-error" class="error-message"></aside>

            <label>
                Model: <input type="text" name="model" placeholder="Model">
            </label>
            <aside id="create-item-model-error" class="error-message"></aside>

            <label>
                Size:
                <select name="size">
                    <?php foreach ($sizes as $size) : ?>
                        <option value="<?php echo $size['sizeId']; ?>"><?php echo htmlspecialchars($size['sizeName'] !== "" ? $size["sizeName"] : "No Size"); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <aside id="create-item-size-error" class="error-message"></aside>

            <label>
                Condition:
                <select name="condition">
                    <?php foreach ($conditions as $condition) : ?>
                        <option value="<?php echo $condition['conditionId']; ?>"><?php echo htmlspecialchars($condition['conditionName']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <aside id="create-item-condition-error" class="error-message"></aside>

            <label>
                Price in <?= $currencyName ?>: <input type="number" step="0.01" name="price" placeholder="Price">
            </label>
            <aside id="create-item-price-error" class="error-message"></aside>

            <label>
                Description: <input type="text" name="description" placeholder="Description">
            </label>
            <aside id="create-item-description-error" class="error-message"></aside>
            <label class="file-input-label">
                Item Image: <input type="file" name="image[]" multiple>
            </label>
            <aside id="create-item-image-error" class="error-message"></aside>
            <button id="create-item-button" type="submit" nonce="<?= $_SESSION['priv_csrf'] ?>">Create Item</button>
        </form>
    </section>
    <?php
}
function draw_edit_item_form($item, array $categories, array $sizes, array $conditions) {
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
?>
    <section id="edit-item">
        <h2>Edit Item</h2>
        <form id="edit-item-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="itemId" value="<?= $item['itemId'] ?>">

            <label>
                Category:
                <select name="category">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['categoryId'] ?>" <?= ($item['categoryId'] == $category['categoryId']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['categoryName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <aside id="edit-item-category-error" class="error-message"></aside>

            <label>
                Brand: <input type="text" name="brand" placeholder="Brand"
                              value="<?= htmlspecialchars((string)$item['brand']) ?>">
            </label>
            <aside id="edit-item-brand-error" class="error-message"></aside>

            <label>
                Model: <input type="text" name="model" placeholder="Model"
                              value="<?= htmlspecialchars((string)$item['model']) ?>">
            </label>
            <aside id="edit-item-model-error" class="error-message"></aside>

            <label>
                Size:
                <select name="size">
                    <?php foreach ($sizes as $size) : ?>
                        <option value="<?= $size['sizeId'] ?>" <?= ($item['size'] == $size['sizeId']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($size['sizeName'] !== "" ? $size["sizeName"] : "No Size") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <aside id="edit-item-size-error" class="error-message"></aside>

            <label>
                Condition:
                <select name="condition">
                    <?php foreach ($conditions as $condition) : ?>
                        <option value="<?= $condition['conditionId'] ?>" <?= ($item['condition'] == $condition['conditionId']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($condition['conditionName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <aside id="edit-item-condition-error" class="error-message"></aside>

            <label>
                Price in <?= $currencyName ?>: <input type="number" step="0.01" name="price" placeholder="Price" value="<?= number_format((float)($item['price'] * $conversionAmount), 2) ?>">
            </label>
            <aside id="edit-item-price-error" class="error-message"></aside>

            <label>
                Description: <input type="text" name="description" placeholder="Description" value="<?= $item['description'] ?>">
            </label>
            <aside id="edit-item-description-error" class="error-message"></aside>

            <label class="file-input-label">
                Add new item images: <input type="file" name="image[]" multiple>
            </label>
            <aside id="edit-item-image-error" class="error-message"></aside>

            <button id="edit-item-button" type="submit" nonce="<?= $_SESSION['priv_csrf'] ?>">Update Item</button>
        </form>
        <form id="delete-item-form" method="post">
            <input type="hidden" name="itemId" value="<?= $item['itemId'] ?>">
            <button id="delete-item-button" type="submit" nonce="<?= $_SESSION['priv_csrf'] ?>">Delete Item</button>
        </form>
    </section>
    <?php
}
