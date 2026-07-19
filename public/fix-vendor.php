<?php
set_time_limit(120);
$vendorDir = __DIR__ . '/../vendor';
$composerDir = $vendorDir . '/composer';

echo "<h2>Vendor Fixer</h2>";

// 1. Check & fix autoload_classmap.php
$classmapFile = $composerDir . '/autoload_classmap.php';
if (file_exists($classmapFile)) {
    $orig = file_get_contents($classmapFile);
    // Extract all class entries
    preg_match_all("/'([^']+)' => __DIR__\s*\.\s*'(.*?)'/", $orig, $matches, PREG_SET_ORDER);
    $removed = 0;
    foreach ($matches as $m) {
        $path = $composerDir . $m[2];
        if (!file_exists($path)) {
            $old = "'{$m[1]}' => __DIR__ . '{$m[2]}'";
            $orig = str_replace($old, '', $orig);
            echo "✗ Removed missing: {$m[1]}<br>";
            $removed++;
        }
    }
    // Fix trailing commas
    $orig = preg_replace('/,\s*,/', ',', $orig);
    $orig = preg_replace('/,\s*\)/', ')', $orig);
    file_put_contents($classmapFile, $orig);
    echo "✓ autoload_classmap.php: $removed entries removed<br>";
}

// 2. Check & fix autoload_static.php
$staticFile = $composerDir . '/autoload_static.php';
if (file_exists($staticFile)) {
    $content = file_get_contents($staticFile);
    // Fix classmap entries in static
    preg_match_all("/'([^']+)' => __DIR__\s*\.\s*'(.*?)'/", $content, $matches, PREG_SET_ORDER);
    $removed = 0;
    foreach ($matches as $m) {
        $path = $composerDir . $m[2];
        if (!file_exists($path)) {
            $old = "'{$m[1]}' => __DIR__ . '{$m[2]}'";
            $content = str_replace($old, '', $content);
            $removed++;
        }
    }
    // Fix PSR4 entries
    preg_match_all("/'([^']+)' => array\s*\([^)]*\)/", $content, $psrMatches, PREG_SET_ORDER);
    foreach ($psrMatches as $m) {
        $ns = $m[1];
        preg_match_all("/__DIR__\s*\.\s*'(.*?)'/", $m[2], $dirMatches);
        $dirs = [];
        foreach ($dirMatches[1] as $d) {
            $fullPath = $composerDir . $d;
            if (is_dir($fullPath)) {
                $dirs[] = "__DIR__ . '$d'";
            } else {
                echo "✗ Removed missing PSR4 dir: $ns => $d<br>";
                $removed++;
            }
        }
        if (empty($dirs)) {
            $content = str_replace($m[0], '', $content);
        } else {
            $newEntry = "'$ns' => array(" . implode(', ', $dirs) . ")";
            $content = str_replace($m[0], $newEntry, $content);
        }
    }
    // Fix trailing commas
    $content = preg_replace('/,\s*,/', ',', $content);
    $content = preg_replace('/,\s*\)/', ')', $content);
    file_put_contents($staticFile, $content);
    echo "✓ autoload_static.php: $removed entries removed<br>";
}

// 3. Check & fix autoload_psr4.php
$psr4File = $composerDir . '/autoload_psr4.php';
if (file_exists($psr4File)) {
    $orig = file_get_contents($psr4File);
    preg_match_all("/'([^']+)' => array\s*\([^)]*\)/", $orig, $matches, PREG_SET_ORDER);
    $removed = 0;
    foreach ($matches as $m) {
        $ns = $m[1];
        preg_match_all("/__DIR__\s*\.\s*'(.*?)'/", $m[2], $dirMatches);
        $dirs = [];
        foreach ($dirMatches[1] as $d) {
            $fullPath = $composerDir . $d;
            if (is_dir($fullPath)) {
                $dirs[] = "__DIR__ . '$d'";
            } else {
                echo "✗ Removed missing PSR4: $ns => $d<br>";
                $removed++;
            }
        }
        if (empty($dirs)) {
            $orig = str_replace($m[0], '', $orig);
        } else {
            $newEntry = "'$ns' => array(" . implode(', ', $dirs) . ")";
            $orig = str_replace($m[0], $newEntry, $orig);
        }
    }
    $orig = preg_replace('/,\s*,/', ',', $orig);
    $orig = preg_replace('/,\s*\)/', ')', $orig);
    file_put_contents($psr4File, $orig);
    echo "✓ autoload_psr4.php: $removed entries removed<br>";
}

// 4. Check & fix autoload_files.php
$filesFile = $composerDir . '/autoload_files.php';
if (file_exists($filesFile)) {
    $orig = file_get_contents($filesFile);
    preg_match_all("/'([^']+)' => __DIR__\s*\.\s*'(.*?)'/", $orig, $matches, PREG_SET_ORDER);
    $removed = 0;
    foreach ($matches as $m) {
        $path = $composerDir . $m[2];
        if (!file_exists($path)) {
            $old = "'{$m[1]}' => __DIR__ . '{$m[2]}'";
            $orig = str_replace($old, '', $orig);
            echo "✗ Removed missing file: {$m[1]}<br>";
            $removed++;
        }
    }
    $orig = preg_replace('/,\s*,/', ',', $orig);
    $orig = preg_replace('/,\s*\)/', ')', $orig);
    file_put_contents($filesFile, $orig);
    echo "✓ autoload_files.php: $removed entries removed<br>";
}

echo "<h3>Done! <a href='/'>Test homepage</a></h3>";
