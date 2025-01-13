-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 06:29 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_pegawai_terbaik`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id` int(11) NOT NULL,
  `nrk` varchar(25) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `masa_aktif` varchar(50) DEFAULT NULL,
  `level_akun` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id`, `nrk`, `nama`, `jabatan`, `masa_aktif`, `level_akun`) VALUES
(71, '0501', 'Ayup Saputra, A.Md', 'Anggota', '2021-07-08', 0),
(72, '0092', 'Khalilullah', 'Anggota', '2013-02-01', 0),
(73, '0249', 'Abdul Manan', 'Anggota', '2016-07-11', 0),
(74, '0228', 'Mulyadi', 'Anggota', '2015-11-26', 0),
(75, '0295', 'Safi\'i', 'Anggota', '2017-11-13', 0),
(76, '0310', 'Muhammad Irvan', 'Anggota', '2018-07-21', 0),
(77, '0629', 'Saifan Nur', 'Anggota', '2022-08-29', 0),
(78, '0492', 'Fahreza Muhammad Akbar', 'Anggota', '2021-07-08', 0),
(79, '0250', 'Mansyur S', 'Anggota', '2016-07-23', 0),
(80, '0052', 'Hermansyah Putra Tanjung', 'Anggota', '2011-11-20', 0),
(81, '0224', 'Suhari', 'Anggota', '2015-07-27', 0),
(82, '0270', 'Zulkarnain Hasibuan', 'Anggota', '2017-09-26', 0),
(83, '0672', 'Fadlon', 'Anggota', '2023-10-04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` varchar(3) NOT NULL,
  `nama` varchar(55) NOT NULL,
  `bobot` int(11) NOT NULL,
  `jenis` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama`, `bobot`, `jenis`) VALUES
('K1', 'Kehadiran', 4, 'benefit'),
('K2', 'Kinerja', 5, 'benefit'),
('K3', 'Masa Kerja', 4, 'benefit');

-- --------------------------------------------------------

--
-- Table structure for table `matrix`
--

CREATE TABLE `matrix` (
  `id` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_kriteria` varchar(3) NOT NULL,
  `nilai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `matrix`
--

INSERT INTO `matrix` (`id`, `id_alternatif`, `id_kriteria`, `nilai`) VALUES
(119, 71, 'K1', 90),
(120, 71, 'K2', 85),
(121, 71, 'K3', 40),
(122, 72, 'K1', 85),
(123, 72, 'K2', 80),
(124, 72, 'K3', 141),
(125, 73, 'K1', 85),
(126, 73, 'K2', 80),
(127, 73, 'K3', 100),
(128, 74, 'K1', 85),
(129, 74, 'K2', 80),
(130, 74, 'K3', 108),
(131, 75, 'K1', 80),
(132, 75, 'K2', 75),
(133, 75, 'K3', 84),
(134, 76, 'K1', 85),
(135, 76, 'K2', 80),
(136, 76, 'K3', 76),
(137, 77, 'K1', 85),
(138, 77, 'K2', 80),
(139, 77, 'K3', 27),
(140, 78, 'K1', 90),
(141, 78, 'K2', 85),
(142, 78, 'K3', 40),
(143, 79, 'K1', 85),
(144, 79, 'K2', 80),
(145, 79, 'K3', 100),
(146, 80, 'K1', 85),
(147, 80, 'K2', 75),
(148, 80, 'K3', 156),
(149, 81, 'K1', 80),
(150, 81, 'K2', 75),
(151, 81, 'K3', 112),
(152, 82, 'K1', 80),
(153, 82, 'K2', 75),
(154, 82, 'K3', 86),
(155, 83, 'K1', 85),
(156, 83, 'K2', 80),
(157, 83, 'K3', 13);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(55) NOT NULL,
  `password` varchar(55) NOT NULL,
  `status` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`) VALUES
(1, 'admin', 'admin', 'admin'),
(2, 'pimpinan', 'pimpinan', 'pimpinan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matrix`
--
ALTER TABLE `matrix`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_alternatif` (`id_alternatif`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `matrix`
--
ALTER TABLE `matrix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matrix`
--
ALTER TABLE `matrix`
  ADD CONSTRAINT `matrix_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matrix_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
