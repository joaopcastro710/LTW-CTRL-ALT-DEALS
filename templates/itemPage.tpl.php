<?php
declare(strict_types=1);

require_once ("../database/item_db.php");
require_once ("../database/connection_db.php");
require_once ("../database/user_db.php");
require_once ("../database/filters_db.php");
require_once ("../database/shoppingCart_db.php");
require_once ("../database/wishlist_shoppingCart_db.php");
require_once ("../utils/currency.php");

function drawItemPage(string $item_id) {
    $db = getDatabaseConnection();
    $item = getItemWithId($db, $item_id);
    $images = getAllItemImages($db, $item_id);
    if (!$item) {
        header('Location: /pages/mainPage.php');
        exit;
    }

    $itemId = $item['itemId'];
    $categoryId = $item['categoryId'];
    $brand = $item['brand'];
    $model = $item['model'];
    $size = $item['size'];
    $condition = $item['condition'];
    $price = $item['price'];
    $seller = $item['seller'];
    $description = $item['description'];

    $session = new Session();

    $sellerProfile = getUserProfile($db, $seller);
    $sellerRating = getSellerRating($db, $seller);
    $categoryName = getCategoryName($db, $categoryId);
    $currencyName = getCurrency();
    $conversionAmount = getCurrencyConversion($currencyName);
    $sizeName = getSize($db, $size);
    $conditionName = getCondition($db, $condition);
    $imageUrls = json_encode(array_map(function ($image) {
        return '/assets/items/originals/' . $image['itemImageId'] . '.jpg';
    }, $images));


?>
    <main class="itemPage">
        <article>
            <div class="itemImages" data-images='<?= $imageUrls ?>'>
                <button class="prev disabled">&lt;</button>
                <div class="imageContainer">
                    <?php if (empty($images)): ?>
                        <img id="carouselImage" src="https://picsum.photos/600/300?random" alt="Placeholder">
                    <?php else: ?>
                        <img id="carouselImage" src="/assets/items/originals/<?= $images[0]['itemImageId'] ?>.jpg"
                             alt="Item Image">
                    <?php endif; ?>
                </div>
                <button class="next">&gt;</button>
            </div>

            <div class="extra">
                <ul>
                    <?php if ($brand !== '') { ?>
                        <li><p>Brand: <?= $brand ?> </p></li> <?php } ?>

                    <?php if ($sizeName['sizeName'] !== '') { ?>
                        <li><p>Size: <?= $sizeName['sizeName'] ?> </p></li> <?php } ?>
                    <?php if ($conditionName['conditionName'] !== '') { ?>
                        <li><p>Condition: <?= $conditionName['conditionName'] ?> </p></li> <?php } ?>

                    <?php if ($categoryName['categoryName'] !== '') { ?>
                        <li><p>Category: <?= $categoryName['categoryName'] ?> </p></li> <?php } ?>

                </ul>

                <div class="description">
                    <?= $description ?>
                    <div class="itemIdentification">
                        <?php if ($itemId !== '') { ?><p>ItemID: <?= $itemId ?> </p><?php } ?>
                    </div>
                </div>
            </div>
        </article>
        <aside>
            <div class="itemPrice">
                <?php if ($model !== '') { ?><h1><?= $model ?> </h1> <?php } ?>
                <?php if ($price !== '') { ?>
                    <h3><?= number_format((float)($price * $conversionAmount), 2) . " " . $currencyName ?> </h3> <?php } ?>

                <?php if ($seller !== $session->getUserId()) { ?>
                    <?php if (!checkIfAlreadyInShoppingCart($db, (int)$item_id)) { ?>
                        <button class="addToCartButton" value="<?= $itemId ?>" nonce="<?= $_SESSION['priv_csrf'] ?>">Add
                            to cart
                        </button>
                    <?php } else { ?>
                        <button class="removeFromCart" value="<?= $itemId ?>" nonce="<?= $_SESSION['priv_csrf'] ?>">
                            Remove from cart
                        </button>
                    <?php } ?>

                    <?php if (!checkIfItemInWishlist($db, (int)$item_id)) { ?>
                        <button class="addToWishlistButton" value="<?= $itemId ?>"
                                nonce="<?= $_SESSION['priv_csrf'] ?>">Add to wishlist
                        </button>
                    <?php } else { ?>
                        <button class="removeFromWishlist" value="<?= $itemId ?>" nonce="<?= $_SESSION['priv_csrf'] ?>">
                            Remove from wishlist
                        </button>
                    <?php } ?>
                <?php } else { ?>
                    <button class="editItem addToCartButton" value="<?= $itemId ?>"
                            nonce="<?= $_SESSION['priv_csrf'] ?>">Edit item
                    </button>
                    <button class="removeItem addToWishlistButton" value="<?= $itemId ?>"
                            nonce="<?= $_SESSION['priv_csrf'] ?>">Remove item
                    </button>
                <?php } ?>
            </div>
            <div class="itemSeller">
                <div>
                    <?php
                    if ($sellerProfile['userImageId'] !== null) {
                        $imagePath = "/assets/users/" . $sellerProfile['userImageId'] . ".jpg";
                        if (file_exists(".." . $imagePath)) {
                            echo '<img id="userProfilePic" src="' . $imagePath . '" alt="User Profile Picture">';
                        } else {
                            echo '<img id="userProfilePic" src="/assets/users/defaultUser.jpg" alt="User Profile Picture">';
                        }
                    } else {
                        echo '<img id="userProfilePic" src="/assets/users/defaultUser.jpg" alt="User Profile Picture">';
                    }
                    ?>
                    <p><?= $sellerProfile['name'] ?></p>
                </div>
                <?php $session = new Session(); ?>
                <div class="sellerInfo">
                    <div class="sellerContactInfo">
                        <?php if ($seller !== $session->getUserId()) { ?>
                            <button class="sendMessageToSeller" value="<?= $itemId ?>"
                                    nonce="<?= $_SESSION['priv_csrf'] ?>">Send a message
                            </button>
                            <button onclick="location.href='mailto:<?= $sellerProfile['email'] ?>'">Send an email
                            </button>
                        <?php } ?>
                    </div>

                    <div class="sellerRating">
                        <?php if ($sellerRating === -1) { ?>
                            <p>This seller doesn't have a rating, yet.</p>
                        <?php } else { ?>
                            <p>Rating: <?= round($sellerRating, 2) ?> out of 5 stars.</p>
                        <?php } ?>
                    </div>

                    <div class="sellerAddress">
                        <p>Address: <?= $sellerProfile['address'] ?></p>
                        <p>Postal Code: <?= $sellerProfile['postalCode'] ?></p>
                    </div>
                </div>
            </div>
        </aside>
    </main>
<?php } ?>