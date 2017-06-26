DROP TABLE IF EXISTS `gocardless_events`;
CREATE TABLE `gocardless_events` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `event_id` varchar(20) NOT NULL,
  `resource_type` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `cause` varchar(20) NOT NULL,
  `details` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `gocardless_events_links`;
CREATE TABLE `gocardless_events_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` varchar(20) NOT NULL,
  `link_type` varchar(20) NOT NULL,
  `link_value` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
