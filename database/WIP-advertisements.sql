--
-- Drop the `adhound` database if it exists
--
DROP DATABASE IF EXISTS `adhound`;
--
-- Create a new `adhound` database
--
CREATE DATABASE `adhound` CHARACTER SET utf8 COLLATE utf8_general_ci;
--
-- Use the `adhound` database
--
USE `adhound`;
--
-- Drop the `locations` table if it exists
--
DROP TABLE IF EXISTS `locations`;
DROP TABLE IF EXISTS `advertisers`;
DROP TABLE IF EXISTS `panels`;
DROP TABLE IF EXISTS `advertisements`;
DROP TABLE IF EXISTS `location_panels`;
DROP TABLE IF EXISTS `levels`;
DROP TABLE IF EXISTS `rooms`;
DROP TABLE IF EXISTS `walls`;
DROP TABLE IF EXISTS `contracts`;
DROP TABLE IF EXISTS `notes`;
DROP TABLE IF EXISTS `states`;
DROP TABLE IF EXISTS `regions`;
DROP TABLE IF EXISTS `contacts`;
DROP TABLE IF EXISTS `damages`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `types`;
DROP TABLE IF EXISTS `damages`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_types`;
--
-- Table structure for table `states`
--
CREATE TABLE IF NOT EXISTS `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `states_Abbreviation` varchar(2) NOT NULL,
  `states_Name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `states`
--
INSERT INTO `states` (`id`, `states_Abbreviation`, `states_Name`) VALUES
(1, 'AL', 'Alabama'),
(2, 'AK', 'Alaska'),
(3, 'AZ', 'Arizona'),
(4, 'AR', 'Arkansas'),
(5, 'CA', 'California'),
(6, 'CO', 'Colorado'),
(7, 'CT', 'Connecticut'),
(8, 'DE', 'Delaware'),
(9, 'FL', 'Florida'),
(10, 'GA', 'Georgia'),
(11, 'HI', 'Hawaii'),
(12, 'ID', 'Idaho'),
(13, 'IL', 'Illinois'),
(14, 'IN', 'Indiana'),
(15, 'IA', 'Iowa'),
(16, 'KS', 'Kansas'),
(17, 'KY', 'Kentucky'),
(18, 'LA', 'Louisiana'),
(19, 'ME', 'Maine'),
(20, 'MD', 'Maryland'),
(21, 'MA', 'Massachusetts'),
(22, 'MI', 'Michigan'),
(23, 'MN', 'Minnesota'),
(24, 'MS', 'Mississippi'),
(25, 'MO', 'Missouri'),
(26, 'MT', 'Montana'),
(27, 'NE', 'Nebraska'),
(28, 'NV', 'Nevada'),
(29, 'NH', 'New Hampshire'),
(30, 'NJ', 'New Jersey'),
(31, 'NM', 'New Mexico'),
(32, 'NY', 'New York'),
(33, 'NC', 'North Carolina'),
(34, 'ND', 'North Dakota'),
(35, 'OH', 'Ohio'),
(36, 'OK', 'Oklahoma'),
(37, 'OR', 'Oregon'),
(38, 'PA', 'Pennsylvania'),
(39, 'RI', 'Rhode Island'),
(40, 'SC', 'South Carolina'),
(41, 'SD', 'South Dakota'),
(42, 'TN', 'Tennessee'),
(43, 'TX', 'Texas'),
(44, 'UT', 'Utah'),
(45, 'VT', 'Vermont'),
(46, 'VA', 'Virginia'),
(47, 'WA', 'Washington'),
(48, 'WV', 'West Virginia'),
(49, 'WI', 'Wisconsin'),
(50, 'WY', 'Wyoming');
--
-- Table structure for table `regions`
--
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL,
  `description` varchar(256) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `regions`
--
INSERT INTO `regions` (`id`, `name`, `description`) VALUES
(1, 'Test Region 1', 'Test Region 1 Description'),
(2, 'Test Region 2', 'Test Region 2 Description'),
(3, 'Test Region 3', 'Test Region 3 Description');

CREATE TABLE IF NOT EXISTS `location_categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(48) NOT NULL,
    `description` varchar(256) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

