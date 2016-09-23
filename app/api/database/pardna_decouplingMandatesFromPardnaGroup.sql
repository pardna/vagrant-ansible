-- Adminer 4.2.3 MySQL dump

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE `configurations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `configurations` (`name`, `value`) VALUES
('gocardless_success_redirect_url', 'http://192.168.33.99/app/frontend/dist/#/payment/confirm');

ALTER TABLE gocardless_customers
DROP COLUMN pardnagroup_member_id
