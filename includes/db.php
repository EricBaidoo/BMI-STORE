<?php
// Minimal DB helper — uses PDO if config.db.name is set, otherwise falls back to JSON file.
$config = require __DIR__ . '/../config.php';

function get_pdo() {
    global $config;
    $db = $config['db'];
    if (empty($db['name'])) return null;
    $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $pdo;
    } catch (Exception $e) {
        error_log('DB connection failed: ' . $e->getMessage());
        return null;
    }
}

function load_books_from_json() {
    $config = require __DIR__ . '/../config.php';
    $file = $config['data_file'];
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return $data ?: [];
}
