-- Create the database
DROP DATABASE IF EXISTS job_poster;
CREATE DATABASE job_poster CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE job_poster;

-- Users table (expanded)
CREATE TABLE `USERS` (
    `UID` INT AUTO_INCREMENT PRIMARY KEY,
    `Email` VARCHAR(255) NOT NULL,
    `Password` VARCHAR(255) NOT NULL,
    `Role` ENUM('Customer','Admin','Employer') NOT NULL DEFAULT 'Customer',
    `Name` VARCHAR(255) NOT NULL,
    `Avatar` TEXT,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Employers table (1:1 with a user who is an employer)
CREATE TABLE `EMPLOYERS` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `company_name` VARCHAR(255) NOT NULL,
    `website` VARCHAR(255),
    `logo` TEXT,
    `contact_phone` VARCHAR(50),
    `contact_email` VARCHAR(255),
    `contact_person` VARCHAR(255),
    `description` TEXT,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `USERS`(`UID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs table (posted_by -> user, employer_id -> employer)
CREATE TABLE `JOBS` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employer_id` INT NOT NULL,
    `posted_by` INT NULL,
    `title` VARCHAR(255) NOT NULL,
    `location` VARCHAR(255),
    `description` TEXT,
    `requirements` TEXT,
    `salary` DECIMAL(10,2),
    `deadline` DATETIME,
    `status` ENUM('open','closed','draft','paused') NOT NULL DEFAULT 'open',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`employer_id`) REFERENCES `EMPLOYERS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`posted_by`) REFERENCES `USERS`(`UID`) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX (`employer_id`),
    INDEX (`posted_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Job categories (master)
CREATE TABLE `JOB_CATEGORIES` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_name` VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Many-to-many: Jobs <-> Categories
CREATE TABLE `JOB_CATEGORY_MAP` (
    `job_id` INT NOT NULL,
    `category_id` INT NOT NULL,
    PRIMARY KEY (`job_id`,`category_id`),
    FOREIGN KEY (`job_id`) REFERENCES `JOBS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `JOB_CATEGORIES`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Staff actions (many-to-many with details)
CREATE TABLE `STAFF_ACTIONS` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `job_id` INT NOT NULL,
    `action_type` VARCHAR(100) NOT NULL,
    `action_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `action_note` TEXT,
    FOREIGN KEY (`user_id`) REFERENCES `USERS`(`UID`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`job_id`) REFERENCES `JOBS`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX (`user_id`),
    INDEX (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `FEEDBACK` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `comments` TEXT,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `USERS`(`UID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;