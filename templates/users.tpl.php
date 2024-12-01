<?php

declare(strict_types=1);

require_once ('../utils/currency.php');

function draw_login_form()
{
    ?>
    <section id="login">
        <form id="login-form" method="post">
            <label>
                Email <input type="text" name="email">
            </label>
            <aside id="login-email-error" class="error-message"></aside>
            <label>
                Password <input type="password" name="password">
            </label>
            <aside id="login-password-error" class="error-message"></aside>
            <button id="login-button" type="submit" nonce="<?= $_SESSION['priv_csrf'] ?>">Log in</button>
            <aside id="login-error" class="error-message"></aside>
        </form>
        <a href="../pages/register.php">Don't have an account? Sign up</a>
    </section>
    <?php
}

function draw_register_form(array $continents)
{
    ?>
    <section id="register">
        <form id="register-form" method="post" enctype="multipart/form-data">
            <label>
                Username <input type="text" name="username">
            </label>
            <aside id="register-username-error" class="error-message"></aside>
            <label>
                E-mail <input type="email" name="email">
            </label>
            <aside id="register-email-error" class="error-message"></aside>
            <label>
                Password <input type="password" name="password">
            </label>
            <aside id="register-password-error" class="error-message"></aside>
            <label>
                Name <input type="text" name="name">
            </label>
            <aside id="register-name-error" class="error-message"></aside>
            <label>
                Address <input type="text" name="address">
            </label>
            <aside id="register-address-error" class="error-message"></aside>
            <label>
                Postal Code <input type="text" name="postcode">
            </label>
            <aside id="register-postcode-error" class="error-message"></aside>
            <label class="file-input-label">
                User Image: <input type="file" name="image">
            </label>
            <label for="continentInput">Continent:</label>
            <select name="continent" id="continentInput">
                <?php foreach ($continents as $continent): ?>
                    <option value="<?= $continent["continentId"] ?>"><?= htmlspecialchars((string)$continent["continentName"]) ?></option>
                <?php endforeach; ?>
            </select>
            <aside id="register-continent-error" class="error-message"></aside>
            <button id="register-button" type="submit" nonce="<?= $_SESSION['priv_csrf'] ?>">Register</button>
            <aside id="register-error" class="error-message"></aside>
        </form>
        <a href="../pages/login.php">Already have an account? Log in</a>
    </section>
    <?php
}

function draw_admin_page_button($db, $userId)
{
    require_once('../database/admin_actions_db.php');
    if (isAdmin($db, $userId)) {
        ?>
        <div class="admin-button-container">
            <form action="../pages/admin_page.php" method="get">
                <button type="submit" class="admin-page-button">Admin Page</button>
            </form>
        </div>
        <?php
    }
}

