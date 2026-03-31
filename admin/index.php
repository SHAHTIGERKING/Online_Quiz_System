<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../includes/db.php';

AuthMiddleware::adminOnly();

$db = Database::getInstance()->getConnection();

// Fetch Stats
$total_users = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_quizzes = $db->query("SELECT COUNT(*) FROM quizzes")->fetchColumn();
$total_questions = $db->query("SELECT COUNT(*) FROM questions")->fetchColumn();
$total_results = $db->query("SELECT COUNT(*) FROM results")->fetchColumn();

// Fetch Recent Logs
$stmt = $db->query("SELECT * FROM logs ORDER BY timestamp DESC LIMIT 10");
$recent_logs = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white text-center p-3">
            <h3><?php echo $total_users; ?></h3>
            <p class="mb-0">Users</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white text-center p-3">
            <h3><?php echo $total_quizzes; ?></h3>
            <p class="mb-0">Quizzes</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white text-center p-3">
            <h3><?php echo $total_questions; ?></h3>
            <p class="mb-0">Questions</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white text-center p-3">
            <h3><?php echo $total_results; ?></h3>
            <p class="mb-0">Submissions</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <h4 class="fw-bold mb-3 border-bottom pb-2">Admin Dashboard</h4>
            <div class="row">
                <div class="col-md-4">
                    <a href="users.php" class="btn btn-outline-primary w-100 p-4 mb-3">
                        <i class="bi bi-people fs-1"></i><br>Manage Users
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="quizzes.php" class="btn btn-outline-success w-100 p-4 mb-3">
                        <i class="bi bi-book fs-1"></i><br>Manage Quizzes
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="logs.php" class="btn btn-outline-dark w-100 p-4 mb-3">
                        <i class="bi bi-file-earmark-text fs-1"></i><br>System Logs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="col-md-12 mt-4">
        <div class="card p-4">
            <h5 class="fw-bold border-bottom pb-2">Recent Activities</h5>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Action</th>
                            <th>Details</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_logs as $log): ?>
                            <tr>
                                <td class="small"><?php echo $log['timestamp']; ?></td>
                                <td><span class="badge bg-secondary"><?php echo $log['action']; ?></span></td>
                                <td class="small"><?php echo htmlspecialchars($log['details']); ?></td>
                                <td class="small text-muted"><?php echo $log['ip_address']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
