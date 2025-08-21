<?php
require_once '../config/config.php';
require_once '../src/Event.php';
require_once '../src/Booking.php';

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    header("Location: index.php");
    exit;
}

$event = new Event($pdo);
$event_data = $event->findById($event_id);

if (!$event_data) {
    // A simple way to handle not found
    die("Event not found.");
}

$message = '';
$booking_successful = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking = new Booking($pdo);
    $customer_name = trim($_POST['customer_name']);
    $customer_email = trim($_POST['customer_email']);
    $notes = trim($_POST['notes']);

    if ($booking->create($event_id, $customer_name, $customer_email, $notes)) {
        $message = "Your booking was successful! Thank you.";
        $booking_successful = true;
    } else {
        $message = "There was an error placing your booking. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event: <?php echo htmlspecialchars($event_data['title']); ?></title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; max-width: 600px; margin: auto; }
        form { margin-top: 20px; border-top: 1px solid #ccc; padding-top: 20px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 15px; }
    </style>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($event_data['title']); ?></h1>
    </header>
    <main>
        <p><strong>Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($event_data['event_date'])); ?></p>
        <p><?php echo nl2br(htmlspecialchars($event_data['description'])); ?></p>

        <hr>

        <?php if ($message): ?>
            <p><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>

        <?php if (!$booking_successful): ?>
            <h2>Book Your Spot</h2>
            <form action="book_event.php?id=<?php echo $event_id; ?>" method="post">
                <label for="customer_name">Your Name:</label>
                <input type="text" id="customer_name" name="customer_name" required>

                <label for="customer_email">Your Email:</label>
                <input type="email" id="customer_email" name="customer_email" required>

                <label for="notes">Notes (optional):</label>
                <textarea id="notes" name="notes" rows="4"></textarea>

                <button type="submit">Confirm Booking</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
