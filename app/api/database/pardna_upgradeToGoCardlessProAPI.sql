-- Adminer 4.2.3 MySQL dump

ALTER TABLE `pardnagroups`
ADD `group_status_code` bigint(20) DEFAULT 2 NOT NULL

DROP TABLE IF EXISTS `pardnagroup_status`;
CREATE TABLE `pardnagroup_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `pardnagroup_status` (`status`) VALUES
('Set up required'),
('Awaiting'),
('On hold'),
('Cancelled'),
('Stopped'),
('Active'),
('Successfully ended');

ALTER TABLE `pardnagroup_members`
ADD `dd_mandate_setup` tinyint(1) unsigned DEFAULT '0'
