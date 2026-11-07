<?php
// register.php – ACTIVITY 1
declare(strict_types=1);
require __DIR__ . '/includes/init.php';

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $email    = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirm  = (string) ($_POST['confirm_password'] ?? '');

    // TODO 1: VALIDATE on the server (never trust the browser).
    if ($username === '') {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
        $errors[] = 'Username must be 3–30 letters, numbers or underscores.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // TODO 2: Check that the username/email is not taken (prepared statement).
    if (!$errors) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $errors[] = 'That username or email is already registered.';
        }
        $stmt->close();
    }

    // TODO 3: Hash the password and INSERT (role is NOT read from the form –
    //         the database default 'student' applies = least privilege).
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $username, $email, $hash);
        $stmt->execute();
        $stmt->close();

        flash('Registration successful. You can now log in.');
        header('Location: login.php');
        exit;
    }
}

$pageTitle = 'Register';
require __DIR__ . '/includes/header.php';
?>
<section class="card narrow">
    <h1>Create an account</h1>

    <?php if ($errors): ?>
        <ul class="errors">
            <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="register.php" novalidate>
        <label>Username
            <input type="text" name="username" value="<?= e($username) ?>" required maxlength="30">
        </label>
        <label>Email
            <input type="email" name="email" value="<?= e($email) ?>" required>
        </label>
        <label>Password (min. 8 characters)
            <input type="password" name="password" required minlength="8" autocomplete="new-password">
        </label>
        <label>Confirm password
            <input type="password" name="confirm_password" required autocomplete="new-password">
        </label>
        <button type="submit">Register</button>
    </form>
    <p class="muted">Already have an account? <a href="login.php">Log in</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
