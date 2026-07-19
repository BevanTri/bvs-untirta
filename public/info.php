<?php
echo "<h2>PHP Version: " . phpversion() . "</h2>";
echo "<h3>Loaded Extensions:</h3><pre>";
$exts = get_loaded_extensions();
sort($exts);
foreach ($exts as $e) {
    if (str_contains($e, 'pdo') || str_contains($e, 'mysql') || str_contains($e, 'mb') || str_contains($e, 'gd') || str_contains($e, 'json') || str_contains($e, 'xml') || str_contains($e, 'curl') || str_contains($e, 'fileinfo') || str_contains($e, 'tokenizer')) {
        echo " ✓ $e\n";
    }
}
echo "</pre>";

echo "<h3>Autoload test:</h3>";
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    echo "✓ vendor/autoload.php exists\n";
    try {
        require $autoload;
        echo "✓ Autoload loaded OK\n";
    } catch (Throwable $e) {
        echo "✗ Autoload error: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ vendor/autoload.php NOT FOUND at: $autoload\n";
}

echo "<h3>Storage test:</h3>";
$storage = __DIR__ . '/../storage';
if (is_writable($storage)) {
    echo "✓ storage is writable\n";
} else {
    echo "✗ storage NOT writable\n";
}
$logs = $storage . '/logs';
if (is_dir($logs)) {
    if (is_writable($logs)) echo "✓ storage/logs is writable\n";
    else echo "✗ storage/logs NOT writable\n";
} else {
    echo "✗ storage/logs directory missing\n";
}

echo "<h3>Last 20 lines of storage/logs/laravel.log:</h3><pre>";
$logFile = $storage . '/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last = array_slice($lines, -20);
    echo htmlspecialchars(implode('', $last));
} else {
    echo "No laravel.log found\n";
}
echo "</pre>";
