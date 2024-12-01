<?php
declare(strict_types=1);

require_once ("../database/item_db.php");
require_once ("../database/connection_db.php");
require_once ("../database/wishlist_shoppingCart_db.php");
require_once ("../utils/session.php");
require_once ("../database/buying_db.php");

function drawWishlistItems(array $wishlistItems) {
    $session = new Session();
    ?>
    <?php foreach ($wishlistItems as $item) {
    if (isItemBought(getDatabaseConnection(), $item['itemId'])) {
        removeWishlistItems(getDatabaseConnection(), $session->getUserId(), $item['itemId']);
        continue;
    }
    ?>
        <div>
            <span>
            <?php $images = getAllItemImages(getDatabaseConnection(), (string)$item['itemId']);
            if (empty($images)) { ?>
                <img src="https://picsum.photos/600/300?random" alt="Placeholder">
            <?php } else { ?>
                <img src="/assets/items/originals/<?= $images[0]['itemImageId'] ?>.jpg" alt="Item Image">
            <?php } ?>
                <a class="responsiveImage" href="item.php/?id=<?= $item['itemId'] ?>"> <?= $item['brand'] ?></a>
            </span>
            <button class="<?= $item['wishlistID'] ?>" id="<?= $item['itemId'] ?>" nonce="<?= $_SESSION['priv_csrf'] ?>">
                Remove Item
            </button>
        </div>
    <?php } ?>
<?php } ?>

<?php function fetchWishlistItems(int $userID) {
    $db = getDatabaseConnection();
    return getWishlistItems($db, $userID);
} ?>

<?php function drawWishlistPage(int $userID) {
    $wishlistItems = fetchWishlistItems($userID);
    ?>
    <main class="wishlistPage">
        <?php if (!$wishlistItems) { ?>
            <article class="wishlist_info">
                <p>No items in wishlist.</p>
            </article>
        <?php } else { ?>
            <article class="wishlist_items">
                <?php drawWishlistItems($wishlistItems); ?>
            </article>
        <?php } ?>
    </main>
<?php } ?>
