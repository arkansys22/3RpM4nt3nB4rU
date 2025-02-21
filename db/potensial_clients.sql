-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2025 at 12:48 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `potensial_clients`
--

CREATE TABLE `potensial_clients` (
  `id` int(11) NOT NULL,
  `id_session` varchar(255) NOT NULL,
  `pc_name` varchar(255) NOT NULL,
  `pc_nowa` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `chat_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `create_by` varchar(100) NOT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `potensial_clients`
--

INSERT INTO `potensial_clients` (`id`, `id_session`, `pc_name`, `pc_nowa`, `event_date`, `chat_date`, `location`, `note`, `create_by`, `create_date`, `status`) VALUES
(60, 'beeef49e54143c52e24f9befad3e8cd3fe8d6e4e', 'dhawy', '124123123', '2025-02-20', '2025-02-12', 'Bogor', 'haha', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-21 11:38:01', 'Deal');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `potensial_clients`
--
ALTER TABLE `potensial_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_session` (`id_session`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `potensial_clients`
--
ALTER TABLE `potensial_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
