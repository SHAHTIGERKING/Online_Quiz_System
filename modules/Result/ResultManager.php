<?php
namespace Modules\Result;

require_once __DIR__ . '/../../includes/db.php';

use Database;
use PDO;

class ResultManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function saveResult($user_id, $quiz_id, $score, $total_marks) {
        $stmt = $this->db->prepare("INSERT INTO results (user_id, quiz_id, score, total_marks) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $quiz_id, $score, $total_marks]);
    }

    public function getUserResults($user_id) {
        $stmt = $this->db->prepare("
            SELECT r.*, q.title as quiz_title 
            FROM results r 
            JOIN quizzes q ON r.quiz_id = q.id 
            WHERE r.user_id = ? 
            ORDER BY r.completed_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getResultById($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, q.title as quiz_title, u.name as user_name 
            FROM results r 
            JOIN quizzes q ON r.quiz_id = q.id 
            JOIN users u ON r.user_id = u.id 
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
