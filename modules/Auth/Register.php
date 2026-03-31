<?php
namespace Modules\Auth;

use Database;
use PDO;

class Register {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function registerUser($name, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $success = $stmt->execute([$name, $email, $hashed_password]);
            
            if ($success) {
                $user_id = $this->db->lastInsertId();
                $this->logAction($user_id, 'Registration', "New user registered: $email");
                return true;
            }
        } catch (\PDOException $e) {
            return false;
        }
        return false;
    }

    private function logAction($user_id, $action, $details) {
        $stmt = $this->db->prepare("INSERT INTO logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $action, $details, $_SERVER['REMOTE_ADDR']]);
    }
}
?>
