
CREATE TABLE `pardnaaccount_scheduled_payments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL,
  `pardna_group_member_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `payment_date` datetime NOT NULL,
  `status` varchar(25) NOT NULL,
  `reference` varchar(25) NOT NULL,
  `response` text NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pardna_group_member_id` (`pardna_group_member_id`),
  KEY `status` (`status`),
  KEY `scheduled_date` (`scheduled_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
