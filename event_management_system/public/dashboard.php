<?php
session_start();
require_once '../src/auth.php';

requireLogin();

include '../views/header.php';
?>

<h2>Dashboard</h2>

<?php if (isset($_GET['error']) && $_GET['error'] === 'unauthorized'): ?>
    <p style="color: red;">You are not authorized to view that page.</p>
<?php endif; ?>

<p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! (Role: <?php echo htmlspecialchars($_SESSION['role']); ?>)</p>
<p>This is your dashboard. Use the navigation bar above to manage your account and events.</p>

<?php include '../views/footer.php'; ?>
