# Create the test script
@'
<?php
require __DIR__."/vendor/autoload.php";

echo "=== Checking Environment ===\n";

// Check .env file directly
$envPath = __DIR__."/.env";
if (file_exists($envPath)) {
    echo "1. .env file exists: YES\n";
    $content = file_get_contents($envPath);
    if (strpos($content, "APP_TIMEZONE=Asia/Manila") !== false) {
        echo "2. APP_TIMEZONE=Asia/Manila found: YES\n";
    } else {
        echo "2. APP_TIMEZONE=Asia/Manila found: NO\n";
        echo "   Content around APP_TIMEZONE:\n";
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (strpos($line, "APP_TIMEZONE") !== false) {
                echo "   -> " . $line . "\n";
            }
        }
    }
} else {
    echo "1. .env file exists: NO\n";
}

// Check $_ENV superglobal
echo "3. \$_ENV['APP_TIMEZONE']: " . ($_ENV["APP_TIMEZONE"] ?? "NOT SET") . "\n";

// Check $_SERVER superglobal  
echo "4. \$_SERVER['APP_TIMEZONE']: " . ($_SERVER["APP_TIMEZONE"] ?? "NOT SET") . "\n";

// Check getenv()
echo "5. getenv('APP_TIMEZONE'): " . (getenv("APP_TIMEZONE") ?: "NOT SET") . "\n";

// Also check if we can parse .env manually
echo "\n6. Manual .env parsing:\n";
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, "APP_TIMEZONE") === 0) {
            $parts = explode("=", $line, 2);
            if (count($parts) === 2) {
                echo "   Found: " . trim($parts[0]) . " = " . trim($parts[1]) . "\n";
            }
        }
    }
}