CREATE DATABASE IF NOT EXISTS ticket_tracker_test;
USE ticket_tracker_test;
SHOW TABLES;

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE `user_permission_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `permission_type_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `fk_user_type_id` (`user_type_id`),
  KEY `fk_permission_type_id` (`permission_type_id`),
  CONSTRAINT `fk_permission_type_id` FOREIGN KEY (`permission_type_id`) REFERENCES `user_permission_types` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `fk_user_type_id` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

CREATE TABLE `ticket_priority_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `ticket_status_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `ticket_resolution_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE `ticket_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `assigned_to_id` int(11) DEFAULT NULL,
  `reported_by_id` int(11) NOT NULL,
  `status_type_id` int(11) NOT NULL DEFAULT '1',
  `resolution_type_id` int(11) DEFAULT NULL,
  `priority_type_id` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `resolved_time` datetime DEFAULT NULL,
  `updated_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assigned_to_id` (`assigned_to_id`),
  KEY `reported_by_id` (`reported_by_id`),
  KEY `fk_status_type_id` (`status_type_id`),
  KEY `fk_ticket_type_id` (`type_id`),
  KEY `fk_priority_type_id` (`priority_type_id`),
  KEY `fk_resolution_type_id` (`resolution_type_id`),
  CONSTRAINT `fk_priority_type_id` FOREIGN KEY (`priority_type_id`) REFERENCES `ticket_priority_types` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `fk_resolution_type_id` FOREIGN KEY (`resolution_type_id`) REFERENCES `ticket_resolution_types` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `fk_status_type_id` FOREIGN KEY (`status_type_id`) REFERENCES `ticket_status_types` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `fk_ticket_type_id` FOREIGN KEY (`type_id`) REFERENCES `ticket_types` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`assigned_to_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`reported_by_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `added_time` datetime NOT NULL,
  `added_by_id` int(11) NOT NULL,
  `file_type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_added_by_id` (`added_by_id`),
  KEY `fk_ticked_id` (`ticket_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `edited_time` datetime DEFAULT NULL,
  `comment` text,
  `added_by_id` int(10) NOT NULL,
  `last_edited_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `fk_added_by_id` (`added_by_id`),
  KEY `fk_last_edited_by_id` (`last_edited_by_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `fk_added_by_id` FOREIGN KEY (`added_by_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `fk_last_edited_by_id` FOREIGN KEY (`last_edited_by_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

SELECT * FROM users;







