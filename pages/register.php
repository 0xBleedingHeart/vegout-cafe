<?php
require_once '../config/database.php';
require_once '../config/session.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    
    $db = getDB();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $db->prepare("INSERT INTO users (role, username, email, password_hash, full_name) VALUES ('customer', ?, ?, ?, ?)");
        $stmt->bind_param('ssss', $username, $email, $hash, $full_name);
        
        if ($stmt->execute()) {
            $success = 'Registration successful! You can now login.';
        }
    } catch (Exception $e) {
        $error = 'Username or email already exists';
    }
}

$pageTitle = 'Register - VegOut Café';
include '../includes/header.php';
?>

<div style="background: linear-gradient(rgba(47, 133, 90, 0.85), rgba(47, 133, 90, 0.85)), url('https://images.unsplash.com/photo-1511690656952-34342bb7c2f2?w=1200') center/cover no-repeat; min-height: 100vh; margin-top: -3.6rem; padding-top: 3.6rem; display: flex; align-items: center; justify-content: center;">
    <div class="form-container" style="background: white; max-width: 450px; width: 90%; margin: 2rem;">
        <h2>Register</h2>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p style="margin-top:1rem;">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
