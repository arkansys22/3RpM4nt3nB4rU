-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Apr 2025 pada 09.29
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
-- Struktur dari tabel `crew_role`
--

CREATE TABLE `crew_role` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `detail` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `crew_role`
--

INSERT INTO `crew_role` (`id`, `role`, `detail`) VALUES
(1, 'Koordinator Acara', '1. Mengatur alur acara sesuai rundown (akad/berkat, resepsi, dll).\r\n2. Briefing & koordinasi dengan MC soal cue dan waktu.\r\n3. Menjaga ketepatan waktu tiap sesi acara.\r\n4. Menjadi penghubung antar pihak, seperti pengantin, MC, keluarga, dan vendor.\r\n5. Monitoring jalannya acara secara real-time & siap ambil keputusan saat ada perubahan.\r\n6. Bekerja sama dengan Koordinator Lapangan agar semua segmen berjalan mulus.'),
(2, 'Koordinator Lapangan', '1. Mengatur dan mengawasi tim teknis di lokasi (dekorasi, catering, dokumentasi, dll).\r\n2. Briefing kru dan vendor sebelum acara dimulai.\r\n3. Memastikan kesiapan lokasi & perlengkapan sesuai kebutuhan rundown.\r\n4. Koordinasi langsung di lapangan untuk eksekusi acara.\r\n5. Menangani kendala teknis dan situasi tak terduga di lokasi.\r\n6. Berkomunikasi intensif dengan Koordinator Acara dan seluruh tim lewat HT/chat.\r\n7. Menjadi penanggung jawab utama atas kelancaran operasional di venue.'),
(3, 'Koordinator Catering', '1. Koordinasi dengan vendor catering soal jumlah porsi, jenis menu, dan waktu penyajian.\r\n2. Memastikan makanan datang tepat waktu dan dalam kondisi baik.\r\n3. Mengatur penataan makanan & area prasmanan (buffet, stall, VIP table, dll).\r\n4. Mengatur alur antrean dan pengisian ulang makanan agar tamu nyaman dan tidak kehabisan.\r\n5. Koordinasi dengan waiter/waitress soal pelayanan tamu dan keluarga.\r\n6. Menangani komplain atau kendala di area catering dengan cepat dan solutif.\r\n7. Cek kebersihan dan kerapihan area catering sebelum, saat, dan setelah acara.'),
(4, 'Koordinator Pengantin', '1. Mendampingi pengantin dari persiapan hingga akhir acara.\r\n2. Menjaga mood & kenyamanan pengantin selama acara berlangsung.\r\n3. Mengatur waktu ganti baju, makeup, dan sesi acara sesuai rundown.\r\n4. Menyiapkan kebutuhan pribadi pengantin (buket, tisu, aksesoris, dll).\r\n5. Koordinasi dengan MUA, fotografer, dan keluarga soal sesi dan waktu.\r\n6. Mengarahkan gerakan pengantin saat masuk venue, foto, atau sesi serah-serahan.\r\n7. Siaga bantu pengantin jika ada hal darurat (makeup luntur, baju rusak, dll).'),
(5, 'Koordinator Tamu', '1. Mengarahkan tamu saat datang ke area registrasi, tempat duduk, dan spot foto.\r\n2. Mengkoordinasi usher/LO tamu agar penyambutan tamu berjalan rapi & ramah.\r\n3. Memastikan daftar tamu & sistem absensi (manual atau digital) berjalan lancar.\r\n4. Mengatur alur tamu VIP & keluarga besar agar tidak tercampur dengan tamu umum.\r\n5. Bekerja sama dengan bagian konsumsi agar tamu dapat makan dengan nyaman & teratur.\r\n6. Siap menangani pertanyaan, komplain, atau kebingungan dari tamu.\r\n7. Mengarahkan tamu ke sesi-sesi acara, seperti foto bersama, hiburan, atau area makan.'),
(6, 'Koordinator Tambahan1', '1. Membantu koordinator lain (acara, lapangan, pengantin, tamu, dll) sesuai kebutuhan.\r\n2. Siap mobile & multitasking di berbagai area acara.\r\n3. Menjadi penghubung antar tim saat komunikasi padat atau urgent.\r\n4. Menangani tugas dadakan & situasi tak terduga di lapangan.\r\n5. Membantu menjaga kelancaran flow acara dan kenyamanan tamu/pengantin.\r\n6. Membawa atau menyiapkan perlengkapan kecil (tisu, kipas, dll).\r\n7. Backup posisi yang sedang butuh bantuan tambahan.'),
(7, 'Koordinator Tambahan2', '1. Membantu koordinator lain (acara, lapangan, pengantin, tamu, dll) sesuai kebutuhan.\r\n2. Siap mobile & multitasking di berbagai area acara.\r\n3. Menjadi penghubung antar tim saat komunikasi padat atau urgent.\r\n4. Menangani tugas dadakan & situasi tak terduga di lapangan.\r\n5. Membantu menjaga kelancaran flow acara dan kenyamanan tamu/pengantin.\r\n6. Membawa atau menyiapkan perlengkapan kecil (tisu, kipas, dll).\r\n7. Backup posisi yang sedang butuh bantuan tambahan.');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `crew_role`
--
ALTER TABLE `crew_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `crew_role`
--
ALTER TABLE `crew_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
