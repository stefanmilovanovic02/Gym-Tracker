-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 16, 2024 at 11:28 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teretana`
--

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

DROP TABLE IF EXISTS `exercises`;
CREATE TABLE IF NOT EXISTS `exercises` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `muscle_group` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `equipment_needed` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_logs`
--

DROP TABLE IF EXISTS `exercise_logs`;
CREATE TABLE IF NOT EXISTS `exercise_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `workout_id` int NOT NULL,
  `exercise_id` int NOT NULL,
  `sets` int NOT NULL,
  `reps` int NOT NULL,
  `weight` float NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workout_id` (`workout_id`),
  KEY `exercise_id` (`exercise_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nutrition`
--

DROP TABLE IF EXISTS `nutrition`;
CREATE TABLE IF NOT EXISTS `nutrition` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `calories` int DEFAULT NULL,
  `protein` float DEFAULT NULL,
  `carbs` float DEFAULT NULL,
  `fats` float DEFAULT NULL,
  `creatine` float DEFAULT NULL,
  `water` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nutrition`
--

INSERT INTO `nutrition` (`id`, `user_id`, `date`, `calories`, `protein`, `carbs`, `fats`, `creatine`, `water`) VALUES
(1, 1, '2024-06-16', 3100, 120, 201, 60, 5, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `nutrition_log`
--

DROP TABLE IF EXISTS `nutrition_log`;
CREATE TABLE IF NOT EXISTS `nutrition_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `calories` int DEFAULT NULL,
  `protein` int DEFAULT NULL,
  `carbs` int DEFAULT NULL,
  `fats` int DEFAULT NULL,
  `water` int DEFAULT NULL,
  `creatine` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `height` int DEFAULT NULL,
  `sex` enum('Male','Female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starting_weight` decimal(5,2) DEFAULT NULL,
  `current_weight` decimal(5,2) DEFAULT NULL,
  `goal_weight` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `height`, `sex`, `starting_weight`, `current_weight`, `goal_weight`) VALUES
(1, 'stefan_milovanovic', 'stefanmilovanovic989@gmail.com', '$2y$10$lmLzfot/6Y1sLWGhuso4oeAdL9.eIgLomwliXKrPJ9ZB8YZKike9a', '2024-06-13 12:23:23', 185, 'Male', 60.00, 60.00, 75.00),
(3, 'stefan2043', 'gamersever55@gmail.com', '$2y$10$GwudKd1limHA7.SgVrgny.4cU12/oKOZnz6qq1ZPUWXe1Nn1pSqkS', '2024-06-14 11:24:04', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `weights`
--

DROP TABLE IF EXISTS `weights`;
CREATE TABLE IF NOT EXISTS `weights` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `weight` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

DROP TABLE IF EXISTS `workouts`;
CREATE TABLE IF NOT EXISTS `workouts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `duration` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
