<?php
require_once '../config/config.php';
require_once '../src/Event.php';
require_once '../src/Venue.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$venue = new Venue($pdo);
$venues = $venue->findAll();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event = new Event($pdo);
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $venue_id = !empty($_POST['venue_id']) ? $_POST['venue_id'] : null;

    if ($event->create($user_id, $venue_id, $title, $description, $event_date)) {
        header("Location: events.php");
        exit;
    } else {
        $message = "Failed to create event.";
    }
}

include '../views/header.php';
?>

<h2>Create Event</h2>
<p><?php echo $message; ?></p>
<form action="create_event.php" method="post">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>
    <br>
    <label for="description">Description:</label>
    <textarea id="description" name="description"></textarea>
    <br>
    <label for="event_date">Event Date:</label>
    <input type="datetime-local" id="event_date" name="event_date" required>
    <br>
    <label for="venue_id">Venue:</label>
    <select id="venue_id" name="venue_id">
        <option value="">Select a venue</option>
        <?php foreach ($venues as $v): ?>
            <option value="<?php echo $v['id']; ?>"><?php echo htmlspecialchars($v['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <button type="submit">Create Event</button>
</form>

<?php include '../views/footer.php'; ?>
