<?php

/**
 * Custom PHP Router untuk development server
 * Memungkinkan akses assets dari root dan routing ke root/index.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Debug log
error_log("Request URI: $uri");

// Jika request untuk assets di root, serve langsung
if (preg_match('/^\/assets\//', $uri)) {
    $filePath = __DIR__ . $uri;
    error_log("Trying to serve asset: $filePath");
    
    if (file_exists($filePath) && is_file($filePath)) {
        // Set content type berdasarkan ekstensi file
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
        ];
        
        $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';
        header("Content-Type: $contentType");
        
        readfile($filePath);
        return true;
    } else {
        http_response_code(404);
        echo "404 - File not found: $uri";
        return true;
    }
}

// Jika file static di root folder
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Serve file static dari root
}

// Semua request lainnya diarahkan ke root/index.php
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

require __DIR__ . '/index.php';
