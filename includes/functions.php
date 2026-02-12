<?php
session_start();
require_once __DIR__ . '/db.php';

function site_name() {
    $c = require __DIR__ . '/../config.php';
    return $c['app_name'] ?? 'Bookshop';
}

function all_books() {
    $pdo = get_pdo();
    if ($pdo) {
        $stmt = $pdo->query('SELECT * FROM books');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return [];
}

/**
 * Return featured books (flagged in the JSON as 'featured' => true).
 * Falls back to returning the first $limit books if none are featured.
 */
function featured_books($limit = 8) {
    $books = all_books();
    $featured = array_filter($books, function($b) { return !empty($b['featured']); });
    if (count($featured)) {
        return array_slice(array_values($featured), 0, $limit);
    }
    return array_slice($books, 0, $limit);
}

function find_book($id_or_slug) {
    $books = all_books();
    foreach ($books as $b) {
        if ((string)($b['id'] ?? '') === (string)$id_or_slug) return $b;
        if (($b['slug'] ?? '') === $id_or_slug) return $b;
    }
    return null;
}

function format_price($p) {
    $currency = detect_currency_code();
    $rate = get_currency_rate($currency);
    $value = (float)$p * $rate;
    return $currency . ' ' . number_format($value, 2);
}

function asset_prefix() {
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
    $assetPrefix = $scriptDir;
    if ($assetPrefix === '' || $assetPrefix === '.') $assetPrefix = '';
    if (basename($assetPrefix) === 'admin') {
        $assetPrefix = dirname($assetPrefix);
    }
    if ($assetPrefix === '\\' || $assetPrefix === '.') $assetPrefix = '';
    return $assetPrefix;
}

function resolve_cover_url($cover) {
    $cover = trim((string)$cover);
    if ($cover === '') return '';
    $lower = strtolower($cover);
    if (strpos($lower, 'http://') === 0 || strpos($lower, 'https://') === 0 || strpos($lower, '//') === 0) {
        return $cover;
    }
    if (strpos($lower, 'data:') === 0 || strpos($lower, '/') === 0) {
        return $cover;
    }
    if (strpos($lower, 'assets/') === 0) {
        return asset_prefix() . '/' . $cover;
    }
    return $cover;
}

function cart_add($book_id, $qty = 1) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['cart'][$book_id])) $_SESSION['cart'][$book_id] = 0;
    $_SESSION['cart'][$book_id] += $qty;
}

function cart_remove($book_id) {
    if (isset($_SESSION['cart'][$book_id])) unset($_SESSION['cart'][$book_id]);
}

function cart_items() {
    $items = [];
    $books = all_books();
    $map = [];
    foreach ($books as $b) $map[$b['id']] = $b;
    foreach ($_SESSION['cart'] ?? [] as $id => $qty) {
        if (isset($map[$id])) {
            $items[] = ['book' => $map[$id], 'qty' => $qty];
        }
    }
    return $items;
}

function cart_total() {
    $total = 0;
    foreach (cart_items() as $it) {
        $total += ($it['book']['price'] ?? 0) * $it['qty'];
    }
    return $total;
}

function detect_currency_code() {
    if (!empty($_COOKIE['currency_code'])) {
        $code = strtoupper(trim($_COOKIE['currency_code']));
        if ($code !== '') {
            $_SESSION['currency_code'] = $code;
            return $code;
        }
    }
    if (!empty($_SESSION['currency_code'])) return $_SESSION['currency_code'];
    $cfg = require __DIR__ . '/../config.php';
    $default = $cfg['currency']['default'] ?? 'GHS';
    $geoTtl = (int)($cfg['currency']['geo_ttl'] ?? 604800);
    $geoTimeout = (float)($cfg['currency']['geo_timeout'] ?? 0.8);

    $ip = get_client_ip();
    if (!$ip || is_private_ip($ip)) {
        $_SESSION['currency_code'] = $default;
        set_currency_cookie($default);
        return $default;
    }

    if (!empty($_SESSION['currency_geo'][$ip]['code']) && !empty($_SESSION['currency_geo'][$ip]['timestamp'])) {
        if ((time() - (int)$_SESSION['currency_geo'][$ip]['timestamp']) < $geoTtl) {
            $code = strtoupper($_SESSION['currency_geo'][$ip]['code']);
            $_SESSION['currency_code'] = $code;
            set_currency_cookie($code);
            return $code;
        }
    }

    $url = 'https://ipapi.co/' . rawurlencode($ip) . '/json/';
    $opts = [
        'http' => [
            'timeout' => $geoTimeout,
            'header' => "User-Agent: BMI-STORE/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $resp = @file_get_contents($url, false, $context);
    if ($resp) {
        $data = json_decode($resp, true);
        if (!empty($data['currency'])) {
            $code = strtoupper($data['currency']);
            $_SESSION['currency_code'] = $code;
            $_SESSION['currency_geo'][$ip] = [
                'code' => $code,
                'timestamp' => time()
            ];
            set_currency_cookie($code);
            return $code;
        }
    }

    $_SESSION['currency_code'] = $default;
    set_currency_cookie($default);
    return $default;
}

function get_currency_rate($currencyCode) {
    $cfg = require __DIR__ . '/../config.php';
    $base = $cfg['currency']['base'] ?? 'USD';
    if (!$currencyCode || strtoupper($currencyCode) === strtoupper($base)) return 1.0;

    $rates = get_exchange_rates($base);
    $key = strtoupper($currencyCode);
    if (!empty($rates[$key])) return (float)$rates[$key];
    return 1.0;
}

function get_exchange_rates($base) {
    $cfg = require __DIR__ . '/../config.php';
    $ttl = (int)($cfg['currency']['cache_ttl'] ?? 21600);
    $rateTimeout = (float)($cfg['currency']['rate_timeout'] ?? 1.0);
    if (!empty($_SESSION['currency_rates']['timestamp']) && !empty($_SESSION['currency_rates']['base']) && !empty($_SESSION['currency_rates']['rates'])) {
        if ($_SESSION['currency_rates']['base'] === $base && (time() - (int)$_SESSION['currency_rates']['timestamp']) < $ttl) {
            return $_SESSION['currency_rates']['rates'];
        }
    }

    $url = 'https://api.exchangerate.host/latest?base=' . rawurlencode($base);
    $opts = [
        'http' => [
            'timeout' => $rateTimeout,
            'header' => "User-Agent: BMI-STORE/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $resp = @file_get_contents($url, false, $context);
    if ($resp) {
        $data = json_decode($resp, true);
        if (!empty($data['rates']) && is_array($data['rates'])) {
            $_SESSION['currency_rates'] = [
                'base' => $base,
                'timestamp' => time(),
                'rates' => $data['rates']
            ];
            return $data['rates'];
        }
    }

    return [];
}

function set_currency_cookie($code) {
    if (headers_sent()) return;
    setcookie('currency_code', strtoupper($code), [
        'expires' => time() + 60 * 60 * 24 * 30,
        'path' => '/',
        'samesite' => 'Lax'
    ]);
}

function get_client_ip() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($parts[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function is_private_ip($ip) {
    if (!filter_var($ip, FILTER_VALIDATE_IP)) return true;
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return preg_match('/^(10\.|127\.|172\.(1[6-9]|2\d|3[0-1])\.|192\.168\.)/', $ip) === 1;
    }
    return $ip === '::1';
}
