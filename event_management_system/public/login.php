<?php
require_once '../config/config.php';
require_once '../src/User.php';

$user = new User($pdo);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loggedInUser = $user->login($username, $password);

    if ($loggedInUser) {
        session_start();
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['username'] = $loggedInUser['username'];
        $_SESSION['role'] = $loggedInUser['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Login failed. Please check your credentials.";
    }
}

include '../views/header.php';
?>

<h2>Login</h2>
<p><?php echo $message; ?></p>
<form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>

<?php include '../views/footer.php'; ?>
