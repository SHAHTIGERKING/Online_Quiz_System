<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';

AuthMiddleware::check();

$score = $_GET['score'] ?? 0;
$total = $_GET['total'] ?? 0;
$percentage = ($total > 0) ? round(($score / $total) * 100, 2) : 0;

include __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg text-center p-5">
            <h2 class="fw-bold mb-4">Quiz Completed!</h2>
            <div class="display-1 fw-bold text-primary mb-3"><?php echo $score; ?> / <?php echo $total; ?></div>
            <p class="fs-4 text-muted mb-4">You scored <strong><?php echo $percentage; ?>%</strong></p>
            
            <div class="progress mb-4" style="height: 1.5rem;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" 
                     style="width: <?php echo $percentage; ?>%;" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <?php if ($percentage >= 70): ?>
                <div class="alert alert-success">Excellent work! You are a master.</div>
            <?php elseif ($percentage >= 40): ?>
                <div class="alert alert-warning">Good start! Keep practicing to improve.</div>
            <?php else: ?>
                <div class="alert alert-danger">Better luck next time. Don't give up!</div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="dashboard.php" class="btn btn-primary px-4 me-2">Go to Dashboard</a>
                <a href="index.php" class="btn btn-outline-secondary px-4">Retake Quiz</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
