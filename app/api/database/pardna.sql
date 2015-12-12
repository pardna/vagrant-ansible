-- Adminer 4.2.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `crudlogs`;
CREATE TABLE `crudlogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(50) NOT NULL,
  `action` varchar(10) NOT NULL,
  `info` longtext NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `table` (`table`),
  KEY `action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL,
  `target_type` varchar(10) NOT NULL,
  `target_id` bigint(20) NOT NULL,
  `deleted` tinyint(4) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `notifications` (`id`, `message`, `target_type`, `target_id`, `deleted`, `created`, `modified`) VALUES
(1,	'Verification code sent',	'user',	1,	NULL,	'0000-00-00 00:00:00',	'2015-10-04 11:43:14'),
(2,	'Verification code sent',	'user',	1,	NULL,	'2015-10-04 11:44:03',	'2015-10-04 11:44:03'),
(3,	'Verification code sent',	'user',	1,	NULL,	'2015-10-04 11:44:52',	'2015-10-04 11:44:52'),
(4,	'Verification code sent',	'user',	1,	NULL,	'2015-10-04 17:40:03',	'2015-10-04 17:40:03'),
(5,	'Your account is verified.',	'user',	1,	NULL,	'2015-10-04 17:44:27',	'2015-10-04 17:44:27'),
(6,	'Verification code sent',	'user',	19,	NULL,	'2015-10-05 22:05:13',	'2015-10-05 22:05:13'),
(7,	'Verification code sent',	'user',	19,	NULL,	'2015-10-06 19:43:24',	'2015-10-06 19:43:24'),
(8,	'Verification code sent',	'user',	19,	NULL,	'2015-10-06 19:57:52',	'2015-10-06 19:57:52'),
(9,	'Your account is verified.',	'user',	19,	NULL,	'2015-10-06 19:58:33',	'2015-10-06 19:58:33'),
(10,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:02:00',	'2015-10-06 20:02:00'),
(11,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:02:22',	'2015-10-06 20:02:22'),
(12,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:05:48',	'2015-10-06 20:05:48'),
(13,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:05:49',	'2015-10-06 20:05:49'),
(14,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:06:40',	'2015-10-06 20:06:40'),
(15,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:08:28',	'2015-10-06 20:08:28'),
(16,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:08:28',	'2015-10-06 20:08:28'),
(17,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:09:03',	'2015-10-06 20:09:03'),
(18,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:10:41',	'2015-10-06 20:10:41'),
(19,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:10:41',	'2015-10-06 20:10:41'),
(20,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:11:06',	'2015-10-06 20:11:06'),
(21,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:12:03',	'2015-10-06 20:12:03'),
(22,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:12:28',	'2015-10-06 20:12:28'),
(23,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:15:37',	'2015-10-06 20:15:37'),
(24,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:16:41',	'2015-10-06 20:16:41'),
(25,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:17:07',	'2015-10-06 20:17:07'),
(26,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:19:33',	'2015-10-06 20:19:33'),
(27,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:19:52',	'2015-10-06 20:19:52'),
(28,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:23:37',	'2015-10-06 20:23:37'),
(29,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:24:07',	'2015-10-06 20:24:07'),
(30,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:25:52',	'2015-10-06 20:25:52'),
(31,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:26:41',	'2015-10-06 20:26:41'),
(32,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:27:03',	'2015-10-06 20:27:03'),
(33,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:28:42',	'2015-10-06 20:28:42'),
(34,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:29:54',	'2015-10-06 20:29:54'),
(35,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:30:08',	'2015-10-06 20:30:08'),
(36,	'Verification code sent',	'user',	6,	NULL,	'2015-10-06 20:32:58',	'2015-10-06 20:32:58'),
(37,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-06 20:33:22',	'2015-10-06 20:33:22'),
(38,	'Verification code sent',	'user',	6,	NULL,	'2015-10-08 21:04:02',	'2015-10-08 21:04:02'),
(39,	'Your account is verified.',	'user',	6,	NULL,	'2015-10-08 21:04:24',	'2015-10-08 21:04:24'),
(40,	'Verification code sent',	'user',	20,	NULL,	'2015-10-10 13:11:05',	'2015-10-10 13:11:05'),
(41,	'Your account is verified.',	'user',	20,	NULL,	'2015-10-10 13:11:18',	'2015-10-10 13:11:18'),
(42,	'Verification code sent',	'user',	21,	NULL,	'2015-10-10 14:31:14',	'2015-10-10 14:31:14'),
(43,	'Your account is verified.',	'user',	21,	NULL,	'2015-10-10 14:32:03',	'2015-10-10 14:32:03');

DROP TABLE IF EXISTS `pardanagroup_payments`;
CREATE TABLE `pardanagroup_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paytype` varchar(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `account` varchar(20) DEFAULT NULL,
  `sortcode` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paydate` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pardnagroups`;
CREATE TABLE `pardnagroups` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `admin` bigint(20) NOT NULL,
  `account` varchar(50) DEFAULT NULL,
  `sortcode` varchar(25) DEFAULT NULL,
  `number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `frequency` varchar(30) NOT NULL,
  `startdate` datetime NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin` (`admin`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pardnagroup_members`;
CREATE TABLE `pardnagroup_members` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `fullname` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `email` (`email`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `verified` tinyint(1) unsigned DEFAULT '0',
  `verification_code` varchar(10) DEFAULT NULL,
  `membership_number` varchar(30) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `login_count` bigint(20) unsigned DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `membership_number` (`membership_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `verified`, `verification_code`, `membership_number`, `mobile`, `fullname`, `email`, `password`, `salt`, `active`, `login_count`, `created`, `modified`) VALUES
(21,	1,	'4298',	'Y80732916',	'07956349218',	'Pa Essa Jabang',	'pjabang@gmail.com',	'b35388e44be38cbcbe7551b194b5544487e939db66125b467f47a483d4a2b387',	'7U2D6n9oH0gm8q97LT4PDDvCib3Sa3lr',	NULL,	8,	'2015-10-10 14:29:37',	'2015-10-10 14:29:37');

-- 2015-12-10 17:31:25
