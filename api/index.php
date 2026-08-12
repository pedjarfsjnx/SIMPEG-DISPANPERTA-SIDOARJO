<?php

// Prepare writable storage directories in /tmp for Vercel Serverless environment
$storagePath = '/tmp/storage';
$dirs = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/cache/data',
    $storagePath . '/bootstrap/cache',
    $storagePath . '/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Set environment variables for Vercel
putenv("APP_STORAGE={$storagePath}");
$_ENV['APP_STORAGE'] = $storagePath;

// Auto-configure bundled SQLite database with 149 records
$sqlitePath = realpath(__DIR__ . '/../database/database.sqlite');
if ($sqlitePath && file_exists($sqlitePath)) {
    putenv("DB_CONNECTION=sqlite");
    putenv("DB_DATABASE={$sqlitePath}");
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = $sqlitePath;
}

// Forward to public/index.php
require __DIR__ . '/../public/index.php';
