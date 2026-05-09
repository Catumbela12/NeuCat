<?php
require_once '../config/config.php';

// Start session early so all controllers can use it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader usando APPROOT (caminho absoluto) para evitar erros com cwd relativo
spl_autoload_register(function($className) {
    $corePath   = APPROOT . '/Core/'    . $className . '.php';
    $modelPath  = APPROOT . '/Models/'  . $className . '.php';

    if (file_exists($corePath)) {
        require_once $corePath;
    } elseif (file_exists($modelPath)) {
        require_once $modelPath;
    }
});
