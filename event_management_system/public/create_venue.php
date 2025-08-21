<?php
require_once '../config/config.php';
require_once '../src/Venue.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venue = new Venue($pdo);
    $name = $_POST['name'];
    $address = $_POST['address'];
    $capacity = $_POST['capacity'];

    if ($venue->create($name, $address, $capacity)) {
        header("Location: venues.php");
        exit;
    } else {
        $message = "Failed to create venue.";
    }
}

include '../views/header.php';
?>

<h2>Create Venue</h2>
<p><?php echo $message; ?></p>
<form action="create_venue.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required>
    <br>
    <label for="capacity">Capacity:</label>
    <input type="number" id="capacity" name="capacity" required>
    <br>
    <button type="submit">Create Venue</button>
</form>

<?php include '../views/footer.php'; ?>
