-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Feb 2025 pada 08.42
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
  `client_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `f_bride_fname` varchar(100) NOT NULL,
  `f_bride_cname` varchar(100) NOT NULL,
  `f_bride_nchild` int(2) NOT NULL,
  `f_bride_hsibling` int(2) NOT NULL,
  `f_bride_fathername` varchar(100) NOT NULL,
  `f_bride_fathercname` varchar(100) NOT NULL,
  `f_bride_mothername` varchar(100) NOT NULL,
  `f_bride_mothercname` varchar(100) NOT NULL,
  `f_bride_sibling` varchar(100) NOT NULL,
  `m_bride_fname` varchar(100) NOT NULL,
  `m_bride_cname` varchar(100) NOT NULL,
  `m_bride_nchild` int(2) NOT NULL,
  `m_bride_hsibling` int(2) NOT NULL,
  `m_bride_fathername` varchar(100) NOT NULL,
  `m_bride_fathercname` varchar(100) NOT NULL,
  `m_bride_mothername` varchar(100) NOT NULL,
  `m_bride_mothercname` varchar(100) NOT NULL,
  `m_bride_sibling` varchar(100) NOT NULL,
  `mahr` varchar(100) NOT NULL,
  `handover` varchar(100) NOT NULL,
  `female_coor` varchar(100) NOT NULL,
  `male_coor` varchar(100) NOT NULL,
  `f_spokesman` varchar(100) NOT NULL,
  `m_spokesman` varchar(100) NOT NULL,
  `wedding_officiant` varchar(100) NOT NULL,
  `guardian` varchar(100) NOT NULL,
  `f_witness` varchar(100) NOT NULL,
  `m_witness` varchar(100) NOT NULL,
  `qori` varchar(100) NOT NULL,
  `advice_doa` varchar(100) NOT NULL,
  `clamp` varchar(100) NOT NULL,
  `jasmine_carrier` varchar(100) NOT NULL,
  `mahr_carrier` varchar(100) NOT NULL,
  `ring_carrier` varchar(100) NOT NULL,
  `pastor` varchar(100) NOT NULL,
  `church` varchar(100) NOT NULL,
  `prayer` varchar(100) NOT NULL,
  `wedding_speech` varchar(100) NOT NULL,
  `wedding_date` date NOT NULL,
  `location` varchar(100) NOT NULL,
  `create_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_day` varchar(10) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `clients`
--

INSERT INTO `clients` (`id`, `id_session`, `client_name`, `email`, `phone`, `f_bride_fname`, `f_bride_cname`, `f_bride_nchild`, `f_bride_hsibling`, `f_bride_fathername`, `f_bride_fathercname`, `f_bride_mothername`, `f_bride_mothercname`, `f_bride_sibling`, `m_bride_fname`, `m_bride_cname`, `m_bride_nchild`, `m_bride_hsibling`, `m_bride_fathername`, `m_bride_fathercname`, `m_bride_mothername`, `m_bride_mothercname`, `m_bride_sibling`, `mahr`, `handover`, `female_coor`, `male_coor`, `f_spokesman`, `m_spokesman`, `wedding_officiant`, `guardian`, `f_witness`, `m_witness`, `qori`, `advice_doa`, `clamp`, `jasmine_carrier`, `mahr_carrier`, `ring_carrier`, `pastor`, `church`, `prayer`, `wedding_speech`, `wedding_date`, `location`, `create_by`, `created_at`, `create_day`, `status`) VALUES
(50, 'ce1f8cfa140582c795212e5af0667e8619f93877', 'bbb', 'gggg@g', '01824012840', '', '', 0, 0, '', '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-01-02', 'bbb', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-18 08:34:05', 'Tuesday', 'delete'),
(51, '4fe7eb389179cd5f168eee04018d7a86c0f324d5', 'kkk', 'kkk@kk', '1111', '', '', 0, 0, '', '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2025-04-02', 'kkk', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-19 04:13:06', 'Wednesday', 'create');

-- --------------------------------------------------------

--
-- Struktur dari tabel `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `id_session` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `value` decimal(15,0) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `religion` varchar(20) NOT NULL,
  `location` varchar(255) NOT NULL,
  `create_by` varchar(100) NOT NULL,
  `create_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `project`
--

INSERT INTO `project` (`id`, `id_session`, `project_name`, `client_name`, `event_date`, `value`, `detail`, `religion`, `location`, `create_by`, `create_date`, `status`) VALUES
(50, 'ce1f8cfa140582c795212e5af0667e8619f93877', 'abc', 'bbb', '2025-01-02', '12412746', 'cab', 'Lainnya', 'bbb', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-18 15:34:05', 'delete'),
(51, '4fe7eb389179cd5f168eee04018d7a86c0f324d5', 'kkk', 'kkk', '2025-04-02', '1000000', 'kkk', 'Hindu', 'kkk', '1d3ee28b20064eb055ea2315493770bf-20220408132300', '2025-02-19 11:13:06', 'create');

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
-- Indeks untuk tabel `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_session` (`id_session`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
