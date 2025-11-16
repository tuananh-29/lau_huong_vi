-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 11:48 AM
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
-- Database: `lau_huong_vi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don`
--

CREATE TABLE `chi_tiet_don` (
  `ma_chi_tiet` int(11) NOT NULL,
  `ma_don_hang` int(11) NOT NULL,
  `ma_mon_an` int(11) NOT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 1,
  `gia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `ma_danh_gia` int(11) NOT NULL,
  `ma_nguoi_dung` int(11) NOT NULL,
  `ma_mon_an` int(11) NOT NULL,
  `sao` tinyint(4) NOT NULL,
  `binh_luan` text DEFAULT NULL,
  `ngay_danh_gia` timestamp NOT NULL DEFAULT current_timestamp(),
  `trang_thai` enum('cho_duyet','da_duyet') NOT NULL DEFAULT 'cho_duyet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `ma_danh_muc` int(11) NOT NULL,
  `ten_danh_muc` varchar(100) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`ma_danh_muc`, `ten_danh_muc`, `mo_ta`, `anh`) VALUES
(1, 'Lẩu', 'Các loại lẩu đặc trưng của quán', NULL),
(2, 'Món lẻ', 'Món ăn kèm / set menu dành cho từng loại lẩu', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dat_ban`
--

CREATE TABLE `dat_ban` (
  `ma_dat_ban` int(11) NOT NULL,
  `ma_nguoi_dung` int(11) DEFAULT NULL,
  `ten_nguoi_dat` varchar(100) NOT NULL,
  `sdt_nguoi_dat` varchar(15) NOT NULL,
  `ngay_den` date NOT NULL,
  `gio_den` time NOT NULL,
  `so_luong_khach` int(11) NOT NULL DEFAULT 1,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'cho_xac_nhan',
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_don_hang` int(11) NOT NULL,
  `ma_nguoi_dung` int(11) DEFAULT NULL,
  `ten_nguoi_nhan` varchar(100) NOT NULL,
  `sdt_nguoi_nhan` varchar(15) NOT NULL,
  `dia_chi_giao_hang` varchar(255) NOT NULL,
  `ngay_dat` timestamp NOT NULL DEFAULT current_timestamp(),
  `tong_tien` int(11) NOT NULL DEFAULT 0,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'cho_xac_nhan',
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mon_an`
--

CREATE TABLE `mon_an` (
  `ma_mon_an` int(11) NOT NULL,
  `ma_danh_muc` int(11) NOT NULL,
  `ten_mon_an` varchar(150) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia` int(11) NOT NULL DEFAULT 0,
  `anh` varchar(255) DEFAULT NULL,
  `trang_thai` enum('con_hang','het_hang') NOT NULL DEFAULT 'con_hang'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mon_an`
--

INSERT INTO `mon_an` (`ma_mon_an`, `ma_danh_muc`, `ten_mon_an`, `mo_ta`, `gia`, `anh`, `trang_thai`) VALUES
(1, 1, 'Lẩu bò sa tế', 'Lẩu bò cay đậm đà vị sa tế', 350000, 'lau_bo_sate.jpg', 'con_hang'),
(2, 1, 'Lẩu cá tầm', 'Lẩu cá tầm thanh ngọt', 420000, 'lau_ca_tam.jpg', 'con_hang'),
(3, 1, 'Lẩu chay', 'Lẩu rau củ dành cho người ăn chay', 280000, 'lau_chay.jpg', 'con_hang'),
(4, 1, 'Lẩu cua đồng', 'Lẩu cua đồng dân dã, chuẩn vị quê', 390000, 'lau_cua_dong.jpg', 'con_hang'),
(5, 1, 'Lẩu dê', 'Lẩu dê thơm ngon, ít mùi', 450000, 'lau_de.jpg', 'con_hang'),
(6, 1, 'Lẩu ếch', 'Lẩu ếch măng cay', 330000, 'lau_ech.jpg', 'con_hang'),
(7, 1, 'Lẩu gà thuốc bắc', 'Lẩu gà hầm thuốc bắc bổ dưỡng', 360000, 'lau_ga_thuoc_bac.webp', 'con_hang'),
(8, 1, 'Lẩu hải sản tươi', 'Lẩu hải sản với tôm mực tươi sống', 480000, 'lau_haisan_tuoi.webp', 'con_hang'),
(9, 1, 'Lẩu hải sản', 'Lẩu hải sản kết hợp nhiều loại topping', 430000, 'lau_haisan.webp', 'con_hang'),
(10, 1, 'Lẩu kimchi', 'Lẩu kimchi chuẩn vị Hàn Quốc', 320000, 'lau_kimchi.jpg', 'con_hang'),
(11, 1, 'Lẩu mắm', 'Lẩu mắm miền Tây đặc trưng', 420000, 'lau_mam.jpg', 'con_hang'),
(12, 1, 'Lẩu nấm', 'Lẩu nhiều loại nấm bổ dưỡng', 300000, 'lau_nam.webp', 'con_hang'),
(13, 1, 'Lẩu riêu cua', 'Lẩu riêu cua thanh mát', 350000, 'lau_rieu_cua.jpg', 'con_hang'),
(14, 1, 'Lẩu thái', 'Lẩu thái chua cay đặc biệt', 340000, 'lau_thai.webp', 'con_hang'),
(15, 1, 'Lẩu Tứ Xuyên', 'Lẩu cay tê đặc trưng Tứ Xuyên', 360000, 'lau_tu_xuyen.jpg', 'con_hang'),
(16, 2, 'Thịt bò Mỹ cuộn', 'Thịt bò Mỹ tươi ngon dùng kèm lẩu', 120000, NULL, 'con_hang'),
(17, 2, 'Thịt heo ba rọi', 'Ba rọi heo thái lát mềm', 90000, NULL, 'con_hang'),
(18, 2, 'Tôm tươi', 'Tôm sú tươi sống cho món lẩu hải sản', 140000, NULL, 'con_hang'),
(19, 2, 'Mực ống', 'Mực ống dai giòn', 130000, NULL, 'con_hang'),
(20, 2, 'Hải sản tổng hợp', 'Tôm – mực – nghêu đầy đủ', 150000, NULL, 'con_hang'),
(21, 2, 'Rau tổng hợp', 'Rau nhúng lẩu gồm cải, mồng tơi, nấm', 60000, NULL, 'con_hang'),
(22, 2, 'Nấm tổng hợp', 'Nấm kim châm, nấm bào ngư, nấm đùi gà', 70000, NULL, 'con_hang'),
(23, 2, 'Mì trứng', 'Mì tươi ăn kèm lẩu', 15000, NULL, 'con_hang'),
(24, 2, 'Đậu hũ non', 'Đậu hũ mềm cho lẩu chay và lẩu nấm', 20000, NULL, 'con_hang'),
(25, 2, 'Há cảo tôm', 'Há cảo tôm dùng ăn kèm các loại lẩu', 70000, NULL, 'con_hang'),
(26, 2, 'Sủi cảo thịt', 'Sủi cảo heo mềm ngon', 75000, NULL, 'con_hang'),
(27, 2, 'Cơm chiên', 'Cơm chiên đi kèm nhiều món lẩu', 50000, 'com_chien.webp', 'con_hang');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `ma_nguoi_dung` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `vai_tro` enum('khachhang','nhanvien','admin') NOT NULL DEFAULT 'khachhang',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`ma_nguoi_dung`, `email`, `mat_khau`, `ho_ten`, `so_dien_thoai`, `vai_tro`, `ngay_tao`) VALUES
(3, 'khach@example.com', '$2y$10$WeRkxxCOUkndwyI8w6a/renRz2H63u9L13d0DLNr6AxolrqoAPUfO', 'nguyễn lê tuấn anh', NULL, 'khachhang', '2025-11-16 06:58:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chi_tiet_don`
--
ALTER TABLE `chi_tiet_don`
  ADD PRIMARY KEY (`ma_chi_tiet`),
  ADD KEY `ma_don_hang` (`ma_don_hang`),
  ADD KEY `ma_mon_an` (`ma_mon_an`);

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`ma_danh_gia`),
  ADD KEY `ma_nguoi_dung` (`ma_nguoi_dung`),
  ADD KEY `ma_mon_an` (`ma_mon_an`);

--
-- Indexes for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`ma_danh_muc`);

--
-- Indexes for table `dat_ban`
--
ALTER TABLE `dat_ban`
  ADD PRIMARY KEY (`ma_dat_ban`),
  ADD KEY `ma_nguoi_dung` (`ma_nguoi_dung`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don_hang`),
  ADD KEY `ma_nguoi_dung` (`ma_nguoi_dung`);

--
-- Indexes for table `mon_an`
--
ALTER TABLE `mon_an`
  ADD PRIMARY KEY (`ma_mon_an`),
  ADD KEY `ma_danh_muc` (`ma_danh_muc`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`ma_nguoi_dung`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `so_dien_thoai` (`so_dien_thoai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chi_tiet_don`
--
ALTER TABLE `chi_tiet_don`
  MODIFY `ma_chi_tiet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `ma_danh_gia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `ma_danh_muc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dat_ban`
--
ALTER TABLE `dat_ban`
  MODIFY `ma_dat_ban` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `ma_don_hang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mon_an`
--
ALTER TABLE `mon_an`
  MODIFY `ma_mon_an` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `ma_nguoi_dung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chi_tiet_don`
--
ALTER TABLE `chi_tiet_don`
  ADD CONSTRAINT `chi_tiet_don_ibfk_1` FOREIGN KEY (`ma_don_hang`) REFERENCES `don_hang` (`ma_don_hang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chi_tiet_don_ibfk_2` FOREIGN KEY (`ma_mon_an`) REFERENCES `mon_an` (`ma_mon_an`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`ma_mon_an`) REFERENCES `mon_an` (`ma_mon_an`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dat_ban`
--
ALTER TABLE `dat_ban`
  ADD CONSTRAINT `dat_ban_ibfk_1` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`ma_nguoi_dung`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `mon_an`
--
ALTER TABLE `mon_an`
  ADD CONSTRAINT `mon_an_ibfk_1` FOREIGN KEY (`ma_danh_muc`) REFERENCES `danh_muc` (`ma_danh_muc`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
