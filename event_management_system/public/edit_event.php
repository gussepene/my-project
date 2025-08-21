<?php
require_once '../config/config.php';
require_once '../src/Event.php';
require_once '../src/Venue.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$event = new Event($pdo);
$venue = new Venue($pdo);
$venues = $venue->findAll();
$message = '';
$event_data = null;

if (isset($_GET['id'])) {
    $event_data = $event->findById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $venue_id = !empty($_POST['venue_id']) ? $_POST['venue_id'] : null;

    if ($event->update($id, $venue_id, $title, $description, $event_date)) {
        header("Location: events.php");
        exit;
    } else {
        $message = "Failed to update event.";
    }
}

include '../views/header.php';
?>

<h2>Edit Event</h2>
<p><?php echo $message; ?></p>
<?php if ($event_data): ?>
<form action="edit_event.php" method="post">
    <input type="hidden" name="id" value="<?php echo $event_data['id']; ?>">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event_data['title']); ?>" required>
    <br>
    <label for="description">Description:</label>
    <textarea id="description" name="description"><?php echo htmlspecialchars($event_data['description']); ?></textarea>
    <br>
    <label for="event_date">Event Date:</label>
    <input type="datetime-local" id="event_date" name="event_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event_data['event_date'])); ?>" required>
    <br>
    <label for="venue_id">Venue:</label>
    <select id="venue_id" name="venue_id">
        <option value="">Select a venue</option>
        <?php foreach ($venues as $v): ?>
            <option value="<?php echo $v['id']; ?>" <?php if ($v['id'] == $event_data['venue_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($v['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br>
    <button type="submit">Update Event</button>
</form>
<?php else: ?>
<p>Event not found.</p>
<?php endif; ?>

<?php include '../views/footer.php'; ?>
