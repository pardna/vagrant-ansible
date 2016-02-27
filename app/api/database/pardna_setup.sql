-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

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


DROP TABLE IF EXISTS `pardnagroup_members`;
CREATE TABLE `pardnagroup_members` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `fullname` varchar(50) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `group_id` bigint(20) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `email` (`email`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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


-- 2016-01-09 13:00:24
