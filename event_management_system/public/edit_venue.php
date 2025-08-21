<?php
require_once '../config/config.php';
require_once '../src/Venue.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

$venue = new Venue($pdo);
$message = '';
$venue_data = null;

if (isset($_GET['id'])) {
    $venue_data = $venue->findById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $capacity = $_POST['capacity'];

    if ($venue->update($id, $name, $address, $capacity)) {
        header("Location: venues.php");
        exit;
    } else {
        $message = "Failed to update venue.";
    }
}

include '../views/header.php';
?>

<h2>Edit Venue</h2>
<p><?php echo $message; ?></p>
<?php if ($venue_data): ?>
<form action="edit_venue.php" method="post">
    <input type="hidden" name="id" value="<?php echo $venue_data['id']; ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($venue_data['name']); ?>" required>
    <br>
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($venue_data['address']); ?>" required>
    <br>
    <label for="capacity">Capacity:</label>
    <input type="number" id="capacity" name="capacity" value="<?php echo htmlspecialchars($venue_data['capacity']); ?>" required>
    <br>
    <button type="submit">Update Venue</button>
</form>
<?php else: ?>
<p>Venue not found.</p>
<?php endif; ?>

<?php include '../views/footer.php'; ?>
