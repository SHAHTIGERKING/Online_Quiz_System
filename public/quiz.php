<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../modules/Quiz/QuizManager.php';
require_once __DIR__ . '/../modules/Quiz/QuestionManager.php';

use Modules\Quiz\QuizManager;
use Modules\Quiz\QuestionManager;

AuthMiddleware::check();

$quiz_id = $_GET['id'] ?? null;
if (!$quiz_id) {
    header("Location: dashboard.php");
    exit();
}

$quizManager = new QuizManager();
$questionManager = new QuestionManager();

$quiz = $quizManager->getQuizById($quiz_id);
if (!$quiz) {
    header("Location: dashboard.php");
    exit();
}

$questions = $questionManager->getQuestionsByQuiz($quiz_id);

include __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <div class="col-lg-9 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header gradient-custom text-white py-3 d-flex justify-content-between align-items-center rounded-top">
                <h4 class="mb-0"><?php echo htmlspecialchars($quiz['title']); ?></h4>
                <div id="timer" class="fw-bold badge bg-white text-primary p-2 fs-6">Time Left: 40:00</div>
            </div>
            <div class="card-body p-4">
                <form id="quizForm" action="submit_quiz.php" method="POST">
                    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
                    
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="question-block mb-5" id="question-<?php echo $index; ?>">
                            <h5 class="fw-bold mb-3">Q<?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['question_text']); ?></h5>
                            <div class="options-group">
                                <?php foreach ($q['options'] as $opt): ?>
                                    <div class="form-check border rounded p-3 mb-2 hover-shadow transition">
                                        <input class="form-check-input ms-0 me-3" type="radio" 
                                               name="answers[<?php echo $q['id']; ?>]" 
                                               id="option_<?php echo $opt['id']; ?>" 
                                               value="<?php echo $opt['id']; ?>" required>
                                        <label class="form-check-label w-100" for="option_<?php echo $opt['id']; ?>">
                                            <?php echo htmlspecialchars($opt['option_text']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-lg btn-success px-5 py-3 fw-bold rounded-pill shadow">Submit Quiz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple Timer Logic
    let timeLeft = 40 * 60; // 40 Minutes
    const timerDisplay = document.getElementById('timer');

    const countdown = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        timerDisplay.innerHTML = `Time Left: ${minutes}:${seconds}`;
        
        if (timeLeft <= 0) {
            clearInterval(countdown);
            document.getElementById('quizForm').submit();
        }
        timeLeft--;
    }, 1000);
</script>

<style>
    .transition { transition: all 0.2s ease-in-out; }
    .form-check:hover { background-color: #f1f4ff; border-color: #4e73df !important; cursor: pointer; }
    .form-check-input:checked + .form-check-label { font-weight: bold; }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
