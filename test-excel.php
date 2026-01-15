<?php
// test-excel.php
require __DIR__ . '/vendor/autoload.php';

echo "=== PHP Extensions Check ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Zip extension: " . (extension_loaded('zip') ? '✓ Loaded' : '✗ NOT LOADED') . "\n";
echo "XML extension: " . (extension_loaded('xml') ? '✓ Loaded' : '✗ NOT LOADED') . "\n";
echo "MBString extension: " . (extension_loaded('mbstring') ? '✓ Loaded' : '✗ NOT LOADED') . "\n";
echo "Iconv extension: " . (extension_loaded('iconv') ? '✓ Loaded' : '✗ NOT LOADED') . "\n";
echo "Fileinfo extension: " . (extension_loaded('fileinfo') ? '✓ Loaded' : '✗ NOT LOADED') . "\n\n";

echo "=== Laravel Excel Check ===\n";
try {
    if (class_exists('Maatwebsite\Excel\Excel')) {
        echo "✓ Maatwebsite/Excel package is loaded\n";
    } else {
        echo "✗ Maatwebsite/Excel package NOT found\n";
    }
} catch (Exception \) {
    echo "✗ Error checking Excel: " . \->getMessage() . "\n";
}

echo "\n=== Memory Limits ===\n";
echo "Memory limit: " . ini_get('memory_limit') . "\n";
echo "Upload max filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";

echo "\n=== Storage Permissions ===\n";
\ = __DIR__ . '/storage';
\ = __DIR__ . '/bootstrap/cache';

echo "Storage writable: " . (is_writable(\) ? '✓ Yes' : '✗ No') . "\n";
echo "Cache writable: " . (is_writable(\) ? '✓ Yes' : '✗ No') . "\n";
