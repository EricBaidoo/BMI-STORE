<?php
// Basic config — edit DB settings if you plan to use MySQL
return [
    'app_name' => 'BMI Bookshop',
    'data_file' => __DIR__ . '/data/books.json',
    'db' => [
        'host' => '127.0.0.1',
        'name' => '',
        'user' => '',
        'pass' => '',
    ],
    // Simple admin credentials for local development only.
    // Change these before deploying to production. You can replace with a hashed password
    // or move to environment variables later.
    'admin' => [
        'user' => 'admin',
        'pass' => 'password'
    ],
    // Secret key for BMI Pay HMAC signing. Change this to a strong random value!
    'bmipay_secret' => 'REPLACE_WITH_A_RANDOM_SECRET_KEY_1234567890',
];
