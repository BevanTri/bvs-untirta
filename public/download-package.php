<?php
set_time_limit(120);
$vendorDir = __DIR__ . '/../vendor';

function downloadPackage($pkg, $target) {
    global $vendorDir;
    // Get package info from Packagist
    $url = "https://repo.packagist.org/packages/{$pkg}.json";
    $json = @file_get_contents($url);
    if (!$json) {
        echo "✗ Cannot fetch package info for {$pkg}<br>";
        return false;
    }
    $data = json_decode($json, true);
    $pkgData = $data['packages'][$pkg] ?? [];
    // Find the latest stable version compatible with PHP 8.3
    $version = null;
    foreach ($pkgData as $v => $info) {
        if (str_contains($v, 'dev')) continue;
        if (!isset($info['dist']['url'])) continue;
        $version = $v;
        // Prefer non-dev, highest version
    }
    if (!$version) {
        echo "✗ No stable version found for {$pkg}<br>";
        return false;
    }
    $distUrl = $pkgData[$version]['dist']['url'];
    $distType = $pkgData[$version]['dist']['type'] ?? 'zip';
    echo "Downloading {$pkg}:{$version} ({$distType})...<br>";
    
    // Download
    $zipContent = @file_get_contents($distUrl);
    if (!$zipContent) {
        echo "✗ Download failed for {$pkg}<br>";
        return false;
    }
    
    // Save to temp
    $tmpZip = sys_get_temp_dir() . '/' . str_replace('/', '_', $pkg) . '.zip';
    file_put_contents($tmpZip, $zipContent);
    
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
    
    // Extract
    $zip = new ZipArchive;
    if ($zip->open($tmpZip) === TRUE) {
        // The zip contains a root folder like "safe-master" or "package-name"
        // We need to find the actual files inside
        $extractTo = dirname($target);
        $zip->extractTo($extractTo);
        $zip->close();
        
        // Find the extracted directory
        $items = scandir($extractTo);
        $extractedDir = null;
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $fullPath = $extractTo . '/' . $item;
            if (is_dir($fullPath) && $fullPath !== $target) {
                // Check if this is the package directory
                if (file_exists($fullPath . '/composer.json')) {
                    $extractedDir = $fullPath;
                    break;
                }
            }
        }
        
        // Rename extracted directory to target name
        if ($extractedDir && $extractedDir !== $target) {
            rename($extractedDir, $target);
        }
        
        echo "✓ {$pkg} installed<br>";
        @unlink($tmpZip);
        return true;
    } else {
        echo "✗ Cannot extract zip for {$pkg}<br>";
        @unlink($tmpZip);
        return false;
    }
}

echo "<h2>Download Missing Packages</h2>";

// Check what's missing by scanning autoload files
$composerDir = $vendorDir . '/composer';
$staticFile = $composerDir . '/autoload_static.php';
$missing = [];

// Scan all __DIR__ . '/..' . '/path' entries and check if file exists
$content = file_get_contents($staticFile);
preg_match_all("/__DIR__\s*\.\s*'\/\.\.'\s*\.\s*'([^']+)'/", $content, $matches);
$foundMissing = 0;
foreach ($matches[1] as $relPath) {
    $fullPath = $vendorDir . $relPath;
    if (!file_exists($fullPath)) {
        echo "Missing: {$relPath}<br>";
        $foundMissing++;
    }
}

if ($foundMissing === 0) {
    echo "No missing files found in autoload_static.php!<br>";
}

// Also scan autoload_files.php
$filesFile = $composerDir . '/autoload_files.php';
if (file_exists($filesFile)) {
    $fcontent = file_get_contents($filesFile);
    preg_match_all("/__DIR__\s*\.\s*'\/\.\.'\s*\.\s*'([^']+)'/", $fcontent, $fmatches);
    foreach ($fmatches[1] as $relPath) {
        $fullPath = $vendorDir . $relPath;
        if (!file_exists($fullPath)) {
            echo "Missing file: {$relPath}<br>";
            $foundMissing++;
        }
    }
}

echo "<br>Total missing: {$foundMissing}<br>";

// Download thecodingmachine/safe
$target = $vendorDir . '/thecodingmachine/safe';
if (!file_exists($target . '/lib/special_cases.php')) {
    echo "<br>Installing thecodingmachine/safe...<br>";
    downloadPackage('thecodingmachine/safe', $target);
} else {
    echo "thecodingmachine/safe already OK<br>";
}

// Check again
echo "<br><strong>After install check:</strong><br>";
if (file_exists($vendorDir . '/thecodingmachine/safe/lib/special_cases.php')) {
    echo "✓ special_cases.php exists now!<br>";
} else {
    echo "✗ Still missing<br>";
}

echo "<br><a href='/'>Test homepage</a>";
