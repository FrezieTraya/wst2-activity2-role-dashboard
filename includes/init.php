<?php
// includes/init.php – load the database, helpers, and start a HARDENED session.
// Put   require __DIR__ . '/includes/init.php';   at the top of every page
// (from a subfolder:   require __DIR__ . '/../includes/init.php';).
//
// This file is complete – you do not need to change it.

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    // Slide 11 – Securing PHP Sessions: harden the cookie BEFORE session_start().
    // 'secure' is turned on only when the site runs on HTTPS; on http://localhost
    // a secure-only cookie would never be sent back and login would never "stick".
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    ini_set('session.use_strict_mode', '1');
    session_start();
}
