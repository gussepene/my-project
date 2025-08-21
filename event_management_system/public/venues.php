<?php
require_once '../config/config.php';
require_once '../src/Venue.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

$venue = new Venue($pdo);
$venues = $venue->findAll();

include '../views/header.php';
?>

<h2>Venues</h2>
<a href="create_venue.php">Create New Venue</a>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($venues as $v): ?>
            <tr>
                <td><?php echo htmlspecialchars($v['name']); ?></td>
                <td><?php echo htmlspecialchars($v['address']); ?></td>
                <td><?php echo htmlspecialchars($v['capacity']); ?></td>
                <td>
                    <a href="edit_venue.php?id=<?php echo $v['id']; ?>">Edit</a>
                    <a href="delete_venue.php?id=<?php echo $v['id']; ?>" onclick="return confirm('Are you sure you want to delete this venue?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>
