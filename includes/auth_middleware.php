<?php
require_once __DIR__ . '/../config/config.php';

class AuthMiddleware {
    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/public/index.php");
            exit();
        }
    }

    public static function adminOnly() {
        self::check();
        if ($_SESSION['user_role'] !== 'admin') {
            header("Location: " . BASE_URL . "/public/dashboard.php");
            exit();
        }
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function redirectIfLoggedIn() {
        if (self::isLoggedIn()) {
            if ($_SESSION['user_role'] === 'admin') {
                header("Location: " . BASE_URL . "/admin/index.php");
            } else {
                header("Location: " . BASE_URL . "/public/dashboard.php");
            }
            exit();
        }
    }
}
?>
