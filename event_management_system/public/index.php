<?php include '../views/header.php'; ?>

<h1>Welcome to the Event Management System</h1>
<p>Your one-stop solution for planning and managing events.</p>

<?php if (isset($_SESSION['user_id'])): ?>
    <p>Go to your <a href="dashboard.php">dashboard</a> to manage your events.</p>
<?php else: ?>
    <p>Please <a href="login.php">login</a> or <a href="register.php">register</a> to continue.</p>
<?php endif; ?>


<?php include '../views/footer.php'; ?>
