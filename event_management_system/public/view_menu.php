<?php
require_once '../config/config.php';
require_once '../src/Menu.php';
require_once '../src/MenuItem.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$menu = new Menu($pdo);
$menuItem = new MenuItem($pdo);
$menu_data = null;
$menu_items = [];

if (isset($_GET['id'])) {
    $menu_id = $_GET['id'];
    $menu_data = $menu->findById($menu_id);
    $menu_items = $menuItem->findByMenuId($menu_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $menu_id = $_POST['menu_id'];

    if ($menuItem->create($menu_id, $name, $description, $price)) {
        header("Location: view_menu.php?id=" . $menu_id);
        exit;
    }
}

if (isset($_GET['delete_item_id'])) {
    $item_id = $_GET['delete_item_id'];
    if ($menuItem->delete($item_id)) {
        header("Location: view_menu.php?id=" . $menu_id);
        exit;
    }
}


include '../views/header.php';
?>

<?php if ($menu_data): ?>
    <h2>Menu: <?php echo htmlspecialchars($menu_data['name']); ?></h2>
    <p><?php echo htmlspecialchars($menu_data['description']); ?></p>

    <h3>Menu Items</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menu_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo htmlspecialchars($item['price']); ?></td>
                    <td><a href="view_menu.php?id=<?php echo $menu_id; ?>&delete_item_id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Add New Item</h3>
    <form action="view_menu.php?id=<?php echo $menu_id; ?>" method="post">
        <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
        <label for="name">Item Name:</label>
        <input type="text" name="name" required>
        <label for="description">Description:</label>
        <textarea name="description"></textarea>
        <label for="price">Price:</label>
        <input type="text" name="price">
        <button type="submit" name="add_item">Add Item</button>
    </form>

<?php else: ?>
    <p>Menu not found.</p>
<?php endif; ?>

<?php include '../views/footer.php'; ?>
