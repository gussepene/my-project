<?php
require_once '../config/config.php';
require_once '../src/User.php';

$user = new User($pdo);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if ($user->register($username, $password, $email)) {
        $message = "Registration successful! You can now <a href='login.php'>login</a>.";
    } else {
        $message = "Registration failed. Please try again.";
    }
}

include '../views/header.php';
?>

<h2>Register</h2>
<p><?php echo $message; ?></p>
<form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Register</button>
</form>

<?php include '../views/footer.php'; ?>
