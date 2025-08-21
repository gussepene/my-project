<?php
require_once '../config/config.php';
require_once '../src/Booking.php';
require_once '../src/Event.php';
require_once '../src/auth.php';
session_start();

requireLogin();

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    header("Location: events.php");
    exit;
}

$event = new Event($pdo);
$event_data = $event->findById($event_id);

if (!$event_data) {
    die("Event not found.");
}

// Security check: Ensure staff can only see their own event's bookings
if (isStaff() && $event_data['user_id'] != $_SESSION['user_id']) {
    header("Location: dashboard.php?error=unauthorized");
    exit;
}

$booking = new Booking($pdo);
$bookings = $booking->findByEventId($event_id);

include '../views/header.php';
?>

<h2>Bookings for: <?php echo htmlspecialchars($event_data['title']); ?></h2>

<a href="events.php">&laquo; Back to Events</a>

<table>
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Booking Date</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($bookings)): ?>
            <tr>
                <td colspan="4">No bookings yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($b['customer_email']); ?></td>
                    <td><?php echo htmlspecialchars($b['booking_date']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($b['notes'])); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php include '../views/footer.php'; ?>
