--
-- Location Panels
--
DROP TABLE IF EXISTS `location_panels`;

CREATE TABLE IF NOT EXISTS `location_panels` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(24) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

LOCK TABLES `location_panels` WRITE;
INSERT INTO `location_panels`
(`id`, `name`)
VALUES
(1, 'Panel A'),
(2, 'Panel B'),
(3, 'Panel C'),
(4, 'Panel D'),
(5, 'Panel E'),
(6, 'Panel F');
UNLOCK TABLES;