function draw_profile_page($db, $userProfile, $userId, array $continents)
{
    ?>
    <section id="profile">
        <section id="profile-data">
            <h2>User Profile</h2>
            <form id="edit-profile-form" method="post" enctype="multipart/form-data">
                <div class="profile-picture">
                    <div class="image-container">
                        <?php
                        if ($userProfile['userImageId'] !== null) {
                            $imagePath = "../assets/users/" . $userProfile['userImageId'] . ".jpg";
                            if (file_exists($imagePath)) {
                                echo '<img id="userProfilePic" src="' . $imagePath . '" alt="User Profile Picture">';
                            } else {
                                echo '<img id="userProfilePic" src="../assets/users/defaultUser.jpg" alt="User Profile Picture">';
                            }
                        } else {
                            echo '<img id="userProfilePic" src="../assets/users/defaultUser.jpg" alt="User Profile Picture">';
                        }
                        ?>
                        <button id="editProfilePictureButton" type="button">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <input id="profilePictureInput" type="file" style="display: none;" name="image">
                    </div>
                </div>

                <div class="input-wrapper">
                    <label for="usernameInput">Username:</label>
                    <input type="text" name="username" id="usernameInput"
                           value="<?php echo htmlspecialchars($userProfile['username']); ?>" readonly>
                    <button type="button" class="edit-profile-icon" id="editUsernameButton">
                        <i class="fa fa-pencil"></i>
                    </button>
                </div>
                <aside id="profile-username-error" class="error-message"></aside>

                <div class="input-wrapper">
                    <label for="emailInput">Email:</label>
                    <input type="email" name="email" id="emailInput"
                           value="<?php echo htmlspecialchars($userProfile['email']); ?>" readonly>
                    <button type="button" class="edit-profile-icon" id="editEmailButton">
                        <i class="fa fa-pencil"></i>
                    </button>
                </div>
                <aside id="profile-email-error" class="error-message"></aside>

                <div class="input-wrapper">
                    <label for="nameInput">Name:</label>
                    <input type="text" name="name" id="nameInput"
                           value="<?php echo htmlspecialchars($userProfile['name']); ?>" readonly>
                    <button type="button" class="edit-profile-icon" id="editNameButton">
                        <i class="fa fa-pencil"></i>
                    </button>
                </div>
                <aside id="profile-name-error" class="error-message"></aside>

                <div class="input-wrapper">
                    <label for="addressInput">Address:</label>
                    <input type="text" name="address" id="addressInput"
                           value="<?php echo htmlspecialchars($userProfile['address']); ?>" readonly>
                    <button type="button" class="edit-profile-icon" id="editAddressButton">
                        <i class="fa fa-pencil"></i>
                    </button>
                </div>
                <aside id="profile-address-error" class="error-message"></aside>

                <div class="input-wrapper">
                    <label for="postalCodeInput">Postal Code:</label>
                    <input type="text" name="postalCode" id="postalCodeInput"
                           value="<?php echo htmlspecialchars($userProfile['postalCode']); ?>" readonly>
                    <button type="button" class="edit-profile-icon" id="editPostalCodeButton">
                        <i class="fa fa-pencil"></i>
                    </button>
                </div>
                <aside id="profile-postalCode-error" class="error-message"></aside>

                <div class="input-wrapper">
                    <label for="continentInput">Continent:</label>
                    <select name="continent" id="continentInput" disabled>
                        <?php foreach ($continents as $continent): ?>
                            <option value="<?= htmlspecialchars((string)$continent["continentId"]) ?>" <?php echo ($userProfile['continentName'] === $continent["continentName"]) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars((string)$continent["continentName"]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="edit-profile-icon" id="editContinentButton">
                        <i class="fa fa-pencil" id="editContinentIcon"></i>
                    </button>
                </div>
                <aside id="profile-continent-error" class="error-message"></aside>

                <div class="input-wrapper">
                    <label for="currencyInput"> Currency:</label>
                    <select name="currency" id="currencyInput" disabled>
                        <?php foreach (CurrencyType::cases() as $currencyType) { ?>
                            <option value="<?php echo htmlspecialchars($currencyType->value, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $currencyType->value === getCurrency() ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($currencyType->value, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <button type="button" class="edit-profile-icon" id="editCurrencyButton">
                        <i class="fa fa-pencil" id="editCurrencyIcon"></i>
                    </button>
                </div>
                <aside id="profile-currency-error" class="error-message"></aside>

                <button id="edit-profile-button" type="submit" nonce="<?= $_SESSION['priv_csrf'] ?>">Save Changes
                </button>
                <aside id="profile-error" class="error-message"></aside>
                <button id="goToPasswordResetButton" type="button">Reset Password</button>
            </form>
            <form id="reset-password-form" method="post">
                <div class="input-wrapper">
                    <label for="currentPasswordInput">Current Password:</label>
                    <input type="password" name="currentPassword" id="currentPasswordInput">
                    <button type="button" class="visibility-toggle-icon" id="toggleCurrentPasswordVisibility">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                <aside id="profile-currentPassword-error" class="error-message"></aside>

                <div class="input-wrapper">
                    <label for="newPasswordInput">New Password:</label>
                    <input type="password" name="newPassword" id="newPasswordInput">
                    <button type="button" class="visibility-toggle-icon" id="toggleNewPasswordVisibility">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                <aside id="profile-newPassword-error" class="error-message"></aside>

                <button id="reset-password-button" type="submit" nonce="<?= $_SESSION['priv_csrf'] ?>">Reset Password
                </button>
                <aside id="reset-password-error" class="error-message"></aside>
                <button id="cancelPasswordResetButton" type="button">Cancel</button>
            </form>
            <aside id="profile-data-success-message" class="success-message"></aside>
        </section>

        <section id="admin-page-button">
            <?php draw_admin_page_button($db, $userId,); ?>
        </section>
        <section id="logout">
            <form id="logout-form" action="../actions/action_logout.php" method="post">
                <button id="logout-button" type="submit">Log out</button>
            </form>
        </section>
    </section>

    <?php
}

function draw_profile_tab()
{
    ?>
    <div id="profile-tabs">
        <ul>
            <li><a href="../pages/profile.php" data-tab="profile-data">Profile Data</a></li>
            <li><a href="../pages/messages.php" data-tab="messages">Messages</a></li>
            <li><a href="../pages/seller_items.php" data-tab="selling-items">Selling Items</a></li>
            <li><a href="../pages/create_item.php" data-tab="add-item">Add item</a></li>
            <li><a href="../pages/itemsbought.php" data-tab="items bought">Bought Items</a></li>
            <li><a href="../pages/itemsold.php" data-tab="items sold">Items Sold</a></li>
        </ul>
    </div>
    <?php
}