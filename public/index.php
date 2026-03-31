<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth_middleware.php';
require_once __DIR__ . '/../modules/Auth/Login.php';

use Modules\Auth\Login;

AuthMiddleware::redirectIfLoggedIn();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $auth = new Login();
    $role = $auth->authenticate($email, $password);
    
    if ($role === 'admin') {
        header("Location: " . BASE_URL . "/admin/index.php");
        exit();
    } elseif ($role === 'user') {
        header("Location: " . BASE_URL . "/public/dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg p-4">
            <h3 class="text-center mb-4 fw-bold">Login to Your Account</h3>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="shahzaib@gmail.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
            </form>

            <div class="mt-4 p-3 bg-light border rounded">
                <p class="mb-1 fw-bold text-muted small text-uppercase">Default Credentials (Testing):</p>
                <div class="small">
                    <strong>Admin:</strong> shahtigerking@gmail.com / shahtigerking29<br>
                    <strong>User:</strong> shahzaib@gmail.com / shahzaib123
                </div>
            </div>

            <div class="text-center mt-3">
                <p>Don't have an account? <a href="register.php">Register now</a></p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
