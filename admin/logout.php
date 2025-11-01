<?php
// Simple logout for admin area
require_once __DIR__ . '/../includes/functions.php';
// Clear admin flag
unset($_SESSION['is_admin']);
// Optionally destroy the session entirely
// session_destroy();
header('Location: login.php'); exit;
