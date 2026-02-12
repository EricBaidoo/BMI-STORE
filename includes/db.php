<?php
// Minimal DB helper — uses PDO if config.db.name is set, otherwise falls back to JSON file.
$config = require __DIR__ . '/../config.php';

function resolve_db_config($config) {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === 'localhost' || $host === '127.0.0.1') {
        return $config['db_local'] ?? $config['db'] ?? [];
    }
    return $config['db_online'] ?? $config['db'] ?? [];
}

function get_pdo() {
    global $config;
    $db = resolve_db_config($config);
    if (empty($db['name'])) return null;
    $port = !empty($db['port']) ? (int)$db['port'] : 3306;
    $dsn = "mysql:host={$db['host']};port={$port};dbname={$db['name']};charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $pdo;
    } catch (Exception $e) {
        error_log('DB connection failed: ' . $e->getMessage());
        return null;
    }
}

