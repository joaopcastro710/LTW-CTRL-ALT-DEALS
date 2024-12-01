<?php

declare(strict_types=1);

require_once ("../database/connection_db.php");
require_once ("../database/user_db.php");
require_once ("../database/message_db.php");
require_once ("../utils/session.php");

function drawMessagePage() {
    $session = new Session();
    ?>
    <div class="popup" id="popup">
        <div class="popup-content">
            <span class="close" id="close">&times;</span>
            <p class="popupInfo"></p>
        </div>
    </div>
    <main class="messagePage">
        <aside class="messagePage_aside">
            <header class="messagePage_asideHeader">
                <button class="messages_as_seller" value="<?= $_SESSION['priv_csrf'] ?>">As Seller</button>
                <button class="messages_as_buyer" value="<?= $_SESSION['priv_csrf'] ?>">As Buyer</button>
            </header>

            <ul>

            </ul>
        </aside>
        <div class="chats">
            <span>Please select a chat.</span>
        </div>
    </main>
<?php } ?>