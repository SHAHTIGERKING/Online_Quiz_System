<?php
namespace Modules\Quiz;

require_once __DIR__ . '/../../includes/db.php';

use Database;
use PDO;

class QuizManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllQuizzes() {
        $stmt = $this->db->query("SELECT * FROM quizzes ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getQuizById($id) {
        $stmt = $this->db->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createQuiz($title, $description) {
        $stmt = $this->db->prepare("INSERT INTO quizzes (title, description) VALUES (?, ?)");
        return $stmt->execute([$title, $description]);
    }

    public function updateQuiz($id, $title, $description) {
        $stmt = $this->db->prepare("UPDATE quizzes SET title = ?, description = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $id]);
    }

    public function deleteQuiz($id) {
        $stmt = $this->db->prepare("DELETE FROM quizzes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
