-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.18-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_dcpackages.doc_packages
CREATE TABLE IF NOT EXISTS `doc_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 1,
  `date_created` timestamp NULL DEFAULT NULL,
  `doc_name` varchar(60) NOT NULL,
  `page_num` int(11) DEFAULT 1,
  `date_ended` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `doc_packages_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 AVG_ROW_LENGTH=5461;

-- Dumping data for table db_dcpackages.doc_packages: ~4 rows (approximately)
/*!40000 ALTER TABLE `doc_packages` DISABLE KEYS */;
INSERT INTO `doc_packages` (`id`, `code`, `user_id`, `date_created`, `doc_name`, `page_num`, `date_ended`) VALUES
	(1, '2_20211128_212916', 2, '2021-11-28 09:29:25', 'DOCUMENT TEST2021-11-28 09:29:25', 4, NULL),
	(2, '2_20211128_213205', 2, '2021-11-28 09:32:12', 'DOCUMENT TEST2021-11-28 09:32:12', 4, NULL),
	(3, '2_20211128_213440', 2, '2021-11-28 09:34:47', 'DOCUMENT TEST2021-11-28 09:34:47', 4, '2021-11-28 09:35:29'),
	(4, '2_20211128_215329', 2, '2021-11-28 09:53:44', 'DOCUMENT TEST2021-11-28 09:53:44', 10, '2021-11-28 09:55:29');
/*!40000 ALTER TABLE `doc_packages` ENABLE KEYS */;

-- Dumping structure for table db_dcpackages.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table db_dcpackages.users: ~2 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`) VALUES
	(1, 'ADMIN ADMIN'),
	(2, 'TESTER');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
