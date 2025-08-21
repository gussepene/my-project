<?php
require_once '../config/config.php';
require_once '../src/Menu.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

$menu = new Menu($pdo);
$menus = $menu->findAll();

include '../views/header.php';
?>

<h2>Menus</h2>
<a href="create_menu.php">Create New Menu</a>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($menus as $m): ?>
            <tr>
                <td><a href="view_menu.php?id=<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['name']); ?></a></td>
                <td><?php echo htmlspecialchars($m['description']); ?></td>
                <td>
                    <a href="edit_menu.php?id=<?php echo $m['id']; ?>">Edit</a>
                    <a href="delete_menu.php?id=<?php echo $m['id']; ?>" onclick="return confirm('Are you sure you want to delete this menu? This will also delete all its items.');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>
