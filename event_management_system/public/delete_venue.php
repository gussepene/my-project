<?php
require_once '../config/config.php';
require_once '../src/Venue.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

if (isset($_GET['id'])) {
    $venue = new Venue($pdo);
    if ($venue->delete($_GET['id'])) {
        header("Location: venues.php");
        exit;
    }
}

// If deletion fails or ID is not provided, redirect to venues page
header("Location: venues.php");
exit;
?>
