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
