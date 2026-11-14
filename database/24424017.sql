-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: 24424017
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'Administrator SPMB','admin@pamitran.ac.id','$2y$10$dVicOpmqPYz3KYGbqSRTPujF8O.dNfAqd5bICqwXV5jI8lLMsK.8C','2026-11-14 01:39:58');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nim` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nim` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswa`
--

LOCK TABLES `mahasiswa` WRITE;
/*!40000 ALTER TABLE `mahasiswa` DISABLE KEYS */;
INSERT INTO `mahasiswa` VALUES (1,'24424017','wiwid','Informatika','2026-10-23 12:52:01'),(3,'24424011','tes','Informatika','2026-10-23 12:55:55');
/*!40000 ALTER TABLE `mahasiswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pendaftar`
--

DROP TABLE IF EXISTS `pendaftar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pendaftar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` varchar(30) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `asal_sekolah` varchar(150) NOT NULL,
  `program_studi_id` int(11) NOT NULL,
  `tahun_akademik_id` int(11) NOT NULL,
  `status` enum('Menunggu','Diterima','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_pendaftaran` (`no_pendaftaran`),
  UNIQUE KEY `nik` (`nik`),
  KEY `fk_pendaftar_prodi` (`program_studi_id`),
  KEY `fk_pendaftar_tahun` (`tahun_akademik_id`),
  CONSTRAINT `fk_pendaftar_prodi` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pendaftar_tahun` FOREIGN KEY (`tahun_akademik_id`) REFERENCES `tahun_akademik` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendaftar`
--

LOCK TABLES `pendaftar` WRITE;
/*!40000 ALTER TABLE `pendaftar` DISABLE KEYS */;
INSERT INTO `pendaftar` VALUES (1,'SPMB-20261113-BCADBD','1234567890123456','Andi Saputra','Karawang','2015-11-18','L','karawang','081234567890','andi@example.com','SMK Negeri 1 Karawang',1,1,'Diterima','2026-11-13 16:43:22','2026-11-14 02:08:03');
/*!40000 ALTER TABLE `pendaftar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `program_studi`
--

DROP TABLE IF EXISTS `program_studi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `program_studi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_prodi` varchar(10) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  `jenjang` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_prodi` (`kode_prodi`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_studi`
--

LOCK TABLES `program_studi` WRITE;
/*!40000 ALTER TABLE `program_studi` DISABLE KEYS */;
INSERT INTO `program_studi` VALUES (1,'TI','Teknik Informatika','S1','2026-11-13 16:01:32'),(2,'SI','Sistem Informasi','S1','2026-11-13 16:01:32'),(3,'MI','Manajemen Informatika','D3','2026-11-13 16:01:32');
/*!40000 ALTER TABLE `program_studi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tahun_akademik`
--

DROP TABLE IF EXISTS `tahun_akademik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tahun_akademik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` varchar(20) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `tahun` (`tahun`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tahun_akademik`
--

LOCK TABLES `tahun_akademik` WRITE;
/*!40000 ALTER TABLE `tahun_akademik` DISABLE KEYS */;
INSERT INTO `tahun_akademik` VALUES (1,'2026/2027',1,'2026-11-13 16:01:55'),(2,'2025/2026',0,'2026-11-13 16:01:55');
/*!40000 ALTER TABLE `tahun_akademik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database '24424017'
--

--
-- Dumping routines for database '24424017'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-14  9:38:45
