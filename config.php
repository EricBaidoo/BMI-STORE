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
    // Secret key for BMI Pay HMAC signing. Must match BMIPAY's BMI_PAY_SECRET constant
    'bmipay_secret' => 'mevaGieB1sMI/lThG2Ztwl1vM/M5HnPDjdX/UWHsHZd9SxFBjyd5UaqokXN71xrV',
    // BMIPAY endpoint used by checkout redirection.
    // Local example: http://localhost/BMIPAY/index.php
    // Hosted example: https://your-bmipay-domain.com/index.php
    'bmipay_base_url' => 'https://pay.bridgeministriesintl.org/',
    // Paystack subaccount code for the bookstore.
    // Create one at: https://dashboard.paystack.com/#/subaccounts
    // Format: ACCT_xxxxxxxxxxxxxxxx
    'paystack_subaccount' => 'ACCT_co6ud58oukiwh4r',
];
