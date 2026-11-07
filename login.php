<?php
// login.php – ACTIVITY 1 (with BONUS account lockout)
declare(strict_types=1);
require __DIR__ . '/includes/init.php';

const MAX_ATTEMPTS = 5;

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    // TODO 1: Fetch the user with a PREPARED statement (never concatenate!).
    //         (locked_until > NOW() is computed by MySQL so PHP/MySQL time zones cannot disagree.)
    $stmt = $conn->prepare(
        'SELECT id, username, password_hash, role, failed_attempts,
                (locked_until IS NOT NULL AND locked_until > NOW()) AS is_locked
         FROM users WHERE username = ? LIMIT 1'
    );
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();   // null if the username does not exist
    $stmt->close();

    // BONUS: a locked account is refused even with the right password.
    $locked = $user && (int) $user['is_locked'] === 1;

    // TODO 2: Success – verify the hash, regenerate the session ID, store session data.
    if ($user && !$locked && password_verify($password, $user['password_hash'])) {
        // BONUS: reset the counters after a successful login.
        $stmt = $conn->prepare('UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?');
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $stmt->close();

        session_regenerate_id(true);              // stops session fixation
        $_SESSION['user_id']  = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        header('Location: dashboard.php');
        exit;                                     // never forget exit!
    }

    // BONUS: count a wrong password; lock for 15 minutes on the 5th failure.
    //   - If an old lock has already expired, start counting again from 1.
    //   - MySQL applies SET from left to right, so locked_until sees the NEW count.
    if ($user && !$locked) {
        $stmt = $conn->prepare(
            'UPDATE users
             SET failed_attempts = IF(locked_until IS NOT NULL AND locked_until <= NOW(), 1, failed_attempts + 1),
                 locked_until    = IF(failed_attempts >= ?, DATE_ADD(NOW(), INTERVAL 15 MINUTE), NULL)
             WHERE id = ?'
        );
        $max = MAX_ATTEMPTS;
        $stmt->bind_param('ii', $max, $user['id']);
        $stmt->execute();
        $stmt->close();
    }

    // TODO 3: ONE generic message for every failure (unknown user, wrong password, locked).
    $error = 'Invalid username or password.';
}

$pageTitle = 'Log in';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow">
    <h1>Log in</h1>
    <?php if ($error): ?><div class="errors"><?= e($error) ?></div><?php endif; ?>

    <form method="post" action="login.php">
        <label>Username
            <input type="text" name="username" value="<?= e($username) ?>" required autocomplete="username">
        </label>
        <label>Password
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit">Log in</button>
    </form>
    <p class="muted">No account yet? <a href="register.php">Register</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
