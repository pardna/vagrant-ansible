-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `account_email_validate`;
CREATE TABLE `account_email_validate` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `selector` varchar(16) DEFAULT NULL,
  `token` varchar(64) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `selector` (`selector`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `configurations` (`id`, `name`, `value`) VALUES
(1,	'base_url',	'http://192.168.33.99/app/frontend/dist/#!'),
(2,	'gocardless_success_redirect_endpoint',	'/payment/confirm'),
(3,	'email_validate_endpoint',	'/account/email/verify?'),
(4,	'register_endpoint',	'/signup');

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
  `accepted` tinyint(1) unsigned DEFAULT '0',
  `invitor_user_id` bigint(20) DEFAULT NULL,
  `ignored` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `type_id` (`type_id`),
  KEY `type` (`type`)
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


DROP TABLE IF EXISTS `pardnagroup_confirmed`;
CREATE TABLE `pardnagroup_confirmed` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pardnagroup_id` bigint(20) NOT NULL,
  `confirmed_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pardnagroup_id` (`pardnagroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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
  `email_verified` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `membership_number` (`membership_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2017-03-17 21:52:34

DROP TABLE IF EXISTS `directdebits`;
CREATE TABLE `directdebits` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account` longtext NOT NULL,
  `sortcode` longtext NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
