<?php
// dashboard.php – ACTIVITY 2 version: uses auth.php + require_login()
declare(strict_types=1);
require __DIR__ . '/auth.php';
require_login();

$pageTitle = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h1>Welcome, <?= e($_SESSION['username'] ?? '') ?>!</h1>
    <p>You are logged in as <strong><?= e($_SESSION['role'] ?? '') ?></strong>.</p>
    <p class="muted">Use the menu above to open the pages your role is allowed to see.</p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
