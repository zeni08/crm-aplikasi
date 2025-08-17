-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2025 at 07:37 AM
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
-- Database: `crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `detail_id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL CHECK (`jumlah` > 0),
  `harga_per_unit` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `transaksi_id` int(11) DEFAULT NULL,
  `isi_feedback` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `tanggal` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `pelanggan_id`, `transaksi_id`, `isi_feedback`, `rating`, `tanggal`, `status`) VALUES
(1, 3, 1, 'hahahaha', 1, '2025-06-21 01:22:30', 'Reviewed');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_poin`
--

CREATE TABLE `loyalty_poin` (
  `poin_id` int(11) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `jumlah_poin` int(11) NOT NULL DEFAULT 0,
  `tanggal_terakhir_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `pelanggan_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`pelanggan_id`, `nama`, `email`, `no_hp`, `alamat`, `tanggal_daftar`) VALUES
(3, 'zeni', 'zeni.zamir@gmail.com', '089608393929', 'Kp.warung seuseupan', '2025-06-21 00:07:40'),
(4, 'rama', 'sdadafef@gmail.com', '-9088734', '124321', '2025-06-21 12:32:16'),
(5, 'susan ', 'ssn@gmail.com', '0-1245329', 'xzfs', '2025-06-21 12:32:34'),
(6, 'diki', 'dki@gmail.com', '124254365', 'QAVQ', '2025-06-21 12:32:58');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `produk_id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `kategori` varchar(100) DEFAULT NULL,
  `tanggal_ditambahkan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`produk_id`, `nama_produk`, `deskripsi`, `harga`, `stok`, `kategori`, `tanggal_ditambahkan`) VALUES
(1, 'ayam', 'ayam filet', 52000.00, 120, 'daging', '2025-06-21 00:04:46');

-- --------------------------------------------------------

--
-- Table structure for table `promosi`
--

CREATE TABLE `promosi` (
  `promosi_id` int(11) NOT NULL,
  `nama_promosi` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kode_promosi` varchar(50) DEFAULT NULL,
  `tipe_diskon` enum('persentase','nominal') NOT NULL,
  `nilai_diskon` decimal(10,2) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `min_pembelian` decimal(10,2) DEFAULT 0.00,
  `status` enum('Aktif','Tidak Aktif','Selesai') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_promosi_pelanggan`
--

CREATE TABLE `riwayat_promosi_pelanggan` (
  `riwayat_id` int(11) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `promosi_id` int(11) NOT NULL,
  `tanggal_digunakan` datetime DEFAULT current_timestamp(),
  `jumlah_diskon_didapat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int(11) NOT NULL,
  `pelanggan_id` int(11) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `total` decimal(12,2) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `status_transaksi` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`transaksi_id`, `pelanggan_id`, `tanggal`, `total`, `metode_pembayaran`, `status_transaksi`) VALUES
(1, 3, '2025-06-20 00:00:00', 1.00, 'cash', 'Completed'),
(2, 3, '2025-06-20 00:00:00', 246654.00, 'cash', 'Completed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indexes for table `loyalty_poin`
--
ALTER TABLE `loyalty_poin`
  ADD PRIMARY KEY (`poin_id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`pelanggan_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`produk_id`);

--
-- Indexes for table `promosi`
--
ALTER TABLE `promosi`
  ADD PRIMARY KEY (`promosi_id`),
  ADD UNIQUE KEY `kode_promosi` (`kode_promosi`);

--
-- Indexes for table `riwayat_promosi_pelanggan`
--
ALTER TABLE `riwayat_promosi_pelanggan`
  ADD PRIMARY KEY (`riwayat_id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`),
  ADD KEY `promosi_id` (`promosi_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loyalty_poin`
--
ALTER TABLE `loyalty_poin`
  MODIFY `poin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `pelanggan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `produk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `promosi`
--
ALTER TABLE `promosi`
  MODIFY `promosi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_promosi_pelanggan`
--
ALTER TABLE `riwayat_promosi_pelanggan`
  MODIFY `riwayat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`transaksi_id`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`pelanggan_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`transaksi_id`);

--
-- Constraints for table `loyalty_poin`
--
ALTER TABLE `loyalty_poin`
  ADD CONSTRAINT `loyalty_poin_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`pelanggan_id`) ON DELETE CASCADE;

--
-- Constraints for table `riwayat_promosi_pelanggan`
--
ALTER TABLE `riwayat_promosi_pelanggan`
  ADD CONSTRAINT `riwayat_promosi_pelanggan_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`pelanggan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `riwayat_promosi_pelanggan_ibfk_2` FOREIGN KEY (`promosi_id`) REFERENCES `promosi` (`promosi_id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`pelanggan_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
