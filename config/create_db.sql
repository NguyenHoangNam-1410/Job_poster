-- Create the database
CREATE DATABASE job_poster;
USE job_poster;

CREATE TABLE `LOGIN` (
    `UID` INT AUTO_INCREMENT PRIMARY KEY,
    `Email` VARCHAR(255) NOT NULL,
    `Password` VARCHAR(255) NOT NULL,
    `Role` ENUM('Customer', 'Admin') NOT NULL,
    `Name` VARCHAR(255) NOT NULL,
    `Avatar` TEXT,
    UNIQUE (`Email`)
);

CREATE TABLE `DISCOUNT_COUPON` (
    `Discount_ID` INT AUTO_INCREMENT PRIMARY KEY,
    `Code` VARCHAR(50) UNIQUE,
    `MoneyDeduct` DECIMAL(10,2) NOT NULL,
    `Condition` TEXT,
    `Quantity` INT,
    `Status` ENUM('Activate','Outdated','Deleted') NOT NULL DEFAULT 'Activate'
);
