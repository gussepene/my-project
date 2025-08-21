<?php
require_once '../config/config.php';
require_once '../src/User.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

$user = new User($pdo);

// Handle role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id_to_update = $_POST['user_id'];
    $new_role = $_POST['role'];
    // Add a check to prevent admin from changing their own role and locking themselves out
    if ($user_id_to_update != $_SESSION['user_id']) {
        $user->updateRole($user_id_to_update, $new_role);
    }
    // Redirect to the same page to see the changes
    header("Location: manage_users.php");
    exit;
}

$users = $user->findAll();

include '../views/header.php';
?>

<h2>Manage Users</h2>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Registered On</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
                <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                <td>
                    <?php if ($u['id'] != $_SESSION['user_id']): // Admin can't change their own role ?>
                        <form action="manage_users.php" method="post" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <select name="role">
                                <option value="staff" <?php if ($u['role'] === 'staff') echo 'selected'; ?>>Staff</option>
                                <option value="admin" <?php if ($u['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                            </select>
                            <button type="submit" name="update_role">Update</button>
                        </form>
                    <?php else: ?>
                        (You)
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>
