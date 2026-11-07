<?php
// admin/manage_users.php – ACTIVITY 2
// Access matrix:  Admin ✓   Instructor ✗   Student ✗
declare(strict_types=1);
require __DIR__ . '/../auth.php';

// Only the admin role may continue past this line.
require_role('admin');


// ---- The rest of the page already works. Do not move your check below this line! ----
$users = $conn->query('SELECT id, username, email, role, created_at FROM users ORDER BY id')
              ->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Manage Users';
require __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <h1>Manage Users <span class="badge">admin only</span></h1>
    <table>
        <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= (int) $u['id'] ?></td>
                <td><?= e($u['username']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['role']) ?></td>
                <td><?= e($u['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p class="muted">Tip: change roles for your test accounts in phpMyAdmin (users table &gt; Edit).</p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
