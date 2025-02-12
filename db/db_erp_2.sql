-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Feb 2025 pada 11.20
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
-- Struktur dari tabel `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `id_session` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `wedding_date` date NOT NULL,
  `location` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `clients`
--

INSERT INTO `clients` (`id`, `id_session`, `name`, `email`, `phone`, `wedding_date`, `location`, `created_at`, `updated_at`, `status`) VALUES
(5, '67a9b61a8898d', 'asg', 'bbo@nagos', '10252', '2025-05-12', '', '2025-02-10 08:17:30', '2025-02-10 09:24:11', 'delete'),
(6, '1c788cb58bc869336bce1b56c3644057a7f52e3e', 'sag', 'agsioh2@gmail.com', '10259102', '2025-05-07', 'asgnioasg', '2025-02-10 08:20:20', '2025-02-12 09:16:11', 'create'),
(9, '64df26e2957d2adaf8371e1f2e62049079d63732', 'ahiogsho', '1nosa@gap.com', '1258', '2025-02-03', 'asgg', '2025-02-12 03:48:43', '2025-02-12 03:48:43', 'create'),
(10, 'c0b663389de1be17b025790467fcfbdc955f9061', 'bajsgjbag', '1nosa@gap.com', '1257', '2025-02-05', 'asgbgoa', '2025-02-12 03:50:20', '2025-02-12 03:50:20', 'create'),
(11, '2c333b7082f6818698a5c3ac046c4c0c8c114c8c', 'nasogboas', 'aaa@gmail.com', '12950', '2025-02-03', 'bagsjg', '2025-02-12 09:16:06', '2025-02-12 09:16:06', 'create');

-- --------------------------------------------------------

--
-- Struktur dari tabel `projek`
--

CREATE TABLE `projek` (
  `id` int(11) NOT NULL,
  `id_session` varchar(255) NOT NULL,
  `nama_projek` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `wedding_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `date_create` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `projek`
--

INSERT INTO `projek` (`id`, `id_session`, `nama_projek`, `author`, `wedding_date`, `location`, `date_create`, `date_update`, `status`) VALUES
(14, '8049d5ac41856655dffbe8d4917b557762883426', 'asnfonso', 'naiosgnoasg', '2025-02-06', 'sagboagsbo', '2025-02-12 11:58:39', '2025-02-12 09:17:08', 'create'),
(15, '70fe635d781a4f1ec9511f00aaa479fd53a1b110', 'nkasfnsifao', 'asnionf', '2025-02-07', 'sagniognsaio', '2025-02-12 14:07:31', '2025-02-12 09:17:17', 'delete');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_session` (`id_session`);

--
-- Indeks untuk tabel `projek`
--
ALTER TABLE `projek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_session` (`id_session`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `projek`
--
ALTER TABLE `projek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
