# WST2 · Activity 2 – Role-Based Dashboard

**Name:** Frezie M. Traya · **Section:** BS INFO-TECH 4F

Extends Activity 1 (secure login) with three roles – **admin, instructor, student** – and protects every page on the server. Deny by default.

## Access matrix
| Page | Admin | Instructor | Student |
|---|:-:|:-:|:-:|
| `admin/manage_users.php` | ✓ | ✗ | ✗ |
| `instructor/encode_grades.php` | ✓ | ✓ | ✗ |
| `student/my_grades.php` | ✗ | ✗ | ✓ |

## What was built
- `auth.php` – `require_login()` (redirects to `BASE_URL . '/login.php'`, then `exit`) and `require_role(string ...$roles)` (strict `in_array`, otherwise HTTP 403 + friendly page, then `exit`).
- Each protected page calls `require_role(...)` on its first line, exactly as the matrix says.
- `includes/header.php` – menu links shown by role (usability only; the real lock is `require_role()`).
- `403.php` – short, polite message with a **Go back to dashboard** button, no technical details.
- `student/my_grades.php` – the student ID comes from `$_SESSION['user_id']`, never from the URL (IDOR fixed).
- `dashboard.php` – uses `auth.php` + `require_login()`.

## Test results (all 11 passed)
| # | Logged in as | URL typed | Expected | Actual |
|---|---|---|---|---|
| 1 | traya_admin | admin/manage_users.php | Page opens | Page opens |
| 2 | traya_admin | instructor/encode_grades.php | Page opens | Page opens |
| 3 | traya_admin | student/my_grades.php | 403 page | 403 page |
| 4 | traya_instructor | admin/manage_users.php | 403 page | 403 page |
| 5 | traya_instructor | instructor/encode_grades.php | Page opens | Page opens |
| 6 | traya_instructor | student/my_grades.php | 403 page | 403 page |
| 7 | traya_student1 | admin/manage_users.php | 403 page | 403 page |
| 8 | traya_student1 | instructor/encode_grades.php | 403 page | 403 page |
| 9 | traya_student1 | student/my_grades.php | Own grades only | Own grades only |
| 10 | traya_student1 | student/my_grades.php?student_id=10 (traya_student2) | Still traya_student1's grades | Still traya_student1's grades |
| 11 | (logged out) | admin/manage_users.php | Login page | Login page |

## How to run (XAMPP)
1. Copy this folder into `C:\xampp\htdocs\wst2_auth` and start Apache + MySQL.
2. In phpMyAdmin run `database/01_users_table.sql`, then `database/02_grades_table.sql`.
3. Register four accounts at http://localhost/wst2_auth/register.php, then set the admin and instructor roles with the `UPDATE` lines in `02_grades_table.sql`.
4. Log in as the instructor and encode grades for both students.
