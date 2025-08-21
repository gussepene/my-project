<?php
require_once '../config/config.php';
require_once '../src/Menu.php';
require_once '../src/auth.php';
session_start();

requireLogin();
requireAdmin();

if (isset($_GET['id'])) {
    $menu = new Menu($pdo);
    if ($menu->delete($_GET['id'])) {
        header("Location: menus.php");
        exit;
    }
}

// If deletion fails or ID is not provided, redirect to menus page
header("Location: menus.php");
exit;
?>
