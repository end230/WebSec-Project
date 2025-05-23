<?php

$basePath = __DIR__;
$storagePath = $basePath . '/storage';

// Create necessary directories
$directories = [
    $storagePath . '/app',
    $storagePath . '/app/public',
    $storagePath . '/framework',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $basePath . '/bootstrap/cache',
];

echo "Checking and creating directories...\n";
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        echo "Creating: $dir\n";
        mkdir($dir, 0775, true);
    } else {
        echo "Exists: $dir\n";
        chmod($dir, 0775);
    }
}

echo "Creating .gitignore files...\n";
$gitignores = [
    $storagePath . '/app/.gitignore' => "*\n!public/\n!.gitignore\n",
    $storagePath . '/app/public/.gitignore' => "*\n!.gitignore\n",
    $storagePath . '/framework/.gitignore' => "*\n!.gitignore\n",
    $storagePath . '/framework/cache/.gitignore' => "*\n!data/\n!.gitignore\n",
    $storagePath . '/framework/cache/data/.gitignore' => "*\n!.gitignore\n",
    $storagePath . '/framework/sessions/.gitignore' => "*\n!.gitignore\n",
    $storagePath . '/framework/views/.gitignore' => "*\n!.gitignore\n",
    $storagePath . '/logs/.gitignore' => "*\n!.gitignore\n",
    $basePath . '/bootstrap/cache/.gitignore' => "*\n!.gitignore\n",
];

foreach ($gitignores as $file => $content) {
    file_put_contents($file, $content);
    echo "Created: $file\n";
}

echo "Storage directory structure fixed.\n";
