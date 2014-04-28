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
-- Database: `appointus`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE IF NOT EXISTS `attendance` (
  `A_ID` int(11) NOT NULL AUTO_INCREMENT,
  `M_ID` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  PRIMARY KEY (`A_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=234 ;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`A_ID`, `M_ID`, `User_id`) VALUES
(219, 628, 1),
(220, 628, 2),
(221, 628, 3),
(222, 628, 4),
(223, 628, 5),
(224, 629, 2),
(225, 629, 1),
(226, 629, 3),
(227, 629, 4),
(228, 629, 5);

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE IF NOT EXISTS `meeting` (
  `M_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `Venue` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Duration` int(11) NOT NULL,
  `Datetime` datetime NOT NULL,
  `User_id` int(11) NOT NULL,
  `IsAddedToCalendar` varchar(10) NOT NULL DEFAULT 'NO',
  PRIMARY KEY (`M_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=631 ;

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`M_ID`, `Title`, `Venue`, `Description`, `Duration`, `Datetime`, `User_id`, `IsAddedToCalendar`) VALUES
(628, 'i am lost', 'with or without', 'without you', 45, '2014-03-17 12:00:00', 1, 'YES'),
(629, 'i just wana', 'whohoo', 'feel this moment', 2, '2014-03-14 08:00:00', 2, 'YES');

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE IF NOT EXISTS `membership` (
  `RM_ID` int(11) NOT NULL AUTO_INCREMENT,
  `R_ID` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  PRIMARY KEY (`RM_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `N_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Event` varchar(255) NOT NULL,
  `Event_ID` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  PRIMARY KEY (`N_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=97 ;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`N_ID`, `Event`, `Event_ID`, `User_id`) VALUES
(82, 'meeting', 628, 1),
(83, 'response', 82, 2),
(84, 'response', 82, 2),
(85, 'meeting', 629, 2),
(87, 'response', 85, 1),
(88, 'response', 85, 1),
(89, 'response', 82, 3),
(90, 'response', 85, 1),
(91, 'response', 85, 3),
(92, 'response', 85, 3),
(93, 'response', 85, 1),
(94, 'response', 82, 5),
(95, 'response', 82, 5),
(96, 'response', 85, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notify`
--

CREATE TABLE IF NOT EXISTS `notify` (
  `Count_ID` int(11) NOT NULL AUTO_INCREMENT,
  `N_ID` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Response` varchar(255) NOT NULL,
  `HasResponded` varchar(255) NOT NULL,
  PRIMARY KEY (`Count_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=184 ;

--
-- Dumping data for table `notify`
--

INSERT INTO `notify` (`Count_ID`, `N_ID`, `User_id`, `Response`, `HasResponded`) VALUES
(160, 82, 2, 'ACCEPT', 'YES'),
(161, 82, 3, 'ACCEPT', 'YES'),
(162, 82, 4, 'ACCEPT', 'NO'),
(163, 82, 5, 'ACCEPT', 'YES'),
(164, 83, 1, 'ACCEPT', 'NO'),
(165, 84, 1, 'ACCEPT', 'NO'),
(166, 85, 1, 'ACCEPT', 'YES'),
(167, 85, 3, 'DECLINE', 'YES'),
(168, 85, 4, 'ACCEPT', 'NO'),
(169, 85, 5, 'ACCEPT', 'NO'),
(174, 87, 2, 'ACCEPT', 'NO'),
(175, 88, 2, 'ACCEPT', 'NO'),
(176, 89, 1, 'ACCEPT', 'NO'),
(177, 90, 2, 'ACCEPT', 'NO'),
(178, 91, 2, 'ACCEPT', 'NO'),
(179, 92, 2, 'ACCEPT', 'NO'),
(180, 93, 2, 'ACCEPT', 'NO'),
(181, 94, 1, 'ACCEPT', 'NO'),
(182, 95, 1, 'ACCEPT', 'NO'),
(183, 96, 2, 'ACCEPT', 'NO');

-- --------------------------------------------------------

--
-- Table structure for table `ring`
--

CREATE TABLE IF NOT EXISTS `ring` (
  `R_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Ringname` varchar(255) NOT NULL,
  `User_id` int(11) NOT NULL,
  PRIMARY KEY (`R_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `Schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `User_id` int(11) NOT NULL,
  `Day` varchar(55) NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  PRIMARY KEY (`Schedule_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`Schedule_id`, `User_id`, `Day`, `StartTime`, `EndTime`) VALUES
(1, 1, 'Monday', '07:30:00', '09:00:00'),
(2, 1, 'Monday', '09:00:00', '10:00:00'),
(3, 1, 'Monday', '13:00:00', '14:30:00'),
(4, 1, 'Monday', '14:30:00', '16:00:00'),
(5, 1, 'Tuesday', '13:00:00', '14:30:00'),
(6, 1, 'Tuesday', '16:00:00', '17:30:00'),
(7, 2, 'Monday', '09:00:00', '10:00:00'),
(8, 2, 'Monday', '10:00:00', '12:00:00'),
(9, 2, 'Monday', '13:00:00', '14:30:00'),
(10, 2, 'Tuesday', '10:30:00', '12:00:00'),
(11, 2, 'Tuesday', '14:30:00', '16:00:00'),
(12, 2, 'Tuesday', '16:00:00', '17:30:00'),
(13, 3, 'Monday', '07:00:00', '08:00:00'),
(14, 3, 'Monday', '14:00:00', '15:00:00'),
(15, 3, 'Tuesday', '10:30:00', '12:00:00'),
(16, 3, 'Tuesday', '13:00:00', '14:30:00'),
(17, 4, 'Monday', '13:00:00', '14:00:00'),
(18, 4, 'Monday', '15:00:00', '16:00:00'),
(19, 4, 'Tuesday', '09:00:00', '10:00:00'),
(20, 5, 'Monday', '09:00:00', '10:00:00'),
(21, 5, 'Monday', '16:00:00', '17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `User_id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `Profile_picture` varchar(255) NOT NULL,
  PRIMARY KEY (`User_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`User_id`, `Username`, `Password`, `Email`, `Firstname`, `Lastname`, `contact_number`, `Profile_picture`) VALUES
(1, 'Lucelle', '1234', 'ohmworx3@gmail.com', 'Lucelle', 'Bureros', '09233724044', ''),
(2, 'Zarah', '12345', 'zarahtabaranzaishappy@gmail.com', 'Zarah Lou', 'Tabaranza', '09335377778', ''),
(3, 'Cristina', '1234', 'cristina@gmail.com', 'Cristina Jean', 'Donato', '09333895479', ''),
(4, 'Claudine', '1234', 'claudine@gmail.com', 'Claudine', 'Javellana', '09239091620', ''),
(5, 'Abigail', '1234', 'abigail@gmail.com', 'Abigail', 'Lauro', '09255006533', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
