<?php
$uri = $_SERVER['REQUEST_URI'];
$path = preg_replace('#^/uploads/#', '', $uri);
$path = str_replace(['..', "\0"], '', $path);
$file = __DIR__ . '/../../storage/app/public/' . $path;
if (file_exists($file)) {
    $mime = mime_content_type($file) ?: 'application/octet-stream';
    header("Content-Type: $mime");
    header("Content-Length: " . filesize($file));
    readfile($file);
    exit;
}
http_response_code(404);
echo 'Not Found';
