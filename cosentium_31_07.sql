-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2013 at 03:55 PM
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
-- Table structure for table `cake_sessions`
--

CREATE TABLE IF NOT EXISTS `cake_sessions` (
  `id` varchar(255) NOT NULL,
  `DATA` text,
  `expires` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cake_sessions`
--

INSERT INTO `cake_sessions` (`id`, `DATA`, `expires`) VALUES
('5jdi8f3l48a6tb487eoirctm81', NULL, 1375269038),
('96o76kbitr3685ja5serr1lfh4', NULL, 1375269693),
('dphpts4gcv44d41oh654e60rh6', NULL, 1375269937),
('kfosg8rk39sun0msmfeoaq53a1', NULL, 1375269728);

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
  `status` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_name` (`company_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `employee_strength`, `phone`, `job_title`, `created`, `modified`, `last_modified_by`, `expiry_date`, `status`) VALUES
(2, 'Cybage', '51-150', 1234567890, 'Job Title', '2013-07-31 15:40:37', '2013-07-31 15:51:45', NULL, '2013-08-30 15:40:37', b'1'),
(3, 'IBM', '16-50', 1234567890, 'SE', '2013-07-31 15:48:35', '2013-07-31 15:48:35', NULL, '2013-08-30 15:48:35', b'1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Sys Admin'),
(2, 'Legal'),
(3, 'Deal Owner'),
(4, 'Executive'),
(5, 'Doc Admin'),
(6, 'Sales Mgr');

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
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `sid` tinyint(4) unsigned NOT NULL,
  `name` char(20) NOT NULL,
  PRIMARY KEY (`id`,`sid`),
  KEY `status_user` (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `sid`, `name`) VALUES
(1, 0, 'new'),
(2, 1, 'unlocked');

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
  `unsuccess_access_counter` tinyint(4) DEFAULT NULL,
  `access_status` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `token` varchar(255) DEFAULT NULL,
  `last_unsuccess_security_access_dt` datetime DEFAULT NULL,
  `unsuccess_security_counter` tinyint(4) DEFAULT NULL,
  `security_question_id` int(10) unsigned DEFAULT NULL,
  `security_answer` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `access_status` (`access_status`),
  KEY `security_question_id` (`security_question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `created`, `modified`, `last_modified_by`, `last_access_dt`, `last_unsuccess_access_dt`, `unsuccess_access_counter`, `access_status`, `token`, `last_unsuccess_security_access_dt`, `unsuccess_security_counter`, `security_question_id`, `security_answer`) VALUES
(2, 'rahulth@cybage.com', '719855e8f4ebd94341277b0b0d50b75c5187133f', 'Rahul', 'Thipse', '2013-07-31 15:40:37', '2013-07-31 15:52:08', NULL, '2013-07-31 15:52:08', NULL, 0, 1, '727c8018bc4c01845dc2d0dfef199d9a', NULL, NULL, 3, '719855e8f4ebd94341277b0b0d50b75c5187133f'),
(3, 'kanchanb@cybage.com', 'b949843935ebe99140feba2804a5eb06687a404e', 'Kanchan', 'Banabakode', '2013-07-31 15:48:35', '2013-07-31 15:55:36', NULL, '2013-07-31 15:55:36', NULL, 0, 1, '805498d6445ca973c4c2818895e7894c', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_companies`
--

CREATE TABLE IF NOT EXISTS `user_companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `status` bit(1) NOT NULL DEFAULT b'1',
  `delegate_to` int(10) DEFAULT NULL,
  `delegation_duration` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_companies`
--

INSERT INTO `user_companies` (`id`, `user_id`, `company_id`, `role_id`, `status`, `delegate_to`, `delegation_duration`) VALUES
(1, 2, 2, 1, b'1', NULL, NULL),
(2, 3, 3, 1, b'1', NULL, NULL);

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
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`access_status`) REFERENCES `status` (`sid`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`security_question_id`) REFERENCES `security_questions` (`id`);

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
