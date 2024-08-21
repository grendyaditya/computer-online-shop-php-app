-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2024 at 04:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tokopc`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_user` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `photo_profile` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_user`, `username`, `password`, `nama_lengkap`, `photo_profile`) VALUES
(1, 'grendy', '$2y$10$Fkr0Y9sbL/YGJr5QG5.Og.x7bCQZb4WPvkJqN.sN3hWUzrgC17EDa', 'Grendy Aditya Pangestu', '65f1d10355ea3.jpg'),
(2, 'adrian', '$2y$10$HdVWn3kZNQhkvG32bd.Hf.w2p45W4pd1mzV4702nGaNu.h35gkmqS', 'Adrian Trinata', 'jadwal kelas.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_cart`
--

CREATE TABLE `tb_cart` (
  `id_cart` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_transaksi`
--

CREATE TABLE `tb_detail_transaksi` (
  `id_detail_transaksi` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) DEFAULT NULL,
  `gambar_kategori` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `nama_kategori`, `gambar_kategori`) VALUES
(55, 'SSD', '6640fdc95bcb3.png'),
(56, 'Hardisk', '6640fde511836.png'),
(57, 'Casing', '6640fdee6dedf.png'),
(58, 'Keyboard', '6640fdfa86935.png'),
(59, 'Motherboard', '6640fe0569051.png'),
(60, 'PC', '6640fe1067117.png'),
(61, 'Processor', '6640fe1b00ef7.png'),
(62, 'PSU', '6640fe2513c83.png'),
(63, 'RAM', '6640fe2e82610.png'),
(64, 'VGA', '6640fe4317584.png');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_produk`
--

CREATE TABLE `tb_kategori_produk` (
  `id_produk` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_penjualan`
--

CREATE TABLE `tb_penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `alamat_pelanggan` text NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `total_pembayaran` decimal(10,2) NOT NULL,
  `tanggal_penjualan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_penjualan`
--

INSERT INTO `tb_penjualan` (`id_penjualan`, `nama_pelanggan`, `deskripsi`, `alamat_pelanggan`, `metode_pembayaran`, `total_pembayaran`, `tanggal_penjualan`) VALUES
(1, 'dsa', 'PAKET PC PELAJAR (4x), PAKET PC GAMING KEREHORE (4x)', '123', 'qris', 37600000.00, '2024-06-02 10:57:38'),
(2, 'hdas', 'PAKET PC PELAJAR (1x), PAKET PC GAMING KEREHORE (2x)', 'jhsa', 'bank_bca', 15100000.00, '2024-06-02 11:00:43'),
(3, 'j', 'PAKET PC PELAJAR (4x), PAKET PC GAMING KEREHORE (2x)', 'b', 'tunai', 26200000.00, '2024-06-02 11:26:50'),
(4, 'jsadjsda', 'PAKET PC GAMING KEREHORE (8x), 120 GB NVME M.2 (3x), GTX 1050 Ti (2x)', 'jasjsa', 'tunai', 49400000.00, '2024-06-02 11:31:39'),
(5, 'Grendy', 'PAKET PC GAMING KEREHORE (6x), 120 GB NVME M.2 (3x)', 'Jl. Andul Karim', 'tunai', 34800000.00, '2024-06-02 12:13:49'),
(6, 'kasdkd', 'PAKET PC GAMING SULTAN (9x), 120 GB NVME M.2 (3x)', 'kdsds', 'tunai', 99999999.99, '2024-06-02 12:18:43'),
(7, 'dsa', '120 GB NVME M.2 (4x), GTX 1050 Ti (7x)', 'sd', 'tunai', 12000000.00, '2024-06-02 12:20:11'),
(8, 'hgsghs', '120 GB NVME M.2 (5x)', 'hs', 'bank_bca', 1000000.00, '2024-06-02 14:33:22'),
(9, 'uh', 'PAKET PC GAMING SULTAN (4x), PAKET PC PELAJAR (1x), GTX 1050 Ti (1x)', 'ki', 'bank_bca', 69300000.00, '2024-06-08 15:28:42'),
(10, 'adada', 'PAKET PC PELAJAR (1x)', 'ada', 'cod', 3700000.00, '2024-06-14 11:03:59'),
(11, 'adada', 'Luv 550V 80+ Silver (1x), PAKET PC GAMING KEREHORE (1x), 120 GB NVME M.2 (1x), PAKET PC PELAJAR (1x)', 'ada', 'tunai', 9900000.00, '2024-06-14 11:04:40'),
(12, 'adad', 'PAKET PC GAMING KEREHORE (1x), PSU CORSAIR !X760i (1x)', 'adada', 'tunai', 10200000.00, '2024-06-14 12:46:08'),
(13, 'adad', 'PAKET PC PELAJAR (1x), Zooms 8gb  (1x), Mather Board PRO Z6090-A WIFI (3x)', 'adad', 'tunai', 4050000.00, '2024-06-16 07:36:02'),
(14, 'Grendy', 'PAKET PC GAMING KEREHORE (1x)', 'Jl', 'transfer', 5700000.00, '2024-06-16 14:12:42'),
(15, 'Grendy', 'SSD NVMe M.2 2280 (2x), Zooms 8gb  (1x)', 'Jl', 'tunai', 11600000.00, '2024-06-16 14:13:02'),
(16, 'Adit', 'PAKET PC GAMING KEREHORE (1x)', 'Rungkut', 'cod', 5700000.00, '2024-06-16 14:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `tb_produk`
--

CREATE TABLE `tb_produk` (
  `id_produk` int(11) NOT NULL,
  `brand` varchar(25) DEFAULT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar_produk` text NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_produk`
--

INSERT INTO `tb_produk` (`id_produk`, `brand`, `nama_produk`, `harga`, `stok`, `deskripsi`, `gambar_produk`, `id_kategori`, `id_user`) VALUES
(30, 'BYTPC', 'PAKET PC PELAJAR', 3700000, 26, 'PROCESSOR\r\nAMD Ryzen 3 3200G 3.6Ghz Up To 4.0Ghz Cache 4MB 65W AM4 [Box] - 4 Core - YD3200C5FHBOX - with AMD Wraith Stealth Cooler (Garansi Lokal/AMD Indonesia)\r\nCPU Socket Type: AM4, Processors Generation: 2nd Gen Ryzen, Family: AMD Ryzen, Cores: 4 Cores, Threads: 4 Threads, Operating Frequency: 3.6 GHz, Max. Turbo Frequency: 4 GHz, Cache: 4 MB, Manufacturing Tech: 12 nm FinFET, Integrated Graphics: -, Thermal Design Power: 65 W, Thermal Solution (Cooler): Included, Warranty: 3 Year(s)\r\nMOTHERBOARD\r\nMAXSUN Challenger A520M-K (AM4, A520, DDR4, USB 3.2, SATA3)\r\nSSD\r\nKLEVV SSD CRAS C710 256GB M.2 2280 NVMe PCle Gen3 x4 - K256GM2SP0-C71 - R1950MB/s W1250MB/s\r\nSeries: CRAS C710, Capacity: 256GB, Interface: NVMe PCle Gen3 x4, Controller: SMI SM2263XT, Memory Components: SLC, Max. Sequential Read: 1,950MB/s, Max. Sequential Write: 1,250MB/s, Form Factor: M.2 2280, Warranty: 3 Year(s)\r\nRAM\r\nCUBE GAMING Saber DDR4 3200MHz PC25600 Single Channel 8GB (1x8GB) - Chipset By Micron\r\nCASING\r\nCUBE GAMING BLIG + PSU (Support ATX Size)\r\nKEYBOARD + MOUSE\r\nLogitech Classic MK120 USB (K120 + B100)\r\nMOUSEPAD\r\nMousepad Standard\r\nSOFTWARE\r\nWindows 11 Home 64Bit (*Unactivated*) &amp; Office Home Student 2021 (*Unactivated*)', '665a95950e301_f9d62b96-8bb5-4190-9ea7-cc914498d4c2.jpg', 60, 1),
(31, 'BYTPC', 'PAKET PC GAMING KEREHORE', 5700000, 32, 'PROCESSOR\r\nAMD Ryzen 5 5600G 3.9Ghz Up To 4.4Ghz Cache 16MB 65W AM4 [Box] - 6 Core - 100-100000252BOX - with AMD Wraith Stealth Cooler (Garansi Lokal/AMD Indonesia)\r\nCPU Socket Type: AM4, Processors Generation: 5th Gen Ryzen, Family: AMD Ryzen, Cores: 6 Cores, Threads: 12 Threads, Operating Frequency: 3.9 GHz, Max. Turbo Frequency: 4.4 GHz, Cache: 16 MB, Manufacturing Tech: 7 nm, Integrated Graphics: Radeon™ Graphics, Thermal Design Power: 65 W, Thermal Solution (Cooler): Include, Warranty: 3 Year(s)\r\nMOTHERBOARD\r\nASRock B450M Pro4 R2.0 (AM4, AMD Promontory B450, DDR4, USB3.2, SATA3)\r\nSSD\r\nADATA LEGEND 850 Lite 500GB NVME PCIe Gen4x4 - R 4700MB/S W 1700MB/S - ALEG-850L-500GCS\r\nRAM\r\nGEIL DDR4 ORION PC25600 3200Mhz Dual Channel 16GB (2X8GB) GAOG416GB3200C22DC (Support AMD &amp; INTEL)\r\nSeries: Orion, Capacity: 16GB, Type: DDR4 288 Pin, Speed: PC25600 (3200Mhz), Cas Latency: -, Voltage: 1.2 V, Timing: -, Warranty: Lifetime\r\nPSU\r\nAntec META V550 - 550W (Efficiency 80%) - 120mm Silent Fan - 2 Years Warranty Replacement\r\nCASING\r\nAntec CX300M RGB BLACK - Mini Tower Gaming Case - 4mm Tempered Glass Side Panel - Free 2Pcs 120mm RGB Fans Reverse + 1Pcs 120mm RGB Fans\r\nFAN CASING 12CM FAN CASE\r\nPCCooler FX-120-3 120MM Fixed LED Color Fan\r\nNETWORKING\r\nTP - Link 150 Mbps Wireless N USB Adapter + Antenna - TL-WN722N\r\nSOFTWARE\r\nWindows 11 Home 64Bit (*Unactivated*) &amp; Office Home Student 2021 (*Unactivated*)', '665a96f31fef0_9c952a8d-7920-4629-8549-c449350decf7.jpg', 60, 1),
(32, 'BYTPC', 'PAKET PC GAMING SULTAN', 16000000, -6, 'PROCESSOR\r\nIntel Core i5-12400F 2.5GHz Up To 4.4GHz - Cache 18MB [Tray] Socket LGA 1700 - Alder Lake Series - NEW UNIT - 1 Years Warranty\r\nCPU Socket Type: LGA 1700, Processors Generation: 12 th gen, Family: Alder Lake, Cores: 6 Cores, Threads: 12 Threads, Operating Frequency: 2.5 GHz, Max. Turbo Frequency: 4.4 GHz, Cache: 18 MB, Manufacturing Tech: Intel 7, Integrated Graphics: -, Thermal Design Power: 65 W, Thermal Solution (Cooler): Include, Warranty: 1 Year(s)\r\nMOTHERBOARD\r\nASRock B760 Pro RS/D4 (LGA1700, B760, DDR4, USB3.2 Type-C, SATA3)\r\nNVIDIA\r\nZotac GeForce RTX 4060 Ti 8GB GDDR6 Twin Edge OC White\r\nSSD\r\nKLEVV SSD CRAS C710 1TB M.2 2280 NVMe PCle Gen3 x4 - K01TBM2SP0-C71 - R2100MB/s W1650MB/s\r\nSeries: CRAS C710, Capacity: 1TB, Interface: NVMe PCle Gen3 x4, Controller: SMI SM2263XT, Memory Components: SLC, Max. Sequential Read: 2,100MB/s, Max. Sequential Write: 1,650MB/s, Form Factor: M.2 2280, Warranty: 3 Year(s)\r\nRAM\r\nADATA DDR4 XPG SPECTRIX D45G RGB WHITE VERSION PC28800 3600MHz 32GB (2X16GB) Dual Channel - AX4U360016G18I-DCWHD45G\r\nSeries: SPECTRIX D45G, Capacity: 32GB (2X16GB), Type: DDR4 SDRAM 288-Pin, Speed: DDR4 3600 (PC4 28800), Cas Latency: -, Voltage: 1.35 V, Timing: -, Warranty: Lifetime\r\nPSU\r\nSuper Flower Mega Series 600W - SF-600R12ST - 80 PLUS White - 5 Years\r\nCASING\r\nCUBE GAMING PREMIUM LAUREN WHITE - ATX Gaming Case - All White Inside - LEFT SIDE TEMPERED GLASS - FRONT &amp; TOP MESH PANEL - PSU COVER - DUST FILTER - Free 3PCS 12CM ARGB FAN\r\nAIR COOLER / HEATSINK COOLER\r\nCUBE GAMING STORM V2 WHITE - SINGLE FAN 12CM ARGB - Universal Socket (AM5 Ready)\r\nFAN CASING 12CM FAN CASE\r\nCUBE GAMING MYSTIQUE FAN White 12CM PWM A-RGB Fan\r\nFAN CASING 12CM FAN CASE\r\nCUBE GAMING MYSTIQUE FAN White 12CM PWM A-RGB Fan (3x 12CM PWM Fan A-RGB)\r\nNETWORKING\r\nTP - Link 150 Mbps Wireless N PCI Express Adapter - TL-WN781ND\r\nSOFTWARE\r\nWindows 11 Home 64Bit (*Unactivated*) &amp; Office Home Student 2021 (*Unactivated*)', '665a97fe9cf7e_17-5-24-Battle-Star-TI1-1716631527.jpg', 60, 1),
(33, 'Zooms', 'Zooms 8gb ', 200000, 18, 'Zooms 8gb ', '666c2a01d10d7_What Is RAM, How Much Do You Need, and Which One Should You Buy.jpeg', 63, 1),
(34, 'Inter', 'Proccessor i5', 1700000, 4, 'Proccesor i5 gen 11', '666c2b57a38cf_19-118-347-05.jpg', 61, 1),
(35, 'MSSI', 'Mather Board PRO Z6090-A WIFI', 50000, 2, 'Mather Board PRO Z6090-A WIFI', '666c2d9d928f6_0aaee-16587569334551-1920.jpg', 59, 1),
(36, 'CORSAIR', 'PSU CORSAIR !X760i', 4500000, 3, 'PSU CORSAIR !X760i', '666c3861deac5_ax760i_psu_sideview_a.png', 62, 1),
(37, 'GIGABYTE', 'SSD NVMe M.2 2280', 5700000, 4, 'SSD NVMe M.2 2280', '666c38fb13f37_Png.png', 55, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_riwayat`
--

CREATE TABLE `tb_riwayat` (
  `id_riwayat` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tindakan` text NOT NULL,
  `tanggal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_riwayat`
--

INSERT INTO `tb_riwayat` (`id_riwayat`, `id_user`, `tindakan`, `tanggal`) VALUES
(1, 1, 'Menambahkan kategori baru: PC', 2024),
(2, 1, 'Menambahkan kategori baru: ACCCCCCC', 1715373565),
(3, 1, 'Menambahkan kategori baru: SS', 1715373569),
(4, 1, 'Mengubah kategori produk dengan ID 22 menjadi CX', 1715373575),
(5, 1, 'Menambahkan kategori baru: HSAD', 1715373583),
(6, 1, 'Mengubah kategori produk dengan ID 26 menjadi PROAS', 1715374005),
(7, 1, 'Menambahkan kategori baru: SJJS', 1715374013),
(8, 1, 'Menambahkan kategori baru: AAX', 1715374019),
(9, 1, 'Menambahkan kategori baru: DAD', 1715374030),
(10, 1, 'Menambahkan kategori baru: Sddsds', 1715374127),
(11, 1, 'Menambahkan kategori baru: Sd', 1715374209),
(12, 1, 'Menambahkan kategori baru: Jdjdsa', 1715374220),
(13, 1, 'Berhasil mengubah profil', 1715375040),
(14, 1, 'Berhasil mengubah profil', 1715375047),
(15, 1, 'Berhasil mengubah password', 1715375081),
(16, 2, 'Berhasil menambahkan produk dasdsa', 1715446406),
(17, 2, 'Berhasil menambahkan produk jsdsd', 1715446421),
(18, 2, 'Berhasil menambahkan produk sdj', 1715447052),
(19, 2, 'Berhasil menghapus produk Dasdsa', 1715448842),
(20, 2, 'Berhasil menghapus produk Jsdsd', 1715448844),
(21, 2, 'Berhasil menghapus produk Sdj', 1715448848),
(22, 2, 'Mengubah kategori produk dengan ID 35 menjadi Sdsad', 1715448902),
(23, 2, 'Menambahkan kategori baru: PC', 1715448912),
(24, 2, 'Menambahkan kategori baru: VGA', 1715448917),
(25, 2, 'Menambahkan kategori baru: Processor', 1715448929),
(26, 2, 'Menambahkan kategori baru: SSD', 1715448938),
(27, 2, 'Menambahkan kategori baru: RAM', 1715448942),
(28, 2, 'Menambahkan kategori baru: Motherboard', 1715448959),
(29, 2, 'Berhasil menambahkan produk fadfd', 1715448979),
(30, 1, 'Berhasil menambahkan produk asddsa', 1715450878),
(31, 1, 'Berhasil menambahkan produk 321321sad', 1715450926),
(32, 1, 'Berhasil menghapus produk 321321sad', 1715451236),
(33, 1, 'Berhasil menghapus produk Asddsa', 1715451238),
(34, 1, 'Berhasil menghapus produk Fadfd', 1715451241),
(35, 1, 'Berhasil menambahkan produk jsdjsd', 1715451257),
(36, 1, 'Berhasil menambahkan produk dssd', 1715451467),
(37, 1, 'Berhasil mengubah produk jsdjsd', 1715453753),
(38, 1, 'Berhasil mengubah produk jsdjsd', 1715453768),
(39, 1, 'Berhasil mengubah produk jsdjsd', 1715454220),
(40, 1, 'Berhasil menambahkan produk sda', 1715507804),
(41, 1, 'Berhasil menambahkan produk asf', 1715507819),
(42, 1, 'Berhasil mengubah produk asf', 1715509188),
(43, 1, 'Berhasil mengubah produk jsdjsd', 1715509199),
(44, 1, 'Berhasil mengubah produk jsdjsd', 1715509258),
(45, 1, 'Berhasil mengubah produk jsdjsd', 1715509283),
(46, 1, 'Berhasil mengubah produk NOVELLL', 1715509991),
(47, 1, 'Berhasil mengubah produk NOVELLL', 1715510000),
(48, 1, 'Berhasil mengubah produk NOVELLL', 1715510044),
(49, 1, 'Berhasil mengubah produk NOVELLL', 1715510050),
(50, 1, 'Berhasil mengubah produk NOVELLL', 1715510595),
(51, 1, 'Berhasil mengubah produk NOVELLL', 1715510603),
(52, 1, 'Berhasil mengubah produk sda', 1715510612),
(53, 1, 'Berhasil mengubah produk dssd', 1715510621),
(54, 1, 'Berhasil menghapus produk Jsdjsd', 1715510713),
(55, 1, 'Berhasil menghapus produk ', 1715510714),
(56, 1, 'Berhasil menghapus produk ', 1715510816),
(57, 1, 'Berhasil menghapus produk ', 1715510817),
(58, 1, 'Berhasil menghapus produk ', 1715510817),
(59, 1, 'Berhasil menghapus produk ', 1715510818),
(60, 1, 'Berhasil menghapus produk ', 1715510818),
(61, 1, 'Berhasil menghapus produk ', 1715510818),
(62, 1, 'Berhasil menghapus produk ', 1715510836),
(63, 1, 'Berhasil menghapus produk ', 1715510856),
(64, 1, 'Berhasil menghapus produk ', 1715510856),
(65, 1, 'Berhasil menghapus produk ', 1715510856),
(66, 1, 'Berhasil menghapus produk ', 1715510857),
(67, 1, 'Berhasil menghapus produk ', 1715510857),
(68, 1, 'Berhasil menghapus produk ', 1715510857),
(69, 1, 'Berhasil menghapus produk ', 1715510890),
(70, 1, 'Berhasil menghapus produk ', 1715510891),
(71, 1, 'Berhasil menghapus produk ', 1715510891),
(72, 1, 'Berhasil menambahkan produk novel', 1715511298),
(73, 1, 'Berhasil menghapus produk ', 1715511311),
(74, 1, 'Berhasil menghapus produk ', 1715511456),
(75, 1, 'Berhasil menghapus produk ', 1715511458),
(76, 1, 'Berhasil menghapus produk ', 1715511460),
(77, 1, 'Berhasil menghapus produk dssd', 1715511662),
(78, 1, 'Berhasil menambahkan produk adfadsdsasad', 1715511949),
(79, 1, 'Berhasil mengubah produk lllllllllllllllllllll', 1715511978),
(80, 1, 'Berhasil mengubah produk novel', 1715512235),
(81, 1, 'Berhasil menghapus produk novel', 1715512333),
(82, 1, 'Berhasil menghapus produk lllllllllllllllllllll', 1715512335),
(83, 1, 'Berhasil menambahkan produk Novel', 1715512354),
(84, 1, 'Berhasil menambahkan produk TES', 1715514535),
(85, 1, 'Berhasil mengubah produk TESS', 1715514603),
(86, 1, 'Berhasil menghapus produk Novel', 1715527024),
(87, 1, 'Berhasil menghapus produk TESS', 1715527026),
(88, 1, 'Menambahkan kategori baru: Casing', 1715527053),
(89, 1, 'Menambahkan kategori baru: Casing', 1715527411),
(90, 1, 'Menambahkan kategori baru: Casing', 1715527985),
(91, 1, 'Menambahkan kategori baru: PSU', 1715528123),
(92, 1, 'Menambahkan kategori baru: VGA', 1715528627),
(93, 1, 'Menambahkan kategori baru: VGA', 1715528981),
(94, 1, 'Menambahkan kategori baru: VGA', 1715529090),
(95, 1, 'Menambahkan kategori baru: VGA', 1715530603),
(96, 1, 'Berhasil menambahkan produk DSDSA', 1715530802),
(97, 1, 'Menambahkan kategori baru: SSD', 1715530905),
(98, 1, 'Berhasil menghapus produk DSDSA', 1715533174),
(99, 1, 'Menambahkan kategori baru: RAM', 1715533202),
(100, 1, 'Berhasil menambahkan produk sad', 1715533423),
(101, 1, 'Menambahkan kategori baru: Sadsda', 1715534087),
(102, 1, 'Berhasil menghapus produk sad', 1715534098),
(103, 1, 'Menambahkan kategori baru: Casing', 1715534423),
(104, 1, 'Menambahkan kategori baru: Casing', 1715534809),
(105, 1, 'Mengubah gambar kategori: 55', 1715534966),
(106, 1, 'Mengubah gambar kategori: 55', 1715534987),
(107, 1, 'Mengubah gambar kategori dan nama kategori: 55', 1715535305),
(108, 1, 'Menambahkan kategori baru: Casing', 1715535319),
(109, 1, 'Mengubah gambar kategori dan nama kategori: 56', 1715535333),
(110, 1, 'Menambahkan kategori baru: Casing', 1715535342),
(111, 1, 'Menambahkan kategori baru: Keyboard', 1715535354),
(112, 1, 'Menambahkan kategori baru: Motherboard', 1715535365),
(113, 1, 'Menambahkan kategori baru: PC', 1715535376),
(114, 1, 'Menambahkan kategori baru: Processor', 1715535387),
(115, 1, 'Menambahkan kategori baru: PSU', 1715535397),
(116, 1, 'Menambahkan kategori baru: RAM', 1715535406),
(117, 1, 'Menambahkan kategori baru: VGA', 1715535427),
(118, 1, 'Berhasil menambahkan produk GTX 1050', 1715535507),
(119, 1, 'Berhasil menambahkan produk i5 10001F', 1715535557),
(120, 1, 'Berhasil menambahkan produk PSU LUV 550V', 1715535604),
(121, 1, 'Berhasil menghapus produk GTX 1050', 1715535764),
(122, 1, 'Berhasil menghapus produk i5 10001F', 1715535768),
(123, 1, 'Berhasil menghapus produk PSU LUV 550V', 1715535769),
(124, 1, 'Berhasil menambahkan produk GTX 1050 Ti', 1715536198),
(125, 1, 'Berhasil menambahkan produk 120 GB NVME M.2', 1715536241),
(126, 1, 'Berhasil menambahkan produk asddaf', 1715536258),
(127, 1, 'Berhasil mengubah produk dengan ID 25', 1715536308),
(128, 1, 'Berhasil menambahkan produk ksdak', 1715536326),
(129, 1, 'Berhasil menghapus produk ksdak', 1715536332),
(130, 2, 'Berhasil mengubah password', 1715536635),
(131, 1, 'Berhasil mengubah password', 1715536686),
(132, 1, 'Berhasil menambahkan produk PC SULTAN', 1716619593),
(133, 1, 'Berhasil menambahkan produk PC KERE HORE', 1716622703),
(134, 1, 'Berhasil menambahkan produk PC DEWA', 1716622734),
(135, 1, 'Berhasil menghapus produk PC KERE HORE', 1717212196),
(136, 1, 'Berhasil menghapus produk PC DEWA', 1717212203),
(137, 1, 'Berhasil menghapus produk PC SULTAN', 1717212213),
(138, 1, 'Berhasil menambahkan produk PC Paket Pelajar', 1717212565),
(139, 1, 'Berhasil menambahkan produk PAKET PC GAMING KEREHORE', 1717212915),
(140, 1, 'Berhasil mengubah produk dengan ID 30', 1717212933),
(141, 1, 'Berhasil menambahkan produk PAKET PC GAMING SULTAN', 1717213182),
(142, 1, 'Berhasil mengubah produk dengan ID 31', 1717213226),
(143, 1, 'Berhasil mengubah produk dengan ID 32', 1717213244),
(144, 1, 'Berhasil mengubah produk dengan ID 30', 1717213254),
(145, 1, 'Berhasil mengubah produk dengan ID 31', 1717213379),
(146, 1, 'Berhasil mengubah produk dengan ID 32', 1717213389),
(147, 1, 'Berhasil mengubah produk dengan ID 30', 1717213401),
(148, 1, 'Berhasil menghapus produk 120 GB NVME M.2', 1718364611),
(149, 1, 'Berhasil menghapus produk GTX 1050 Ti', 1718364613),
(150, 1, 'Berhasil menghapus produk Luv 550V 80+ Silver', 1718364615),
(151, 1, 'Berhasil menambahkan produk Zooms 8gb ', 1718364673),
(152, 1, 'Berhasil menambahkan produk Proccessor i5', 1718365015),
(153, 1, 'Berhasil menambahkan produk Mather Board PRO Z6090-A WIFI', 1718365597),
(154, 1, 'Berhasil menambahkan produk PSU CORSAIR !X760i', 1718368353),
(155, 1, 'Berhasil menambahkan produk SSD NVMe M.2 2280', 1718368507);

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tb_cart`
--
ALTER TABLE `tb_cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `tb_kategori_produk`
--
ALTER TABLE `tb_kategori_produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `id_produk` (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `fk_kategori_produk` (`id_kategori`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_riwayat`
--
ALTER TABLE `tb_riwayat`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_cart`
--
ALTER TABLE `tb_cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  MODIFY `id_detail_transaksi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_produk`
--
ALTER TABLE `tb_produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tb_riwayat`
--
ALTER TABLE `tb_riwayat`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_cart`
--
ALTER TABLE `tb_cart`
  ADD CONSTRAINT `tb_cart_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `tb_produk` (`id_produk`);

--
-- Constraints for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD CONSTRAINT `tb_detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id_transaksi`),
  ADD CONSTRAINT `tb_detail_transaksi_ibfk_3` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id_kategori`),
  ADD CONSTRAINT `tb_detail_transaksi_ibfk_4` FOREIGN KEY (`id_produk`) REFERENCES `tb_produk` (`id_produk`);

--
-- Constraints for table `tb_kategori_produk`
--
ALTER TABLE `tb_kategori_produk`
  ADD CONSTRAINT `tb_kategori_produk_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id_kategori`);

--
-- Constraints for table `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD CONSTRAINT `fk_kategori_produk` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id_kategori`),
  ADD CONSTRAINT `tb_produk_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_admin` (`id_user`);

--
-- Constraints for table `tb_riwayat`
--
ALTER TABLE `tb_riwayat`
  ADD CONSTRAINT `tb_riwayat_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_admin` (`id_user`);

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_admin` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
