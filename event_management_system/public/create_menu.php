<?php
require_once '../config/config.php';
require_once '../src/Menu.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu = new Menu($pdo);
    $name = $_POST['name'];
    $description = $_POST['description'];

    if ($menu->create($name, $description)) {
        header("Location: menus.php");
        exit;
    } else {
        $message = "Failed to create menu.";
    }
}

include '../views/header.php';
?>

<h2>Create Menu</h2>
<p><?php echo $message; ?></p>
<form action="create_menu.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="description">Description:</label>
    <textarea id="description" name="description"></textarea>
    <br>
    <button type="submit">Create Menu</button>
</form>

<?php include '../views/footer.php'; ?>
