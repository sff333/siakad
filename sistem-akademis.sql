-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 16, 2026 at 10:38 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem-akademis`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('Hadir','Sakit','Izin','Alpa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Hadir',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `anggota_rombel`
--

CREATE TABLE `anggota_rombel` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anggota_rombel`
--

INSERT INTO `anggota_rombel` (`id`, `siswa_id`, `tahun_ajaran`, `kelas_id`, `created_at`, `updated_at`) VALUES
(31, 31, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(32, 32, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(33, 33, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(34, 34, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(35, 35, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(36, 36, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(37, 37, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(38, 38, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(39, 39, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(40, 40, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(41, 41, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(42, 42, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(43, 43, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(44, 44, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(45, 45, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(46, 46, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(47, 47, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(48, 48, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(49, 49, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(50, 50, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(51, 51, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(52, 52, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(53, 53, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(54, 54, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(55, 55, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(56, 56, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(57, 57, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(58, 58, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(59, 59, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(60, 60, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(61, 61, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(62, 62, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(63, 63, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(64, 64, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(65, 65, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(66, 66, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(67, 67, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(68, 68, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(69, 69, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(70, 70, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(71, 71, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(72, 72, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(73, 73, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(74, 74, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(75, 75, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(76, 76, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(77, 77, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(78, 78, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(79, 79, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(80, 80, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(81, 81, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(82, 82, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(83, 83, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(84, 84, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(85, 85, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(86, 86, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(87, 87, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(88, 88, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(89, 89, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(90, 90, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(91, 91, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(92, 92, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(93, 93, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(94, 94, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(95, 95, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(96, 96, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(97, 97, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(98, 98, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(99, 99, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(100, 100, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(101, 101, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(102, 102, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(103, 103, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(104, 104, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(105, 105, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(106, 106, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(107, 107, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(108, 108, '2026/2027', 5, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(109, 109, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(110, 110, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(111, 111, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(112, 112, '2026/2027', 2, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(113, 113, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(114, 114, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(115, 115, '2026/2027', 3, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(116, 116, '2026/2027', 6, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(117, 117, '2026/2027', 4, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(118, 118, '2026/2027', 1, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(119, 119, '2026/2027', 5, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(120, 120, '2026/2027', 4, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(121, 121, '2026/2027', 5, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(122, 122, '2026/2027', 6, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(123, 123, '2026/2027', 6, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(124, 124, '2026/2027', 2, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(125, 125, '2026/2027', 3, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(126, 126, '2026/2027', 3, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(127, 127, '2026/2027', 1, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(128, 128, '2026/2027', 4, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(129, 129, '2026/2027', 4, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(130, 130, '2026/2027', 5, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(131, 131, '2026/2027', 3, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(132, 132, '2026/2027', 4, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(133, 133, '2026/2027', 6, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(134, 134, '2026/2027', 3, '2026-10-31 21:38:14', '2026-10-31 21:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pendidikan_terakhir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `user_id`, `nip`, `nama`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `telepon`, `email`, `jabatan`, `pendidikan_terakhir`, `foto`, `status_aktif`, `created_at`, `updated_at`) VALUES
(7, NULL, '-', 'R.R Sri Wulan Dari', 'P', 'Sei Sijenggi', '2001-05-19', 'Melati 1 Gang Ali, Perbaungan', '081378106679', 'wd.110917@gmail.com', 'Guru Kelas', 'S1', 'guru/bzEqD8bYtQ6FwtJESFvpkQGnACuec0kZ4FsqESTT.png', 1, '2026-10-31 21:50:48', '2026-10-31 21:50:48'),
(8, NULL, '-', 'Muhammad Rizky Ananda', 'L', 'Tualang', '1999-06-08', 'Melati 1 Gang Ali', '087763854586', 'perbaungan.id@gmail.com', 'GURU TIK', 'S1', NULL, 1, '2026-10-31 21:56:52', '2026-10-31 21:56:52'),
(9, NULL, '-', 'Masdaleva, S.Pd', 'P', 'Tg. Mulia', '1988-02-27', 'Melati 1  Gang Ali', '085261327959', NULL, 'Guru Kelas', 'S1', NULL, 1, '2026-10-31 22:05:48', '2026-10-31 22:05:48'),
(10, NULL, '-', 'Rindi Atika', 'P', 'Melati II', '1999-03-24', 'Dusun Kelapa', NULL, NULL, 'Guru Kelas', 'S1', NULL, 1, '2026-10-31 22:06:42', '2026-10-31 22:06:42'),
(11, NULL, '-', 'Sayyidatul Ulya Balqis', 'P', 'Ujung Rambung', '2003-04-12', 'Ujung Rambung', NULL, NULL, 'Guru Kelas', 'S1', NULL, 1, '2026-10-31 22:07:47', '2026-10-31 22:07:47'),
(12, NULL, '-', 'Dea Atinia Putri', 'P', 'Kota Galuh', '2004-04-18', 'Kota Galuh', '085677858841', 'deaatinia99@gmail.com', 'Guru Kelas', 'S1', 'guru/rmiFQePvcP9cVzXiFzhNsCB4vDNKEEcS0wVTAkNR.jpg', 1, '2026-10-31 22:19:43', '2026-11-08 04:29:29'),
(13, NULL, '-', 'M. Rahim', 'L', 'Tualang', '2002-05-01', 'Tualang', '081244515512', NULL, 'Guru Kelas', 'S1', NULL, 1, '2026-10-31 22:20:22', '2026-10-31 22:20:22');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `mata_pelajaran_id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED DEFAULT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `kelas_id`, `mata_pelajaran_id`, `guru_id`, `hari`, `jam_mulai`, `jam_selesai`, `tahun_ajaran_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 'Senin', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(2, 1, 3, NULL, 'Senin', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(3, 1, 4, NULL, 'Selasa', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-11-11 05:47:07'),
(4, 1, 5, NULL, 'Selasa', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(5, 1, 1, NULL, 'Rabu', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(6, 1, 6, NULL, 'Rabu', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(7, 1, 8, NULL, 'Kamis', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(8, 1, 7, NULL, 'Kamis', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(9, 1, 9, NULL, 'Jumat', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(10, 2, 15, NULL, 'Senin', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(11, 2, 20, NULL, 'Senin', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(12, 2, 25, NULL, 'Selasa', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(13, 2, 30, NULL, 'Selasa', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(14, 2, 10, NULL, 'Rabu', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(15, 2, 35, NULL, 'Rabu', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(16, 2, 45, NULL, 'Kamis', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(17, 2, 40, NULL, 'Kamis', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(18, 2, 50, NULL, 'Jumat', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(19, 3, 16, NULL, 'Senin', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(20, 3, 21, NULL, 'Senin', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(21, 3, 26, NULL, 'Selasa', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(22, 3, 31, NULL, 'Selasa', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(23, 3, 11, NULL, 'Rabu', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(24, 3, 36, NULL, 'Rabu', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(25, 3, 46, NULL, 'Kamis', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(26, 3, 41, NULL, 'Kamis', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(27, 3, 51, NULL, 'Jumat', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(28, 4, 17, NULL, 'Senin', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(29, 4, 22, NULL, 'Senin', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(30, 4, 27, NULL, 'Selasa', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(31, 4, 32, NULL, 'Selasa', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(32, 4, 12, NULL, 'Rabu', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(33, 4, 37, NULL, 'Rabu', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(34, 4, 47, NULL, 'Kamis', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(35, 4, 42, NULL, 'Kamis', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(36, 4, 52, NULL, 'Jumat', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(37, 5, 18, NULL, 'Senin', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(38, 5, 23, NULL, 'Senin', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(39, 5, 28, NULL, 'Selasa', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(40, 5, 33, NULL, 'Selasa', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(41, 5, 13, NULL, 'Rabu', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(42, 5, 38, NULL, 'Rabu', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(43, 5, 48, NULL, 'Kamis', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(44, 5, 43, NULL, 'Kamis', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(45, 5, 53, NULL, 'Jumat', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(46, 6, 19, NULL, 'Senin', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(47, 6, 24, NULL, 'Senin', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(48, 6, 29, NULL, 'Selasa', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(49, 6, 34, NULL, 'Selasa', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(50, 6, 14, NULL, 'Rabu', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(51, 6, 39, NULL, 'Rabu', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(52, 6, 49, NULL, 'Kamis', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(53, 6, 44, NULL, 'Kamis', '09:15:00', '10:45:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31'),
(54, 6, 54, NULL, 'Jumat', '07:30:00', '09:00:00', 2, '2026-10-30 02:27:31', '2026-10-30 02:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` int NOT NULL,
  `rombel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guru_id` bigint UNSIGNED DEFAULT NULL,
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL,
  `kapasitas` int NOT NULL DEFAULT '30',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `rombel`, `guru_id`, `tahun_ajaran_id`, `kapasitas`, `created_at`, `updated_at`) VALUES
(1, 'Kelas 1A', 1, 'A', 10, NULL, 30, '2026-05-03 08:19:41', '2026-11-08 04:29:43'),
(2, 'Kelas 2A', 2, 'A', 12, NULL, 30, '2026-05-03 08:19:41', '2026-10-31 22:19:50'),
(3, 'Kelas 3A', 3, 'A', 9, NULL, 30, '2026-05-03 08:19:41', '2026-10-31 22:06:50'),
(4, 'Kelas 4A', 4, 'A', 13, NULL, 30, '2026-05-03 08:19:41', '2026-10-31 22:20:34'),
(5, 'Kelas 5A', 5, 'A', 11, NULL, 30, '2026-05-03 08:19:41', '2026-10-31 22:07:58'),
(6, 'Kelas 6A', 6, 'A', 7, 2, 30, '2026-05-03 08:19:41', '2026-10-31 21:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama_pengguna` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktivitas` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `halaman` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `user_id`, `nama_pengguna`, `peran`, `aktivitas`, `halaman`, `created_at`, `updated_at`) VALUES
(1, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Masuk ke aplikasi.', NULL, '2026-11-07 23:27:07', '2026-11-07 23:27:07'),
(2, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 00:06:54', '2026-11-08 00:06:54'),
(3, 1, 'Administrator', 'Administrator', 'Masuk ke aplikasi.', NULL, '2026-11-08 03:48:57', '2026-11-08 03:48:57'),
(4, 1, 'Administrator', 'Administrator', 'Keluar dari aplikasi.', NULL, '2026-11-08 03:49:07', '2026-11-08 03:49:07'),
(5, 1, 'Administrator', 'Administrator', 'Masuk ke aplikasi.', NULL, '2026-11-08 03:49:23', '2026-11-08 03:49:23'),
(6, 1, 'Administrator', 'Administrator', 'Keluar dari aplikasi.', NULL, '2026-11-08 03:50:55', '2026-11-08 03:50:55'),
(7, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Masuk ke aplikasi.', NULL, '2026-11-08 03:51:03', '2026-11-08 03:51:03'),
(8, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Keluar dari aplikasi.', NULL, '2026-11-08 03:51:21', '2026-11-08 03:51:21'),
(9, 1, 'Administrator', 'Administrator', 'Masuk ke aplikasi.', NULL, '2026-11-08 03:51:45', '2026-11-08 03:51:45'),
(10, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 03:58:13', '2026-11-08 03:58:13'),
(11, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 03:58:18', '2026-11-08 03:58:18'),
(12, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 04:01:07', '2026-11-08 04:01:07'),
(13, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 04:01:21', '2026-11-08 04:01:21'),
(14, 1, 'Administrator', 'Administrator', 'Pengguna berhasil diperbarui.', '/pengguna/2', '2026-11-08 04:13:09', '2026-11-08 04:13:09'),
(15, 1, 'Administrator', 'Administrator', 'Pengguna berhasil diperbarui.', '/pengguna/2', '2026-11-08 04:13:17', '2026-11-08 04:13:17'),
(16, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 04:13:37', '2026-11-08 04:13:37'),
(17, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 04:18:35', '2026-11-08 04:18:35'),
(18, 1, 'Administrator', 'Administrator', 'Data guru berhasil diperbarui!', '/guru/12', '2026-11-08 04:22:54', '2026-11-08 04:22:54'),
(19, 1, 'Administrator', 'Administrator', 'Data guru berhasil diperbarui!', '/guru/12', '2026-11-08 04:23:59', '2026-11-08 04:23:59'),
(20, 1, 'Administrator', 'Administrator', 'Data guru berhasil diperbarui!', '/guru/12', '2026-11-08 04:24:11', '2026-11-08 04:24:11'),
(21, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Masuk ke aplikasi.', NULL, '2026-11-08 04:24:28', '2026-11-08 04:24:28'),
(22, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 04:24:38', '2026-11-08 04:24:38'),
(23, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-08 04:29:10', '2026-11-08 04:29:10'),
(24, 1, 'Administrator', 'Administrator', 'Data guru berhasil diperbarui!', '/guru/12', '2026-11-08 04:29:29', '2026-11-08 04:29:29'),
(25, 1, 'Administrator', 'Administrator', 'Data kelas berhasil diperbarui!', '/kelas/1', '2026-11-08 04:29:37', '2026-11-08 04:29:37'),
(26, 1, 'Administrator', 'Administrator', 'Data kelas berhasil diperbarui!', '/kelas/1', '2026-11-08 04:29:43', '2026-11-08 04:29:43'),
(27, 1, 'Administrator', 'Administrator', 'Masuk ke aplikasi.', NULL, '2026-11-10 02:48:55', '2026-11-10 02:48:55'),
(28, 1, 'Administrator', 'Administrator', 'Masuk ke aplikasi.', NULL, '2026-11-10 03:04:48', '2026-11-10 03:04:48'),
(29, 1, 'Administrator', 'Administrator', 'Pengaturan predikat disimpan dan predikat nilai kelas ini sudah diperbarui.', '/nilai/predikat', '2026-11-10 03:05:45', '2026-11-10 03:05:45'),
(30, 1, 'Administrator', 'Administrator', 'Kegiatan ekstrakurikuler berhasil disimpan.', '/nilai/ekstrakurikuler', '2026-11-10 03:07:13', '2026-11-10 03:07:13'),
(31, 1, 'Administrator', 'Administrator', 'Keluar dari aplikasi.', NULL, '2026-11-10 07:09:12', '2026-11-10 07:09:12'),
(32, 1, 'Administrator', 'Administrator', 'Masuk ke aplikasi.', NULL, '2026-11-11 05:28:20', '2026-11-11 05:28:20'),
(33, 1, 'Administrator', 'Administrator', 'Akun berhasil dinonaktifkan.', '/pengguna/2/status', '2026-11-11 05:28:29', '2026-11-11 05:28:29'),
(34, 1, 'Administrator', 'Administrator', 'Akun berhasil diaktifkan.', '/pengguna/2/status', '2026-11-11 05:29:00', '2026-11-11 05:29:00'),
(35, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Masuk ke aplikasi.', NULL, '2026-11-11 05:29:04', '2026-11-11 05:29:04'),
(36, 1, 'Administrator', 'Administrator', 'Masuk ke aplikasi.', NULL, '2026-11-11 05:33:27', '2026-11-11 05:33:27'),
(37, 1, 'Administrator', 'Administrator', 'Kop surat buku rapor berhasil diperbarui!', '/rapor/kop-surat', '2026-11-11 05:36:57', '2026-11-11 05:36:57'),
(38, 1, 'Administrator', 'Administrator', 'Nilai dan capaian kompetensi berhasil disimpan.', '/nilai', '2026-11-11 05:37:49', '2026-11-11 05:37:49'),
(39, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-11 05:37:50', '2026-11-11 05:37:50'),
(40, 1, 'Administrator', 'Administrator', 'Nilai dan capaian kompetensi berhasil disimpan.', '/nilai', '2026-11-11 05:38:04', '2026-11-11 05:38:04'),
(41, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-11 05:39:32', '2026-11-11 05:39:32'),
(42, 1, 'Administrator', 'Administrator', 'Data siswa berhasil ditambahkan ke Buku Induk!', '/siswa', '2026-11-11 05:44:10', '2026-11-11 05:44:10'),
(43, 1, 'Administrator', 'Administrator', 'Data siswa berhasil dihapus!', '/siswa/135', '2026-11-11 05:44:16', '2026-11-11 05:44:16'),
(44, 1, 'Administrator', 'Administrator', 'Buku Induk siswa berhasil diperbarui!', '/siswa/31', '2026-11-11 05:44:25', '2026-11-11 05:44:25'),
(45, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Data kelas berhasil ditambahkan!', '/kelas', '2026-11-11 05:45:59', '2026-11-11 05:45:59'),
(46, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Data kelas berhasil dihapus!', '/kelas/7', '2026-11-11 05:46:05', '2026-11-11 05:46:05'),
(47, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Jadwal kelas berhasil diperbarui.', '/kelas/1/jadwal/3', '2026-11-11 05:46:58', '2026-11-11 05:46:58'),
(48, 2, 'Muhammad Rizky Ananda', 'Operator Sekolah', 'Jadwal kelas berhasil diperbarui.', '/kelas/1/jadwal/3', '2026-11-11 05:47:07', '2026-11-11 05:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kkm` int NOT NULL DEFAULT '70',
  `guru_id` bigint UNSIGNED DEFAULT NULL,
  `is_pilar` tinyint(1) NOT NULL DEFAULT '0',
  `is_muatan_lokal` tinyint(1) NOT NULL DEFAULT '0',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kelas_id` bigint UNSIGNED DEFAULT NULL,
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id`, `kode_mapel`, `nama_mapel`, `kkm`, `guru_id`, `is_pilar`, `is_muatan_lokal`, `status_aktif`, `created_at`, `updated_at`, `kelas_id`, `tahun_ajaran_id`) VALUES
(1, 'PAI', 'Pendidikan Agama Islam', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, 2),
(2, 'PKN', 'Pendidikan Kewarganegaraan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, 2),
(3, 'BIN', 'Bahasa Indonesia', 70, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-10-31 04:16:48', 1, 2),
(4, 'MTK', 'Matematika', 65, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, 2),
(5, 'IPA', 'Ilmu Pengetahuan Alam', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, 2),
(6, 'IPS', 'Ilmu Pengetahuan Sosial', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, 2),
(7, 'SBK', 'Seni Budaya dan Keterampilan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, 2),
(8, 'PJOK', 'Pendidikan Jasmani', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, 2),
(9, 'BING', 'Bahasa Inggris', 65, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, 2),
(10, 'PAI', 'Pendidikan Agama Islam', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 2, 2),
(11, 'PAI', 'Pendidikan Agama Islam', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 3, 2),
(12, 'PAI', 'Pendidikan Agama Islam', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 4, 2),
(13, 'PAI', 'Pendidikan Agama Islam', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 5, 2),
(14, 'PAI', 'Pendidikan Agama Islam', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 6, 2),
(15, 'PKN', 'Pendidikan Kewarganegaraan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 2, 2),
(16, 'PKN', 'Pendidikan Kewarganegaraan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 3, 2),
(17, 'PKN', 'Pendidikan Kewarganegaraan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 4, 2),
(18, 'PKN', 'Pendidikan Kewarganegaraan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 5, 2),
(19, 'PKN', 'Pendidikan Kewarganegaraan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 6, 2),
(20, 'BIN', 'Bahasa Indonesia', 70, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-10-31 04:16:48', 2, 2),
(21, 'BIN', 'Bahasa Indonesia', 70, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-10-31 04:16:48', 3, 2),
(22, 'BIN', 'Bahasa Indonesia', 70, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-10-31 04:16:48', 4, 2),
(23, 'BIN', 'Bahasa Indonesia', 70, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-10-31 04:16:48', 5, 2),
(24, 'BIN', 'Bahasa Indonesia', 70, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-10-31 04:16:48', 6, 2),
(25, 'MTK', 'Matematika', 65, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 2, 2),
(26, 'MTK', 'Matematika', 65, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 3, 2),
(27, 'MTK', 'Matematika', 65, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 4, 2),
(28, 'MTK', 'Matematika', 65, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 5, 2),
(29, 'MTK', 'Matematika', 65, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 6, 2),
(30, 'IPA', 'Ilmu Pengetahuan Alam', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 2, 2),
(31, 'IPA', 'Ilmu Pengetahuan Alam', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 3, 2),
(32, 'IPA', 'Ilmu Pengetahuan Alam', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 4, 2),
(33, 'IPA', 'Ilmu Pengetahuan Alam', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 5, 2),
(34, 'IPA', 'Ilmu Pengetahuan Alam', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 6, 2),
(35, 'IPS', 'Ilmu Pengetahuan Sosial', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 2, 2),
(36, 'IPS', 'Ilmu Pengetahuan Sosial', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 3, 2),
(37, 'IPS', 'Ilmu Pengetahuan Sosial', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 4, 2),
(38, 'IPS', 'Ilmu Pengetahuan Sosial', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 5, 2),
(39, 'IPS', 'Ilmu Pengetahuan Sosial', 70, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 6, 2),
(40, 'SBK', 'Seni Budaya dan Keterampilan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 2, 2),
(41, 'SBK', 'Seni Budaya dan Keterampilan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 3, 2),
(42, 'SBK', 'Seni Budaya dan Keterampilan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 4, 2),
(43, 'SBK', 'Seni Budaya dan Keterampilan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 5, 2),
(44, 'SBK', 'Seni Budaya dan Keterampilan', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 6, 2),
(45, 'PJOK', 'Pendidikan Jasmani', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 2, 2),
(46, 'PJOK', 'Pendidikan Jasmani', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 3, 2),
(47, 'PJOK', 'Pendidikan Jasmani', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 4, 2),
(48, 'PJOK', 'Pendidikan Jasmani', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 5, 2),
(49, 'PJOK', 'Pendidikan Jasmani', 75, NULL, 0, 0, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 6, 2),
(50, 'BING', 'Bahasa Inggris', 65, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 2, 2),
(51, 'BING', 'Bahasa Inggris', 65, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 3, 2),
(52, 'BING', 'Bahasa Inggris', 65, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 4, 2),
(53, 'BING', 'Bahasa Inggris', 65, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 5, 2),
(54, 'BING', 'Bahasa Inggris', 65, NULL, 0, 1, 1, '2026-05-03 08:19:41', '2026-05-03 08:19:41', 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_create_profil_sekolah_table', 1),
(5, '2024_01_01_000002_create_tahun_ajaran_table', 1),
(6, '2024_01_01_000003_create_guru_table', 1),
(7, '2024_01_01_000004_create_kelas_table', 1),
(8, '2024_01_01_000005_create_siswa_table', 1),
(9, '2024_01_01_000006_create_mata_pelajaran_table', 1),
(10, '2024_01_01_000007_create_jadwal_table', 1),
(11, '2024_01_01_000008_create_absensi_table', 1),
(12, '2024_01_01_000009_create_nilai_table', 1),
(13, '2024_01_01_000010_create_pengumuman_table', 1),
(14, '2024_01_01_000011_add_role_to_users_table', 1),
(15, '2026_10_24_150056_add_buku_induk_fields_to_siswa_table', 2),
(16, '2026_10_24_152000_add_kop_surat_fields_to_profil_sekolah_table', 3),
(17, '2026_10_31_000001_add_rapor_inputs', 4),
(18, '2026_10_31_000002_create_anggota_rombel_table', 5),
(19, '2026_10_31_000003_create_pengaturan_predikat_table', 6),
(20, '2026_10_31_000004_scope_subjects_to_classes', 7),
(21, '2026_11_01_000001_allow_incomplete_student_and_teacher_imports', 8),
(22, '2026_11_08_000001_update_user_roles', 9),
(23, '2026_11_08_000002_add_is_active_to_users_table', 10),
(24, '2026_11_08_000003_create_log_aktivitas_table', 11),
(25, '2026_11_08_000004_add_page_permissions_to_users', 12);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `mata_pelajaran_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED NOT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT NULL,
  `nilai_uts` decimal(5,2) DEFAULT NULL,
  `nilai_uas` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `predikat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `capaian_kompetensi` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id`, `siswa_id`, `mata_pelajaran_id`, `kelas_id`, `tahun_ajaran_id`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_akhir`, `predikat`, `catatan`, `created_at`, `updated_at`, `capaian_kompetensi`) VALUES
(1, 31, 3, 1, 2, 100.00, 90.00, 90.00, 93.00, 'A', NULL, '2026-11-11 05:37:49', '2026-11-11 05:37:49', 'Bagus'),
(2, 31, 9, 1, 2, 100.00, 85.00, 90.00, 91.50, 'A', NULL, '2026-11-11 05:38:04', '2026-11-11 05:38:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_ekstrakurikuler`
--

CREATE TABLE `nilai_ekstrakurikuler` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED NOT NULL,
  `kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `predikat` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai_ekstrakurikuler`
--

INSERT INTO `nilai_ekstrakurikuler` (`id`, `siswa_id`, `tahun_ajaran_id`, `kegiatan`, `predikat`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 31, 2, 'Pramuka', 'Baik', 'Belajar dengan baik', '2026-11-10 03:07:13', '2026-11-10 03:07:13');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_predikat`
--

CREATE TABLE `pengaturan_predikat` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED NOT NULL,
  `min_a` decimal(5,2) NOT NULL,
  `min_b` decimal(5,2) NOT NULL,
  `min_c` decimal(5,2) NOT NULL,
  `min_d` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaturan_predikat`
--

INSERT INTO `pengaturan_predikat` (`id`, `kelas_id`, `tahun_ajaran_id`, `min_a`, `min_b`, `min_c`, `min_d`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 90.00, 80.00, 70.00, 60.00, '2026-11-10 03:05:45', '2026-11-10 03:05:45');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `tanggal_publikasi` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `kategori`, `user_id`, `is_published`, `tanggal_publikasi`, `created_at`, `updated_at`) VALUES
(1, 'Jadwal Penilaian Akhir Semester (PAS)', 'Diberitahukan kepada seluruh bapak/ibu guru, siswa, dan orang tua/wali murid bahwa Penilaian Akhir Semester (PAS) Genap akan dilaksanakan serentak mulai tanggal 2 Juni hingga 9 Juni 2026. Harap seluruh siswa mempersiapkan diri dengan baik.', 'Akademik', 1, 1, '2026-10-24', '2026-10-24 06:41:59', '2026-10-31 22:18:19'),
(2, 'Pelepasan & Gelar Karya Siswa Berprestasi Kelas 6', 'Kegiatan pelepasan siswa kelas 6 sekaligus pameran karya seni dan inovasi proyek P5 akan diselenggarakan di aula utama sekolah pada hari Sabtu, 20 Juni 2026. Seluruh wali murid diundang untuk menghadiri acara apresiasi ini.', 'Kegiatan', 1, 1, '2026-10-21', '2026-10-24 06:41:59', '2026-10-24 06:41:59'),
(3, 'Sosialisasi Akses E-Rapor Digital bagi Orang Tua Siswa', 'Sekolah telah mengaktifkan layanan cetak rapor digital dan transparansi nilai melalui Sistem Akademik SD. Wali murid dapat memantau capaian belajar putra-putrinya melalui modul rapor yang disediakan.', 'Sistem', 1, 1, '2026-10-18', '2026-10-24 06:41:59', '2026-10-24 06:41:59');

-- --------------------------------------------------------

--
-- Table structure for table `profil_sekolah`
--

CREATE TABLE `profil_sekolah` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_induk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'PEMERINTAH KOTA / DINAS PENDIDIKAN',
  `sub_instansi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `npsn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nss` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akreditasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kelurahan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kabupaten` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kepala_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip_kepala_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_kiri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_garis_kop` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'ganda',
  `visi` text COLLATE utf8mb4_unicode_ci,
  `misi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profil_sekolah`
--

INSERT INTO `profil_sekolah` (`id`, `instansi_induk`, `sub_instansi`, `nama_sekolah`, `npsn`, `nss`, `akreditasi`, `alamat`, `kelurahan`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `telepon`, `email`, `website`, `kepala_sekolah`, `nip_kepala_sekolah`, `logo`, `logo_kiri`, `tipe_garis_kop`, `visi`, `misi`, `created_at`, `updated_at`) VALUES
(1, 'PEMERINTAH KOTA JAKARTA PUSAT / DINAS PENDIDIKAN', 'WILAYAH KECAMATAN MENTENG', 'SD IT AL-FALAH', '70001424', '234234', 'B', 'Jalan Kebun Kelapa, Lingkungan VII', 'Tualang', 'Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', '087763854544', 'sditalfalahperbaungan@gmail.com', NULL, 'Nurul Hidayani, S.Kom', '-', NULL, NULL, 'ganda', 'Menjadi sekolah unggul yang menghasilkan lulusan berkarakter, cerdas, dan berakhlak mulia.', '1. Menyelenggarakan pendidikan yang berkualitas\r\n2. Membentuk karakter siswa yang berakhlak mulia\r\n3. Mengembangkan potensi siswa secara optimal\r\n4. Menciptakan lingkungan belajar yang kondusif', '2026-05-03 08:19:41', '2026-11-11 05:36:57');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7c9mtlwsOvqc4bHT1hWRweVZ0Kf324q7x7WAeYNS', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJBclg4Q0RNanh6WEVqQU1oWGhlcU8xSm1CeVdTcUVha3FkUkY1eGFGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo1MDAwXC9uaWxhaT9rZWxhc19pZD0xJm1hcGVsX2lkPTMmbW9kZT1wZWxhamFyYW4mdGFodW5fYWphcmFuX2lkPTIiLCJyb3V0ZSI6Im5pbGFpLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1794405516),
('hyxl9kmI40iMuW17xaR0OelYgOZuzLu0oasi4m07', 1, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Mobile Safari/537.36', 'eyJfdG9rZW4iOiJPWFk1VHc5SktYWWluR0FRRkNPV3ZtYXZBS2lUVkppRXRERG5JQm1RIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3NkaXQuYm95Y3JlYXRpdmUubXkuaWRcL2Fic2Vuc2lcL3Jla2FwIiwicm91dGUiOiJhYnNlbnNpLnJla2FwIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1794407588),
('MtO2oR5LY25987H4yzBTQd5sdiXquBQc6yih8h1f', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIzb1NveGcxdExubEdNZFUxcEExTThtMldTMDNOTlJtbkVjWkJqblVNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo1MDAwXC9yYXBvciIsInJvdXRlIjoicmFwb3IuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1794405486);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_panggilan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kewarganegaraan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'WNI',
  `anak_ke` tinyint UNSIGNED DEFAULT NULL,
  `jumlah_saudara` tinyint UNSIGNED DEFAULT NULL,
  `golongan_darah` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '-',
  `tinggi_badan` smallint UNSIGNED DEFAULT NULL,
  `berat_badan` smallint UNSIGNED DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `rt` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelurahan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kabupaten` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pos` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tinggal_bersama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon_ortu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_ayah` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pendidikan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_ibu` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pendidikan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hubungan_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('aktif','lulus','pindah','keluar') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `tanggal_masuk` date DEFAULT NULL,
  `asal_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_ijazah_sebelumnya` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nisn`, `nik`, `nama`, `nama_panggilan`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `kewarganegaraan`, `anak_ke`, `jumlah_saudara`, `golongan_darah`, `tinggi_badan`, `berat_badan`, `alamat`, `rt`, `rw`, `kelurahan`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `tinggal_bersama`, `telepon_ortu`, `nama_ayah`, `nik_ayah`, `pekerjaan_ayah`, `pendidikan_ayah`, `penghasilan_ayah`, `nama_ibu`, `nik_ibu`, `pekerjaan_ibu`, `pendidikan_ibu`, `penghasilan_ibu`, `nama_wali`, `hubungan_wali`, `pekerjaan_wali`, `telepon_wali`, `foto`, `kelas_id`, `status`, `tanggal_masuk`, `asal_sekolah`, `nomor_ijazah_sebelumnya`, `created_at`, `updated_at`) VALUES
(31, '001', '3202701840', '1218025911200002', 'Aara Ghaisani', NULL, 'P', 'Perbaungan', '2020-11-19', 'Islam', 'Indonesia', 1, 0, '-', NULL, NULL, 'Tualang', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Gunawan', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'Sahrani Indah Sari', NULL, 'Tidak bekerja', 'SD / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-11-11 05:44:25'),
(32, NULL, '3154517005', '1218020710150004', 'ABDILLA RIZKHUA RANGKUTI', NULL, 'L', 'PERBAUNGAN', '2015-10-07', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'LINGKUNGAN III', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'RAJALI RANGKUTI', NULL, 'Sudah Meninggal', 'SMA / sederajat', NULL, 'ASNI', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(33, NULL, '3171548350', '1218020612170002', 'ABI RAPATIH', NULL, 'L', 'Tualang', '2017-12-06', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'Lingkungan II', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Supriadi', NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'Suarni', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(34, NULL, '3203658889', '1218021001200004', 'ADAM GIBRAN ALGHIFARI', NULL, 'L', 'SERDANG BEDAGAI', '2020-01-10', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'DUSUN I DESA PEMATANG SIJONAM', '0', '0', 'Pematang Sijonam', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'FADHIL FALDIANSYAH', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'ROHIMATUN RISA', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(35, '018', '3153990353', NULL, 'ADE AMJAYA', NULL, 'L', 'KOTA GALUH', '2015-12-14', 'Islam', 'Indonesia', 2, 2, NULL, NULL, NULL, 'KOTA GALUH', '0', '0', 'KOTA GALUH', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'ASMAUL HUSNA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(36, '019', '3145370276', '1218010111140003', 'ADIT PRASETYO', NULL, 'L', 'LUBUK SABAN', '2014-11-01', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'LUBUK SABAN', '0', '0', 'LUBUK SABAN', 'Kec. Pantai Cermin', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'DANI UMBARA', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'HARIANI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(37, NULL, '3200379911', '1218022309200003', 'AFIF AHWAL SAID', NULL, 'L', 'SERDANG BEDAGAI', '2020-09-23', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'PEMATANG SIJONAM', '0', '0', 'Desa/Kel. Kesatuan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'NUR RAHIM', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'SRI ERNAWATI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(38, NULL, '3206200902', '1218021505200005', 'AHMAD FAJRI RISKI RAMADHAN', NULL, 'L', 'PERBAUNGAN', '2020-05-15', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'LINGKUNGAN VII', NULL, NULL, '-', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'HANAFI', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'FITRIYANTI TANJUNG', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(39, '020', '3150571414', '1472050802150001', 'AHMAD PAZRI ARAHMAN', NULL, 'L', 'TUALANG', '2015-02-08', 'Islam', 'Indonesia', 2, 2, NULL, NULL, NULL, 'TUALANG', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'DEWI PUSPITA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(40, NULL, '3175346804', '1218020808170001', 'AIDIL MUSTAQIM', NULL, 'L', 'Perbaungan', '2017-08-08', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'JL. JOHAR LINK I', '0', '0', 'Desa/Kel. Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Zainal', NULL, 'Sudah Meninggal', 'SMA / sederajat', NULL, 'Susilawati Lubis', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(41, NULL, '3190229888', '1218026201190001', 'AIRISH LATHISYAH', NULL, 'P', 'TUALANG', '2019-01-22', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGK.VI KEL.TUALANG', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'TRI INDRA TARUNA', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'NILA SORAYA', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(42, NULL, '3172540716', '1218025502170003', 'AISYAH AYUDIA INARA', NULL, 'P', 'TUALANG', '2017-02-15', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'JL. KARYA LINK VI', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'HERI WAHYUDI', NULL, 'Buruh', 'SD / sederajat', NULL, 'SUKMAWATI', NULL, 'Tidak bekerja', 'SD / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(43, NULL, '3171678548', '1271036206170009', 'AISYAH JEUMPA LUTHFIA HASIBUAN', NULL, 'P', 'MEDAN', '2017-06-22', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'PERBAUNGAN', '0', '0', 'Desa/Kel. Kota Galuh', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'LUTHFI IQBAL HASIBUAN', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'MUTHIA MUTHMAINNAH, SE', NULL, 'Karyawan BUMN', 'S1', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(44, NULL, '3160282237', '1218024812160003', 'AISYAH SRI AN NURTIAWAN', NULL, 'P', 'PERBAUNGAN', '2016-12-08', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'JL. RUMAH SAKIT NO 10', '0', '0', 'Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'AGUSTIAWAN', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'NURMALILA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(45, NULL, '3164257681', '1218054501160001', 'AL AZHURA MAULANA', NULL, 'P', 'TUALANG', '2016-01-05', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'TUALANG', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Karyawan Swasta', 'SMP / sederajat', NULL, 'SURIYANI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(46, NULL, '3156999609', '1103162610150001', 'AL BAGAS RAJENDRA', NULL, 'L', 'LANGKAT', '2015-10-26', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, '-', '0', '0', 'KEUDE', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'ANDI HARFA\'I', NULL, 'PNS/TNI/Polri', 'SMA / sederajat', NULL, 'MASDALEVA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(47, NULL, '3193550134', '1218021703190003', 'AL FATIH MUHAMMAD SINULINGGA', NULL, 'L', 'PERBAUNGAN', '2019-04-17', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'JL. SERDANG GANG. FAMILI', '0', '0', 'Kota Galuh', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'KARSA HARI WIBOWO SINULINGGA', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'RUSMITA YANTI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(48, '021', '3158070179', '1218024806150005', 'AL ZAHRA HUMAIRAH', NULL, 'P', 'LUBUK PAKAM', '2015-06-08', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'JL. DELI', NULL, NULL, 'Desa/Kel. Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'HASAN BASRI SIREGAR', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'EVA NIRWANTI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(49, NULL, '3186514750', '1218021905180001', 'ALBIAN DWI RAMADHAN', NULL, 'L', 'TUALANG', '2018-05-19', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGKUNGAN X', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'DANA WIRAYANTO', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'EVA YULIANA', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(50, NULL, '3175611627', '1218021711170003', 'ALFANDI FAIZA', NULL, 'L', 'PERBAUNGAN', '2017-11-17', 'Islam', 'Indonesia', 2, 2, NULL, NULL, NULL, 'LINGKUNGAN IV', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SUKANDAR', NULL, 'Pedagang Besar', 'SMA / sederajat', NULL, 'DIANA PUTRI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(51, NULL, '3160959186', '1218020909160002', 'Alfin Syahputra', NULL, 'L', 'Perbaungan', '2016-09-09', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'LINK IV', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Sukandar', NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'Diana Putri', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(52, NULL, '3199744125', '1103160212190001', 'ALIF KIANDRA', NULL, 'L', 'ACEH TIMUR', '2019-12-02', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'TUALANG', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'ANDI HARFA\'I', NULL, 'PNS/TNI/Polri', 'SMA / sederajat', NULL, 'MASDALEVA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(53, NULL, '3195414877', '1218021503190006', 'ALVINO ALRAIS MAHADIKA', NULL, 'L', 'PERBAUNGAN', '2019-03-15', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGKUNGAN IV', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'MHD.HAMDANI', NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'FITRI IRMALASARI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(54, NULL, '3203411770', '1218024409200003', 'AMEERATU KHANZA', NULL, 'P', 'PERBAUNGAN', '2020-09-04', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'DUSUN II', '0', '0', 'Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'MUHAMMAD IQBAL', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'NADA MUSTIKA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(55, '022', '3150401126', '1218024404150003', 'APRILLIA RAHMI', NULL, 'P', 'TUALANG', '2015-04-04', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'KOTA GALUH', '0', '0', 'KOTA GALOH', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'SRI ERNAWATI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(56, '023', '3158161916', '1218025007150002', 'AQILA AZAHRA', NULL, 'P', 'TUALANG', '2015-07-10', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'JL. KARYA', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'ERNA WATI', NULL, 'Lainnya', 'D3', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(57, NULL, '3186997335', '1218023006180003', 'ARIEL HAGANTA WIJAYA', NULL, 'L', 'PERBAUNGAN', '2018-06-30', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'GG. JAMBU N0.60 LINK. MANGGIS', '0', '0', 'Desa/Kel. Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'AMIN SUYONO', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'MELISA HARIS SOERAYA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(58, NULL, '3170290023', '1218024809170005', 'ARUMI NASHA RAZITA ALWI', NULL, 'P', 'KISARAN', '2017-09-08', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'MELATI', '0', '0', 'Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'WIBI SONO', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'CUT ALFI SARI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(59, NULL, '3172752305', '1218020309170001', 'Arya Setyadi', NULL, 'L', 'Perbaungan', '2017-09-03', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'LINK VII', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Junaidi', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'Dormauli Panggabean', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(60, NULL, '0163430564', '1218024701160003', 'ASIFA RAFANDA', NULL, 'P', 'TUALANG', '2016-01-07', 'Islam', 'Indonesia', 2, 2, NULL, NULL, NULL, 'LINGKUNGAN IV', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'ANDI SAPUTRA', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'LENA', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(61, '024', '3145265837', '1218026512140003', 'AULIA ZAHRA MARBUN', NULL, 'P', 'TUALANG', '2014-12-25', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'LINK III', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'NUR \'AINUN', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(62, NULL, NULL, NULL, 'AYSILA RAMADHANI', NULL, 'P', 'PASAR BENGKEL', '2018-05-29', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'TUALANG', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'PRAYOGA WIDODO', NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'FARIDAH HANUM', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(63, NULL, '3173277654', '1218025002170001', 'Ayumi Qintara', NULL, 'P', 'Melati I', '2017-02-10', 'Islam', 'Indonesia', 3, 1, NULL, NULL, NULL, 'LINK I JL. JOHAR', '0', '0', 'Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Supriono', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'Julianti', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(64, NULL, '3166381534', '1218026404160002', 'CAHAYA ADEVA', NULL, 'P', 'PEMATANG SETRAK', '2016-04-24', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'LINGKUNGAN VI', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SARIFUDDIN LUBIS', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'NILA SORAYA', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(65, NULL, '3170526649', '1218024808170003', 'CINTA TRI ARISTA', NULL, 'P', 'Tualang', '2017-08-08', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'Lingkungan IV', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Ade Arianto', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'Lusia Dewi', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(66, NULL, '3154615075', '1218036506150004', 'DARA PUSPITA SARI LUBIS', NULL, 'P', 'TUALANG', '2015-06-25', 'Islam', 'Indonesia', 4, 3, NULL, NULL, NULL, 'DUSUN DARUL AMAN', '0', '0', 'SEI BULUH', 'Kec. Teluk Mengkudu', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'RIJAL HADI LUBIS', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'SAMSINAR BR DAMANIK', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(67, NULL, '3189973690', '1218020805180001', 'DENISWARA GIANDRA', NULL, 'L', 'TUALANG', '2018-05-08', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'TUALANG', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'MUHAMMAD WAHYU', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'PERTIWI DIANINGSIH', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(68, NULL, '3171734361', '1218026404170003', 'FAHIRA ANJANI', NULL, 'P', 'TUALANG', '2017-04-24', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGKUNGAN VIII', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'FARIZA PUTRA PRABOWO', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'ISNAINI PUTRI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(69, '025', '3150999806', '1218026201150003', 'FAIHA NADAZALFA', NULL, 'P', 'PERBAUNGAN', '2015-01-22', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'SEI SIJENGGI', '0', '0', 'SEI SIJENGGI', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'FIFI ANGGRAINI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(70, NULL, NULL, '1218021905200004', 'FAQI FAURIZHA', NULL, 'L', 'PERBAUNGAN', '2019-05-19', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'LINGKUNGAN IX', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'ANTO', NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'ANGGI ERWANTI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(71, NULL, '3205066243', '1218021905200003', 'FARIS FAURIZHA', NULL, 'L', 'PERBAUNGAN', '2020-05-19', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGKUNGAN IX', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'ANTO', NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'ANGGRI ERWANTI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(72, NULL, '3194412717', '1218023105190001', 'FATHIAN ZIDAN ALFAREZI', NULL, 'L', 'PERBAUNGAN', '2019-05-31', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'DUSUN II', '0', '0', 'Desa/Kel. Lidah Tanah', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'MUHAMMAD RIANTO', NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'LINDA WATI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(73, '026', '3154249392', '1218024503150006', 'FATIMAH AQILA', NULL, 'P', 'MEDAN', '2015-03-05', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'LINGKUNGAN VIII', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'ISNAINI PUTRI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(74, NULL, '3166727566', '1218020803160002', 'HAFIZ RIFQI PRANAJA', NULL, 'L', 'TUALANG', '2016-03-08', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'LINGKUNGAN XI', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'DEKA SYAMSARI', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'SRI NINGSIH', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(75, NULL, '3191148362', '1271034808190005', 'HUMAIRA GEULIMA LUTHFIA HASIBUAN', NULL, 'P', 'MEDAN', '2019-08-08', 'Islam', 'Indonesia', 2, 0, NULL, NULL, NULL, 'SIMPANG TIGA PEKAN', '0', '0', 'Desa/Kel. Kota Galuh', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'LUTHFI IQBAL HASIBUAN', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'MUTHIA MUTHMAINNAH, SE', NULL, 'Karyawan Swasta', 'S1', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(76, NULL, '3196633118', '1218020603190004', 'I KOMANG AZZAM', NULL, 'L', 'SERDANG BEDAGAI', '2019-03-06', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'DUSUN DELIMA', '0', '0', 'Desa/Kel. Melati Dua', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'KETUT SUBRATA', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'EVI SUSANTI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(77, NULL, '3179142118', '1218020403170001', 'IMAM MAHADI', NULL, 'L', 'PERBAUNGAN', '2017-03-04', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'JALAN DELI', '0', '0', 'Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'IRFAN MAHADI', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'SARIANI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(78, NULL, '3206651562', '1218020504200003', 'IZZATUL IRSYAM', NULL, 'L', 'Deli serdang', '2020-04-05', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'kota galuh', '0', '0', 'Desa/Kel. Kota Galuh', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, NULL, 'Tidak sekolah', NULL, 'SRI WAHYUNI', NULL, 'Karyawan Swasta', 'S1', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(79, NULL, '3160697083', '1218026807160002', 'KHAIRUNISA SALSABILA', NULL, 'P', 'tualang', '2016-07-28', 'Islam', 'Indonesia', 2, 3, NULL, NULL, NULL, 'lingkungan V', '0', '0', 'tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Muhammad Wahyu', NULL, 'Petani', 'SD / sederajat', NULL, 'Pertiwi Dianingsih', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(80, NULL, '0133670972', '1218027009150003', 'KHANZA DARIN AZZAHRA', NULL, 'P', 'TUALANG', '2015-09-30', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'TUALANG', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SUTRISNO', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'RINI OKTAVIANI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(81, NULL, '3169893264', '1218024308160003', 'KHARIZA HUMAZZAHRA BR SIPAYUNG', NULL, 'P', 'TUALANG', '2016-08-03', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'LINK IX', NULL, NULL, 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'FERI FADLI SIPAYUNG', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'UTRYANINGSIH', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(82, NULL, '3177453143', '1218024809170001', 'LEAH AZ-ZAHRA', NULL, 'P', 'Perbaungan', '2017-09-08', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'Jl. Kebun Kelapa Link VII', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Rino Wartoyo', NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'SITI JUMAIDAH', NULL, 'PNS/TNI/Polri', 'S1', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(83, NULL, '3167447475', '1218022307160003', 'M RIANSYAH PUTRA', NULL, 'L', 'PERBAUNGAN', '2016-07-23', 'Islam', 'Indonesia', 4, 4, NULL, NULL, NULL, 'LINGKUNGAN PASIRAN', '0', '0', 'Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SOPIAN', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'LINA SUTIANI', NULL, 'Tidak bekerja', 'SD / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(84, NULL, '3193347516', '1218020402190002', 'M. RAFLI FEBRIANSYAH', NULL, 'L', 'PERBAUNGAN', '2019-02-04', 'Islam', 'Indonesia', 4, 3, NULL, NULL, NULL, 'JL. KABUPATEN NO. 84 LK. PEKAN I', '0', '0', 'Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'FADLI HAKIM', NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'DEWI KARTIKA SARI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(85, NULL, '3193128105', '1218025202190004', 'MAHNOER KANZA KANSYAH TIAWAN', NULL, 'P', 'PERBAUNGAN', '2019-02-12', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'JALAN RUMAH SAKIT NO 10', '0', '0', 'Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'AGUSTIAWAN', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'NURMALILA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(86, NULL, '3152836849', '1272067108150002', 'MALICHA TRIANA AGUSTIN', NULL, 'P', 'PEMATANG SIANTAR', '2015-08-31', 'Islam', 'Indonesia', 4, 3, NULL, NULL, NULL, 'JL. AMIR HAMZAH', NULL, NULL, 'KOTA GALUH', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SAMIN', NULL, 'Sudah Meninggal', 'SD / sederajat', NULL, 'SUMARNI BOYONG', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(87, NULL, '3201225656', '1116106305200001', 'MEI DITA RAMADANI', NULL, 'P', 'SERDANG BEDAGAI', '2020-05-23', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'TUALANG', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'RUDI', NULL, 'Petani', 'SD / sederajat', NULL, 'NURUL FADILLAH', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(88, NULL, '3172295805', '1218025407170004', 'Melati', NULL, 'P', 'Perbaungan', '2017-07-14', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'LINK III', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Sugiono', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'Fitri Muliani', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(89, NULL, '3171350598', '1218022704170002', 'MHD FAZA MUMTAZ HASIBUAN', NULL, 'L', 'Melati I', '2017-04-27', 'Islam', 'Indonesia', 1, 4, NULL, NULL, NULL, 'JL. SAWO LINGK. II', '0', '0', 'Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Fauzi Usman Hasibuan', NULL, 'Wiraswasta', 'D3', NULL, 'Faujiah Hanum', NULL, 'Lainnya', 'S1', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(90, NULL, '3170822049', '1218020507170003', 'Mhd Hafidz Al Hadi', NULL, 'L', 'Tualang', '2017-07-05', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'LINK III', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Rusmahadi', NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'Ani Sahrani', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(91, NULL, '3202844240', '1218022503200001', 'MHD. DEVANO ARTHAVA GIBRAN', NULL, 'L', 'PERBAUNGAN', '2020-03-25', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'TUALANG', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SRI HAIRUDI', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'DINA RIA PUTRI', NULL, 'Wirausaha', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(92, NULL, '3202028097', '1218021208200006', 'MUHAMMAD AKARA REYNALDI', NULL, 'L', 'SERDANG BEDAGAI', '2020-08-12', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'MELATI I', '0', '0', 'Desa/Kel. Melati Dua', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'KOKO REY NALDI', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'INDAH LESTARI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(93, NULL, '3161971832', '1218022712160002', 'MUHAMMAD ALFATHIR AMRI', NULL, 'L', 'PERBAUNGAN', '2016-12-27', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'JL. ALWASHLIYAH LK. JUANI', '0', '0', 'Desa/Kel. Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SYAFRUDDIN', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'SRI HARTATI', NULL, 'Lainnya', 'S1', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(94, NULL, '3181232253', '1218022712180001', 'MUHAMMAD AZKA PRATAMA', NULL, 'L', 'MELATI II', '2018-12-27', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGKUNGAN IX', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SUPRIANDI', NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'RIA HANI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(95, NULL, '3177114086', '1218022909170004', 'MUHAMMAD AZZAM TSAQIB', NULL, 'L', 'CITAMAN JERNIH', '2017-09-29', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'JL. CENDERAWASIH', '0', '0', 'Citaman Jernih', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'RAHMAD DONI SOLIHIN', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'DINA SILVIYA ROZA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(96, NULL, '3160756427', '1218022202160002', 'MUHAMMAD FAREL', NULL, 'L', 'TUALANG', '2016-02-22', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGKUNGAN VII', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'LEGIRAN', NULL, 'Buruh', 'SMP / sederajat', NULL, 'SITI NURHAIDAH', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(97, NULL, '3190374386', '1218020309190004', 'Muhammad Farrel Alwi', NULL, 'L', 'Kisaran', '2019-09-03', 'Islam', 'Indonesia', 2, 0, NULL, NULL, NULL, 'Melati I', '0', '0', 'Desa/Kel. Melati Dua', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Wibi Sono', NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'CUT ALFI SARI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(98, '027', '0137818698', '1218022211130001', 'Muhammad Fatih', NULL, 'L', 'Sei Naga Lawan', '2013-11-22', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'SEI NAGALAWAN', '0', '0', 'Desa/Kel. SEI NAGA LAWAN', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Muhammad Abdul Hakim', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'Sulina Wati', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(99, NULL, '3180318303', '1218021604180002', 'MUHAMMAD FAWWAZ AZZUBAIRI HASIBUAN', NULL, 'L', 'Melati I', '2018-04-16', 'Islam', 'Indonesia', 2, 4, NULL, NULL, NULL, 'JL. SAWO LINGK. II', NULL, NULL, 'Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Fauzi Usman Hasibuan', NULL, 'Wiraswasta', 'D3', NULL, 'Faujiah Hanum', NULL, 'Lainnya', 'S1', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(100, NULL, '3190217737', '1218022810190006', 'MUHAMMAD HABIB', NULL, 'L', 'SERDANG BEDAGAI', '2019-10-28', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'GG. SEMPURNA', '0', '0', 'Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'WINARTO', NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'NILA ANGRAINI', NULL, 'Tidak bekerja', 'SD / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(101, '028', '3152854277', '1218022511150004', 'MUHAMMAD HAIKAL NOFRIZAL', NULL, 'L', 'TUALANG', '2015-11-25', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'LINK III', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'SULASTRI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(102, NULL, '3156604104', '1218021011150001', 'MUHAMMAD ISKANDAR ZUKARNAEN', NULL, 'L', 'LUBUK PAKAM', '2015-11-10', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'LINGKUNGAN V', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'JAR\'AN', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'SRI HERLINA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(103, NULL, '3191152654', '1218020110190003', 'Muhammad Zahid Ghazwan Hasibuan', NULL, 'L', 'Melati I', '2019-10-01', 'Islam', 'Indonesia', 3, 0, NULL, NULL, NULL, 'Jl Karya Jaya Gg Hidayah II No 7', '0', '0', 'Gedung Johor', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Fauzi Usman Hasibuan', NULL, 'Lainnya', 'S1', NULL, 'Faujiah Hanum', NULL, 'Tidak bekerja', 'S1', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(104, '029', '3146611931', '1218026207140004', 'NAJWA RAHMADANI', NULL, 'P', 'PERBAUNGAN', '2014-07-22', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'LINGKUNGAN VI', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'MASTURA', NULL, 'Tidak bekerja', 'SD / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(105, '030', '3158279618', '1218025604150002', 'NAZLA MAURI FADHILLA', NULL, 'P', 'KOTA GALUH', '2015-04-16', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'KOTA GALUH', '0', '0', 'KOTA GALUH', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SAMSUL BAHRI', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'MAULIZA ANTIKA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(106, NULL, '3192110382', '1218025805190004', 'NAZWAH AISAH PUTRI', NULL, 'P', 'BENGKEL', '2019-05-18', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGKUNGAN V', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'PURWANTO', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'SRI YANI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(107, NULL, '3157242815', '1218025711150005', 'NICKY ALIKHA PUTRI', NULL, 'P', 'TUALANG', '2015-11-17', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'LINGKUNGAN IV', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'MHD. HAMDANI', NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'FITRI IRMALASARI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(108, NULL, '3168952840', '1218021201160002', 'NUR ALDO PRANATA', NULL, 'L', 'PERBAUNGAN', '2016-01-12', 'Islam', 'Indonesia', 4, 3, NULL, NULL, NULL, 'JLN. KARYA LINK VI', NULL, NULL, 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'JASWADI', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'SRI WAHYUNI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(109, '031', '3144002779', '1218025109140002', 'PUTRI KALISYAH', NULL, 'P', 'MELATI I', '2014-09-11', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINK I JL. JOHAR', '0', '0', 'MELATI I', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'RIA KURNIATI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(110, NULL, '3179792817', '1218025304170003', 'QISYA ARSYLA FATEEN', NULL, 'P', 'SERDANG BEDAGAI', '2017-04-13', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'JL. SEROJA KL. JUANI', '0', '0', 'Simpang Tiga Pekan', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'RAHAYU SANTOSO', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'RITA LUBIS', NULL, 'Tidak bekerja', 'D3', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(111, NULL, '3186598631', '1218020609180003', 'QORIB WIJAYA', NULL, 'L', 'Tualang', '2018-09-06', 'Islam', 'Indonesia', 4, 3, NULL, NULL, NULL, 'Lingkungan XI', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Dedi Afandi', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'Sri Wulandari', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(112, NULL, '3187579217', '1218021808180008', 'RADIT ALFAJAR', NULL, 'L', 'SERDANG BEDAGAI', '2018-08-18', 'Islam', 'Indonesia', 4, 3, NULL, NULL, NULL, 'GG. SEMPURNA', '0', '0', 'Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'M. RADIKAL', NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'DEWI', NULL, 'Tidak bekerja', 'SD / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(113, '032', '3118545207', '1218021612110005', 'RAFA FADILLA', NULL, 'L', 'PERBAUNGAN', '2011-12-16', 'Islam', 'Indonesia', 2, 2, NULL, NULL, NULL, 'LINGKUNGAN VI', NULL, NULL, 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'AHMAD SARIPUDIN', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'MASTURA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(114, NULL, '3178992245', '1218020910170001', 'RAFFASYA ARFAN', NULL, 'L', 'PERBAUNGAN', '2017-10-09', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'JL. CENDERAWASIH', '0', '0', 'Citaman Jernih', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'RUDI SYAHPUTRA', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'IRDA NINA SARI', NULL, 'Lainnya', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(115, NULL, '3172287574', '1218021608170002', 'RAFIF AL FARIZI', NULL, 'L', 'TUALANG', '2017-08-16', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'LINGK. II TUALANG', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SUPRIADI', NULL, 'Petani', 'SMA / sederajat', NULL, 'OKTI SAFRIDA LUBIS', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(116, '033', '3155987345', '1218020308150006', 'REDI EFENDI', NULL, 'L', 'TUALANG', '2015-08-03', 'Islam', 'Indonesia', 2, 2, NULL, NULL, NULL, 'LINK VII', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'SUWARNI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(117, NULL, '3173693150', '1218026007170003', 'Risma Efendi', NULL, 'P', 'Perbaungan', '2017-07-20', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'LINK VII', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Rizal Efendi', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'Suwarni', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(118, NULL, '3192785444', '1218025602190002', 'RIZKIA ADISTY', NULL, 'P', 'MELATI', '2019-02-16', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'MELATI', '0', '0', 'Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'ADE IRAWAN', NULL, 'Wiraswasta', 'Tidak sekolah', NULL, 'DIANA', NULL, 'Pedagang Kecil', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(119, NULL, '3169210561', '1218020404160003', 'RIZKY PRATAMA', NULL, 'L', 'PERBAUNGAN', '2016-04-04', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'LINGKUNGAN X', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'FERY JULIANDI', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'SUKMA WATI', NULL, 'Tidak bekerja', 'Tidak sekolah', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:13', '2026-10-31 21:38:13'),
(120, NULL, '3174989264', '1218025109170002', 'Sabrina', NULL, 'P', 'Perbaungan', '2017-09-11', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'Lingkungan III', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Asrul', NULL, 'Pedagang Besar', 'SMA / sederajat', NULL, 'SA\'DIYAH', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(121, NULL, '3154762395', '1218025111150006', 'SALSA BILA RUSYIDA', NULL, 'P', 'CITAMAN JERNIH', '2015-11-11', 'Islam', 'Indonesia', 1, 2, NULL, NULL, NULL, 'JL. KUTILNG', NULL, NULL, 'CITAMAN JERNIH', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'JAYA HARTONO', NULL, 'Wiraswasta', 'SMP / sederajat', NULL, 'LATIFA HANUM BR LUBIS', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(122, '034', '0139387886', '1216012204130001', 'SUHENRI ANTONIUS', NULL, 'L', 'SIHABONGHABONG', '2013-04-22', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'Tualang', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SUTRISTIO HERMAN SIHOTANG', NULL, 'Buruh', 'SMP / sederajat', NULL, 'SUMARNI SITUMORANG', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(123, '035', '0157374271', '1218026207150001', 'SYAFA AZZAHRA', NULL, 'P', 'PERBAUNGAN', '2015-07-22', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'JL. KARYA LINK VI', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'RINI HARIANI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(124, NULL, '3195488558', '1218022010190001', 'SYAIFUDDIN QUTUZ IBADURRAHMAN', NULL, 'L', 'CITAMAN JERNIH', '2019-10-20', 'Islam', 'Indonesia', 2, 3, NULL, NULL, NULL, 'JL. CENDRAWASIH', '0', '0', 'Citaman Jernih', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'RAHMAD DONI SOLIHIN', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'DINA SILVIYA ROZA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 2, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(125, NULL, '3182931067', '1218020511180003', 'SYAUQI HAQQI', NULL, 'L', 'TUALANG', '2018-11-05', 'Islam', 'Indonesia', 1, 1, NULL, NULL, NULL, 'LINGKUNGAN X', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'TEGUH HARI SUROSO', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'KUSMAYANI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14');
INSERT INTO `siswa` (`id`, `nis`, `nisn`, `nik`, `nama`, `nama_panggilan`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `agama`, `kewarganegaraan`, `anak_ke`, `jumlah_saudara`, `golongan_darah`, `tinggi_badan`, `berat_badan`, `alamat`, `rt`, `rw`, `kelurahan`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `tinggal_bersama`, `telepon_ortu`, `nama_ayah`, `nik_ayah`, `pekerjaan_ayah`, `pendidikan_ayah`, `penghasilan_ayah`, `nama_ibu`, `nik_ibu`, `pekerjaan_ibu`, `pendidikan_ibu`, `penghasilan_ibu`, `nama_wali`, `hubungan_wali`, `pekerjaan_wali`, `telepon_wali`, `foto`, `kelas_id`, `status`, `tanggal_masuk`, `asal_sekolah`, `nomor_ijazah_sebelumnya`, `created_at`, `updated_at`) VALUES
(126, NULL, NULL, NULL, 'SYIFA RAMADHANI', NULL, 'P', 'MELATI I', '2018-05-26', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'MELATI I', '0', '0', 'Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SARWANIK', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'ERVINA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(127, NULL, '3192369557', '1218024708190003', 'ULFA AZMI HUMAIRA', NULL, 'P', 'SERDANG BEDAGAI', '2019-08-07', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'CITAMAN JERNIH', '0', '0', 'Desa/Kel. Citaman Jernih', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SAPRIAL', NULL, 'Wiraswasta', 'SD / sederajat', NULL, 'NURUL APRIANTI', NULL, 'Tidak bekerja', 'SD / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(128, NULL, '3171334835', '1218021502170002', 'Virzha Al Rasyid', NULL, 'L', 'Jambur Pulau', '2017-02-15', 'Islam', 'Indonesia', 3, 2, NULL, NULL, NULL, 'Jambur Pulau', '0', '0', 'Desa/Kel. Jambur Pulau', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Gunawan', NULL, 'Petani', 'SMA / sederajat', NULL, 'Sri Wahyuni', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(129, NULL, '3167943400', '1218021909160002', 'WENDO KURNIAWAN', NULL, 'L', 'PERBAUNGAN', '2016-09-19', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINGKUNGAN VII', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'SYAIFUL ANWAR', NULL, 'Wiraswasta', 'S1', NULL, 'NURHAYATI', NULL, 'Tidak bekerja', 'SMP / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(130, NULL, '3154567529', '1218026110150002', 'YUKA ALUNA', NULL, 'P', 'JAMBUR PULAU', '2015-10-21', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'DUSUN IV', '0', '0', 'JAMBUR PULAU', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'ANDRI PRANOTO', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'MALA BUDIARTI', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(131, NULL, '3177577697', '1218024709170002', 'ZAFIRAH AZRINA', NULL, 'P', 'TUALANG', '2017-09-07', 'Islam', 'Indonesia', 3, 1, NULL, NULL, NULL, 'LINGKUNGAN V', '0', '0', 'Desa/Kel. Tualang', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'HENDRO WIBOWO', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'HENNISYAH', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(132, NULL, '3172480041', '1218025402170001', 'Zahira Hutagaol', NULL, 'P', 'Melati', '2017-02-14', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'LINK I Gg. SEMPURNA', '0', '0', 'Desa/Kel. Melati Satu', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'Mikhael Khapel Hutagaol', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'Kurnia', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 4, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(133, '036', '0144695871', '1218022304140003', 'ZAID ABU KAUTSAR', NULL, 'L', 'LUBUK PAKAM', '2014-04-23', 'Islam', 'Indonesia', 2, 1, NULL, NULL, NULL, 'LINK III', '0', '0', 'TUALANG', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, NULL, NULL, 'Karyawan Swasta', 'SMA / sederajat', NULL, 'SITI MAIMUNAH', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 6, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14'),
(134, NULL, '3177014292', '1218021111170002', 'ZIDAN ARSHAKA AMRAN', NULL, 'L', 'BENGKEL', '2017-11-11', 'Islam', 'Indonesia', 1, 0, NULL, NULL, NULL, 'DUSUN III SEI JENGGI', '0', '0', 'Desa/Kel. SEI SIJENGGI', 'Kec. Perbaungan', 'Serdang Bedagai', 'Sumatera Utara', '20986', 'Bersama orang tua', NULL, 'ISKANDAR AMRAN', NULL, 'Wiraswasta', 'SMA / sederajat', NULL, 'LIA MUSTIKA', NULL, 'Tidak bekerja', 'SMA / sederajat', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'aktif', NULL, NULL, NULL, '2026-10-31 21:38:14', '2026-10-31 21:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('Ganjil','Genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id`, `tahun_ajaran`, `semester`, `is_active`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(2, '2026/2027', 'Ganjil', 1, '2026-07-01', '2026-12-31', '2026-10-24 06:53:14', '2026-10-24 06:53:19'),
(3, '2026/2027', 'Genap', 0, '2027-01-01', '2027-06-30', '2026-10-24 06:53:49', '2026-10-24 06:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operator',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `page_permissions` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_active`, `page_permissions`) VALUES
(1, 'Administrator', 'admin@sditalfalah.sch.id', 'admin', NULL, '$2y$12$QJ5NsDlizdClbYiQxTbcjOAj82XbLluF87YZjTzas36f1qX8pXo96', 'kUi0R6GNBoJeWCqCTgmCOCuZX0e0u26ywrc7I2D3gLvOzspyrEVmRzCzUc0I', '2026-05-03 08:19:41', '2026-05-03 08:19:41', 1, NULL),
(2, 'Muhammad Rizky Ananda', 'perbaungan.id@gmail.com', 'operator', NULL, '$2y$12$wpFLIX/Dz33BtSm23heTt.teKbhp88a4m7K9xsU3JrIu4jn3OdcuG', 'VF7WKZdB9YSpHYO4DQQJRY9TeF6FUQ7mSqWwFJKt8SmhErGLSJfj4aYZBfjp', '2026-11-07 23:15:01', '2026-11-11 05:28:59', 1, '[\"dashboard\", \"siswa\", \"guru\", \"kelas\", \"absensi\", \"rekap-presensi\", \"nilai\", \"rapor\", \"pengumuman\", \"tahun-ajaran\", \"profil-sekolah\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `absensi_siswa_id_tanggal_unique` (`siswa_id`,`tanggal`),
  ADD KEY `absensi_kelas_id_foreign` (`kelas_id`),
  ADD KEY `absensi_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `anggota_rombel`
--
ALTER TABLE `anggota_rombel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anggota_rombel_siswa_id_tahun_ajaran_unique` (`siswa_id`,`tahun_ajaran`),
  ADD KEY `anggota_rombel_kelas_id_foreign` (`kelas_id`),
  ADD KEY `anggota_rombel_tahun_ajaran_kelas_id_index` (`tahun_ajaran`,`kelas_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_user_id_foreign` (`user_id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_kelas_id_foreign` (`kelas_id`),
  ADD KEY `jadwal_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  ADD KEY `jadwal_guru_id_foreign` (`guru_id`),
  ADD KEY `jadwal_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_guru_id_foreign` (`guru_id`),
  ADD KEY `kelas_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_aktivitas_user_id_foreign` (`user_id`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mapel_kelas_periode_kode_unique` (`kelas_id`,`tahun_ajaran_id`,`kode_mapel`),
  ADD KEY `mata_pelajaran_guru_id_foreign` (`guru_id`),
  ADD KEY `mata_pelajaran_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nilai_siswa_id_mata_pelajaran_id_tahun_ajaran_id_unique` (`siswa_id`,`mata_pelajaran_id`,`tahun_ajaran_id`),
  ADD KEY `nilai_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  ADD KEY `nilai_kelas_id_foreign` (`kelas_id`),
  ADD KEY `nilai_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `nilai_ekstrakurikuler`
--
ALTER TABLE `nilai_ekstrakurikuler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nilai_ekstrakurikuler_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  ADD KEY `nilai_ekstrakurikuler_siswa_id_tahun_ajaran_id_index` (`siswa_id`,`tahun_ajaran_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengaturan_predikat`
--
ALTER TABLE `pengaturan_predikat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengaturan_predikat_kelas_id_tahun_ajaran_id_unique` (`kelas_id`,`tahun_ajaran_id`),
  ADD KEY `pengaturan_predikat_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengumuman_user_id_foreign` (`user_id`);

--
-- Indexes for table `profil_sekolah`
--
ALTER TABLE `profil_sekolah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswa_nis_unique` (`nis`),
  ADD KEY `siswa_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `anggota_rombel`
--
ALTER TABLE `anggota_rombel`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nilai_ekstrakurikuler`
--
ALTER TABLE `nilai_ekstrakurikuler`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengaturan_predikat`
--
ALTER TABLE `pengaturan_predikat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profil_sekolah`
--
ALTER TABLE `profil_sekolah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `anggota_rombel`
--
ALTER TABLE `anggota_rombel`
  ADD CONSTRAINT `anggota_rombel_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `anggota_rombel_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jadwal_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD CONSTRAINT `mata_pelajaran_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mata_pelajaran_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `mata_pelajaran_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_ekstrakurikuler`
--
ALTER TABLE `nilai_ekstrakurikuler`
  ADD CONSTRAINT `nilai_ekstrakurikuler_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_ekstrakurikuler_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengaturan_predikat`
--
ALTER TABLE `pengaturan_predikat`
  ADD CONSTRAINT `pengaturan_predikat_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengaturan_predikat_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
