-- Design for Pardna group

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

/* Table where we will be storing details of all created pardna groups. */
/*    When a Pardna group is created, it is assigned an id (pardnagroup_id)
      The name of the pardna group is stored as name
*/
DROP TABLE IF EXISTS `pardnagroups`;
CREATE TABLE `pardnagroups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table where we will be storing the email addresses of all pardna group invites */
/*  When a Pardna group is created and invites are sent, they are saved in this table
      If the email exists in this table and pardnagroup_id is the same, we just increase the invited_count,
      If the email exists in this table and pardnagroup_id is NOT the same, we create a new entry
*/
DROP TABLE IF EXISTS `pardnagroup_members_invites`;
CREATE TABLE `pardnagroup_members_invites` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `pardnagroup_id` bigint(20) DEFAULT NULL,
  `invited_date` datetime NOT NULL,
  `invited_count` bigint(20) unsigned DEFAULT '1',
  `joined` tinyint(1) unsigned DEFAULT '0',
  `joined_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pardnagroup_id` (`pardnagroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/* Table where we will be storing the details of users (pardna.com members) invited to join the pardna group */
/*  When a users (pardna.com members) is added to the group, we stored the following details about them
      1. membership_number
      2. Change status to "active"
      3. assign role_id to determine for pardna group admin priviledges
      4. verify - denotes whether the user has accepted the invitation to join the pardna group
*/
DROP TABLE IF EXISTS `pardnagroup_members`;
CREATE TABLE `pardnagroup_members` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pardnagroup_id` bigint(20) NOT NULL,
  `membership_number` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `verified` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pardnagroup_id` (`pardnagroup_id`),
  KEY `membership_number` (`membership_number`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/* Table where we will be storing details of the pardna */
/* The details of the pardna are as follows
    1. pardnagroup_id - pardna group associated with this pardna_id
    2. pardna_account_id - The pardna account where the payments are lending into and are being taken out of.
    3. amount - amount agreed for the pardna.
    4. paydate - date the pardna is being started. Confirm if this is date of first payment.
    5. paytype - may be just to mention that gocardless is being used.
*/
DROP TABLE IF EXISTS `pardna`;
CREATE TABLE `pardna` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pardnagroup_id` bigint(20) NOT NULL,
  `pardna_account_id` bigint(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(30) NOT NULL,
  `frequency` varchar(30) NOT NULL,
  `paydate` datetime NOT NULL,
  `paytype` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pardnagroup_id` (`pardnagroup_id`),
  KEY `pardna_account_id` (`pardna_account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table where we will be storing details of the pardna group members taking part in the pardna */
/* We will only add details of member to this table once they have confirmed payments*/
/* Because at the end of a pardna, we may have another pardna starting with the same people but at (maybe) a different amount, I wanted to keep pardna_members separate from pardnagroup_members
    The following details about the participants of the pardna are stored in this table
    1. membership_number
    2. pardna_id - linked pardna id
    3. status - pardna member status - denotes status in terms of bill payments
    4. gocardless_account_id - the gocardless account number for the pardna member
    5. gocardless_verified - whether gocardless account has been verified
    6. verify - denotes whether the user has accepted the invitation to join the pardna group
*/
DROP TABLE IF EXISTS `pardna_members`;
CREATE TABLE `pardna_members` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `membership_number` varchar(30) NOT NULL,
  `pardna_id` bigint(20) NOT NULL,
  `status` varchar(30) NOT NULL,
  `total_amount_paid_in` decimal(10,2) NOT NULL,
  `total_amount_paid_in_minus_fees` decimal(10,2) NOT NULL,
  `total_amount_paid_out` decimal(10,2) NOT NULL,
  `total_amount_paid_out_minus_fees` decimal(10,2) NOT NULL,
  `created` datetime DEFAULT NULL,
  `gocardless_account_id` varchar(30) DEFAULT NULL,
  `gocardless_verified` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `membership_number` (`membership_number`),
  KEY `pardna_id` (`pardna_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* In this table, we'll keep the pardna account in which the money will be coming to and from. */
/* Ideally, for security purposes, we do not need sortcode and accountnumber in this table, only gocardless_merchant_id*/
DROP TABLE IF EXISTS `pardna_accounts`;
CREATE TABLE `pardnagroup_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sortcode` varchar(30) DEFAULT NULL,
  `accountnumber` varchar(30) DEFAULT NULL,
  `gocardless_merchant_id` varchar(30) NOT NULL,
  `gocardless_bank_reference` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Pardna group roles table */
DROP TABLE IF EXISTS `pardnagroup_roles`;
CREATE TABLE `pardnagroup_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `canInviteMembers` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/* When we begin handling webhooks, we'll need to keep a logs of all payments details in this table */
/* Details are as follows
  | source_id - The parent resource of the bill e.g. a subscription or pre-authorization
  | source_type - Returns only bills which were created under a specific type of source; 'subscription', 'pre_authorization' or 'ad_hoc_authorization' (ie one-off)
  | status - denotes whether the bill was creared, paid, withdrawn, failed, cancelled, refunded, chargedback, retried
*/
DROP TABLE IF EXISTS `pardanagroup_payments`;
DROP TABLE IF EXISTS `pardna_payments`;
CREATE TABLE `pardna_payments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pardna_id` bigint(20) NOT NULL,
  `membership_number` varchar(30) DEFAULT NULL,
  `gocardless_payment_id` bigint(20) NOT NULL,
  `pay_date` datetime NOT NULL,
  `source_id` varchar(255) NOT NULL,
  `source_type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `payment_uri` varchar(50) NOT NULL,
  `merchant_id` varchar(50) NOT NULL,
  `paytype` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amount_minus_fees` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pardna_id` (`pardna_id`),
  KEY `membership_number` (`membership_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
