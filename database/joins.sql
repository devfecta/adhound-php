--
-- Joins contacts with locations
-- Example: DELETE FROM location_contact WHERE location_id=1 AND contact_id=2;
--
DROP TABLE IF EXISTS `location_contact`;

CREATE TABLE IF NOT EXISTS `location_contact` (
    `location_id` int(11) NOT NULL,
    `contact_id` int(11) NOT NULL,
    PRIMARY KEY (`location_id`, `contact_id`),
    CONSTRAINT `location_fk` FOREIGN KEY (`location_id`) REFERENCES locations(`id`) ON DELETE CASCADE,
    CONSTRAINT `contact_fk` FOREIGN KEY (`contact_id`) REFERENCES location_contacts(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `location_contact` WRITE;
INSERT INTO `location_contact`
(`location_id`, `contact_id`)
VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(2, 3);
UNLOCK TABLES;
--
-- Example: DELETE FROM location_category WHERE location_id=1 AND category_id=2;
--
DROP TABLE IF EXISTS `location_category`;

CREATE TABLE IF NOT EXISTS `location_category` (
    `location_id` int(11) NOT NULL,
    `category_id` int(11) NOT NULL,
    PRIMARY KEY (`location_id`, `category_id`),
    CONSTRAINT `category_location_fk` FOREIGN KEY (`location_id`) REFERENCES locations(`id`) ON DELETE CASCADE,
    CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES location_categories(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `location_category` WRITE;
INSERT INTO `location_category`
(`location_id`, `category_id`)
VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 2);
UNLOCK TABLES;
--
-- Example: DELETE FROM location_room WHERE location_id=1 AND level_id=2;
--
DROP TABLE IF EXISTS `location_level`;

CREATE TABLE IF NOT EXISTS `location_level` (
    `location_id` int(11) NOT NULL,
    `level_id` int(11) NOT NULL,
    PRIMARY KEY (`location_id`, `level_id`),
    CONSTRAINT `locations_fk` FOREIGN KEY (`location_id`) REFERENCES locations(`id`) ON DELETE CASCADE,
    CONSTRAINT `level_fk` FOREIGN KEY (`level_id`) REFERENCES location_levels(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `location_level` WRITE;
INSERT INTO `location_level`
(`location_id`, `level_id`)
VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2);
UNLOCK TABLES;
--
-- Example: DELETE FROM location_room WHERE location_id=1 AND level_id=1 AND room_id=3;
--
DROP TABLE IF EXISTS `location_room`;

CREATE TABLE IF NOT EXISTS `location_room` (
    `location_id` int(11) NOT NULL,
    `level_id` int(11) NOT NULL,
    `room_id` int(11) NOT NULL,
    PRIMARY KEY (`location_id`, `level_id`, `room_id`),
    CONSTRAINT `location_level_fk` FOREIGN KEY (`location_id`, `level_id`) REFERENCES location_level(`location_id`, `level_id`) ON DELETE CASCADE,
    CONSTRAINT `room_fk` FOREIGN KEY (`room_id`) REFERENCES location_rooms(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `location_room` WRITE;
INSERT INTO `location_room`
(`location_id`, `level_id`, `room_id`)
VALUES
(1, 1, 1),
(1, 1, 3),
(1, 2, 2),
(1, 3, 3),
(2, 1, 2),
(2, 2, 1);
UNLOCK TABLES;
--
-- Example: DELETE FROM location_wall WHERE location_id=1 AND level_id=1 AND room_id=3 AND wall_id=2;
--
DROP TABLE IF EXISTS `location_wall`;

CREATE TABLE IF NOT EXISTS `location_wall` (
    `location_id` int(11) NOT NULL,
    `level_id` int(11) NOT NULL,
    `room_id` int(11) NOT NULL,
    `wall_id` int(11) NOT NULL,
    PRIMARY KEY (`location_id`, `level_id`, `room_id`, `wall_id`),
    CONSTRAINT `location_wall_fk` FOREIGN KEY (`location_id`, `level_id`, `room_id`) REFERENCES location_room(`location_id`, `level_id`, `room_id`) ON DELETE CASCADE,
    CONSTRAINT `wall_fk` FOREIGN KEY (`wall_id`) REFERENCES location_walls(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `location_wall` WRITE;
INSERT INTO `location_wall`
(`location_id`, `level_id`, `room_id`, `wall_id`)
VALUES
(1, 1, 1, 1),
(1, 1, 1, 2),
(1, 1, 1, 3),
(1, 1, 1, 4),
(1, 1, 3, 1),
(1, 1, 3, 2),
(1, 1, 3, 3),
(1, 1, 3, 4),
(2, 2, 1, 1),
(2, 2, 1, 2),
(2, 2, 1, 3);
UNLOCK TABLES;
--
-- Example: DELETE FROM location_panel WHERE location_id=1 AND level_id=1 AND room_id=3 AND wall_id=2 AND panel_id=1;
--
DROP TABLE IF EXISTS `location_panel`;

CREATE TABLE IF NOT EXISTS `location_panel` (
    `location_id` int(11) NOT NULL,
    `level_id` int(11) NOT NULL,
    `room_id` int(11) NOT NULL,
    `wall_id` int(11) NOT NULL,
    `panel_id` int(11) NOT NULL,
    `height` float(5,3) NOT NULL,
    `width` float(5,3) NOT NULL,
    `description` varchar(1024) NULL,
    PRIMARY KEY (`location_id`, `level_id`, `room_id`, `wall_id`, `panel_id`),
    CONSTRAINT `location_panel_fk` FOREIGN KEY (`location_id`, `level_id`, `room_id`, `wall_id`) REFERENCES location_wall(`location_id`, `level_id`, `room_id`, `wall_id`) ON DELETE CASCADE,
    CONSTRAINT `panel_fk` FOREIGN KEY (`panel_id`) REFERENCES location_panels(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `location_panel` WRITE;
INSERT INTO `location_panel`
(`location_id`, `level_id`, `room_id`, `wall_id`, `panel_id`, `height`, `width`)
VALUES
(1, 1, 1, 1, 1, 33, 24.5),
(1, 1, 1, 1, 2, 24.75, 18),
(1, 1, 1, 1, 3, 33, 24.5),
(1, 1, 1, 1, 4, 48, 20),
(1, 1, 1, 1, 5, 20.75, 11),
(1, 1, 1, 1, 6, 30, 24.5),
(1, 1, 1, 2, 1, 33, 24.5),
(1, 1, 1, 2, 2, 24.75, 24.5),
(1, 1, 1, 3, 1, 11.375, 8.5),
(1, 1, 1, 3, 2, 11.375, 8.5),
(1, 1, 1, 3, 3, 33, 24.5),
(2, 2, 1, 1, 1, 11.375, 8.5),
(2, 2, 1, 1, 3, 24.75, 24.5),
(2, 2, 1, 2, 1, 11.375, 8.5),
(2, 2, 1, 2, 2, 33, 24.5),
(2, 2, 1, 2, 3, 24.75, 24.5),
(2, 2, 1, 3, 1, 33, 24.5),
(2, 2, 1, 3, 2, 24.75, 24.5);
UNLOCK TABLES;
--
-- Joins users with a user
-- Example: DELETE FROM user_users WHERE admin_id=1 AND user_id=2;
--
DROP TABLE IF EXISTS `user_users`;

CREATE TABLE IF NOT EXISTS `user_users` (
    `admin_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    PRIMARY KEY (`admin_id`, `user_id`),
    CONSTRAINT `user_admin_fk` FOREIGN KEY (`admin_id`) REFERENCES users(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_users` WRITE;
INSERT INTO `user_users`
(`admin_id`, `user_id`)
VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 2),
(2, 4),
(2, 5);
UNLOCK TABLES;
--
-- Joins admin users with locations
-- Example: DELETE FROM users WHERE id=2; OR
-- Example: DELETE FROM locations WHERE id=2;
--
DROP TABLE IF EXISTS `user_locations`;

CREATE TABLE IF NOT EXISTS `user_locations` (
    `admin_id` int(11) NOT NULL,
    `location_id` int(11) NOT NULL,
    CONSTRAINT `user_admin_location_fk` FOREIGN KEY (`admin_id`) REFERENCES users(`id`) ON DELETE CASCADE,
    CONSTRAINT `user_location_fk` FOREIGN KEY (`location_id`) REFERENCES locations(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_locations` WRITE;
INSERT INTO `user_locations`
(`admin_id`, `location_id`)
VALUES
(1, 1),
(1, 2),
(1, 3);
UNLOCK TABLES;