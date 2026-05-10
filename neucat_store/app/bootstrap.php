<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

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
