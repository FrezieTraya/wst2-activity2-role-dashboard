<?php
// instructor/encode_grades.php – ACTIVITY 2
// Access matrix:  Admin ✓   Instructor ✓   Student ✗
declare(strict_types=1);
require __DIR__ . '/../auth.php';

// Allow BOTH admin and instructor (deny everyone else).
require_role('admin', 'instructor');


// ---- The rest of the page already works. ----
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
    $subject   = trim((string) ($_POST['subject'] ?? ''));
    $grade     = filter_input(INPUT_POST, 'grade', FILTER_VALIDATE_FLOAT);

    if (!$studentId) { $errors[] = 'Choose a student.'; }
    if ($subject === '') { $errors[] = 'Subject is required.'; }
    if ($grade === false || $grade === null || $grade < 0 || $grade > 100) { $errors[] = 'Grade must be 0 to 100.'; }

    if (!$errors) {
        $encodedBy = (int) $_SESSION['user_id'];
        $stmt = $conn->prepare(
            'INSERT INTO grades (student_id, subject, grade, encoded_by) VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE grade = VALUES(grade), encoded_by = VALUES(encoded_by)'
        );
        $stmt->bind_param('isdi', $studentId, $subject, $grade, $encodedBy);
        $stmt->execute();
        flash('Grade saved.');
        header('Location: encode_grades.php');
        exit;
    }
}

$students = $conn->query("SELECT id, username FROM users WHERE role = 'student' ORDER BY username")
                 ->fetch_all(MYSQLI_ASSOC);
$grades = $conn->query(
    'SELECT u.username, g.subject, g.grade, g.updated_at
     FROM grades g JOIN users u ON u.id = g.student_id ORDER BY g.updated_at DESC LIMIT 50'
)->fetch_all(MYSQLI_ASSOC);

$pageTitle = 'Encode Grades';
require __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <h1>Encode Grades <span class="badge">admin · instructor</span></h1>
    <?php if ($errors): ?>
        <ul class="errors"><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
    <?php endif; ?>

    <form method="post" class="grid-form">
        <label>Student
            <select name="student_id" required>
                <option value="">— choose —</option>
                <?php foreach ($students as $s): ?>
                    <option value="<?= (int) $s['id'] ?>"><?= e($s['username']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Subject <input type="text" name="subject" maxlength="100" required></label>
        <label>Grade (0–100) <input type="number" name="grade" min="0" max="100" step="0.01" required></label>
        <button type="submit">Save grade</button>
    </form>

    <h2>Recently encoded</h2>
    <table>
        <thead><tr><th>Student</th><th>Subject</th><th>Grade</th><th>Updated</th></tr></thead>
        <tbody>
        <?php foreach ($grades as $g): ?>
            <tr><td><?= e($g['username']) ?></td><td><?= e($g['subject']) ?></td><td><?= e($g['grade']) ?></td><td><?= e($g['updated_at']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
