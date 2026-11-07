<?php
// includes/helpers.php – small helpers you may use. Complete – no changes needed.

declare(strict_types=1);

/** Escape output to prevent XSS. Use it for EVERY value you print into HTML. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** One-time message for the next page.
 *  flash('Saved!');   -> store a message
 *  flash();           -> read and remove it (header.php already does this) */
function flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }
    $msg = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $msg;
}
