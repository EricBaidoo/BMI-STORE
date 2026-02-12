<?php
// Basic config — edit DB settings if you plan to use MySQL
return [
    'app_name' => 'BMI Bookshop',
    'data_file' => __DIR__ . '/data/books.json',
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'BOOKSTORE',
        'user' => 'root',
        'pass' => 'root',
        'port' => 3306
    ],
    'db_local' => [
        'host' => '127.0.0.1',
        'name' => 'BOOKSTORE',
        'user' => 'root',
        'pass' => 'root',
        'port' => 3306
    ],
    'db_online' => [
        'host' => '127.0.0.1',
        'name' => 'u145148023_bookstore',
        'user' => 'u145148023_bmi_admin1',
        'pass' => 'Bmi@2025',
        'port' => 3306
    ],
    // Simple admin credentials for local development only.
    // Change these before deploying to production. You can replace with a hashed password
    // or move to environment variables later.
    'admin' => [
        'user' => 'admin',
        'pass' => 'admin'
    ],
    // Currency settings for auto-detection and conversion
    'currency' => [
        'base' => 'GHS',
        'default' => 'GHS',
        'cache_ttl' => 21600,
        'geo_ttl' => 604800,
        'geo_timeout' => 0.8,
        'rate_timeout' => 1.0
    ],
    // Secret key for BMI Pay HMAC signing. Change this to a strong random value!
    'bmipay_secret' => 'REPLACE_WITH_A_RANDOM_SECRET_KEY_1234567890',
];
