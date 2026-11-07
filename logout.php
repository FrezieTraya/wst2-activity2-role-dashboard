<?php
// logout.php – ACTIVITY 1
// Goal: FULLY destroy the session so the old session ID becomes useless.
declare(strict_types=1);
require __DIR__ . '/includes/init.php';

// TODO 1: Clear all session variables.
$_SESSION = [];

// TODO 2: Delete the session cookie in the browser.
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

// TODO 3: Destroy the session on the server.
session_destroy();

// TODO 4: Redirect to login.php – and stop the script.
header('Location: login.php');
exit;
