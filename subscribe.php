<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

$email = trim($_POST['email'] ?? '');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // simple redirect back with a query param to show an error (front-end can handle it)
    header('Location: index.php?sub_error=1'); exit;
}

$pdo = get_pdo();
if ($pdo) {
    try {
        $stmt = $pdo->prepare('INSERT INTO subscribers (email) VALUES (?)');
        $stmt->execute([$email]);
    } catch (Exception $e) {
        // ignore duplicates or insert errors silently
    }
}
header('Location: index.php?sub_ok=1'); exit;
