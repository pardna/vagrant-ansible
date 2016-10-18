-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `configurations` (`id`, `name`, `value`) VALUES
(1,	'gocardless_success_redirect_url',	'http://192.168.33.99/app/frontend/dist/#/payment/confirm');

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


DROP TABLE IF EXISTS `gocardless_customers`;
CREATE TABLE `gocardless_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` varchar(20) NOT NULL,
  `pardnagroup_member_id` varchar(20) NOT NULL,
  `cust_bank_account` varchar(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cust_id` (`cust_id`),
  KEY `pardnagroup_member_id` (`pardnagroup_member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `gocardless_customers` (`id`, `cust_id`, `pardnagroup_member_id`, `cust_bank_account`, `created`, `modified`, `user_id`) VALUES
(1,	'CU00010BRCWA3M',	'20',	'BA0000XHQYZ9ZZ',	'2016-08-25 18:39:13',	'2016-08-25 18:39:13',	0),
(2,	'CU000115JBFE2K',	'20',	'BA0000YA23R1W5',	'2016-09-09 18:46:28',	'2016-09-09 18:46:28',	0),
(3,	'CU00011D7QMAMF',	'20',	'BA0000YHCA6D3A',	'2016-09-14 14:27:01',	'2016-09-14 14:27:01',	0),
(4,	'CU00011D8590EK',	'20',	'BA0000YHCSKS68',	'2016-09-14 14:29:05',	'2016-09-14 14:29:05',	0),
(5,	'CU00014D6ZYWYZ',	'',	'',	'2016-10-18 18:17:48',	'2016-10-18 18:17:48',	31);

DROP TABLE IF EXISTS `gocardless_mandates`;
CREATE TABLE `gocardless_mandates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` varchar(20) NOT NULL,
  `mandate_id` varchar(20) NOT NULL,
  `cust_bank_account` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cust_id` (`cust_id`),
  KEY `mandate_id` (`mandate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `gocardless_mandates` (`id`, `cust_id`, `mandate_id`, `cust_bank_account`) VALUES
(1,	'CU00010BRCWA3M',	'MD0000XXAKM579',	'BA0000XHQYZ9ZZ'),
(2,	'CU000115JBFE2K',	'MD0000YN76TTP1',	'BA0000YA23R1W5'),
(3,	'CU00011D7QMAMF',	'MD0000YWDCNB40',	'BA0000YHCA6D3A'),
(4,	'CU00011D8590EK',	'MD0000YWDW1J6H',	'BA0000YHCSKS68'),
(5,	'CU00014D6ZYWYZ',	'MD00011V9BGD0R',	'BA00011G9D1B4P');

DROP TABLE IF EXISTS `gocardless_subscriptions`;
CREATE TABLE `gocardless_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mandate_id` varchar(20) NOT NULL,
  `subscription_id` varchar(20) NOT NULL,
  `status` varchar(6) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mandate_id` (`mandate_id`),
  KEY `subscription_id` (`subscription_id`)
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
(43,	'Your account is verified.',	'user',	21,	NULL,	'2015-10-10 14:32:03',	'2015-10-10 14:32:03'),
(44,	'Verification code sent',	'user',	29,	NULL,	'2016-07-09 11:50:31',	'2016-07-09 11:50:31'),
(45,	'Your account is verified.',	'user',	29,	NULL,	'2016-07-09 11:50:40',	'2016-07-09 11:50:40'),
(46,	'Verification code sent',	'user',	31,	NULL,	'2016-08-13 10:12:30',	'2016-08-13 10:12:30'),
(47,	'Verification code sent',	'user',	31,	NULL,	'2016-08-13 10:13:56',	'2016-08-13 10:13:56'),
(48,	'Your account is verified.',	'user',	31,	NULL,	'2016-08-13 10:14:15',	'2016-08-13 10:14:15');

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
(2,	'Current Account',	'7406-7232-0428',	'D92961773',	0.00,	'2016-04-10 20:24:37',	'2016-04-10 20:24:37'),
(3,	'Current Account',	'0970-8899-9165',	'J18219886',	0.00,	'2016-07-09 11:44:37',	'2016-07-09 11:44:37'),
(4,	'Current Account',	'8722-4005-4542',	'F72586696',	0.00,	'2016-07-09 11:52:46',	'2016-07-09 11:52:46'),
(5,	'Current Account',	'3739-9752-5038',	'S89190202',	0.00,	'2016-07-09 12:00:45',	'2016-07-09 12:00:45');

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
  `currency` varchar(3) NOT NULL DEFAULT 'GBP',
  PRIMARY KEY (`id`),
  KEY `admin` (`admin`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroups` (`id`, `name`, `admin`, `slots`, `account`, `sortcode`, `number`, `amount`, `frequency`, `startdate`, `created`, `modified`, `currency`) VALUES
(49,	'test',	31,	9,	NULL,	NULL,	NULL,	50.00,	'Monthly',	'2016-09-01',	'2016-08-13 11:17:10',	'2016-08-13 11:17:10',	'GBP');

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
  `dd_mandate_id` varchar(20) NOT NULL,
  `dd_mandate_status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `email` (`email`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroup_members` (`id`, `email`, `user_id`, `fullname`, `mobile`, `group_id`, `slot_id`, `created`, `modified`, `dd_mandate_id`, `dd_mandate_status`) VALUES
(20,	'pjabang@gmail.com',	31,	'Pa Essa Jabang',	'07877068998',	49,	NULL,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10',	'',	'');

DROP TABLE IF EXISTS `pardnagroup_payments`;
CREATE TABLE `pardnagroup_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paytype` varchar(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `account` varchar(20) DEFAULT NULL,
  `sortcode` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paydate` date NOT NULL,
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
  `total_contribution` decimal(10,2) DEFAULT NULL,
  `charge_percent` decimal(10,2) DEFAULT NULL,
  `charge_amount` decimal(10,2) DEFAULT NULL,
  `pay_amount` decimal(10,2) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroup_slots` (`id`, `pardnagroup_id`, `position`, `claimant`, `claimed_date`, `pay_date`, `total_contribution`, `charge_percent`, `charge_amount`, `pay_amount`, `created`, `modified`) VALUES
(103,	49,	1,	'S89190202',	'2016-08-13 11:17:10',	'2016-10-01',	450.00,	4.50,	20.25,	429.75,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10'),
(104,	49,	2,	NULL,	NULL,	'2016-11-01',	450.00,	4.00,	18.00,	432.00,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10'),
(105,	49,	3,	NULL,	NULL,	'2016-12-01',	450.00,	3.50,	15.75,	434.25,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10'),
(106,	49,	4,	NULL,	NULL,	'2017-01-01',	450.00,	3.00,	13.50,	436.50,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10'),
(107,	49,	5,	NULL,	NULL,	'2017-02-01',	450.00,	2.50,	11.25,	438.75,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10'),
(108,	49,	6,	NULL,	NULL,	'2017-03-01',	450.00,	2.00,	9.00,	441.00,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10'),
(109,	49,	7,	NULL,	NULL,	'2017-04-01',	450.00,	1.50,	6.75,	443.25,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10'),
(110,	49,	8,	NULL,	NULL,	'2017-05-01',	450.00,	1.00,	4.50,	445.50,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10'),
(111,	49,	9,	NULL,	NULL,	'2017-06-01',	450.00,	0.00,	0.00,	450.00,	'2016-08-13 11:17:10',	'2016-08-13 11:17:10');

DROP TABLE IF EXISTS `pardnagroup_status`;
CREATE TABLE `pardnagroup_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(6) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroup_status` (`id`, `code`, `status`) VALUES
(1,	'SETRQ',	'Set up required'),
(2,	'AWTNG',	'Awaiting'),
(3,	'OHOLD',	'On hold'),
(4,	'RDSRT',	'Ready to Start'),
(5,	'CNCLD',	'Cancelled'),
(6,	'STOPD',	'Stopped'),
(7,	'ACTVE',	'Active'),
(8,	'SCESS',	'Successfully ended');

DROP TABLE IF EXISTS `pardnagroup_status_reasons`;
CREATE TABLE `pardnagroup_status_reasons` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(6) NOT NULL,
  `reason` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroup_status_reasons` (`id`, `code`, `reason`) VALUES
(1,	'EMPSL',	'Some slots are empty');

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
(6,	21,	'Pa Essa Jabang',	25,	'Pardna Ten',	'ACCEPTED',	'2015-12-23 19:33:27',	'2015-12-23 19:33:27'),
(7,	31,	'Pa Essa Jabang',	31,	'Pa Essa Jabang',	'ACCEPTED',	'2016-08-25 18:33:42',	'2016-08-25 18:33:42');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `verified` tinyint(1) unsigned DEFAULT '0',
  `verification_code` varchar(10) DEFAULT NULL,
  `membership_number` varchar(30) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
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

INSERT INTO `users` (`id`, `verified`, `verification_code`, `membership_number`, `mobile`, `fullname`, `firstname`, `lastname`, `email`, `password`, `salt`, `active`, `login_count`, `created`, `modified`) VALUES
(31,	1,	'8314',	'S89190202',	'07877068998',	'Pa Essa Jabang',	'Pa Essa',	'Jabang',	'pjabang@gmail.com',	'd54a8f52f2a8332978cee0ab4b8c9bbb7b974ef3c783c89c239e2a45d6cf3ed2',	'bDvWImBlZ0eIN4LNbeCqwm0U5eNl0RfW',	NULL,	10,	'2016-07-09 12:00:45',	'2016-07-09 12:00:45');

-- 2016-10-18 17:47:50
