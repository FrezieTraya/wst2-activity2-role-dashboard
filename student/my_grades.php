<?php
// student/my_grades.php – ACTIVITY 2
// Access matrix:  Admin ✗   Instructor ✗   Student ✓
// Rule: a student must see ONLY their OWN record.
declare(strict_types=1);
require __DIR__ . '/../auth.php';

// Only the student role may continue.
require_role('student');


// FIXED (IDOR – Slide 21/22): the ID comes from the trusted SESSION, never from the URL.
//   my_grades.php?student_id=4 is now ignored – a student only ever sees their own record.
$id = (int) $_SESSION['user_id'];


// Already uses a prepared statement – keep it that way.
$stmt = $conn->prepare('SELECT subject, grade, updated_at FROM grades WHERE student_id = ? ORDER BY subject');
$stmt->bind_param('i', $id);
$stmt->execute();
$grades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'My Grades';
require __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <h1>My Grades <span class="badge">student only</span></h1>
    <p class="muted">Showing records for <strong><?= e($_SESSION['username'] ?? '') ?></strong>.</p>
    <table>
        <thead><tr><th>Subject</th><th>Grade</th><th>Last updated</th></tr></thead>
        <tbody>
        <?php foreach ($grades as $g): ?>
            <tr><td><?= e($g['subject']) ?></td><td><?= e($g['grade']) ?></td><td><?= e($g['updated_at']) ?></td></tr>
        <?php endforeach; ?>
        <?php if (!$grades): ?><tr><td colspan="3" class="muted">No grades yet.</td></tr><?php endif; ?>
        </tbody>
    </table>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
