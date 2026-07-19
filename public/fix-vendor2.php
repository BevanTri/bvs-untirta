<?php
set_time_limit(120);
$vendorDir = __DIR__ . '/../vendor';
$composerDir = $vendorDir . '/composer';

echo "<h2>Vendor Fixer v2</h2>";

// Check actual file: special_cases.php
$missingFile = $vendorDir . '/thecodingmachine/safe/lib/special_cases.php';
echo "Checking: $missingFile<br>";
echo "Exists: " . (file_exists($missingFile) ? 'YES' : 'NO') . "<br>";

// Check if thecodingmachine/safe directory exists
$pkgDir = $vendorDir . '/thecodingmachine/safe';
echo "Package dir exists: " . (is_dir($pkgDir) ? 'YES' : 'NO') . "<br>";
if (is_dir($pkgDir)) {
    echo "Contents:<br>";
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pkgDir));
    foreach ($it as $f) {
        if ($f->isFile()) echo $f->getPathname() . "<br>";
    }
}

// Dump autoload_files.php entries
$filesFile = $composerDir . '/autoload_files.php';
if (file_exists($filesFile)) {
    echo "<h3>autoload_files.php entries:</h3><pre>";
    $content = file_get_contents($filesFile);
    // Show lines that match __DIR__
    $lines = explode("\n", $content);
    foreach ($lines as $l) {
        if (str_contains($l, '__DIR__') && str_contains($l, 'safe')) {
            echo htmlspecialchars(trim($l)) . "\n";
        }
    }
    echo "</pre>";
}

// Dump autoload_static.php relevant parts
$staticFile = $composerDir . '/autoload_static.php';
if (file_exists($staticFile)) {
    echo "<h3>autoload_static.php safe entries:</h3><pre>";
    $content = file_get_contents($staticFile);
    $lines = explode("\n", $content);
    foreach ($lines as $l) {
        if (str_contains($l, 'safe') || str_contains($l, 'Safe')) {
            echo htmlspecialchars(trim($l)) . "\n";
        }
    }
    echo "</pre>";
}
