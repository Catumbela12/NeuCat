<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'neucat_db');

define('APPROOT',  dirname(dirname(__FILE__)) . '/app');
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
define('URLROOT',  $protocol . $host . '/neucat_store/public');
define('SITENAME', 'Neucat');

// Admin credentials  – senha padrão: neucat@admin2025
// Para gerar um novo hash: /opt/lampp/bin/php -r "echo password_hash('nova_senha', PASSWORD_DEFAULT);"
define('ADMIN_USER',      'admin');
define('ADMIN_PASS_HASH', '$2y$10$SLbMZqpA2hxBoaawRz.p3OSQHjocc44EtsP50k5ZzlZOLpCwc3Oxi');
