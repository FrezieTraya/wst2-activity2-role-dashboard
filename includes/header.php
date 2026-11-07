<?php
// includes/header.php – ACTIVITY 2 version (replace your Activity 1 header.php with this file).
// Show menu links based on the user's role.
// REMEMBER: hiding a link is only for USABILITY. The real lock is require_role() on each page.
declare(strict_types=1);
$loggedIn = !empty($_SESSION['user_id']);
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'WST2 Secure App') ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= BASE_URL ?>/dashboard.php">WST2 Secure App</a>
    <?php if ($loggedIn): ?>
        <nav>
            <a href="<?= BASE_URL ?>/dashboard.php">Dashboard</a>

            <?php if ($role === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin/manage_users.php">Manage Users</a>
            <?php endif; ?>

            <?php if (in_array($role, ['admin', 'instructor'], true)): ?>
                <a href="<?= BASE_URL ?>/instructor/encode_grades.php">Encode Grades</a>
            <?php endif; ?>

            <?php if ($role === 'student'): ?>
                <a href="<?= BASE_URL ?>/student/my_grades.php">My Grades</a>
            <?php endif; ?>

            <?php // Activity 3 forum – every logged-in user (the page checks require_login()). ?>
            <?php if (is_file(__DIR__ . '/../posts.php')): ?>
                <a href="<?= BASE_URL ?>/posts.php">Forum</a>
            <?php endif; ?>

            <span class="who"><?= e($_SESSION['username'] ?? '') ?> (<?= e($role) ?>)</span>
            <a class="btn-small" href="<?= BASE_URL ?>/logout.php">Log out</a>
        </nav>
    <?php endif; ?>
</header>
<main class="container">
<?php if ($msg = flash()): ?>
    <div class="alert"><?= e($msg) ?></div>
<?php endif; ?>
