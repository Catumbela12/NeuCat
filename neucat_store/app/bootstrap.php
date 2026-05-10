
<?php
// app/bootstrap.php

// Carrega configurações primeiro (antes de qualquer saída)
require_once __DIR__ . '/../config/config.php';

// Inicia sessão ANTES de qualquer saída
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader
spl_autoload_register(function($className) {
    $paths = [
        __DIR__ . '/Core/' . $className . '.php',
        __DIR__ . '/Controllers/' . $className . '.php',
        __DIR__ . '/Models/' . $className . '.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
