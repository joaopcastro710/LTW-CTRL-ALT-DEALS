<?php
declare(strict_types=1);

require_once('../database/connection_db.php');
require_once('../database/user_db.php');
require_once('../database/item_db.php');
require_once('../utils/session.php');
require_once ('../database/shoppingCart_db.php');
require_once "../database/search_db.php";
require_once "../templates/itemPage.tpl.php";
require_once "../templates/common.tpl.php";
require_once "../templates/mainPage.tpl.php";
require_once '../utils/currency.php';

function drawSearchPage($db,array $matchingItems){
    ?>

    <main class="searchPage">
        <h1>Search Results</h1>
        <section id="search-results">
            <?php if (!$matchingItems) { ?>
                <p>No items found.</p>
            <?php } else { ?>
                <div id="item-container">
                    <?php drawItemForMainPage($matchingItems); ?>
                </div>
            <?php } ?>
        </section>
    </main>
<?php } ?>
