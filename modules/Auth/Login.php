<?php
namespace Modules\Auth;

require_once __DIR__ . '/../../includes/db.php';

use Database;
use PDO;

class Login {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            
            $this->logAction($user['id'], 'Login', "User logged in successfully from {$_SERVER['REMOTE_ADDR']}");
            return $user['role'];
        }
        
        $this->logAction(null, 'Login Failure', "Failed login attempt for email: $email");
        return false;
    }

    private function logAction($user_id, $action, $details) {
        $stmt = $this->db->prepare("INSERT INTO logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $action, $details, $_SERVER['REMOTE_ADDR']]);
    }

    public static function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "/public/index.php");
        exit();
    }
}
