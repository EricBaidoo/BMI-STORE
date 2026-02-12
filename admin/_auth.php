<?php
// Small admin auth include for plain-PHP scaffold.
// Usage: include_once __DIR__ . '/_auth.php'; after including `includes/functions.php`.

// Ensure a session exists (functions.php typically starts session, but be defensive).
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Load config
$cfg = require __DIR__ . '/../config.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Redirect to login page in the admin folder
    header('Location: login.php');
    exit;
}

// Optionally expose current admin username
$CURRENT_ADMIN = $_SESSION['admin_user'] ?? ($cfg['admin']['user'] ?? 'admin');

?>
