CREATE TABLE `countries` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255) UNIQUE,
  `name` varchar(255)
);

CREATE TABLE `buildings` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `country_id` int,
  `name` varchar(255)
);

CREATE TABLE `departments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) UNIQUE
);

CREATE TABLE `employees` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `rfid_card_id` int UNIQUE NOT NULL
);

CREATE TABLE `rfid_cards` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `code` varchar(255) UNIQUE
);

CREATE TABLE `building_departments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `building_id` int,
  `department_id` int
);

CREATE TABLE `employee_department_access` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `employee_id` int,
  `department_id` int
);

CREATE INDEX `idx-buildings-country_id` ON `buildings` (`country_id`);

CREATE INDEX `idx-employees-rfid_card_id` ON `employees` (`rfid_card_id`);

CREATE INDEX `idx-rfid_cards-code` ON `rfid_cards` (`code`);

CREATE INDEX `idx-building_departments-building_id` ON `building_departments` (`building_id`);

CREATE INDEX `idx-building_departments-department_id` ON `building_departments` (`department_id`);

CREATE INDEX `idx-employee_department_access-employee_id` ON `employee_department_access` (`employee_id`);

CREATE INDEX `idx-employee_department_access-department_id` ON `employee_department_access` (`department_id`);

ALTER TABLE `buildings` ADD CONSTRAINT `fk_buildings_country_id_countries_id` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

ALTER TABLE `building_departments` ADD CONSTRAINT `fk_building_departments_building_id_buildings_id` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`);

ALTER TABLE `building_departments` ADD CONSTRAINT `fk_building_departments_department_id_departments_id` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

ALTER TABLE `employees` ADD CONSTRAINT `fk_employees_rfid_card_id_rfid_cards_id` FOREIGN KEY (`rfid_card_id`) REFERENCES `rfid_cards` (`id`);

ALTER TABLE `employee_department_access` ADD CONSTRAINT `fk_employee_department_access_employee_id_employees_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

ALTER TABLE `employee_department_access` ADD CONSTRAINT `fk_employee_department_access_department_id_departments_id` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
