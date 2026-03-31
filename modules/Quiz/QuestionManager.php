<?php
namespace Modules\Quiz;

require_once __DIR__ . '/../../includes/db.php';

use Database;
use PDO;

class QuestionManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getQuestionsByQuiz($quiz_id) {
        $stmt = $this->db->prepare("SELECT * FROM questions WHERE quiz_id = ?");
        $stmt->execute([$quiz_id]);
        $questions = $stmt->fetchAll();

        foreach ($questions as &$question) {
            $question['options'] = $this->getOptionsByQuestion($question['id']);
        }
        return $questions;
    }

    public function getOptionsByQuestion($question_id) {
        $stmt = $this->db->prepare("SELECT * FROM options WHERE question_id = ?");
        $stmt->execute([$question_id]);
        return $stmt->fetchAll();
    }

    public function addQuestion($quiz_id, $question_text, $marks = 1) {
        $stmt = $this->db->prepare("INSERT INTO questions (quiz_id, question_text, marks) VALUES (?, ?, ?)");
        $stmt->execute([$quiz_id, $question_text, $marks]);
        return $this->db->lastInsertId();
    }

    public function addOption($question_id, $option_text, $is_correct = 0) {
        $stmt = $this->db->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
        return $stmt->execute([$question_id, $option_text, $is_correct]);
    }

    public function deleteQuestion($id) {
        $stmt = $this->db->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
