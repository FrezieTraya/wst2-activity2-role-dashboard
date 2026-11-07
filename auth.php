<?php
// auth.php – ACTIVITY 2. Include on EVERY protected page.
// Based on Slide 20 "Enforcing Access Control in PHP".
//
// Usage at the top of a page in a subfolder:
//     require __DIR__ . '/../auth.php';
//     require_role('admin');
declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';   // db + helpers + session_start()

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;   // header() alone does NOT stop the script!
    }
}

function require_role(string ...$roles): void
{
    require_login();

    // Deny by default: if the role is not on the list, the answer is NO. Strict comparison.
    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        http_response_code(403);
        require __DIR__ . '/403.php';   // friendly page
        exit;
    }
}
