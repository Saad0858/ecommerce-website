<?php
// Router script for PHP built-in web server
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Let the server handle the request as-is
}

// Route /frontend/public/ requests
if (strpos($uri, '/frontend/public/') === 0) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        // If it's a PHP file, include it
        if (substr($file, -4) === '.php') {
            include $file;
            return true;
        }
        // Otherwise serve the file directly
        return false;
    }
}

// Default to the index page
include __DIR__ . '/frontend/public/index.php';