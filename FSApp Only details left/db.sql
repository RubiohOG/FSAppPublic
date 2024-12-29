
-- Script para crear la base de datos o resetearla

-- Si no existe creamos el usuario de la base de datos
CREATE USER IF NOT EXISTS 'tswuser'@'localhost' IDENTIFIED BY 'tswblogpass';
GRANT ALL PRIVILEGES ON *.* TO 'tswuser'@'localhost';
FLUSH PRIVILEGES;

-- Creación de la base de datos
DROP DATABASE IF EXISTS `tswblog`;
CREATE DATABASE `tswblog`;

-- Selección de la base de datos
USE `tswblog`;

-- Creación de las tablas --

-- Tabla de usuarios
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `username` varchar(255) NOT NULL,
    `passwd` varchar(255) DEFAULT NULL,
    `email` varchar(255) NOT NULL,
    PRIMARY KEY (`username`)
);

-- Tabla de proyectos
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
    `project_id` int NOT NULL AUTO_INCREMENT,
    `project_name` varchar(100) NOT NULL,
    `owner_username` varchar(50) NOT NULL,
    PRIMARY KEY (`project_id`),
    KEY `owner_username` (`owner_username`),
    CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`owner_username`) REFERENCES `users` (`username`) ON DELETE CASCADE
);

-- Tabla de usuarios de proyectos
DROP TABLE IF EXISTS `project_users`;
CREATE TABLE `project_users` (
    `project_user_id` int NOT NULL AUTO_INCREMENT,
    `project_id` int NOT NULL,
    `user_name` varchar(50) NOT NULL,
    PRIMARY KEY (`project_user_id`),
    KEY `project_id` (`project_id`),
    KEY `user_name` (`user_name`),
    CONSTRAINT `project_users_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
    CONSTRAINT `project_users_ibfk_2` FOREIGN KEY (`user_name`) REFERENCES `users` (`username`) ON DELETE CASCADE
);

-- Tabla de pagos
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
    `payment_id` int NOT NULL AUTO_INCREMENT,
    `project_id` int NOT NULL,
    `payer_username` varchar(50) NOT NULL,
    `payment_name` varchar(100) NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    PRIMARY KEY (`payment_id`),
    KEY `project_id` (`project_id`),
    KEY `payer_username` (`payer_username`),
    CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
    CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`payer_username`) REFERENCES `users` (`username`) ON DELETE CASCADE
);

-- Tabla de deudas
DROP TABLE IF EXISTS `debts`;
CREATE TABLE `debts` (
    `debt_id` int NOT NULL AUTO_INCREMENT,
    `payment_id` int DEFAULT NULL,
    `debtor_username` varchar(255) DEFAULT NULL,
    `amount` decimal(10,2) NOT NULL,
    PRIMARY KEY (`debt_id`),
    KEY `payment_id` (`payment_id`),
    KEY `debtor_username` (`debtor_username`),
    CONSTRAINT `debts_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`) ON DELETE CASCADE,
    CONSTRAINT `debts_ibfk_2` FOREIGN KEY (`debtor_username`) REFERENCES `users` (`username`) ON DELETE CASCADE
);


-- Inserción de datos de prueba --

-- Usuarios
INSERT INTO `users` (`username`, `passwd`, `email`) VALUES
    ('ivan', 'ivanpass', 'ivan@prueba.com'),
    ('brais', 'braispass', 'brais@prueba.com'),
    ('prueba', 'prueba', 'prueba@prueba.com');

-- Proyectos
INSERT INTO `projects` (`project_id`, `project_name`, `owner_username`) VALUES
    (1, 'ProyectoIvan', 'ivan'),
    (2, 'ProyectoBrais', 'brais'),
    (3, 'ProyectoPrueba', 'prueba');

-- Usuarios de proyectos
INSERT INTO `project_users` (`project_user_id`, `project_id`, `user_name`) VALUES
    (1, 1, 'ivan'),
    (2, 1, 'brais'),
    (3, 2, 'brais'),
    (4, 1, 'ivan'),
    (5, 3, 'prueba'),
    (6, 3, 'ivan'),
    (7, 3, 'brais');

-- Pagos
INSERT INTO `payments` (`payment_id`, `project_id`, `payer_username`, `payment_name`, `amount`) VALUES
    (1, 3, 'prueba', 'PagoPrueba', 30.00);

-- Deudas
INSERT INTO `debts` (`debt_id`, `payment_id`, `debtor_username`, `amount`) VALUES
    (1, 1, 'ivan', 10.00),
    (2, 1, 'brais', 10.00);


