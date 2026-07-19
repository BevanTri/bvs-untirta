<?php
set_time_limit(120);
$zipPath = __DIR__ . '/vendor.zip';
$targetDir = __DIR__ . '/../vendor';

if (!file_exists($zipPath)) {
    die("Error: vendor.zip not found in public/ directory");
}

// Delete old vendor
if (is_dir($targetDir)) {
    echo "Deleting old vendor/...<br>";
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($targetDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $f) {
        $f->isDir() ? @rmdir($f->getRealPath()) : @unlink($f->getRealPath());
    }
    @rmdir($targetDir);
    echo "Old vendor deleted<br>";
}

$zip = new ZipArchive;
if ($zip->open($zipPath) === TRUE) {
    $zip->extractTo(__DIR__ . '/../');
    $zip->close();
    echo "Extract OK!<br>";
} else {
    die("Error: cannot open vendor.zip");
}

// Verify
if (file_exists($targetDir . '/autoload.php')) {
    echo "✓ vendor/autoload.php exists<br>";
} else {
    echo "✗ vendor/autoload.php MISSING<br>";
}

echo "Done! <a href='/'>Go to homepage</a>";
