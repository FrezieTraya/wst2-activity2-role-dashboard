<?php
// 403.php – ACTIVITY 2: a FRIENDLY "access denied" page.
// require_role() includes this file after sending the 403 status code.
declare(strict_types=1);
require_once __DIR__ . '/includes/init.php';
if (http_response_code() !== 403) {
    http_response_code(403);   // also correct if someone opens 403.php directly
}

$pageTitle = '403 – Access denied';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow center">
    <p class="big-code">403</p>

    <h1>Access denied</h1>
    <p>Sorry, you do not have permission to open this page.</p>
    <p class="muted">If you think this is a mistake, please contact your administrator.</p>

    <a class="button" href="<?= BASE_URL ?>/dashboard.php">Go back to dashboard</a>

</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
