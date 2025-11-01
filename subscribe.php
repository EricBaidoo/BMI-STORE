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

$subsFile = __DIR__ . '/data/subscribers.json';
// read-modify-write with a simple lock to avoid race conditions
$subs = [];
$fp = fopen($subsFile, 'c+');
if ($fp) {
    // obtain exclusive lock
    if (flock($fp, LOCK_EX)) {
        // read existing
        $contents = stream_get_contents($fp);
        if ($contents) {
            $s = json_decode($contents, true);
            if (is_array($s)) $subs = $s;
        }
        // avoid duplicates
        foreach ($subs as $existing) {
            if (isset($existing['email']) && strtolower($existing['email']) === strtolower($email)) {
                flock($fp, LOCK_UN);
                fclose($fp);
                header('Location: index.php?sub_ok=1'); exit;
            }
        }
        $subs[] = ['email' => $email, 'subscribed_at' => date('c')];
        // rewind and truncate then write
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($subs, JSON_PRETTY_PRINT));
        fflush($fp);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}
header('Location: index.php?sub_ok=1'); exit;
