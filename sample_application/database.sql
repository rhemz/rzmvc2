CREATE DATABASE `todo` /*!40100 DEFAULT CHARACTER SET utf8 */;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `email` varchar(90) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_UNIQUE` (`id`),
  UNIQUE KEY `user_name_UNIQUE` (`name`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `todo_list` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) COMMENT='' ENGINE='InnoDB' DEFAULT CHARSET=utf8;


CREATE TABLE `todo_item` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `list_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  FOREIGN KEY (`list_id`) REFERENCES `todo_list` (`id`) ON DELETE CASCADE
) COMMENT='' ENGINE='InnoDB' DEFAULT CHARSET=utf8;