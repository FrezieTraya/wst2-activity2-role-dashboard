-- =====================================================================
-- ACTIVITY 2 – grades table (run this in phpMyAdmin > wst2_auth > SQL)
-- Your users table from Activity 1 must already exist.
-- =====================================================================
USE wst2_auth;

CREATE TABLE IF NOT EXISTS grades (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id  INT UNSIGNED NOT NULL,
    subject     VARCHAR(100) NOT NULL,
    grade       DECIMAL(5,2) NOT NULL,
    encoded_by  INT UNSIGNED NOT NULL,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_student_subject (student_id, subject),
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (encoded_by) REFERENCES users(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- TEST ACCOUNTS: create at least FOUR accounts through YOUR register.php, e.g.
--     admin1, instructor1, student1, student2
-- Then set their roles here (change the usernames to yours):
-- ---------------------------------------------------------------------
-- UPDATE users SET role = 'admin'      WHERE username = 'admin1';
-- UPDATE users SET role = 'instructor' WHERE username = 'instructor1';
-- (student1 and student2 keep the default role 'student')

-- After that, log in as the instructor and encode a few grades
-- for BOTH students so you can test that each student sees only their own.
