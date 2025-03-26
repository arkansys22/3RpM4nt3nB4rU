-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 01:15 PM
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
-- Table structure for table `operational_kategori`
--

CREATE TABLE `operational_kategori` (
  `id` int(11) NOT NULL,
  `nomer_kategori` varchar(10) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `detail_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `operational_kategori`
--

INSERT INTO `operational_kategori` (`id`, `nomer_kategori`, `nama_kategori`, `detail_kategori`) VALUES
(1, '100003', 'IDR - BCA 167-246-8421', 'Cash/Bank'),
(2, '110303', 'Account Receivable - Wedding package', 'Account Receivable'),
(3, '110304', 'Account Receivable - Wedding Organizer Only', 'Account Receivable'),
(4, '110305', 'Account Receivable - Wedding Organizer Only (Vendor)', 'Account Receivable'),
(5, '310001', 'Opening Balance Equity', 'Equity'),
(6, '610100', 'Biaya Iklan', 'Expense'),
(7, '620101', 'Biaya Gaji, Lembur, THR', 'Expense'),
(8, '620301', 'Biaya Listrik', 'Expense'),
(9, '620303', 'Biaya Telephone dan Internet', 'Expense'),
(10, '620501', 'Biaya Sallary Freelance Photo', 'Expense'),
(11, '620701', 'Biaya Sallary Freelance Wedding Organizer', 'Expense'),
(12, '710099', 'Pendapatan Lain lain', 'Other Income'),
(13, '720002', 'Biaya Adm Bank & Buku Cek/Giro', 'Other Expense');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `operational_kategori`
--
ALTER TABLE `operational_kategori`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `operational_kategori`
--
ALTER TABLE `operational_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
