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

ALTER TABLE gocardless_customers
ADD user_id bigint(20) NOT NULL;

ALTER TABLE gocardless_mandates
ADD cust_bank_account varchar(20) NOT NULL;

UPDATE gocardless_customers, gocardless_mandates
SET gocardless_mandates.cust_bank_account=gocardless_customers.cust_bank_account
WHERE gocardless_mandates.cust_id=gocardless_customers.cust_id;

ALTER TABLE gocardless_customers
DROP COLUMN cust_bank_account

ALTER TABLE pardnagroup_members
DROP COLUMN `dd_mandate_setup`;

ALTER TABLE pardnagroup_members
ADD `dd_mandate_id` varchar(20) NOT NULL;

ALTER TABLE pardnagroup_members
ADD `dd_mandate_status` varchar(50) NOT NULL;
