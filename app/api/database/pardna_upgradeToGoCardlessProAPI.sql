-- Adminer 4.2.3 MySQL dump

-- ALTER TABLE `pardnagroups`
-- ADD `group_status_code` bigint(20) DEFAULT 2 NOT NULL

ALTER TABLE `pardnagroups`
ADD `currency` varchar(3) DEFAULT 'GBP' NOT NULL

DROP TABLE IF EXISTS `pardnagroup_status`;
CREATE TABLE `pardnagroup_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(6) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pardnagroup_status_reasons`;
CREATE TABLE `pardnagroup_status_reasons` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(6) NOT NULL,
  `reason` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pardnagroup_status` (`code`, `status`) VALUES
('SETRQ', 'Set up required'),
('AWTNG', 'Awaiting'),
('OHOLD', 'On hold'),
('RDSRT', 'Ready to Start'),
('CNCLD', 'Cancelled'),
('STOPD', 'Stopped'),
('ACTVE', 'Active'),
('SCESS', 'Successfully ended');

INSERT INTO `pardnagroup_status_reasons` (`code`, `reason`) VALUES
('EMPSL', 'Some slots are empty');

ALTER TABLE `pardnagroup_members`
ADD `dd_mandate_setup` tinyint(1) unsigned DEFAULT '0'

DROP TABLE IF EXISTS `gocardless_customers`;
CREATE TABLE `gocardless_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` varchar(20) NOT NULL,
  `pardnagroup_member_id` varchar(20) NOT NULL,
  `cust_bank_account` varchar(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cust_id` (`cust_id`),
  KEY `pardnagroup_member_id` (`pardnagroup_member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `gocardless_mandates`;
CREATE TABLE `gocardless_mandates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` varchar(20) NOT NULL,
  `mandate_id` varchar(20) NOT NULL,
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
