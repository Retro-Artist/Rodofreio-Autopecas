<?php
// path_info.php
// Utility script to help diagnose path and URL configurations

// Get server information
$server_info = [
    'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'],
    'PHP_SELF' => $_SERVER['PHP_SELF'],
    'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'],
    'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'],
    'REQUEST_URI' => $_SERVER['REQUEST_URI'],
    'HTTP_HOST' => $_SERVER['HTTP_HOST'],
];

// Try to determine the base path
$current_dir = dirname($_SERVER['SCRIPT_NAME']);

// Get protocol
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

// Construct potential base URLs
$potential_base_urls = [
    $protocol . '://' . $_SERVER['HTTP_HOST'],
    $protocol . '://' . $_SERVER['HTTP_HOST'] . $current_dir,
    $protocol . '://' . $_SERVER['HTTP_HOST'] . str_replace('/public_html', '', $current_dir)
];

// Output the information
echo "<!DOCTYPE html>
<html>
<head>
    <title>Path and URL Diagnostics</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        h1, h2 { color: #333; }
    </style>
</head>
<body>
    <h1>Server Path and URL Diagnostics</h1>
    
    <h2>Server Information</h2>
    <table>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>";

foreach ($server_info as $key => $value) {
    echo "<tr>
            <td>" . htmlspecialchars($key) . "</td>
            <td>" . htmlspecialchars($value) . "</td>
          </tr>";
}

echo "</table>

    <h2>Potential Base URLs</h2>
    <table>
        <tr>
            <th>Potential Base URL</th>
        </tr>";

foreach ($potential_base_urls as $url) {
    echo "<tr>
            <td>" . htmlspecialchars($url) . "</td>
          </tr>";
}

echo "</table>

    <h2>Current Directory Structure</h2>
    <pre>";

// Function to list directory contents
function listDirectoryContents($dir) {
    $results = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        $path = $dir . '/' . $item;
        $results[] = is_dir($path) 
            ? '[DIR] ' . $item 
            : '[FILE] ' . $item;
    }
    return $results;
}

// List contents of current directory and parent directory
echo "Current Directory (" . getcwd() . "):\n";
print_r(listDirectoryContents(getcwd()));

echo "\nParent Directory:\n";
print_r(listDirectoryContents(dirname(getcwd())));

echo "</pre>
</body>
</html>";