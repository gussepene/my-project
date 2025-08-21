<?php
session_start();
// Using __DIR__ makes the path more robust, regardless of where this file is included from.
require_once __DIR__ . '/../src/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="events.php">My Events</a></li>
                    <?php if (isAdmin()): ?>
                        <li><a href="venues.php">Manage Venues</a></li>
                        <li><a href="menus.php">Manage Menus</a></li>
                        <li><a href="manage_users.php">Manage Users</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
