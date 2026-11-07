<?php
// config/db.php – one place for the database connection and app settings.
// XAMPP/Laragon default: user "root", empty password. Change for production!

declare(strict_types=1);

// Folder name inside htdocs (XAMPP) or www (Laragon). Used for redirects.
// If you rename the project folder, change this line too.
const BASE_URL = '/wst2_auth';

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'wst2_auth';

// Throw exceptions on SQL errors instead of failing silently.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Never show raw database errors to users – they leak table names and paths.
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Please contact the administrator.');
}
