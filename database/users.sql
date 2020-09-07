DROP TABLE IF EXISTS `user_types`;

CREATE TABLE IF NOT EXISTS `user_types` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(48) NOT NULL,
    `description` varchar(256) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

LOCK TABLES `user_types` WRITE;
INSERT INTO `user_types`
(`id`, `name`)
VALUES
(1, 'Administrator'),
(2, 'Assistant'),
(3, 'Runner');
UNLOCK TABLES;


DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(24) NOT NULL,
  `password` varchar(128) NOT NULL,
  `first_name` varchar(24) NOT NULL,
  `last_name` varchar(24) NOT NULL,
  `phone` varchar(15) NULL,
  `fax` varchar(15) NULL,
  `email` varchar(128) NULL,
  `address` varchar(64) NULL,
  `city` varchar(24) NOT NULL,
  `state_id` int(2) NOT NULL,
  `zipcode` varchar(11) NOT NULL,
  `type_id` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  CONSTRAINT `users_state_fk` FOREIGN KEY (`state_id`) REFERENCES states(`id`),
  CONSTRAINT `users_type_fk` FOREIGN KEY (`type_id`) REFERENCES user_types(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
--
-- Delete from this table when deleting a contact. It will delete the contact,
-- but not the location.
--
LOCK TABLES `users` WRITE;
INSERT INTO `users`
(`id`, `username`, `password`, `first_name`, `last_name`, `phone`, `fax`, `email`, `address`, `city`, `state_id`, `zipcode`, `type_id`)
VALUES
(1, 'kkelm', '$2y$10$J5Dy.vfoLfhE6HKq3Mal3eIdn9kdVPO7NahHpNWLgYiMoyqwn17QW', 'Kevin', 'Kelm', '(123) 456-7890', '(123) 789-4560', 'kkelm@outlook.com', '123 Test Road', 'Fitchburg', 49, '12345-67890', 1),
(2, 'assistant', '$2y$10$J5Dy.vfoLfhE6HKq3Mal3eIdn9kdVPO7NahHpNWLgYiMoyqwn17QW', 'Darth', 'Vader', '(123) 456-7890', '(123) 789-4560', 'test@gmail.com', '123 Test Street', 'Madison', 25, '12345-67890', 2),
(3, 'runner', '$2y$10$J5Dy.vfoLfhE6HKq3Mal3eIdn9kdVPO7NahHpNWLgYiMoyqwn17QW', 'Luke', 'Skywalker', '(123) 456-7890', '(123) 789-4560', 'test@yahoo.com', '123 Test Circle', 'Markesan', 33, '12345-67890', 3),
(4, 'admin_test', '$2y$10$J5Dy.vfoLfhE6HKq3Mal3eIdn9kdVPO7NahHpNWLgYiMoyqwn17QW', 'Luke', 'Skywalker', '(123) 456-7890', '(123) 789-4560', 'test@yahoo.com', '123 Test Circle', 'Markesan', 33, '12345-67890', 1),
(5, 'assistant_test', '$2y$10$J5Dy.vfoLfhE6HKq3Mal3eIdn9kdVPO7NahHpNWLgYiMoyqwn17QW', 'Luke', 'Skywalker', '(123) 456-7890', '(123) 789-4560', 'test@yahoo.com', '123 Test Circle', 'Markesan', 33, '12345-67890', 2);
UNLOCK TABLES;