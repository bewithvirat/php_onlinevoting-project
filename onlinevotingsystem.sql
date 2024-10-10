-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 10, 2024 at 11:53 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlinevotingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidate_details`
--

DROP TABLE IF EXISTS `candidate_details`;
CREATE TABLE IF NOT EXISTS `candidate_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `election_id` int DEFAULT NULL,
  `candidate_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `candidate_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `candidate_photo` text COLLATE utf8mb4_general_ci,
  `inserted_by` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inserted_on` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate_details`
--

INSERT INTO `candidate_details` (`id`, `election_id`, `candidate_name`, `candidate_details`, `candidate_photo`, `inserted_by`, `inserted_on`) VALUES
(45, 40, 'cutie sharma', 'influencer', '../asset/images/candidate/9545321213_viru.jpg', 'random', '2024-10-10'),
(48, 38, 'anamika', 'kai', '../asset/images/candidate/2525851510_viru.jpg', 'random', '2024-10-10');

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

DROP TABLE IF EXISTS `elections`;
CREATE TABLE IF NOT EXISTS `elections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `election_topic` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_of_candidate` int DEFAULT NULL,
  `starting_date` date DEFAULT NULL,
  `ending_date` date DEFAULT NULL,
  `status` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inserted_by` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inserted_on` date DEFAULT NULL,
  `max_candidates` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `election_topic`, `no_of_candidate`, `starting_date`, `ending_date`, `status`, `inserted_by`, `inserted_on`, `max_candidates`) VALUES
(38, 'du', 2, '2024-10-09', '2024-10-12', 'active', 'random', '2024-10-10', NULL),
(40, 'vidhan', 1, '2024-10-11', '2024-10-12', 'inactive', 'random', '2024-10-10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_no` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` text COLLATE utf8mb4_general_ci,
  `user_role` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `contact_no`, `password`, `user_role`) VALUES
(37, 'test1', '5555', '17ba0791499db908433b80f37c5fbc89b870084b', 'voter'),
(44, 'hi', 'hj', 'b1d5781111d84f7b3fe45a0852e59758cd7a87e5', 'voter'),
(39, 'abc', 'hello', 'fb96549631c835eb239cd614cc6b5cb7d295121a', 'voter'),
(40, 'random', 'hero', '43814346e21444aaf4f70841bf7ed5ae93f55a9d', 'Admin'),
(41, 'rasgul', 'chip', 'd321d6f7ccf98b51540ec9d933f20898af3bd71e', 'voter'),
(42, 'champ ', 'yours', '1c6637a8f2e1f75e06ff9984894d6bd16a3a36a9', 'voter'),
(43, 'random', '5555', 'b6589fc6ab0dc82cf12099d1c2d40ab994e8410c', 'voter'),
(36, 'random', 'rad', '43814346e21444aaf4f70841bf7ed5ae93f55a9d', 'voter');

-- --------------------------------------------------------

--
-- Table structure for table `votings`
--

DROP TABLE IF EXISTS `votings`;
CREATE TABLE IF NOT EXISTS `votings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `election_id` int DEFAULT NULL,
  `voters_id` int DEFAULT NULL,
  `candidate_id` int NOT NULL,
  `vote_date` date DEFAULT NULL,
  `vote_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votings`
--

INSERT INTO `votings` (`id`, `election_id`, `voters_id`, `candidate_id`, `vote_date`, `vote_time`) VALUES
(79, 34, 36, 41, '2024-09-10', '15:36:40'),
(80, 36, 37, 42, '2024-09-10', '18:20:22'),
(81, 36, 39, 43, '2024-09-18', '01:58:55'),
(82, 36, 41, 44, '2024-09-18', '02:17:48'),
(83, 38, 44, 48, '2024-10-10', '16:21:24');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
