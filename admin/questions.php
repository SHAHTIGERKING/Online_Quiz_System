<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../modules/Quiz/QuestionManager.php';

use Modules\Quiz\QuestionManager;

AuthMiddleware::adminOnly();

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) { header("Location: quizzes.php"); exit(); }

$questionManager = new QuestionManager();

// Handle Add Question
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $q_text = $_POST['question_text'];
    $marks = $_POST['marks'];
    $options = $_POST['options']; // Array
    $correct_index = $_POST['correct_option'];

    $question_id = $questionManager->addQuestion($quiz_id, $q_text, $marks);
    foreach ($options as $index => $opt_text) {
        $is_correct = ($index == $correct_index) ? 1 : 0;
        $questionManager->addOption($question_id, $opt_text, $is_correct);
    }
    header("Location: questions.php?quiz_id=$quiz_id");
    exit();
}

$questions = $questionManager->getQuestionsByQuiz($quiz_id);

include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Manage Questions</h4>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">Add Question</button>
        <a href="quizzes.php" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card p-4">
    <?php foreach ($questions as $index => $q): ?>
        <div class="border-bottom pb-3 mb-3">
            <div class="d-flex justify-content-between">
                <h6 class="fw-bold">Q<?php echo $index+1; ?>: <?php echo htmlspecialchars($q['question_text']); ?> (Marks: <?php echo $q['marks']; ?>)</h6>
                <a href="?quiz_id=<?php echo $quiz_id; ?>&delete=<?php echo $q['id']; ?>" 
                   class="text-danger small" onclick="return confirm('Delete this question?')">Remove</a>
            </div>
            <ul class="list-group list-group-flush mt-2">
                <?php foreach ($q['options'] as $o): ?>
                    <li class="list-group-item py-1 border-0 <?php echo $o['is_correct'] ? 'text-success fw-bold' : ''; ?>">
                        <?php echo $o['is_correct'] ? '✓' : '○'; ?> <?php echo htmlspecialchars($o['option_text']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New MCQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Question Text</label>
                    <textarea name="question_text" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Marks</label>
                    <input type="number" name="marks" class="form-control" value="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Options (Check the correct one)</label>
                    <?php for($i=0; $i<4; $i++): ?>
                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="radio" name="correct_option" value="<?php echo $i; ?>" <?php echo $i==0 ? 'checked' : ''; ?>>
                            </div>
                            <input type="text" name="options[]" class="form-control" placeholder="Option <?php echo $i+1; ?>" required>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_question" class="btn btn-primary">Save Question</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