LOCK TABLES `location_categories` WRITE;
INSERT INTO `location_categories`
(`id`, `name`)
VALUES
(1, 'Bar'),
(2, 'Resturant'),
(3, 'Sport Complex');
UNLOCK TABLES;

--
-- Create `locations` table structure
-- Locations can have many contacts, contracts, categories, panels, notes
--
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL,
  `phone` varchar(15) NULL,
  `fax` varchar(15) NULL,
  `address` varchar(64) NULL,
  `city` varchar(24) NOT NULL,
  `state_id` int(11) NOT NULL,
  `zipcode` varchar(11) NOT NULL,
  `region_id` int(11) NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `locations_state_fk` FOREIGN KEY (`state_id`) REFERENCES states(`id`) ON DELETE NO ACTION,
  CONSTRAINT `locations_region_fk` FOREIGN KEY (`region_id`) REFERENCES regions(`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
--
-- Delete from this table when deleting a location. It will delete the location
-- and the contacts associated with it.
--
LOCK TABLES `locations` WRITE;
INSERT INTO `locations`
(`id`, `name`, `phone`, `fax`, `address`, `city`, `state_id`, `zipcode`)
VALUES
(1, 'Test Location 1', '(123) 456-7789', '(123) 455-3890', '123 Test Road', 'Madison', 49, '12345-67890'),
(2, 'Test Location 2', '(445) 123-7890', '(123) 444-7390', '321 Test Road', 'Fitchburg', 25, '67890-12345'),
(3, 'Test Location 3', '(664) 877-7890', '(123) 444-7390', '441 Test Road', 'Markesan', 19, '67890-12345');
UNLOCK TABLES;

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

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(24) NOT NULL,
  `last_name` varchar(24) NOT NULL,
  `phone` varchar(15),
  `fax` varchar(15),
  `email` varchar(128),
  `address` varchar(64),
  `city` varchar(24) NOT NULL,
  `state_id` int(11) NOT NULL,
  `zipcode` varchar(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `contacts_state_fk` FOREIGN KEY (`state_id`) REFERENCES states(`id`) ON DELETE NO ACTION,
  CONSTRAINT `contacts_type_fk` FOREIGN KEY (`type_id`) REFERENCES contact_types(`id`) ON DELETE NO ACTION,
  CONSTRAINT `contacts_location_fk` FOREIGN KEY (`location_id`) REFERENCES locations(`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
--
-- Delete from this table when deleting a contact. It will delete the contact,
-- but not the location.
--
LOCK TABLES `contacts` WRITE;
INSERT INTO `contacts`
(`id`, `first_name`, `last_name`, `city`, `state_id`, `zipcode`, `type_id`, `location_id`)
VALUES
(1, 'Test', 'Contact 1', 'Madison', 16, '12345-67890', 1, 1),
(2, 'Test', 'Contact 2', 'Markesan', 20, '12345-67890', 1, 2),
(3, 'Test', 'Contact 3', 'Fitchburg', 33, '67890-12345', 2, 2);
UNLOCK TABLES;


CREATE TABLE IF NOT EXISTS `location_contact` (
    `location_id` int(11) NOT NULL,
    `contact_id` int(11) NOT NULL,
    CONSTRAINT `location_fk` FOREIGN KEY (`location_id`) REFERENCES locations(`id`) ON DELETE CASCADE,
    CONSTRAINT `contact_fk` FOREIGN KEY (`contact_id`) REFERENCES contacts(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `location_contact` WRITE;
INSERT INTO `location_contact`
(`location_id`, `contact_id`)
VALUES
(1, 1),
(2, 2),
(2, 3);
UNLOCK TABLES;


CREATE TABLE IF NOT EXISTS `location_category` (
    `location_id` int(11) NOT NULL,
    `category_id` int(11) NOT NULL,
    CONSTRAINT `category_location_fk` FOREIGN KEY (`location_id`) REFERENCES locations(`id`) ON DELETE CASCADE,
    CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES location_categories(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `location_category` WRITE;
INSERT INTO `location_category`
(`location_id`, `category_id`)
VALUES
(1, 1),
(2, 1),
(2, 2);
UNLOCK TABLES;