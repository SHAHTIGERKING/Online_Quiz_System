<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../modules/Result/ResultManager.php';
require_once __DIR__ . '/../includes/db.php';

use Modules\Result\ResultManager;

AuthMiddleware::check();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id = $_POST['quiz_id'];
    $user_answers = $_POST['answers'] ?? []; // Array of question_id => option_id
    
    $db = Database::getInstance()->getConnection();
    
    // Calculate Score
    $score = 0;
    $total_questions = 0;

    // Fetch correct options for this quiz
    $stmt = $db->prepare("
        SELECT q.id as question_id, o.id as correct_option_id, q.marks
        FROM questions q
        JOIN options o ON q.id = o.question_id
        WHERE q.quiz_id = ? AND o.is_correct = 1
    ");
    $stmt->execute([$quiz_id]);
    $correct_data = $stmt->fetchAll();

    $total_marks = 0;
    foreach ($correct_data as $row) {
        $total_marks += $row['marks'];
        if (isset($user_answers[$row['question_id']]) && $user_answers[$row['question_id']] == $row['correct_option_id']) {
            $score += $row['marks'];
        }
    }

    $resultManager = new ResultManager();
    $resultManager->saveResult($_SESSION['user_id'], $quiz_id, $score, $total_marks);
    
    // Log the submission
    $log_stmt = $db->prepare("INSERT INTO logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
    $log_stmt->execute([$_SESSION['user_id'], 'Quiz Submission', "User submitted quiz ID: $quiz_id. Scored: $score/$total_marks", $_SERVER['REMOTE_ADDR']]);

    header("Location: result.php?quiz_id=$quiz_id&score=$score&total=$total_marks");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
