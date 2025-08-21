<?php
require_once '../config/config.php';
require_once '../src/Event.php';
require_once '../src/auth.php';
session_start();

requireLogin();

$event = new Event($pdo);

// If user is admin, show all events. Otherwise, show only their own.
if (isAdmin()) {
    $events = $event->findAll();
} else {
    $events = $event->findByUserId($_SESSION['user_id']);
}


include '../views/header.php';
?>

<h2><?php echo isAdmin() ? 'All Events' : 'My Events'; ?></h2>
<a href="create_event.php">Create New Event</a>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Venue</th>
            <th>Date</th>
            <?php if (isAdmin()): ?>
                <th>Created By</th>
            <?php endif; ?>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($events as $e): ?>
            <tr>
                <td><?php echo htmlspecialchars($e['title']); ?></td>
                <td><?php echo htmlspecialchars($e['venue_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($e['event_date']); ?></td>
                <?php if (isAdmin()): ?>
                    <td><?php echo htmlspecialchars($e['created_by']); ?></td>
                <?php endif; ?>
                <td>
                    <a href="edit_event.php?id=<?php echo $e['id']; ?>">Edit</a> |
                    <a href="delete_event.php?id=<?php echo $e['id']; ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a> |
                    <a href="view_bookings.php?id=<?php echo $e['id']; ?>">View Bookings</a> |
                    <a href="book_event.php?id=<?php echo $e['id']; ?>" target="_blank">Public Link</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>
