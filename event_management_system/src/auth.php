<?php

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isStaff() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireAdmin() {
    if (!isAdmin()) {
        // Redirect to a safe page, like the dashboard, with an error message
        header("Location: dashboard.php?error=unauthorized");
        exit;
    }
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}
