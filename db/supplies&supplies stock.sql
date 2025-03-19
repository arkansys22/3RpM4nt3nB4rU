-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Mar 2025 pada 10.33
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
-- Struktur dari tabel `supplies`
--

CREATE TABLE `supplies` (
  `id` int(11) NOT NULL,
  `id_session` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_day` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `supplies`
--

INSERT INTO `supplies` (`id`, `id_session`, `product_name`, `type`, `created_by`, `created_day`, `created_at`, `status`) VALUES
(25, '104b3a58ed08e26b6c7131760033f7642fd8a0e46ee70337393a8350a0cab711', 'asnfo', 'asnof', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 'Wednesday', '2025-03-19 00:50:49', 'created'),
(26, '2cf4b6910271fa5e9925799f2d5c1f767f13f526fce706e77d62206a06c62d36', 'asnf', 'nofsa', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 'Wednesday', '2025-03-19 00:54:34', 'created'),
(27, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', 'as', 'asgg', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 'Wednesday', '2025-03-19 01:02:21', 'created'),
(28, '8de836796eab427de8636548f628c488bc1d5066bb9ef43605cd9aebb353966f', 'asgg', 'asgggg', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 'Wednesday', '2025-03-19 01:12:51', 'created'),
(30, '07bc8d1ed207cfbd21094b828d502e5c5cb4b7c9b9cfcecae55f5fd06623f536', 'aan', 'android', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 'Wednesday', '2025-03-19 04:07:23', 'deleted');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplies_stock`
--

CREATE TABLE `supplies_stock` (
  `id` int(11) NOT NULL,
  `id_session` varchar(255) NOT NULL,
  `id_sessionstock` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `goods_in` int(11) NOT NULL,
  `goods_out` int(11) NOT NULL,
  `detail` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `supplies_stock`
--

INSERT INTO `supplies_stock` (`id`, `id_session`, `id_sessionstock`, `created_by`, `amount`, `goods_in`, `goods_out`, `detail`, `created_at`, `updated_at`, `status`) VALUES
(44, '104b3a58ed08e26b6c7131760033f7642fd8a0e46ee70337393a8350a0cab711', '9e3ff9d903a001f7fe6812d013edb4c69f870598331a16012acee8c8639142a5', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 0, 0, 0, '', '2025-03-19 00:50:49', '2025-03-19 00:50:49', 'created'),
(45, '104b3a58ed08e26b6c7131760033f7642fd8a0e46ee70337393a8350a0cab711', 'aa5a424c2265ec67a00f041b18f801171b144259fed11074c9b4cd0f31311bcb', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 124, 124, 0, '', '2025-03-19 00:50:57', '2025-03-19 00:50:57', 'created'),
(46, '2cf4b6910271fa5e9925799f2d5c1f767f13f526fce706e77d62206a06c62d36', 'bf658d4bea1163b57b77ed3b1ea0b49d471f49daf0807886cbde160f619da72b', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 0, 0, 0, '', '2025-03-19 00:54:34', '2025-03-19 00:54:34', 'created'),
(47, '2cf4b6910271fa5e9925799f2d5c1f767f13f526fce706e77d62206a06c62d36', '8ade01b1a02271a3579f48bbac4759fc02785ed81058cd7c5e064b5500e1446e', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 1222, 1222, 0, '', '2025-03-19 00:54:40', '2025-03-19 00:54:40', 'created'),
(48, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', 'f83d5c0ce137d582479ed753fc3d8bb7a52efdad0e876829ebb7be0c2311c3b8', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 0, 0, 0, '', '2025-03-19 01:02:21', '2025-03-19 01:02:21', 'created'),
(49, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', 'fc85b4b70f87067cda75f4750f8e5e12ae69b214f7c188931281cbf5b8aa79ba', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 100, 100, 0, '', '2025-03-19 01:08:12', '2025-03-19 01:08:12', 'created'),
(50, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', 'fb463a0551b365fb71d0f90f5efc9a5fcee66f592d934a43a40d8ad2fcde96ed', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 200, 200, 0, '', '2025-03-19 01:08:19', '2025-03-19 01:08:19', 'created'),
(51, '8de836796eab427de8636548f628c488bc1d5066bb9ef43605cd9aebb353966f', 'c25db5b7b0a4e43662ca17e2a71c0d4982f2adf11a14c271a165b17cabaa286c', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 0, 0, 0, '', '2025-03-19 01:12:51', '2025-03-19 01:12:51', 'created'),
(52, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '3d1f9f3263619d66d8d71fbd9f8421c40c5f3d102f5f13552ecbc853530012ac', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 644, 444, 0, '', '2025-03-19 01:12:59', '2025-03-19 01:12:59', 'created'),
(53, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '12063a1c43bfdfc51c222b8af7b6c75c3c16069d7c194a648077185ca5cebc4a', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 645, 1, 0, '', '2025-03-19 01:13:09', '2025-03-19 01:13:09', 'created'),
(54, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', 'bdc3e57d228f69f14097d31b215cfa57de8f5256b3abe2dc2d4be393ce113a5c', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 745, 100, 0, '', '2025-03-19 01:13:18', '2025-03-19 01:13:18', 'created'),
(55, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '9a9672e04b3e5c4bd1aafa3de023a5285d17dfa4b2b6b41c7a7da1a56c67c3a3', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 705, 0, 40, '', '2025-03-19 01:21:55', '2025-03-19 01:21:55', 'created'),
(56, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', 'aebda69e8ae6fac2f71d182a15801b7401ebf990da508f28b786a6619454f4ee', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 710, 5, 0, '', '2025-03-19 01:24:08', '2025-03-19 01:24:08', 'created'),
(57, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '0db8cfb917b73ed820c05085d669ab19bda3361a4ef660a59dda9a13f96a7130', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 700, 0, 10, '', '2025-03-19 01:24:16', '2025-03-19 01:24:16', 'created'),
(58, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '2096387a6c83256ff66e11cab0471f644137be62092d05102f214d2d81f82cca', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 650, 0, 50, 'masuk dari jauh', '2025-03-19 02:32:59', '2025-03-19 02:55:31', 'created'),
(62, '07bc8d1ed207cfbd21094b828d502e5c5cb4b7c9b9cfcecae55f5fd06623f536', '8aa5f2a89b7a0d7af447da2d250ba1ebd34d2a1d22721db2a5432e2e59576cc0', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 0, 0, 0, '', '2025-03-19 04:07:23', '2025-03-19 04:09:10', 'deleted'),
(63, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '33ec92d0c32ef0cd68157a1ebb9b4737fc2763b6192dd1f420850d3e7b596116', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 850, 200, 0, 'dari padang', '2025-03-19 08:45:19', '2025-03-19 08:45:19', 'created'),
(64, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '868011fa7ae0263ae8898ba8cd658d840ed96b3f3bf9886118c3f3f8a9da9446', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 840, 0, 10, 'dibawa', '2025-03-19 08:47:22', '2025-03-19 08:47:22', 'created'),
(65, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '23bda3ec61a10c9de752565aa4da629c36d4057c78f7c5cef9d2b441f4baf766', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 900, 60, 0, 'au', '2025-03-19 08:53:51', '2025-03-19 08:53:51', 'created'),
(66, '8de836796eab427de8636548f628c488bc1d5066bb9ef43605cd9aebb353966f', 'c7ee107ced5718d4b993cf68e2bd1ec59a03a7ab1067b8082b7b359d9b461f1e', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 100, 100, 0, 'kkl', '2025-03-19 08:54:19', '2025-03-19 08:54:19', 'created'),
(67, '8de836796eab427de8636548f628c488bc1d5066bb9ef43605cd9aebb353966f', '851bbcd5ea5262a4cb91421de309aad5a852995a0dd9dd4fa2358c8ab0dc81e8', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 20, 0, 80, 'kkk', '2025-03-19 08:54:30', '2025-03-19 08:54:30', 'created'),
(68, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '2d686de8d08cb0d12a6b320db9d604088091298df39b65ba82c22563f65791c9', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 100, 0, -800, 'hilang', '2025-03-19 09:03:30', '2025-03-19 09:03:30', 'created'),
(69, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '791a6e6afd67f139f9f10f42d3b411d20b627674f723e92da53dc61e530fd57b', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 120, 20, 0, 'aa', '2025-03-19 09:05:54', '2025-03-19 09:05:54', 'created'),
(70, '2db01f91e2499f9c75650e451fbcc9db922ea44faccb861ba2e30bfa7f7cbf59', '5b462358bb01ad9f706e865ee0bdadbb3fc8933aa481ea4a96c21b6ef2541da8', '1d3ee28b20064eb055ea2315493770bf-20220408132300', 70, 0, -50, 'll', '2025-03-19 09:06:12', '2025-03-19 09:06:12', 'created');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `supplies_stock`
--
ALTER TABLE `supplies_stock`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `supplies`
--
ALTER TABLE `supplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `supplies_stock`
--
ALTER TABLE `supplies_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
