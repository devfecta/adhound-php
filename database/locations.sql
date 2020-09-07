--
-- Table structure for table `location_categories`
--
DROP TABLE IF EXISTS `location_categories`;

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
DROP TABLE IF EXISTS `locations`;

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL,
  `phone` varchar(15) NULL,
  `fax` varchar(15) NULL,
  `address` varchar(64) NULL,
  `city` varchar(24) NOT NULL,
  `state_id` int(2) NOT NULL,
  `zipcode` varchar(11) NOT NULL,
  `region_id` int(11) NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `locations_state_fk` FOREIGN KEY (`state_id`) REFERENCES states(`id`) ON DELETE NO ACTION,
  CONSTRAINT `locations_region_fk` FOREIGN KEY (`region_id`) REFERENCES location_regions(`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
--
-- Delete from this table when deleting a location. It will delete the location
-- and the contacts associated with it.
--
LOCK TABLES `locations` WRITE;
INSERT INTO `locations`
(`id`, `name`, `phone`, `fax`, `address`, `city`, `state_id`, `zipcode`, `region_id`)
VALUES
(1, 'BW-3s', '(123) 456-7789', '(123) 455-3890', '123 Test Road', 'Madison', 49, '12345-67890', 1),
(2, 'AJ Bombers', '(445) 123-7890', '(123) 444-7390', '321 Test Road', 'Fitchburg', 25, '67890-12345', 2),
(3, 'Great Dane', '(664) 877-7890', '(123) 444-7390', '441 Test Road', 'Markesan', 19, '67890-12345', 3);
UNLOCK TABLES;
--
-- Location levels/floors
--
DROP TABLE IF EXISTS `location_levels`;

CREATE TABLE IF NOT EXISTS `location_levels` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(24) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

LOCK TABLES `location_levels` WRITE;
INSERT INTO `location_levels`
(`id`, `name`)
VALUES
(1, '1st Floor'),
(2, '2nd Floor'),
(3, '3rd Floor');
UNLOCK TABLES;
--
-- Table structure for table `location_notes`
--
--
-- Example: DELETE FROM location_notes WHERE id=1;
--
DROP TABLE IF EXISTS `location_notes`;

CREATE TABLE IF NOT EXISTS `location_notes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `note` varchar(1024) NULL,
    `location_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `notes_location_fk` FOREIGN KEY (`location_id`) REFERENCES locations(`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

LOCK TABLES `location_notes` WRITE;
INSERT INTO `location_notes`
(`id`, `note`, `location_id`)
VALUES
(1, 'Test Note 1', 1),
(2, 'Test Note 2', 1),
(3, 'Test Note 3', 2),
(4, 'Test Note 4', 2),
(5, 'Test Note 5', 3);
UNLOCK TABLES;
--
-- Location rooms
--
DROP TABLE IF EXISTS `location_rooms`;

CREATE TABLE IF NOT EXISTS `location_rooms` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(24) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

LOCK TABLES `location_rooms` WRITE;
INSERT INTO `location_rooms`
(`id`, `name`)
VALUES
(1, 'Men\'s Bathroom'),
(2, 'Entryway'),
(3, 'Women\'s Bathroom');
UNLOCK TABLES;
--
-- Location walls
--
DROP TABLE IF EXISTS `location_walls`;

CREATE TABLE IF NOT EXISTS `location_walls` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(24) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

LOCK TABLES `location_walls` WRITE;
INSERT INTO `location_walls`
(`id`, `name`)
VALUES
(1, 'Wall A'),
(2, 'Wall B'),
(3, 'Wall C'),
(4, 'Wall D');
UNLOCK TABLES;
