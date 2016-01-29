-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 29, 2013 at 05:28 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cosentium`
--

-- --------------------------------------------------------
--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(50) NOT NULL,
  `employee_strength` varchar(50) NOT NULL,
  `phone` int(15) NOT NULL,
  `job_title` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `last_modified_by` int(10) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `status` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_name` (`company_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `employee_strength`, `phone`, `job_title`, `created`, `modified`, `last_modified_by`, `expiry_date`, `status`) VALUES
(3, 'Cybage', '10,001-25,001', 1234567890, 'Job Title', '2013-07-25 14:36:36', '2013-07-25 15:10:01', NULL, NULL, b'1'),
(4, 'IBM', '6-15', 1234567890, 'SE', '2013-06-25 10:26:27', '2013-07-25 15:30:16', NULL, NULL, b'1'),
(5, 'Cybage1', '151-500', 123456789, 'Job Title', '2013-07-26 14:50:25', '2013-07-26 14:50:25', NULL, NULL, b'1'),
(9, 'Cybage2', '51-150', 1234567890, 'Job Title', '2013-07-26 16:55:30', '2013-07-26 17:46:27', NULL, '2013-08-25 16:55:30', b'1'),
(10, 'Test', '501-2,500', 123456789, 'Job Title', '2013-07-29 15:30:16', '2013-07-29 15:37:05', NULL, '2013-08-28 15:30:16', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Sys Admin'),
(2, 'Legal'),
(3, 'Deal Owner'),
(4, 'Executive'),
(5, 'Doc Admin'),
(6, 'Sales Mgr'),
(7, 'Doc Admin'),
(8, 'Sales Mgr');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`,`company_id`,`role_id`,`permission_id`),
  KEY `company_id` (`company_id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_questions`
--

CREATE TABLE IF NOT EXISTS `security_questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `security_questions`
--

INSERT INTO `security_questions` (`id`, `question`) VALUES
(1, '--No Selection--'),
(2, 'What city were you born in?'),
(3, 'What was the name of your first pet?'),
(4, 'What is your mother''s middle name?'),
(5, 'What is the name of an elementary school you attended?');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `first_name` char(50) NOT NULL,
  `last_name` char(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `last_modified_by` int(10) DEFAULT NULL,
  `last_access_dt` datetime DEFAULT NULL,
  `last_unsuccess_access_dt` datetime DEFAULT NULL,
  `unsuccess_access_counter` int(1) DEFAULT NULL,
  `access_status` tinyint(4) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `last_unsuccess_security_access_dt` datetime DEFAULT NULL,
  `unsuccess_security_counter` bit(1) DEFAULT NULL,
  `security_question_id` int(10) unsigned DEFAULT NULL,
  `security_answer` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `security_question_id` (`security_question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `created`, `modified`, `last_modified_by`, `last_access_dt`, `last_unsuccess_access_dt`, `unsuccess_access_counter`, `access_status`, `token`, `last_unsuccess_security_access_dt`, `unsuccess_security_counter`, `security_question_id`, `security_answer`) VALUES
(3, 'rahulth@cybage.com', '719855e8f4ebd94341277b0b0d50b75c5187133f', 'Rahul', 'Thipse', '2013-07-24 17:10:01', '2013-07-29 17:13:55', NULL, NULL, '2013-07-29 17:11:15', 5, 3, '3e70ce10ad96d611d70d7daca21bbb2c', NULL, NULL, 2, 'Test@123'),
(4, 'kanchanb@cybage.com', 'b949843935ebe99140feba2804a5eb06687a404e', 'Kanchan', 'Banabakode', '2013-07-25 15:30:16', '2013-07-29 17:23:39', NULL, NULL, NULL, 0, 1, '4872216d19baa5d3a80cde97b0a3bb0d', NULL, b'0', NULL, 'Test@123'),
(5, 'abhayl@cybage.com', NULL, 'Abhay', 'Lalpotu', '2013-07-26 14:50:25', '2013-07-26 14:50:25', NULL, NULL, NULL, NULL, 0, '1abf6160edf64c432447c4b28d575c08', NULL, NULL, NULL, NULL),
(9, 'rthipse@cybage.com', '719855e8f4ebd94341277b0b0d50b75c5187133f', 'Rahul', 'Thipse', '2013-07-26 16:55:30', '2013-07-26 18:11:10', NULL, NULL, NULL, 0, 1, '6e284822db0c2382d049f709b00bb92d', NULL, NULL, 2, '719855e8f4ebd94341277b0b0d50b75c5187133f'),
(10, 'test@test.com', '719855e8f4ebd94341277b0b0d50b75c5187133f', 'Test', 'Test', '2013-07-29 15:30:16', '2013-07-29 15:37:05', NULL, NULL, NULL, NULL, 1, 'e6e390e85ab26e81be7f5e64029df775', NULL, NULL, 2, '719855e8f4ebd94341277b0b0d50b75c5187133f');

-- --------------------------------------------------------

--
-- Table structure for table `user_companies`
--

CREATE TABLE IF NOT EXISTS `user_companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `status` int(1) NOT NULL,
  `delegate_to` int(10) DEFAULT NULL,
  `delegation_duration` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `user_companies`
--

INSERT INTO `user_companies` (`id`, `user_id`, `company_id`, `role_id`, `status`, `delegate_to`, `delegation_duration`) VALUES
(2, 3, 3, 1, 1, NULL, NULL),
(3, 4, 4, 1, 1, NULL, NULL),
(4, 5, 4, 1, 0, NULL, NULL),
(8, 9, 9, 1, 1, NULL, NULL),
(9, 10, 10, 1, 1, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `role_permissions_ibfk_3` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`security_question_id`) REFERENCES `security_questions` (`id`);

--
-- Constraints for table `user_companies`
--
ALTER TABLE `user_companies`
  ADD CONSTRAINT `user_companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_companies_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `user_companies_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
