DROP TABLE IF EXISTS `contact_types`;

CREATE TABLE IF NOT EXISTS `contact_types` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(48) NOT NULL,
    `description` varchar(256) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

LOCK TABLES `contact_types` WRITE;
INSERT INTO `contact_types`
(`id`, `name`)
VALUES
(1, 'Main Contact'),
(2, 'Secondary Contact'),
(3, 'Emergency Contact');
UNLOCK TABLES;

DROP TABLE IF EXISTS `location_contacts`;

CREATE TABLE IF NOT EXISTS `location_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(24) NOT NULL,
  `last_name` varchar(24) NOT NULL,
  `phone` varchar(15) NULL,
  `fax` varchar(15) NULL,
  `email` varchar(128) NULL,
  `address` varchar(64) NULL,
  `city` varchar(24) NOT NULL,
  `state_id` int(2) NOT NULL,
  `zipcode` varchar(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `contacts_state_fk` FOREIGN KEY (`state_id`) REFERENCES states(`id`),
  CONSTRAINT `contacts_type_fk` FOREIGN KEY (`type_id`) REFERENCES contact_types(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
--
-- Delete from this table when deleting a contact. It will delete the contact,
-- but not the location.
--
LOCK TABLES `location_contacts` WRITE;
INSERT INTO `location_contacts`
(`id`, `first_name`, `last_name`, `phone`, `fax`, `email`, `city`, `state_id`, `zipcode`, `type_id`)
VALUES
(1, 'Test 1', 'Last Name 1', '(605) 456-8844', '(605) 454-7844', 'test1@example.com', 'Madison', 16, '12345-67890', 1),
(2, 'Test 2', 'Last Name  2', '(705) 646-9743', '(705) 345-7874', 'test2@example.com', 'Markesan', 20, '12345-67890', 1),
(3, 'Test 3', 'Last Name  3', '(805) 876-2244', '(805) 777-7888', 'test3@example.com', 'Fitchburg', 33, '67890-12345', 2);
UNLOCK TABLES;