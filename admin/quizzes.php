<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../modules/Quiz/QuizManager.php';

use Modules\Quiz\QuizManager;

AuthMiddleware::adminOnly();

$quizManager = new QuizManager();

// Handle Operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_quiz'])) {
        $quizManager->createQuiz($_POST['title'], $_POST['description']);
    } elseif (isset($_POST['edit_quiz'])) {
        $quizManager->updateQuiz($_POST['id'], $_POST['title'], $_POST['description']);
    }
    header("Location: quizzes.php");
    exit();
}

if (isset($_GET['delete'])) {
    $quizManager->deleteQuiz($_GET['delete']);
    header("Location: quizzes.php");
    exit();
}

$quizzes = $quizManager->getAllQuizzes();

include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Manage Quizzes</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuizModal">Add New Quiz</button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Questions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizzes as $q): ?>
                    <tr>
                        <td><?php echo $q['id']; ?></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($q['title']); ?></td>
                        <td class="small"><?php echo htmlspecialchars($q['description']); ?></td>
                        <td>
                            <a href="questions.php?quiz_id=<?php echo $q['id']; ?>" class="btn btn-outline-info btn-sm">Manage Questions</a>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editQuizModal<?php echo $q['id']; ?>">Edit</button>
                            <a href="?delete=<?php echo $q['id']; ?>" 
                               class="btn btn-outline-danger btn-sm" 
                               onclick="return confirm('Deleting quiz will delete all its questions. Proceed?')">Delete</a>
                        </td>
                    </tr>

                    <!-- Edit Quiz Modal -->
                    <div class="modal fade" id="editQuizModal<?php echo $q['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="" method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Quiz</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo $q['id']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Quiz Title</label>
                                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($q['title']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($q['description']); ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="edit_quiz" class="btn btn-warning">Update Quiz</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Quiz Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_quiz" class="btn btn-primary">Create Quiz</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
