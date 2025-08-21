<?php
require_once '../config/config.php';
require_once '../src/Menu.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

$menu = new Menu($pdo);
$message = '';
$menu_data = null;

if (isset($_GET['id'])) {
    $menu_data = $menu->findById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    if ($menu->update($id, $name, $description)) {
        header("Location: menus.php");
        exit;
    } else {
        $message = "Failed to update menu.";
    }
}

include '../views/header.php';
?>

<h2>Edit Menu</h2>
<p><?php echo $message; ?></p>
<?php if ($menu_data): ?>
<form action="edit_menu.php" method="post">
    <input type="hidden" name="id" value="<?php echo $menu_data['id']; ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($menu_data['name']); ?>" required>
    <br>
    <label for="description">Description:</label>
    <textarea id="description" name="description"><?php echo htmlspecialchars($menu_data['description']); ?></textarea>
    <br>
    <button type="submit">Update Menu</button>
</form>
<?php else: ?>
<p>Menu not found.</p>
<?php endif; ?>

<?php include '../views/footer.php'; ?>
