-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 20, 2025 at 03:57 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hris_trento`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

DROP TABLE IF EXISTS `apps`;
CREATE TABLE IF NOT EXISTS `apps` (
  `appID` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`appID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_actions`
--

DROP TABLE IF EXISTS `app_actions`;
CREATE TABLE IF NOT EXISTS `app_actions` (
  `appActionID` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `appID` tinyint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`appActionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_modules`
--

DROP TABLE IF EXISTS `app_modules`;
CREATE TABLE IF NOT EXISTS `app_modules` (
  `appModuleID` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `appID` tinyint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `rank` tinyint NOT NULL,
  PRIMARY KEY (`appModuleID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_module_actions`
--

DROP TABLE IF EXISTS `app_module_actions`;
CREATE TABLE IF NOT EXISTS `app_module_actions` (
  `appModuleActionID` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
  `appModuleID` smallint UNSIGNED NOT NULL,
  `appActionID` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`appModuleActionID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `auditLogID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `userID` int UNSIGNED NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `ipAddress` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `userAgent` text COLLATE utf8mb4_general_ci NOT NULL,
  `appID` tinyint UNSIGNED NOT NULL,
  `appModuleActionID` mediumint UNSIGNED NOT NULL,
  `tableName` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `primaryKey` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `primaryKeyID` int UNSIGNED NOT NULL,
  `dateInserted` datetime DEFAULT NULL,
  `dataOld` json DEFAULT NULL,
  `dataNew` json DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`auditLogID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_log_details`
--

DROP TABLE IF EXISTS `audit_log_details`;
CREATE TABLE IF NOT EXISTS `audit_log_details` (
  `auditLogDetailID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `auditLogID` int UNSIGNED NOT NULL,
  `field` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `fieldName` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `valueOld` text COLLATE utf8mb4_general_ci NOT NULL,
  `valueNew` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`auditLogDetailID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `authentication_logs`
--

DROP TABLE IF EXISTS `authentication_logs`;
CREATE TABLE IF NOT EXISTS `authentication_logs` (
  `authenticationLogID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `userID` int UNSIGNED NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `ipAddress` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `userAgent` text COLLATE utf8mb4_general_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_general_ci NOT NULL,
  `dateInserted` datetime DEFAULT NULL,
  `status` tinyint NOT NULL COMMENT '-1=Failed, 0=Logout, 1=Success',
  PRIMARY KEY (`authenticationLogID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE IF NOT EXISTS `configurations` (
  `configurationID` smallint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `value` text COLLATE utf8mb4_general_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_general_ci NOT NULL,
  `isEditable` tinyint NOT NULL COMMENT '0=No, 1=Yes',
  PRIMARY KEY (`configurationID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `tokenID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `userID` int UNSIGNED NOT NULL,
  `token` text COLLATE utf8mb4_general_ci NOT NULL,
  `dateInserted` datetime DEFAULT NULL,
  `dateExpired` datetime DEFAULT NULL,
  `dateDeactivated` datetime DEFAULT NULL,
  `timeDuration` mediumint UNSIGNED NOT NULL COMMENT 'In seconds',
  `timeUsed` mediumint UNSIGNED NOT NULL COMMENT 'In seconds',
  `status` int NOT NULL COMMENT '0=Inactive, 1=Active',
  PRIMARY KEY (`tokenID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `userTypeID` tinyint UNSIGNED NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_accesses`
--

DROP TABLE IF EXISTS `user_accesses`;
CREATE TABLE IF NOT EXISTS `user_accesses` (
  `userAccessID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `userID` int UNSIGNED NOT NULL,
  `appModuleActionID` mediumint UNSIGNED NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  PRIMARY KEY (`userAccessID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

DROP TABLE IF EXISTS `user_types`;
CREATE TABLE IF NOT EXISTS `user_types` (
  `userTypeID` tinyint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`userTypeID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_type_accesses`
--

DROP TABLE IF EXISTS `user_type_accesses`;
CREATE TABLE IF NOT EXISTS `user_type_accesses` (
  `userTypeAccessID` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `userTypeID` tinyint UNSIGNED NOT NULL,
  `appModuleActionID` mediumint UNSIGNED NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  PRIMARY KEY (`userTypeAccessID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
