-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 06:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_helpdesk`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id_log` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `warna` varchar(20) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id_log`, `keterangan`, `icon`, `warna`, `timestamp`) VALUES
(1, 'Sistem E-Helpdesk Aktif', 'fa-check-circle', 'success', '2026-01-26 20:16:40'),
(2, 'Admin melakukan pemantauan database', 'fa-user-shield', 'info', '2026-01-26 20:16:40'),
(3, 'Lucas membuat laporan baru: coba aplikasi', 'fa-paper-plane', 'primary', '2026-02-02 23:04:16'),
(4, 'Athala membalas chat #4', 'fa-comment', 'info', '2026-02-02 23:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `bpr`
--

CREATE TABLE `bpr` (
  `id_bpr` int(11) NOT NULL,
  `nama_bpr` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status_akun` enum('pending','aktif') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bpr`
--

INSERT INTO `bpr` (`id_bpr`, `nama_bpr`, `username`, `password`, `id_pegawai`, `email`, `status_akun`) VALUES
(1, 'BPR INTERNAL / UMUM', 'bpr_umum', '123', NULL, NULL, 'aktif'),
(2, 'Lucas', 'lucasx', 'adalucas', 4, NULL, 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_bpr` int(11) DEFAULT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `judul_masalah` varchar(200) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('C','O','U','F') DEFAULT 'C',
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id_chat`, `id_bpr`, `id_pegawai`, `judul_masalah`, `foto`, `status`, `timestamp`) VALUES
(3, 1, NULL, 'tambahkan logo ', '1769188685_Screenshot 2026-01-24 001208.png', 'F', '2026-01-24 00:18:05'),
(4, 1, NULL, 'uji coba', '1769189292_Screenshot 2026-01-24 002759.png', 'O', '2026-01-24 00:28:12'),
(5, 2, 4, 'coba aplikasi', '1770048256_Screenshot 2026-02-02 221248.png', 'C', '2026-02-02 23:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `detail_chat`
--

CREATE TABLE `detail_chat` (
  `id_detail` int(11) NOT NULL,
  `id_chat` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `sender_role` enum('bpr','pegawai','supervisor','admin') DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `foto_lampiran` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_chat`
--

INSERT INTO `detail_chat` (`id_detail`, `id_chat`, `sender_id`, `sender_role`, `pesan`, `foto_lampiran`, `timestamp`) VALUES
(1, 4, 4, 'pegawai', 'ada masalah apa?', '', '2026-02-02 23:11:17'),
(2, 4, 4, 'pegawai', 'ada masalah apa?', '', '2026-02-02 23:12:16'),
(3, 4, 4, 'pegawai', 'ada masalah apa?', '', '2026-02-02 23:20:35'),
(4, 5, 2, 'bpr', 'tolong coba aplikasi.', '', '2026-02-02 23:22:46'),
(5, 5, 4, 'pegawai', 'baik akan dicoba.', '', '2026-02-02 23:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `role` enum('admin','pegawai','supervisor') DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status_akun` enum('pending','aktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama`, `role`, `username`, `password`, `status_akun`) VALUES
(1, 'Administrator IT', 'admin', 'admin', 'admin123', 'aktif'),
(2, 'Tara', 'pegawai', 'taraa', 'cobatara', 'aktif'),
(3, 'Leon', 'supervisor', 'leonn', 'leontest', 'aktif'),
(4, 'Athala', 'pegawai', 'thala', 'inithala', 'aktif'),
(5, 'iyan', 'supervisor', 'iyann', 'sayaiyan', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor`
--

CREATE TABLE `supervisor` (
  `id_supervisor` int(11) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `bpr`
--
ALTER TABLE `bpr`
  ADD PRIMARY KEY (`id_bpr`),
  ADD KEY `fk_bpr_pegawai` (`id_pegawai`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`),
  ADD KEY `id_bpr` (`id_bpr`);

--
-- Indexes for table `detail_chat`
--
ALTER TABLE `detail_chat`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_chat` (`id_chat`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indexes for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD PRIMARY KEY (`id_supervisor`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bpr`
--
ALTER TABLE `bpr`
  MODIFY `id_bpr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detail_chat`
--
ALTER TABLE `detail_chat`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supervisor`
--
ALTER TABLE `supervisor`
  MODIFY `id_supervisor` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bpr`
--
ALTER TABLE `bpr`
  ADD CONSTRAINT `fk_bpr_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE SET NULL;

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_bpr`) REFERENCES `bpr` (`id_bpr`) ON DELETE CASCADE;

--
-- Constraints for table `detail_chat`
--
ALTER TABLE `detail_chat`
  ADD CONSTRAINT `detail_chat_ibfk_1` FOREIGN KEY (`id_chat`) REFERENCES `chat` (`id_chat`) ON DELETE CASCADE;

--
-- Constraints for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD CONSTRAINT `supervisor_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
