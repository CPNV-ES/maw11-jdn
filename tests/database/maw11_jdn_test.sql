/*
 * Author: Nathan Chauveau, David Dieperink, Julien Schneider
 * Version: 19.12.2024
 * Description: SQL script to create and initialize the database for the ExerciseLooper application.
 * 
 * This script includes the creation of necessary tables for the ExerciseLooper app. 
 * It also populates the tables with initial data for testing purposes.
 *
 * Steps:
 * 1. Creates the `fields_type` table which defines the types of fields used in exercises (single-line, multi-line, etc.).
 * 2. Creates the `status` table for tracking the status of exercises (edit, answering, closed).
 * 3. Creates the `exercises` table to store exercise details, with a foreign key relation to the `status` table.
 * 4. Creates the `fields` table that stores fields related to each exercise, with foreign keys linking them to `exercises` and `fields_type`.
 * 5. Creates the `fulfillments` table to track fulfillment records associated with exercises, with a foreign key to `exercises`.
 * 6. Creates the `answers` table to store answers to fields, linking them to `fields` and `fulfillments`.
 *
 * The script also handles the disabling and re-enabling of foreign key checks to avoid errors during table drops and creations.
 *
 * Tables:
 * - `fields_type`: Stores field types (single_line, single_line_list, multi_line).
 * - `status`: Stores exercise statuses (edit, answering, closed).
 * - `exercises`: Stores exercises, with a reference to the `status` table.
 * - `fields`: Stores fields linked to exercises, with references to both `exercises` and `fields_type`.
 * - `fulfillments`: Stores fulfillment data related to exercises.
 * - `answers`: Stores answers to fields, associated with fulfillments and fields.
 *
 * This script inserts sample data for each table to allow for immediate use and testing.
 */

CREATE DATABASE IF NOT EXISTS `maw11-jdn`;
USE `maw11-jdn`;

-- Temporary deactivation of foreign keys
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `answers`;
DROP TABLE IF EXISTS `fulfillments`;
DROP TABLE IF EXISTS `fields`;
DROP TABLE IF EXISTS `exercises`;
DROP TABLE IF EXISTS `status`;
DROP TABLE IF EXISTS `fields_type`;

-- Table creation
CREATE TABLE `fields_type` (
    `id_fields_type` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
);

CREATE TABLE `status` (
    `id_status` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
);

CREATE TABLE `exercises` (
    `id_exercises` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `id_status` INT,
    FOREIGN KEY (`id_status`) REFERENCES `status`(`id_status`)
);

CREATE TABLE `fields` (
    `id_fields` INT AUTO_INCREMENT PRIMARY KEY,
    `label` VARCHAR(255) NOT NULL,
    `id_exercises` INT,
    `id_fields_type` INT,
    FOREIGN KEY (`id_exercises`) REFERENCES `exercises`(`id_exercises`) ON DELETE CASCADE,
    FOREIGN KEY (`id_fields_type`) REFERENCES `fields_type`(`id_fields_type`)
);

CREATE TABLE `fulfillments` (
    `id_fulfillments` INT AUTO_INCREMENT PRIMARY KEY,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME,
    `id_exercises` INT NOT NULL,
    FOREIGN KEY (`id_exercises`) REFERENCES `exercises`(`id_exercises`) ON DELETE CASCADE
);

CREATE TABLE `answers` (
    `id_answers` INT AUTO_INCREMENT PRIMARY KEY,
    `value` TEXT,
    `id_fields` INT NOT NULL,
    `id_fulfillments` INT,
    FOREIGN KEY (`id_fields`) REFERENCES `fields`(`id_fields`) ON DELETE CASCADE,
    FOREIGN KEY (`id_fulfillments`) REFERENCES `fulfillments`(`id_fulfillments`) ON DELETE CASCADE
);

-- Data insertions
INSERT INTO `fields_type` (`id_fields_type`, `name`)
VALUES
    (1, 'single_line'),
    (2, 'single_line_list'),
    (3, 'multi_line');

INSERT INTO `status` (`id_status`, `name`)
VALUES
    (1, 'edit'),
    (2, 'answering'),
    (3, 'closed');

INSERT INTO `exercises` (`id_exercises`, `title`, `id_status`)
VALUES
    (1, 'Exercise with Status 1', 1),
    (2, 'Exercise with Status 2', 2),
    (3, 'Exercise with Status 3', 3);

INSERT INTO `fields` (`id_fields`, `label`, `id_exercises`, `id_fields_type`)
VALUES
    (1, 'Fields linked to exercise 1 with type 1', 1, 1),
    (2, 'Fields linked to exercise 1 with type 2', 1, 2),
    (3, 'Fields linked to exercise 1 with type 3', 1, 3),
    (4, 'Fields linked to exercise 2 with type 1', 2, 1),
    (5, 'Fields linked to exercise 2 with type 2', 2, 2),
    (6, 'Fields linked to exercise 2 with type 3', 2, 3),
    (7, 'Fields linked to exercise 2 with type 1', 3, 1),
    (8, 'Fields linked to exercise 2 with type 2', 3, 2),
    (9, 'Fields linked to exercise 2 with type 3', 3, 3);

INSERT INTO `fulfillments` (`id_fulfillments`, `id_exercises`, `created_at`)
VALUES
    (1, 1, '2024-11-22 07:35:02'),
    (2, 1, '2024-10-22 08:35:02'),
    (3, 2, '2024-11-22 07:35:02'),
    (4, 2, '2024-09-22 07:35:02'),
    (5, 3, '2024-11-22 06:35:02'),
    (6, 3, '2024-08-22 07:35:02');

INSERT INTO `answers` (`id_answers`, `value`, `id_fields`, `id_fulfillments`)
VALUES
    (1, 'Answer for field 1', 1, 1),
    (2, 'Answer for field 2', 2, 1),
    (3, '', 3, 1),
    (4, '', 1, 2),
    (5, '', 1, 2),
    (6, 'Answer for field 3', 3, 2),
    (7, '', 4, 3),
    (8, 'Answer for field 6', 5, 3),
    (9, 'Answer for field 6', 6, 3),
    (10, 'Answer for field 4', 4, 4),
    (11, '', 5, 4),
    (12, '', 6, 4),
    (13, 'Answer for field 7', 7, 5),
    (14, 'Answer for field 8', 8, 5),
    (15, 'Answer for field 9', 9, 5),
    (16, '', 7, 6),
    (17, '', 8, 6),
    (18, '', 9, 6);

-- Reactive foreign keys constraints
SET FOREIGN_KEY_CHECKS = 1;