-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         10.1.30-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando datos para la tabla db_fact_quantum.entity: ~19 rows (aproximadamente)
/*!40000 ALTER TABLE `entity` DISABLE KEYS */;
INSERT IGNORE INTO `entity` (`id`, `name`) VALUES
	(1, 'Payment Type'),
	(2, 'Mail Server Type'),
	(3, 'Mail Security Type'),
	(4, 'Mail Identification Type'),
	(5, 'Permission'),
	(6, 'Role'),
	(7, 'Module'),
	(8, 'Document Type'),
	(9, 'Tax'),
	(10, 'Currency'),
	(11, 'Country'),
	(12, 'Language'),
	(13, 'Transportation Type'),
	(14, 'Identification Type'),
	(15, 'Emission Type'),
	(16, 'Environment Type'),
	(17, 'Tax Percentage'),
	(18, 'Bank'),
	(19, 'Time Unit'),
	(20, 'Retention Documents '),
	(21, 'Tax Retention'),
	(22, 'Tax Retention Percentage');
/*!40000 ALTER TABLE `entity` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
