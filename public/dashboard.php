<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../modules/Quiz/QuizManager.php';
require_once __DIR__ . '/../modules/Result/ResultManager.php';

use Modules\Quiz\QuizManager;
use Modules\Result\ResultManager;

AuthMiddleware::check();

$quizManager = new QuizManager();
$resultManager = new ResultManager();

$quizzes = $quizManager->getAllQuizzes();
$history = $resultManager->getUserResults($_SESSION['user_id']);

include __DIR__ . '/../includes/header.php';
?>

<div class="row">
    <!-- Quiz List -->
    <div class="col-md-8">
        <h4 class="fw-bold mb-3">Available Quizzes</h4>
        <div class="row">
            <?php foreach ($quizzes as $quiz): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($quiz['title']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($quiz['description']); ?></p>
                            <a href="quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-primary btn-sm">Start Quiz</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Score History -->
    <div class="col-md-4">
        <h4 class="fw-bold mb-3">Your Progress</h4>
        <div class="card p-3">
            <h6 class="fw-bold border-bottom pb-2">Recent Attempts</h6>
            <?php if (empty($history)): ?>
                <p class="text-muted small">No quizzes attempted yet.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($history as $res): ?>
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold small"><?php echo htmlspecialchars($res['quiz_title']); ?></span><br>
                                    <span class="text-muted" style="font-size: 0.75rem;"><?php echo date('d M Y, h:i A', strtotime($res['completed_at'])); ?></span>
                                </div>
                                <span class="badge bg-success rounded-pill"><?php echo $res['score']; ?>/<?php echo $res['total_marks']; ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
