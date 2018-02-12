-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 12, 2018 at 12:26 AM
-- Server version: 5.7.20-0ubuntu0.16.04.1
-- PHP Version: 7.0.26-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sloan2018`
--

-- --------------------------------------------------------

--
-- Table structure for table `defense_rating`
--

CREATE TABLE `defense_rating` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL,
  `game_date` datetime NOT NULL,
  `rating` decimal(4,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `defense_rating`
--

INSERT INTO `defense_rating` (`id`, `team_id`, `game_date`, `rating`) VALUES
(1, 1, '2018-02-01 00:00:00', '103.1'),
(2, 1, '2018-02-02 00:00:00', '105.3'),
(3, 1, '2018-02-03 00:00:00', '97.2'),
(4, 1, '2018-02-04 00:00:00', '99.6'),
(5, 1, '2018-02-05 00:00:00', '109.2'),
(6, 2, '2018-02-01 00:00:00', '104.5'),
(7, 2, '2018-02-02 00:00:00', '107.1'),
(8, 2, '2018-02-03 00:00:00', '108.9'),
(9, 2, '2018-02-04 00:00:00', '99.6'),
(10, 2, '2018-02-05 00:00:00', '102.3'),
(11, 3, '2018-02-01 00:00:00', '104.6'),
(12, 3, '2018-02-02 00:00:00', '108.1'),
(13, 3, '2018-02-03 00:00:00', '100.0'),
(14, 3, '2018-02-04 00:00:00', '102.1'),
(15, 3, '2018-02-05 00:00:00', '103.5'),
(16, 6, '2018-02-01 00:00:00', '99.5'),
(17, 6, '2018-02-02 00:00:00', '105.8'),
(18, 6, '2018-02-03 00:00:00', '108.7'),
(19, 6, '2018-02-04 00:00:00', '105.4'),
(20, 6, '2018-02-05 00:00:00', '102.3'),
(21, 4, '2018-02-01 00:00:00', '24.1'),
(22, 4, '2018-02-03 00:00:00', '28.8'),
(23, 4, '2018-02-05 00:00:00', '29.9'),
(24, 5, '2018-02-01 00:00:00', '30.3'),
(25, 5, '2018-02-03 00:00:00', '34.4'),
(26, 5, '2018-02-05 00:00:00', '33.2');

-- --------------------------------------------------------

--
-- Table structure for table `league`
--

CREATE TABLE `league` (
  `id` int(10) UNSIGNED NOT NULL,
  `league_name` varchar(50) NOT NULL,
  `abbreviation` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `league`
--

INSERT INTO `league` (`id`, `league_name`, `abbreviation`) VALUES
(1, 'National Basketball Assocation', 'NBA'),
(2, 'National Football League', 'NFL'),
(3, 'Major League Baseball', 'MLB'),
(4, 'National Hockey League', 'NHL');

-- --------------------------------------------------------

--
-- Table structure for table `offense_rating`
--

CREATE TABLE `offense_rating` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL,
  `game_date` datetime NOT NULL,
  `rating` decimal(4,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `offense_rating`
--

INSERT INTO `offense_rating` (`id`, `team_id`, `game_date`, `rating`) VALUES
(1, 1, '2018-02-01 00:00:00', '113.1'),
(2, 1, '2018-02-02 00:00:00', '114.6'),
(3, 1, '2018-02-03 00:00:00', '105.7'),
(4, 1, '2018-02-04 00:00:00', '121.2'),
(5, 1, '2018-02-05 00:00:00', '118.3'),
(6, 2, '2018-02-01 00:00:00', '101.3'),
(7, 2, '2018-02-02 00:00:00', '98.6'),
(8, 2, '2018-02-03 00:00:00', '104.3'),
(9, 2, '2018-02-04 00:00:00', '107.4'),
(10, 2, '2018-02-05 00:00:00', '100.5'),
(11, 3, '2018-02-01 00:00:00', '112.5'),
(12, 3, '2018-02-02 00:00:00', '115.5'),
(13, 3, '2018-02-03 00:00:00', '108.1'),
(14, 3, '2018-02-04 00:00:00', '110.0'),
(15, 3, '2018-02-05 00:00:00', '102.3'),
(16, 6, '2018-02-01 00:00:00', '104.6'),
(17, 6, '2018-02-02 00:00:00', '119.3'),
(18, 6, '2018-02-03 00:00:00', '115.4'),
(19, 6, '2018-02-04 00:00:00', '110.2'),
(20, 6, '2018-02-05 00:00:00', '108.2'),
(21, 4, '2018-02-01 00:00:00', '25.5'),
(22, 4, '2018-02-03 00:00:00', '34.3'),
(23, 4, '2018-02-05 00:00:00', '34.1'),
(24, 5, '2018-02-01 00:00:00', '22.1'),
(25, 5, '2018-02-03 00:00:00', '24.3'),
(26, 5, '2018-02-05 00:00:00', '25.6');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(10) UNSIGNED NOT NULL,
  `league_id` int(10) UNSIGNED NOT NULL,
  `city` varchar(50) NOT NULL,
  `team_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `league_id`, `city`, `team_name`) VALUES
(1, 1, 'Houston', 'Rockets'),
(2, 1, 'Detroit', 'Pistons'),
(3, 1, 'Golden State', 'Warriors'),
(4, 2, 'Houston', 'Texans'),
(5, 2, 'Detroit', 'Lions'),
(6, 1, 'Boston', 'Celtics');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `api_key` varchar(128) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `is_super_admin` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_admin` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `api_key`, `email`, `is_super_admin`, `is_admin`) VALUES
(1, 'jason', 'sloanadmin', 'j@jayroman.com', 1, 1),
(2, 'sloan', 'sloan2018', NULL, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `defense_rating`
--
ALTER TABLE `defense_rating`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_id` (`team_id`,`game_date`);

--
-- Indexes for table `league`
--
ALTER TABLE `league`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `abbreviation` (`abbreviation`),
  ADD UNIQUE KEY `league_name` (`league_name`) USING BTREE;

--
-- Indexes for table `offense_rating`
--
ALTER TABLE `offense_rating`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_id` (`team_id`,`game_date`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `league_id` (`league_id`,`city`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `api_key` (`api_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `defense_rating`
--
ALTER TABLE `defense_rating`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `league`
--
ALTER TABLE `league`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `offense_rating`
--
ALTER TABLE `offense_rating`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `defense_rating`
--
ALTER TABLE `defense_rating`
  ADD CONSTRAINT `defense_rating_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

--
-- Constraints for table `offense_rating`
--
ALTER TABLE `offense_rating`
  ADD CONSTRAINT `offense_rating_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `league` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
