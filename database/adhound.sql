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
-- The tables in this file are considered general tables and can be used with
-- multiple tables.
--
DROP TABLE IF EXISTS `states`;
--
-- Table structure for table `states`
--
CREATE TABLE IF NOT EXISTS `states` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
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
--
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
--
-- Table structure for table `regions`
--
DROP TABLE IF EXISTS `location_regions`;

CREATE TABLE IF NOT EXISTS `location_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(48) NOT NULL,
  `description` varchar(256) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `regions`
--
INSERT INTO `location_regions` (`id`, `name`, `description`) VALUES
(1, 'Test Region 1', 'Test Region 1 Description'),
(2, 'Test Region 2', 'Test Region 2 Description'),
(3, 'Test Region 3', 'Test Region 3 Description');
--
-- Import and Run Additional SQL Files
--
source contacts.sql;
source locations.sql;
source panels.sql;
source users.sql;

source joins.sql;