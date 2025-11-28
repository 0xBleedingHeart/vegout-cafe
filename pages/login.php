<?php
require_once '../config/database.php';
require_once '../config/session.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $db = getDB();
    $stmt = $db->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ? AND status = 'active'");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: /vegout-cafe/index.php');
            exit;
        }
    }
    $error = 'Invalid username or password';
}

$pageTitle = 'Login - VegOut Café';
include '../includes/header.php';
?>

<div style="background: linear-gradient(rgba(47, 133, 90, 0.85), rgba(47, 133, 90, 0.85)), url('https://images.unsplash.com/photo-1511690656952-34342bb7c2f2?w=1200') center/cover no-repeat; min-height: 100vh; margin-top: -3.6rem; padding-top: 3.6rem; display: flex; align-items: center; justify-content: center;">
    <div class="form-container" style="background: white; max-width: 450px; width: 90%; margin: 2rem;">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p style="margin-top:1rem;">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
