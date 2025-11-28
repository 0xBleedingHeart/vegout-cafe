<?php
require_once '../config/database.php';
require_once '../config/session.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$db = getDB();

// Update user status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];
    $stmt = $db->prepare("UPDATE users SET status = ? WHERE user_id = ?");
    $stmt->bind_param('si', $status, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php');
    exit;
}

$users_result = $db->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $users_result ? $users_result->fetch_all(MYSQLI_ASSOC) : [];

$pageTitle = 'Manage Users - Admin';
include '../includes/header.php';
?>

<div class="container" style="min-height: calc(100vh - 200px);">
    <h2>Manage Users</h2>
    <a href="dashboard.php" class="btn">Back to Dashboard</a>
    
    <div style="background:white;padding:2rem;border-radius:10px;margin-top:2rem;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:2px solid #ddd;">
                    <th style="padding:0.5rem;text-align:left;">ID</th>
                    <th style="padding:0.5rem;text-align:left;">Username</th>
                    <th style="padding:0.5rem;text-align:left;">Email</th>
                    <th style="padding:0.5rem;text-align:left;">Full Name</th>
                    <th style="padding:0.5rem;text-align:left;">Role</th>
                    <th style="padding:0.5rem;text-align:left;">Status</th>
                    <th style="padding:0.5rem;text-align:left;">Joined</th>
                    <th style="padding:0.5rem;text-align:left;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:0.5rem;"><?= $user['user_id'] ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($user['username']) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($user['email']) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($user['full_name']) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($user['role']) ?></td>
                    <td style="padding:0.5rem;"><?= htmlspecialchars($user['status']) ?></td>
                    <td style="padding:0.5rem;"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                    <td style="padding:0.5rem;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <select name="status">
                                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>Banned</option>
                            </select>
                            <button type="submit" class="btn">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
