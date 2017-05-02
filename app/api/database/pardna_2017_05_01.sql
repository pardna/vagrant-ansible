DROP TABLE IF EXISTS `reset_password_links`;
CREATE TABLE `reset_password_links` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `reset_code` varchar(30) NOT NULL,
  `reset_link` varchar(250) NOT NULL,
  `email` varchar(50) NOT NULL,
  `reset_complete` tinyint(1) unsigned DEFAULT '0',
  `expires` datetime NOT NULL,
  `email_sent` tinyint(1) unsigned DEFAULT '0',
  `expired` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `configurations` (`name`, `value`) VALUES
('reset_password_endpoint',	'/reset-password?');
