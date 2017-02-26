DROP TABLE IF EXISTS `pardnagroup_confirmed`;

CREATE TABLE `pardnagroup_confirmed` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pardnagroup_id` bigint(20) NOT NULL,
  `confirmed_on` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pardnagroup_id` (`pardnagroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
