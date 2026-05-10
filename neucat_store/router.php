<?php
// Router para o servidor embutido do PHP (php -S)
if (preg_match('/\.(?:png|jpg|jpeg|gif|webp|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false; // serve o arquivo estático diretamente
} else {
    $_GET['url'] = ltrim($_SERVER["REQUEST_URI"], '/');
    require_once 'public/index.php';
}
