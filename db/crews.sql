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
-- Struktur dari tabel `crews`
--

CREATE TABLE `crews` (
  `id` int(11) NOT NULL,
  `id_session` varchar(255) DEFAULT NULL,
  `crew_name` varchar(255) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `religion` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `age` int(11) NOT NULL,
  `joining_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','delete') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `crews`
--

INSERT INTO `crews` (`id`, `id_session`, `crew_name`, `gender`, `religion`, `phone`, `address`, `age`, `joining_date`, `created_at`, `status`) VALUES
(1, '64c40f32b5647a48057153a40cafcfab576cb4303153795c085db1094b12a989', 'hoho', 'Female', 'Kristen', '222', '222', 22, '2025-02-02', '2025-02-21 08:29:15', 'delete'),
(2, '1d7f1af822d1bad6421ba22d9c94cdffd7cf6a2bb3cdc28d1fac2b861ba79194', 'haha', 'Male', 'Islam', '111', '111', 21, '2025-01-01', '2025-02-21 08:30:17', 'active'),
(3, '93aa6eb5520c1273d6152f5be62325dadda6aac48bcde684f7b4e418d3d7872b', 'Waku waku', 'Male', 'Katolik', '121251', 'haha\r\nhihi\r\nhoho\r\nhehe\r\nhuhu', 28, '2025-01-30', '2025-02-21 08:30:50', 'active'),
(4, 'f18df5bb5ff67291a257add53d74d2d6ebb205967f2e215f1b7a2d813ef5f1be', 'lololo', 'Male', 'Hindu', '11205970', 'asfa', 24, '2025-05-26', '2025-02-21 08:31:58', 'active'),
(5, 'b003a83ea4e92e5cdb622fc05210b24cf2f44be7f2f97a1b6979d6d12f80801b', 'lelele', 'Female', 'Hindu', '142124', 'faqw', 25, '2025-05-02', '2025-02-21 08:32:33', 'active'),
(6, 'df841427ef84d0e8a596dc18358395ff7215b640a6df2b8a4706c6bb504ee895', 'asojaps', 'Male', 'Buddha', '12241', 'aag', 22, '2025-02-05', '2025-02-21 08:32:56', 'active'),
(7, '18c79d24208eb9e6fe0017c8c34dbe2ca10896a0c5b109783bd3c68883bdfc1f', 'asiog', 'Male', 'Lainnya', '124', '214', 25, '2024-02-02', '2025-02-21 08:33:13', 'active');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `crews`
--
ALTER TABLE `crews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_session` (`id_session`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `crews`
--
ALTER TABLE `crews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
