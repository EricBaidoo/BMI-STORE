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
    return load_books_from_json();
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
    return '$' . number_format($p, 2);
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
