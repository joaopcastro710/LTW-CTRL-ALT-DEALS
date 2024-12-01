<?php
declare(strict_types=1);

require_once ('../database/connection_db.php');
require_once ('../database/user_db.php');
require_once ('../database/item_db.php');
require_once ('../database/admin_actions_db.php');

function drawAdminPage(int $userId) {
    $db = getDatabaseConnection();
    if (!isset($userId) || !isAdmin($db, $userId)) {
        header('Location: /pages/login.php');
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $message = '';
        if (isset($_POST['add_admin'])) {
            $username = $_POST['username'];
            $message = addAdmin($db, $username);
        } else if (isset($_POST['new_category'])) {
            $message = addCategory($db, $_POST['category_name']);
        } else if (isset($_POST['new_size'])) {
            $message = addItemSize($db, $_POST['size_name']);
        } else if (isset($_POST['new_condition'])) {
            $message = addItemCondition($db, $_POST['condition_name']);
        }
        if ($message) {
            echo '<div class="admin-message">' . $message . '</div>';
        }
    } elseif (isset($_GET['message'])) {
        $message = urldecode($_GET['message']);
        echo '<div class="admin-message">' . $message . '</div>';
    }
    ?>
    <main class="AdminPage">
        <h1>Admin Dashboard</h1>
        <div class="admin-functions">
            <form id="AdminForm" method="post" action="/actions/action_addAdmin.php">
                <label for="username">Username of the new Admin:</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required>
                <input type="hidden" name="token" value="<?= $_SESSION['priv_csrf'] ?>">
                <button type="submit" name="add_admin">Add Admin</button>
            </form>

            <form method="post" action="/actions/action_addCategory.php">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" placeholder="Enter new category name"
                       required>
                <input type="hidden" name="token" value="<?= $_SESSION['priv_csrf'] ?>">
                <button type="submit" name="new_category">New Item Categories</button>
            </form>

            <form method="post" action="/actions/action_addItemSize.php">
                <label for="size_name">Size Name:</label>
                <input type="text" id="size_name" name="size_name" placeholder="Enter new size name" required>
                <input type="hidden" name="token" value="<?= $_SESSION['priv_csrf'] ?>">
                <button type="submit" name="new_size">New Item Sizes</button>
            </form>

            <form method="post" action="/actions/action_addItemCondition.php">
                <label for="condition_name">Condition Name:</label>
                <input type="text" id="condition_name" name="condition_name" placeholder="Enter new condition name"
                       required>
                <input type="hidden" name="token" value="<?= $_SESSION['priv_csrf'] ?>">
                <button type="submit" name="new_condition">New Item Condition</button>
            </form>
        </div>
    </main>

<?php } ?>