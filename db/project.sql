-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Feb 2025 pada 12.54
-- Versi server: 10.1.37-MariaDB
-- Versi PHP: 7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
-- Struktur dari tabel `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `id_session` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `event_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `detail` text,
  `religion` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya') NOT NULL,
  `create_by` varchar(255) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('create','delete') DEFAULT 'create'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `project`
--

INSERT INTO `project` (`id`, `id_session`, `project_name`, `client_name`, `value`, `event_date`, `location`, `detail`, `religion`, `create_by`, `create_date`, `status`) VALUES
(1, '66ad164e3cc3a997cb2fe3c51bea2149e29f88eb821880ee0907e1718eced345', 'wakasimatsu', 'wakacau', '1251551251.00', '2025-02-05', 'shibuya', 'gpgnai', 'Islam', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-21 08:48:03', 'create'),
(2, '224eaf80d192d75925bb97bdbd0a2be128fccb1613c4db8382bdc8d9345242de', 'aaa', 'aaa', '14212441.00', '2025-12-30', '124124', 'asfsfa', 'Kristen', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-21 08:55:31', 'delete'),
(3, '43f38f467dc7edb3acf2e0b1b7f5ed42ea1647f270ee0a494f0e7025c0aa91eb', 'asgg', 'asgsga', '125215.00', '2025-04-01', 'bbb', 'asgga', 'Islam', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-21 09:26:31', 'delete'),
(4, '1341b171d6c9562bb52c9a8ea7047e988a7203ce3969f2e75c14c2fc72a16ec3', 'safsa', 'asfa', '1251.00', '2025-12-31', '1521', '12515', 'Islam', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-21 09:49:00', 'create'),
(5, 'f916ead117df8a1433a943720f3b356807e226383489a80462500481d080f6e3', 'as', 'as', '114141.00', '2025-12-31', 'shiagso', '124', 'Islam', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-21 10:58:45', 'create');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
