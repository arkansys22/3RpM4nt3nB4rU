-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Feb 2025 pada 12.53
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
-- Struktur dari tabel `crew_projects`
--

CREATE TABLE `crew_projects` (
  `id` int(11) NOT NULL,
  `project_id` varchar(255) NOT NULL,
  `koor_pengawas` varchar(255) NOT NULL,
  `koor_acara` varchar(255) NOT NULL,
  `koor_lapangan` varchar(255) NOT NULL,
  `koor_catering` varchar(255) NOT NULL,
  `koor_pengantin` varchar(255) NOT NULL,
  `koor_tamu` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `crew_projects`
--

INSERT INTO `crew_projects` (`id`, `project_id`, `koor_pengawas`, `koor_acara`, `koor_lapangan`, `koor_catering`, `koor_pengantin`, `koor_tamu`, `created_at`) VALUES
(1, '224', '', '', '', '', '', '', '2025-02-21 15:55:31'),
(4, '66ad164e3cc3a997cb2fe3c51bea2149e29f88eb821880ee0907e1718eced345', '18c79d24208eb9e6fe0017c8c34dbe2ca10896a0c5b109783bd3c68883bdfc1f', '', '', '', '', '', '2025-02-21 17:15:45'),
(5, 'f916ead117df8a1433a943720f3b356807e226383489a80462500481d080f6e3', '', '', '', '1d7f1af822d1bad6421ba22d9c94cdffd7cf6a2bb3cdc28d1fac2b861ba79194', '', '', '2025-02-21 17:58:45');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `crew_projects`
--
ALTER TABLE `crew_projects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `crew_projects`
--
ALTER TABLE `crew_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
