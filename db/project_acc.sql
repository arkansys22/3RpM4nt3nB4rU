-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 05:12 AM
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
-- Table structure for table `project_acc`
--

CREATE TABLE `project_acc` (
  `id` int(200) NOT NULL,
  `id_session` varchar(255) NOT NULL,
  `project_id_session` varchar(255) NOT NULL,
  `nama_transaksi` varchar(255) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `nominal_transaksi` int(200) NOT NULL,
  `metode_transaksi` varchar(20) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_acc`
--

INSERT INTO `project_acc` (`id`, `id_session`, `project_id_session`, `nama_transaksi`, `tanggal_transaksi`, `nominal_transaksi`, `metode_transaksi`, `detail`, `status`, `create_by`, `create_date`) VALUES
(5, 'e63cc34985dacd7cbe91e57d3e922b2911863740b3fed5dbb16f807c411d4e51', 'be4a69c4396b70e2b87da42c93916f0912d585c9540d2c2ee89a63c8494287e9', 'asdasdasd', '2025-03-05', 23123, 'Transfer', 'asdsad', '', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-03-24 08:57:13'),
(6, '8f5962b8708148bfe9191165e86200ec36db7231006335f2ce31719d89402c55', 'be4a69c4396b70e2b87da42c93916f0912d585c9540d2c2ee89a63c8494287e9', 'tsess', '2025-03-10', 100000, 'Transfer', 'aaa', '', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-03-24 09:27:59'),
(7, 'e278cea3bddd8d69e8bd1e916c43613596f086fc5b4c7099eddca705603da318', 'be4a69c4396b70e2b87da42c93916f0912d585c9540d2c2ee89a63c8494287e9', 'Gaji Crew bunga', '2025-03-23', 500000, 'Transfer', '18c79d24208eb9e6fe0017c8c34dbe2ca10896a0c5b109783bd3c68883bdfc1f', '', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-03-24 09:51:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project_acc`
--
ALTER TABLE `project_acc`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project_acc`
--
ALTER TABLE `project_acc`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
