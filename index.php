<?php

/**
 * XAMPP / Apache — document root proje köküne işaret ediyorsa Laravel public klasörüne yönlendirir.
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$uri = $uri === '' ? '/' : $uri;

if ($uri !== '/' && $uri !== '/index.php' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

require __DIR__ . '/public/index.php';
