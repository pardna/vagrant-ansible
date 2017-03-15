-- Adminer 4.2.3 MySQL dump

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


DROP TABLE IF EXISTS `invitations`;
CREATE TABLE `invitations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `type_id` bigint(20) NOT NULL,
  `message` text,
  `sent` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `type_id` (`type_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `invitations` (`id`, `email`, `type`, `type_id`, `message`, `sent`, `created`, `modified`) VALUES
(55,	'pardna-3@pardna.com',	'USER',	21,	'I would like to invite you to join pardna',	0,	'2015-12-22 20:13:52',	'2015-12-22 20:13:52'),
(57,	'pardna-5@pardna.com',	'USER',	21,	'I would like to invite you to join pardna',	0,	'2015-12-22 20:13:52',	'2015-12-22 20:13:52'),
(58,	'pardna-1@pardna.com',	'PARDNAGROUP',	17,	'',	0,	'2015-12-22 20:17:29',	'2015-12-22 20:17:29'),
(59,	'pardna-2@pardna.com',	'PARDNAGROUP',	17,	'',	0,	'2015-12-22 20:17:29',	'2015-12-22 20:17:29'),
(60,	'pardna-3@pardna.com',	'PARDNAGROUP',	17,	'',	0,	'2015-12-22 20:17:29',	'2015-12-22 20:17:29'),
(61,	'pardna-4@pardna.com',	'PARDNAGROUP',	17,	'',	0,	'2015-12-22 20:17:29',	'2015-12-22 20:17:29'),
(62,	'pardna-5@pardna.com',	'PARDNAGROUP',	17,	'',	0,	'2015-12-22 20:17:29',	'2015-12-22 20:17:29'),
(63,	'pardna-1@pardna.com',	'PARDNAGROUP',	18,	'',	0,	'2015-12-22 21:09:11',	'2015-12-22 21:09:11'),
(64,	'pardna-2@pardna.com',	'PARDNAGROUP',	18,	'',	0,	'2015-12-22 21:09:11',	'2015-12-22 21:09:11'),
(65,	'pardna-3@pardna.com',	'PARDNAGROUP',	18,	'',	0,	'2015-12-22 21:09:11',	'2015-12-22 21:09:11'),
(67,	'pardna-1@pardna.com',	'PARDNAGROUP',	19,	'',	0,	'2015-12-23 19:18:26',	'2015-12-23 19:18:26'),
(68,	'pardna-2@pardna.com',	'PARDNAGROUP',	19,	'',	0,	'2015-12-23 19:18:26',	'2015-12-23 19:18:26'),
(69,	'pardna-3@pardna.com',	'PARDNAGROUP',	19,	'',	0,	'2015-12-23 19:18:26',	'2015-12-23 19:18:26'),
(70,	'pardna-4@pardna.com',	'PARDNAGROUP',	19,	'',	0,	'2015-12-23 19:18:26',	'2015-12-23 19:18:26'),
(71,	'pardna-5@pardna.com',	'PARDNAGROUP',	19,	'',	0,	'2015-12-23 19:18:26',	'2015-12-23 19:18:26'),
(73,	'pjabang@gmail.com',	'PARDNAGROUP',	20,	'',	0,	'2015-12-23 19:23:19',	'2015-12-23 19:23:19'),
(74,	'pardna-1@pardna.com',	'PARDNAGROUP',	20,	'',	0,	'2015-12-23 19:23:19',	'2015-12-23 19:23:19'),
(75,	'pardna-2@pardna.com',	'PARDNAGROUP',	20,	'',	0,	'2015-12-23 19:23:19',	'2015-12-23 19:23:19'),
(76,	'pjabang@gmail.com',	'PARDNAGROUP',	21,	'',	0,	'2015-12-23 19:24:53',	'2015-12-23 19:24:53'),
(77,	'pardna-1@pardna.com',	'PARDNAGROUP',	21,	'',	0,	'2015-12-23 19:24:53',	'2015-12-23 19:24:53'),
(78,	'pardna-2@pardna.com',	'PARDNAGROUP',	21,	'',	0,	'2015-12-23 19:24:53',	'2015-12-23 19:24:53'),
(79,	'pjabang@gmail.com',	'PARDNAGROUP',	22,	'',	0,	'2015-12-23 19:30:25',	'2015-12-23 19:30:25'),
(80,	'pardna-1@pardna.com',	'PARDNAGROUP',	22,	'',	0,	'2015-12-23 19:30:25',	'2015-12-23 19:30:25'),
(81,	'pardna-2@pardna.com',	'PARDNAGROUP',	22,	'',	0,	'2015-12-23 19:30:25',	'2015-12-23 19:30:25'),
(82,	'pardna-1@pardna.com',	'PARDNAGROUP',	23,	'',	0,	'2015-12-23 20:09:03',	'2015-12-23 20:09:03'),
(83,	'pardna-1@pardna.com',	'PARDNAGROUP',	26,	'',	0,	'2015-12-23 20:13:05',	'2015-12-23 20:13:05'),
(84,	'pardna-1@pardna.com',	'PARDNAGROUP',	27,	'',	0,	'2015-12-23 20:13:30',	'2015-12-23 20:13:30'),
(85,	'pjabang@gmail.com',	'PARDNAGROUP',	28,	'',	0,	'2015-12-23 20:14:40',	'2015-12-23 20:14:40'),
(86,	'pardna-4@pardna.com',	'PARDNAGROUP',	28,	'',	0,	'2015-12-23 20:14:40',	'2015-12-23 20:14:40'),
(87,	'pardna-8@pardna.com',	'PARDNAGROUP',	28,	'',	0,	'2015-12-23 20:14:40',	'2015-12-23 20:14:40'),
(88,	'pjabang@gmail.com',	'PARDNAGROUP',	29,	'',	0,	'2016-01-23 09:59:04',	'2016-01-23 09:59:04'),
(89,	'test-1@pardna.com',	'PARDNAGROUP',	30,	'',	0,	'2016-04-10 12:45:15',	'2016-04-10 12:45:15');

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

