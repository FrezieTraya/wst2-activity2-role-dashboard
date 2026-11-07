-- =====================================================================
-- ACTIVITY 1 – Create the users table
-- 1. In phpMyAdmin, create a database named:  wst2_auth  (collation utf8mb4_unicode_ci)
-- 2. Run this file in the SQL tab.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS wst2_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE wst2_auth;

CREATE TABLE IF NOT EXISTS users (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- username – max 30 characters, required, UNIQUE
    username         VARCHAR(30)  NOT NULL UNIQUE,

    -- email – required, UNIQUE
    email            VARCHAR(255) NOT NULL UNIQUE,

    -- password_hash – VARCHAR(255) because password_hash() output can grow
    -- when PHP changes its default algorithm. NEVER the plain password.
    password_hash    VARCHAR(255) NOT NULL,

    -- role – least privilege by default (used in Activity 2)
    role             ENUM('admin','instructor','student') NOT NULL DEFAULT 'student',

    -- created_at – when the account was made
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- BONUS (account lockout)
    failed_attempts  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    locked_until     DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DELIVERABLE: after testing, export this table
-- (phpMyAdmin > wst2_auth > users > Export > SQL) and include the .sql file.
