<?php
/**
 * config.php
 * Database connection + small helpers used across the project.
 */

// --- Database connection settings (XAMPP defaults) ---
$DB_HOST = 'localhost';
$DB_NAME = 'discover_saudi';
$DB_USER = 'root';
$DB_PASS = '';   // XAMPP default root password is empty

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . htmlspecialchars($e->getMessage()));
}

/**
 * Escape output for HTML safely.
 */
function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Resolve an image source: works whether the column holds
 * a full URL (https://...) or just a filename in /uploads/.
 */
function img_src(?string $value): string {
    if (!$value) {
        return 'https://via.placeholder.com/600x400?text=No+Image';
    }
    if (preg_match('#^https?://#i', $value)) {
        return $value;
    }
    // Resolve relative to project root regardless of which page we are on
    $base = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/admin/') !== false ? '../' : '';
    return $base . 'uploads/' . $value;
}
