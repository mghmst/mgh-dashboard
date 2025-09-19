<?php
header('Content-Type: application/json');
$dir = $_GET['path'] ?? './'; // Default to current directory if no path is provided
// Sanitize path to prevent directory traversal
$dir = realpath($dir);
if ($dir === false || !is_dir($dir)) {
    http_response_code(404);
    echo json_encode(['error' => 'Directory not found']);
    exit;
}

$files = scandir($dir);
$result = array_map(function($item) use ($dir) {
    $path = $dir . DIRECTORY_SEPARATOR . $item;
    return [
        'name' => $item,
        'type' => is_dir($path) ? 'directory' : 'file',
        'size' => is_file($path) ? filesize($path) : null,
        'lastModified' => date('c', filemtime($path))
    ];
}, array_filter($files, fn($item) => $item !== '.' && $item !== '..'));

echo json_encode($result);
?>