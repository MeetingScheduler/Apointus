-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2014 at 08:01 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fullcalendar`
--

-- --------------------------------------------------------

--
-- Table structure for table `evenement`
--

CREATE TABLE IF NOT EXISTS `evenement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `allDay` tinyint(1) NOT NULL DEFAULT '0',
  `User_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=41 ;

--
-- Dumping data for table `evenement`
--

INSERT INTO `evenement` (`id`, `title`, `start`, `end`, `url`, `allDay`, `User_id`) VALUES
(34, 'i am lost', '2014-03-17 12:00:00', NULL, '', 0, 1),
(35, 'i am lost', '2014-03-17 12:00:00', NULL, '', 0, 2),
(36, 'i just wana', '2014-03-14 08:00:00', NULL, '', 0, 2),
(38, 'i just wana', '2014-03-14 08:00:00', NULL, '', 0, 1),
(39, 'i am lost', '2014-03-17 12:00:00', NULL, '', 0, 3),
(40, 'i am lost', '2014-03-17 12:00:00', NULL, '', 0, 5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
