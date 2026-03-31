<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../includes/db.php';

AuthMiddleware::adminOnly();

$db = Database::getInstance()->getConnection();

// Handle Log Clear if requested
if (isset($_POST['clear_logs'])) {
    $db->exec("TRUNCATE TABLE logs");
    header("Location: logs.php?cleared=1");
    exit();
}

// Fetch all logs
$stmt = $db->query("SELECT * FROM logs ORDER BY timestamp DESC");
$logs = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">System Activity Logs</h3>
    <div>
        <form method="POST" onsubmit="return confirm('Are you sure you want to clear all logs?');" class="d-inline">
            <button type="submit" name="clear_logs" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i> Clear All Logs
            </button>
        </form>
        <a href="index.php" class="btn btn-secondary btn-sm ms-2">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<?php if (isset($_GET['cleared'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        All logs have been cleared successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 200px;">Timestamp</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No activity logs found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="small text-muted"><?php echo $log['timestamp']; ?></td>
                            <td>
                                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($log['action']); ?></span>
                            </td>
                            <td class="small"><?php echo htmlspecialchars($log['details']); ?></td>
                            <td class="small font-monospace"><?php echo $log['ip_address']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
