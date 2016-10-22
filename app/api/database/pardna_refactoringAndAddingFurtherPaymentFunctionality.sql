-- Adminer 4.2.3 MySQL dump

INSERT INTO `configurations` (`name`, `value`) VALUES
('email_validate_url', 'http://192.168.33.99/app/frontend/dist/#/account/email/verify?');

DROP TABLE IF EXISTS `account_email_validate`;
CREATE TABLE `account_email_validate` (
    id bigint(11) NOT NULL AUTO_INCREMENT,
    user_id bigint(11) NOT NULL,
    selector varchar(16),
    token varchar(64),
    expires datetime,
    PRIMARY KEY(id),
    KEY(selector)
);

ALTER TABLE `users`
ADD `email_verified` tinyint(1) unsigned DEFAULT '0'
