<?php
require_once '../config/config.php';
require_once '../src/Event.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $event = new Event($pdo);
    // Add a check to make sure the user owns the event
    $event_data = $event->findById($_GET['id']);
    if ($event_data['user_id'] == $_SESSION['user_id']) {
        if ($event->delete($_GET['id'])) {
            header("Location: events.php");
            exit;
        }
    }
}

// If deletion fails or ID is not provided, redirect to events page
header("Location: events.php");
exit;
?>
