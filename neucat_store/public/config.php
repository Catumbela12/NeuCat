<?php
// config.php - Configuração para Render

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'neucat');
define('DB_PORT', getenv('DB_PORT') ?: '3306');

define('APP_URL', getenv('APP_URL') ?: 'https://neucat2.onrender.com');
define('URLROOT', APP_URL);
