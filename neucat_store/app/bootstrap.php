<?php
// app/bootstrap.php

// Carrega configurações do banco
require_once __DIR__ . '/../config/config.php';

// Inicia sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader Melhorado
spl_autoload_register(function($className) {
    $paths = [
        APPROOT . '/app/Core/' . $className . '.php',
        APPROOT . '/app/Controllers/' . $className . '.php',
        APPROOT . '/app/Models/' . $className . '.php',
        APPROOT . '/Core/' . $className . '.php',
        APPROOT . '/Controllers/' . $className . '.php',
        APPROOT . '/Models/' . $className . '.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
