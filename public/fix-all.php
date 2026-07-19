<?php
set_time_limit(180);
$vendorDir = __DIR__ . '/../vendor';
$composerDir = $vendorDir . '/composer';

echo "<h2>Clean Autoload + Fix Vendor</h2>";

function removeMissingFromAutoload($file) {
    global $composerDir;
    if (!file_exists($file)) return 0;
    
    $content = file_get_contents($file);
    $removed = 0;
    
    // Match: __DIR__ . '/..' . '/some/path.php'
    // or:      __DIR__ . '/..' . '/some/dir/'
    preg_match_all("/__DIR__\s*\.\s*'\/\.\.'\s*\.\s*'([^']+)'/", $content, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $m) {
        $fullRelPath = $m[1]; // e.g., /thecodingmachine/safe/lib/special_cases.php
        $fullPath = $vendorDir . $fullRelPath;
        
        if (!file_exists($fullPath) && !is_dir($fullPath)) {
            // Remove the entire line containing this reference
            // Find the surrounding array entry
            $lines = explode("\n", $content);
            $newLines = [];
            foreach ($lines as $line) {
                if (str_contains($line, $fullRelPath)) {
                    $removed++;
                    echo "Removing: {$fullRelPath}<br>";
                    continue;
                }
                $newLines[] = $line;
            }
            $content = implode("\n", $newLines);
        }
    }
    
    // Also handle __DIR__ . '/..' . DIRECTORY_SEPARATOR . 'path' style
    preg_match_all("/__DIR__\s*\.\s*'\/\.\.'\s*\.\s*DIRECTORY_SEPARATOR\s*\.\s*'([^']+)'/", $content, $dsMatches, PREG_SET_ORDER);
    foreach ($dsMatches as $m) {
        $fullRelPath = '/' . $m[1];
        $fullPath = $vendorDir . $fullRelPath;
        if (!file_exists($fullPath) && !is_dir($fullPath)) {
            $content = str_replace($m[0], '', $content);
            $removed++;
            echo "Removing: {$fullRelPath}<br>";
        }
    }
    
    // Clean up empty array entries and double commas
    $content = preg_replace('/,\s*,/', ',', $content);
    $content = preg_replace('/\(\s*,/', '(', $content);
    $content = preg_replace('/,\s*\)/', ')', $content);
    $content = preg_replace('/array\s*\(\s*\)/', 'array()', $content);
    
    file_put_contents($file, $content);
    return $removed;
}

// Fix all autoload files
$files = ['autoload_classmap.php', 'autoload_static.php', 'autoload_psr4.php', 'autoload_files.php', 'autoload_namespaces.php'];
$totalRemoved = 0;
foreach ($files as $f) {
    $path = $composerDir . '/' . $f;
    if (file_exists($path)) {
        $r = removeMissingFromAutoload($path);
        echo "✓ {$f}: {$r} entries removed<br>";
        $totalRemoved += $r;
    }
}

echo "<br>Total removed: {$totalRemoved}<br>";

// Now download thecodingmachine/safe using curl or file_get_contents
echo "<br><strong>Downloading thecodingmachine/safe...</strong><br>";

// Try to determine the package version from installed data
$version = '2.1.8'; // Latest stable as of 2026
$pkgName = 'thecodingmachine-safe';

// Try multiple download methods
$zipUrl = "https://github.com/thecodingmachine/safe/archive/refs/tags/v{$version}.zip";
$zipContent = null;

// Method 1: curl
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $zipUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $zipContent = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "Curl: HTTP {$httpCode}, size: " . strlen($zipContent) . "<br>";
}

// Method 2: file_get_contents with stream context
if (!$zipContent || strlen($zipContent) < 100) {
    $ctx = stream_context_create(['http' => ['timeout' => 30, 'follow_location' => true], 'ssl' => ['verify_peer' => false]]);
    $zipContent = @file_get_contents($zipUrl, false, $ctx);
    echo "file_get_contents: size " . strlen($zipContent) . "<br>";
}

if ($zipContent && strlen($zipContent) > 100) {
    $tmpZip = sys_get_temp_dir() . '/' . $pkgName . '.zip';
    file_put_contents($tmpZip, $zipContent);
    
    $target = $vendorDir . '/thecodingmachine/safe';
    
    // Remove old directory
    if (is_dir($target)) {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            $f->isDir() ? @rmdir($f->getRealPath()) : @unlink($f->getRealPath());
        }
        @rmdir($target);
    }
    
    $zip = new ZipArchive;
    if ($zip->open($tmpZip) === TRUE) {
        $extractDir = dirname($target); // vendor/thecodingmachine
        $zip->extractTo($extractDir);
        $zip->close();
        
        // Find extracted dir (usually "safe-2.1.8" or similar)
        $items = scandir($extractDir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $fullItem = $extractDir . '/' . $item;
            if (is_dir($fullItem) && $fullItem !== $target && str_contains($item, 'safe')) {
                rename($fullItem, $target);
                echo "✓ Extracted to: {$target}<br>";
                break;
            }
        }
        
        @unlink($tmpZip);
    } else {
        echo "✗ Cannot open zip<br>";
    }
} else {
    echo "✗ Download failed for thecodingmachine/safe<br>";
    echo "URL tried: {$zipUrl}<br>";
}

// Final check
echo "<br><strong>Final check:</strong><br>";
$checkFiles = [
    $vendorDir . '/thecodingmachine/safe/lib/special_cases.php',
    $vendorDir . '/autoload.php',
];
$allOk = true;
foreach ($checkFiles as $f) {
    if (file_exists($f)) {
        echo "✓ " . basename(dirname(dirname($f))) . "/" . basename(dirname($f)) . "/" . basename($f) . " exists<br>";
    } else {
        echo "✗ " . $f . " missing<br>";
        $allOk = false;
    }
}

// Test autoload
echo "<br><strong>Autoload test:</strong><br>";
try {
    require $vendorDir . '/autoload.php';
    echo "✓ Autoload loaded OK!<br>";
} catch (Throwable $e) {
    echo "✗ Autoload error: " . $e->getMessage() . "<br>";
}

if ($allOk) {
    echo "<br><a href='/' style='font-size:18px;'>Test Homepage</a>";
}
