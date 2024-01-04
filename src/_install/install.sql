CREATE DATABASE `home_inventory` COLLATE 'utf8mb4_hungarian_ci';

USE `home_inventory`;

CREATE TABLE `item` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(150) NOT NULL,
  `category_id` int NOT NULL,
  `measurement_id` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  `created_by` int NULL,
  `updated_by` int NULL,
  `deleted_by` int NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE='InnoDB';


CREATE TABLE `item_category` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  `created_by` int NULL,
  `updated_by` int NULL,
  `deleted_by` int NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE='InnoDB';


CREATE TABLE `measurement` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  `created_by` int NULL,
  `updated_by` int NULL,
  `deleted_by` int NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE='InnoDB';


CREATE TABLE `inventory` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `household_id` int NOT NULL,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `important` tinyint(1) NOT NULL DEFAULT '0',
  `min_quantity` int NULL DEFAULT '0',
  `in_shopping_list` tinyint NOT NULL DEFAULT '0',
  `req_quantity` int NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  `created_by` int NULL,
  `updated_by` int NULL,
  `deleted_by` int NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE='InnoDB';


CREATE TABLE `household` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(150) NOT NULL,
  `admin_user_id` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  `created_by` int NULL,
  `updated_by` int NULL,
  `deleted_by` int NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE='InnoDB';


CREATE TABLE `users_in_household` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int NOT NULL,
  `household_id` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  `updated_by` int NULL,
  `deleted_by` int NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE='InnoDB';


CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(20) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL
) ENGINE='InnoDB';

ALTER TABLE `user`
ADD UNIQUE `username` (`username`),
ADD UNIQUE `email` (`email`);


CREATE VIEW `shopping_list` AS
SELECT i.id, item.name, i.household_id, i.quantity, i.important, i.min_quantity, i.in_shopping_list, i.req_quantity, m.name AS measurement_name 
FROM inventory AS i
INNER JOIN item ON i.item_id = item.id 
INNER JOIN measurement AS m ON item.measurement_id = m.id 
WHERE (i.min_quantity > i.quantity AND i.important = 1) OR i.in_shopping_list = 1 AND i.deleted = 0;


CREATE TABLE `forgotten_password_token` (
  `user_id` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL
);

ALTER TABLE `forgotten_password_token`
ADD UNIQUE `user_id` (`user_id`),
ADD UNIQUE `token` (`token`);


CREATE TABLE `remember_me_token` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashed_validator` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL
);

ALTER TABLE `remember_me_token`
ADD UNIQUE `selector` (`selector`);


CREATE TABLE `invite_token` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `household_id` int NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashed_validator` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL,
  `created_by` int NOT NULL
);

ALTER TABLE `invite_token`
ADD UNIQUE `selector` (`selector`);


INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('g', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('dkg', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('kg', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('mg', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('ml', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('cl', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('dl', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('l', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('db', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `measurement` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('karton', now(), NULL, NULL, NULL, NULL, NULL, '0');



INSERT INTO `item_category` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('Élelmiszer', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `item_category` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('Háztartási cikkek', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `item_category` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('Személyes higiénia', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `item_category` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('Ruha és cipő', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `item_category` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('Technikai cikkek', now(), NULL, NULL, NULL, NULL, NULL, '0');

INSERT INTO `item_category` (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `deleted`)
VALUES ('Irodaszer', now(), NULL, NULL, NULL, NULL, NULL, '0');