<?php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'neucat_db');
define('DB_PORT', getenv('DB_PORT') ?: '3306');

define('APPROOT',  dirname(dirname(__FILE__)) . '/app');
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https://' : 'http://';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

// Check if running on Render (Render sets the RENDER environment variable)
if (getenv('RENDER') !== false) {
    // On Render, the app is hosted at the root domain
    define('URLROOT', $protocol . $host);
} else {
    // Locally on XAMPP, it's inside the neucat_store/public folder
    define('URLROOT', $protocol . $host . '/neucat_store/public');
}
define('SITENAME', 'Neucat');

// Admin credentials  – senha padrão: neucat@admin2025
// Para gerar um novo hash: /opt/lampp/bin/php -r "echo password_hash('nova_senha', PASSWORD_DEFAULT);"
define('ADMIN_USER',      'admin');
define('ADMIN_PASS_HASH', '$2y$10$SLbMZqpA2hxBoaawRz.p3OSQHjocc44EtsP50k5ZzlZOLpCwc3Oxi');
