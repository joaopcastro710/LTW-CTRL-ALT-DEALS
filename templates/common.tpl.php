<?php

declare(strict_types=1);

require_once ("../utils/session.php");

function draw_header(string $title, array $scripts){
$session = new Session();
?>
    <!DOCTYPE html>
<html lang="en-US">
<head>
    <title><?php echo $title; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/assets/icon.ico">
    <link href="/css/responsive.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">
    <link href="/css/layout.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php foreach ($scripts as $script) { ?>
        <script src="<?= $script ?>" defer></script>
    <?php } ?>

</head>
<body>
<header>
    <h1><a href="/pages/mainPage.php">Ctrl+Alt+Deals</a></h1>
    <?php if (!$session->isLoggedIn()) {
        echo '<div><a href="/pages/login.php">Login</a></div>';
    } else { ?>
        <form id="searchBar" action="/pages/search.php" method="get">
            <input type="search" name="search" placeholder="Search for an item">
            <input type="submit" value="search">
        </form>
        <div>
            <nav id="navigation">
                <ul>
                    <li class="profile-dropdown">
                        <a href="/pages/profile.php"><i class="fa fa-user"></i> Profile</a>
                        <ul class="dropdown-content">
                            <li><a href="/pages/profile.php">Profile Data</a></li>
                            <li><a href="/pages/messages.php">Messages</a></li>
                            <li><a href="/pages/create_item.php">Add Item</a></li>
                            <li><a href="/pages/seller_items.php">Selling Items</a></li>
                            <li><a href="/pages/itemsbought.php">Bought Items</a></li>
                            <li><a href="/pages/itemsold.php">Items Sold</a></li>
                            <li><a href="/actions/action_logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <a href="/pages/search.php?search="><i class="fa fa-search" aria-hidden="true"></i></a>
            <a href="/pages/wishlist.php"><i class="fa fa-heart"></i></a>
            <a href="/pages/shoppingcart.php"><i class="fa fa-shopping-cart"></i></a>
        </div>
    <?php } ?>
</header>
<?php } ?>

<?php function draw_footer(){ ?>
<footer>
    Designed by G01 of T10.
</footer>
</body>
</html>
<?php } ?>