DROP TABLE IF EXISTS `pardnaaccounts`;
CREATE TABLE `pardnaaccounts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `owner` varchar(25) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnaaccounts` (`id`, `name`, `code`, `owner`, `balance`, `created`, `modified`) VALUES
(1,	'Current Account',	'4508-2509',	'U28386438',	0.00,	'2016-04-10 20:21:50',	'2016-04-10 20:21:50'),
(2,	'Current Account',	'7406-7232-0428',	'D92961773',	0.00,	'2016-04-10 20:24:37',	'2016-04-10 20:24:37');

DROP TABLE IF EXISTS `pardnaaccount_payments`;
CREATE TABLE `pardnaaccount_payments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_code` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `user` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pardnagroups`;
CREATE TABLE `pardnagroups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `admin` bigint(20) NOT NULL,
  `slots` int(3) NOT NULL,
  `account` varchar(50) DEFAULT NULL,
  `sortcode` varchar(25) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `frequency` varchar(30) NOT NULL,
  `startdate` date NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin` (`admin`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroups` (`id`, `name`, `admin`, `slots`, `account`, `sortcode`, `number`, `amount`, `frequency`, `startdate`, `created`, `modified`) VALUES
(37,	'slot 8',	21,	0,	NULL,	NULL,	NULL,	10.00,	'monthly',	'2016-04-20',	'2016-04-10 13:17:58',	'2016-04-10 13:17:58'),
(38,	'slot 1',	21,	0,	NULL,	NULL,	NULL,	10.00,	'monthly',	'2016-04-20',	'2016-04-10 13:20:31',	'2016-04-10 13:20:31'),
(39,	'slot 2',	21,	0,	NULL,	NULL,	NULL,	10.00,	'monthly',	'1970-01-01',	'2016-04-10 14:02:29',	'2016-04-10 14:02:29'),
(40,	'SLOTS 13',	21,	12,	NULL,	NULL,	NULL,	10.00,	'monthly',	'2016-04-26',	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(41,	'Slot 14',	21,	6,	NULL,	NULL,	NULL,	10.00,	'monthly',	'2016-04-27',	'2016-04-11 21:10:35',	'2016-04-11 21:10:35'),
(42,	'Slot 15',	21,	6,	NULL,	NULL,	NULL,	10.00,	'monthly',	'2016-04-30',	'2016-04-11 21:12:57',	'2016-04-11 21:12:57'),
(43,	'SLOT 17',	21,	12,	NULL,	NULL,	NULL,	50.00,	'weekly',	'2016-05-25',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36');

DROP TABLE IF EXISTS `pardnagroup_members`;
CREATE TABLE `pardnagroup_members` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `fullname` varchar(50) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `group_id` bigint(20) NOT NULL,
  `slot_id` bigint(20) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `email` (`email`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroup_members` (`id`, `email`, `user_id`, `fullname`, `mobile`, `group_id`, `slot_id`, `created`, `modified`) VALUES
(11,	'pjabang@gmail.com',	21,	'Pa Essa Jabang',	'07956349218',	38,	NULL,	'2016-04-10 13:20:31',	'2016-04-10 13:20:31'),
(12,	'pjabang@gmail.com',	21,	'Pa Essa Jabang',	'07956349218',	39,	NULL,	'2016-04-10 14:02:29',	'2016-04-10 14:02:29'),
(13,	'pjabang@gmail.com',	21,	'Pa Essa Jabang',	'07956349218',	40,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(14,	'pjabang@gmail.com',	21,	'Pa Essa Jabang',	'07956349218',	41,	NULL,	'2016-04-11 21:10:35',	'2016-04-11 21:10:35'),
(15,	'pjabang@gmail.com',	21,	'Pa Essa Jabang',	'07956349218',	42,	NULL,	'2016-04-11 21:12:57',	'2016-04-11 21:12:57'),
(16,	'pjabang@gmail.com',	21,	'Pa Essa Jabang',	'07956349218',	43,	NULL,	'2016-04-11 21:13:36',	'2016-04-11 21:13:36');

DROP TABLE IF EXISTS `pardnagroup_payments`;
CREATE TABLE `pardnagroup_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paytype` varchar(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `account` varchar(20) DEFAULT NULL,
  `sortcode` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paydate` datetime NOT NULL,
  `group_id` bigint(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pardnagroup_slots`;
CREATE TABLE `pardnagroup_slots` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pardnagroup_id` bigint(20) NOT NULL,
  `position` int(3) NOT NULL,
  `claimant` varchar(25) DEFAULT NULL,
  `claimed_date` datetime DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroup_slots` (`id`, `pardnagroup_id`, `position`, `claimant`, `claimed_date`, `pay_date`, `created`, `modified`) VALUES
(19,	6,	1,	NULL,	NULL,	NULL,	'2016-04-10 13:17:58',	'2016-04-10 13:17:58'),
(20,	6,	2,	NULL,	NULL,	NULL,	'2016-04-10 13:17:58',	'2016-04-10 13:17:58'),
(21,	6,	3,	NULL,	NULL,	NULL,	'2016-04-10 13:17:58',	'2016-04-10 13:17:58'),
(22,	6,	4,	NULL,	NULL,	NULL,	'2016-04-10 13:17:58',	'2016-04-10 13:17:58'),
(23,	6,	5,	NULL,	NULL,	NULL,	'2016-04-10 13:17:58',	'2016-04-10 13:17:58'),
(24,	6,	6,	NULL,	NULL,	NULL,	'2016-04-10 13:17:58',	'2016-04-10 13:17:58'),
(25,	38,	1,	'Y80732916',	NULL,	NULL,	'2016-04-10 13:20:31',	'2016-04-10 13:20:31'),
(26,	38,	2,	NULL,	NULL,	NULL,	'2016-04-10 13:20:31',	'2016-04-10 13:20:31'),
(27,	38,	3,	NULL,	NULL,	NULL,	'2016-04-10 13:20:31',	'2016-04-10 13:20:31'),
(28,	38,	4,	NULL,	NULL,	NULL,	'2016-04-10 13:20:31',	'2016-04-10 13:20:31'),
(29,	38,	5,	NULL,	NULL,	NULL,	'2016-04-10 13:20:31',	'2016-04-10 13:20:31'),
(30,	38,	6,	NULL,	NULL,	NULL,	'2016-04-10 13:20:31',	'2016-04-10 13:20:31'),
(31,	39,	1,	'Y80732916',	'2016-04-10 14:02:29',	NULL,	'2016-04-10 14:02:29',	'2016-04-10 14:02:29'),
(32,	39,	2,	NULL,	NULL,	NULL,	'2016-04-10 14:02:29',	'2016-04-10 14:02:29'),
(33,	39,	3,	NULL,	NULL,	NULL,	'2016-04-10 14:02:29',	'2016-04-10 14:02:29'),
(34,	39,	4,	NULL,	NULL,	NULL,	'2016-04-10 14:02:29',	'2016-04-10 14:02:29'),
(35,	39,	5,	NULL,	NULL,	NULL,	'2016-04-10 14:02:29',	'2016-04-10 14:02:29'),
(36,	39,	6,	NULL,	NULL,	NULL,	'2016-04-10 14:02:29',	'2016-04-10 14:02:29'),
(37,	40,	1,	'Y80732916',	'2016-04-10 19:40:55',	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(38,	40,	2,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(39,	40,	3,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(40,	40,	4,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(41,	40,	5,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(42,	40,	6,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(43,	40,	7,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(44,	40,	8,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(45,	40,	9,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(46,	40,	10,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(47,	40,	11,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(48,	40,	12,	NULL,	NULL,	NULL,	'2016-04-10 19:40:55',	'2016-04-10 19:40:55'),
(49,	41,	1,	'Y80732916',	'2016-04-11 21:10:35',	'2016-05-27',	'2016-04-11 21:10:35',	'2016-04-11 21:10:35'),
(50,	41,	2,	NULL,	NULL,	'2016-05-27',	'2016-04-11 21:10:35',	'2016-04-11 21:10:35'),
(51,	41,	3,	NULL,	NULL,	'2016-05-27',	'2016-04-11 21:10:35',	'2016-04-11 21:10:35'),
(52,	41,	4,	NULL,	NULL,	'2016-05-27',	'2016-04-11 21:10:35',	'2016-04-11 21:10:35'),
(53,	41,	5,	NULL,	NULL,	'2016-05-27',	'2016-04-11 21:10:35',	'2016-04-11 21:10:35'),
(54,	41,	6,	NULL,	NULL,	'2016-05-27',	'2016-04-11 21:10:35',	'2016-04-11 21:10:35'),
(55,	42,	1,	'Y80732916',	'2016-04-11 21:12:57',	'2016-05-30',	'2016-04-11 21:12:57',	'2016-04-11 21:12:57'),
(56,	42,	2,	NULL,	NULL,	'2016-06-30',	'2016-04-11 21:12:57',	'2016-04-11 21:12:57'),
(57,	42,	3,	NULL,	NULL,	'2016-07-30',	'2016-04-11 21:12:57',	'2016-04-11 21:12:57'),
(58,	42,	4,	NULL,	NULL,	'2016-08-30',	'2016-04-11 21:12:57',	'2016-04-11 21:12:57'),
(59,	42,	5,	NULL,	NULL,	'2016-09-30',	'2016-04-11 21:12:57',	'2016-04-11 21:12:57'),
(60,	42,	6,	NULL,	NULL,	'2016-10-30',	'2016-04-11 21:12:57',	'2016-04-11 21:12:57'),
(61,	43,	1,	'Y80732916',	'2016-04-11 21:13:36',	'2016-06-01',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(62,	43,	2,	NULL,	NULL,	'2016-06-08',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(63,	43,	3,	NULL,	NULL,	'2016-06-15',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(64,	43,	4,	NULL,	NULL,	'2016-06-22',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(65,	43,	5,	NULL,	NULL,	'2016-06-29',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(66,	43,	6,	NULL,	NULL,	'2016-07-06',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(67,	43,	7,	NULL,	NULL,	'2016-07-13',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(68,	43,	8,	NULL,	NULL,	'2016-07-20',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(69,	43,	9,	NULL,	NULL,	'2016-07-27',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(70,	43,	10,	NULL,	NULL,	'2016-08-03',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(71,	43,	11,	NULL,	NULL,	'2016-08-10',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36'),
(72,	43,	12,	NULL,	NULL,	'2016-08-17',	'2016-04-11 21:13:36',	'2016-04-11 21:13:36');

DROP TABLE IF EXISTS `relationships`;
CREATE TABLE `relationships` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_1` bigint(20) NOT NULL,
  `fullname_1` varchar(50) NOT NULL,
  `user_2` bigint(20) NOT NULL,
  `fullname_2` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_1` (`user_1`),
  KEY `user_2` (`user_2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `relationships` (`id`, `user_1`, `fullname_1`, `user_2`, `fullname_2`, `status`, `created`, `modified`) VALUES
(2,	21,	'Pa Essa Jabang',	23,	'Pardna Two',	'ACCEPTED',	'2015-12-23 18:09:15',	'2015-12-23 18:09:15'),
(3,	21,	'Pa Essa Jabang',	22,	'Pardna One',	'ACCEPTED',	'2015-12-23 18:42:41',	'2015-12-23 18:42:41'),
(4,	22,	'Pardna One',	23,	'Pardna Two',	'ACCEPTED',	'2015-12-23 19:08:25',	'2015-12-23 19:08:25'),
(5,	21,	'Pa Essa Jabang',	24,	'Pardna Four',	'ACCEPTED',	'2015-12-23 19:19:43',	'2015-12-23 19:19:43'),
(6,	21,	'Pa Essa Jabang',	25,	'Pardna Ten',	'ACCEPTED',	'2015-12-23 19:33:27',	'2015-12-23 19:33:27');

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
(21,	1,	'4298',	'Y80732916',	'07956349218',	'Pa Essa Jabang',	'pjabang@gmail.com',	'b35388e44be38cbcbe7551b194b5544487e939db66125b467f47a483d4a2b387',	'7U2D6n9oH0gm8q97LT4PDDvCib3Sa3lr',	NULL,	36,	'2015-10-10 14:29:37',	'2015-10-10 14:29:37'),
(22,	0,	NULL,	'A54110124',	NULL,	'Pardna One',	'pardna-1@pardna.com',	'5f502c7813fd4b5db1d2976d73504d682129d2d3fea4dae547f2b29d6ed83349',	'Dt9TaS2Cxv8mKiJiLtzUNvtB5bQfvWrG',	NULL,	3,	'2015-12-21 20:28:36',	'2015-12-21 20:28:36'),
(23,	0,	NULL,	'Z97002066',	NULL,	'Pardna Two',	'pardna-2@pardna.com',	'50268aaf586419eebf0ca3df59d6616b078d1636ac170d8d138ebc49f72271ca',	'5VDHchnMog3GCnViTrQQwIWWVXYx2h2r',	NULL,	5,	'2015-12-22 20:14:54',	'2015-12-22 20:14:54'),
(24,	0,	NULL,	'B14113225',	NULL,	'Pardna Four',	'pardna-4@pardna.com',	'b7cd02ebd5eead5da923f66075f9d1c9b038ead4e0d0dddebc3e6850db5343db',	'OZwP6BYOB6Fl3WnyDUfwlFfmOCliRZ9P',	NULL,	1,	'2015-12-23 19:19:17',	'2015-12-23 19:19:17'),
(25,	0,	NULL,	'V02625717',	NULL,	'Pardna Ten',	'pardna-10@pardna.com',	'2e05e81828705c441564651a58942678dcd57dee18e2bcbacd44c2fe713d15b8',	'2Q5XT5O5SpYtr58sRHpYVDxfs2XfVDJB',	NULL,	3,	'2015-12-23 19:21:08',	'2015-12-23 19:21:08'),
(26,	0,	NULL,	'Q53010357',	NULL,	'Pardna 20',	'pardna-20@pardna.com',	'e5fa557076eac645ce8fb0085968d591ebec3dfc7337b28d55e5d75cb4a7d9ea',	'GP6KzydwdW9CS3OeTeOPlrqklkpJsuF6',	NULL,	0,	'2016-04-10 20:20:38',	'2016-04-10 20:20:38'),
(27,	0,	NULL,	'U28386438',	NULL,	'Pardna 21',	'pardna-21@pardna.com',	'3ed6f35bf0a3a39faee79d08ee8bfe78148f032ca4b5366226863ba4377b3ba2',	'o3ZuJPnNsHPnn9194Xk0IELDTRlTFnEy',	NULL,	0,	'2016-04-10 20:21:50',	'2016-04-10 20:21:50'),
(28,	0,	NULL,	'D92961773',	NULL,	'Pardna 25',	'pardna25@pardna.com',	'915e7fecf2de70ebfa071068cd21a20afb399f15da03e5ecc9f28c106d8f8f6d',	'TCk2ZLTsaRHGCTtDyrFBNSPYAHf74GLd',	NULL,	1,	'2016-04-10 20:24:37',	'2016-04-10 20:24:37');

-- 2016-05-03 20:20:04
