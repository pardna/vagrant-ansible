-- Adminer 4.2.3 MySQL dump

-- ALTER TABLE `pardnagroups`
-- ADD `group_status_code` bigint(20) DEFAULT 2 NOT NULL

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `configurations` (`name`, `value`) VALUES
('base_url',	'http://192.168.33.99/app/frontend/dist/#!'),
('gocardless_success_redirect_endpoint',	'/payment/confirm'),
('email_validate_endpoint',	'/account/email/verify?'),
('register_endpoint',	'/signup');

ALTER TABLE invitations
ADD`accepted` tinyint(1) unsigned DEFAULT '0',
ADD`ignored` tinyint(1) unsigned DEFAULT '0',
ADD `invitor_user_id` bigint(20);
