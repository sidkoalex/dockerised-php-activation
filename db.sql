CREATE DATABASE IF NOT EXISTS `activation`;


CREATE TABLE IF NOT EXISTS `key`
(
    `id`          int(11)       NOT NULL AUTO_INCREMENT,
    `private_key` varchar(1280) NOT NULL,
    `public_key`  varchar(512)  NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


CREATE TABLE IF NOT EXISTS `serial`
(
    `id`          int(11)      NOT NULL AUTO_INCREMENT,
    `key_id`      int(11)      NOT NULL,
    `is_banned`   bool         NOT NULL DEFAULT false,
    `serial`      varchar(256) NOT NULL,
    `expire_date` date         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `key_id` (`key_id`),
    CONSTRAINT `serial_ibfk_1` FOREIGN KEY (`key_id`) REFERENCES `key` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


CREATE TABLE IF NOT EXISTS `user_serial`
(
    `id`        int(11)      NOT NULL AUTO_INCREMENT,
    `user_id`   int(11)      DEFAULT NULL,
    `user_name` varchar(256) DEFAULT NULL,
    `serial_id` int(11)      NOT NULL,
    `pc_hash`   varchar(256) NOT NULL,
    `status`    varchar(256) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `serial_id` (`serial_id`),
    CONSTRAINT `user_serial_ibfk_1` FOREIGN KEY (`serial_id`) REFERENCES `serial` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
