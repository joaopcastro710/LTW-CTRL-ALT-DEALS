<?php

if (session_status() !== PHP_SESSION_ACTIVE)
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => 'localhost',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

function generate_random_token() {
    return bin2hex(openssl_random_pseudo_bytes(32));
}

class Session{

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
            if (!isset($_SESSION['priv_csrf'])) {
                $_SESSION['priv_csrf'] = generate_random_token();
            }
        }
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_destroy();
    }

    public function setUserId(int $uid) {
        $_SESSION['user_id'] = $uid;
    }

    public function getUserId(): ?int {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    public function setPromotionPopUpShown(bool $shown): void {
        $_SESSION['promo'] = $shown;
    }

    public function getPromotionPopUpShown(): bool {
        return $_SESSION['promo'] ?? false;
    }
}