<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/seed_data.php';

$db = Database::getInstance()->getConnection();

try {
    // Disable foreign key checks to truncate
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");
    $db->exec("TRUNCATE TABLE options");
    $db->exec("TRUNCATE TABLE questions");
    $db->exec("TRUNCATE TABLE quizzes");
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "Existing quiz data cleared.\n";

    // 1. Seed Users (using INSERT IGNORE to avoid duplicates if table not truncated)
    $admin_password = password_hash('shahtigerking29', PASSWORD_DEFAULT);
    $user_password = password_hash('shahzaib123', PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT IGNORE INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Admin User', 'shahtigerking@gmail.com', $admin_password, 'admin']);
    $stmt->execute(['Default User', 'shahzaib@gmail.com', $user_password, 'user']);
    echo "Users seeded successfully.\n";

    // 2. Seed Quizzes and Questions from seed_data.php
    foreach ($quizzes_data as $quiz) {
        $stmt = $db->prepare("INSERT INTO quizzes (title, description) VALUES (?, ?)");
        $stmt->execute([$quiz['title'], $quiz['description']]);
        $quiz_id = $db->lastInsertId();

        echo "Seeding quiz: {$quiz['title']}...\n";

        foreach ($quiz['questions'] as $q_data) {
            $stmt_q = $db->prepare("INSERT INTO questions (quiz_id, question_text, marks) VALUES (?, ?, ?)");
            $stmt_q->execute([$quiz_id, $q_data['q'], 1]);
            $question_id = $db->lastInsertId();

            foreach ($q_data['options'] as $index => $option_text) {
                $is_correct = ($index === $q_data['correct']) ? 1 : 0;
                $stmt_o = $db->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                $stmt_o->execute([$question_id, $option_text, $is_correct]);
            }
        }
    }
    echo "All quizzes with real MCQs seeded successfully.\n";

} catch (PDOException $e) {
    die("Error seeding database: " . $e->getMessage());
}
?>
