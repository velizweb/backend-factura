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

-- Volcando estructura para tabla db_fact_quantum.tax_document_line
CREATE TABLE IF NOT EXISTS `tax_document_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_document_id` int(11) NOT NULL,
  `referral_document_type` int(11) NOT NULL,
  `referral_document` varchar(15) NOT NULL,
  `doc_emission_date` date NOT NULL,
  `tax_type_code` int(11) NOT NULL,
  `retention_type_code` int(11) NOT NULL,
  `tax_base_amount` decimal(15,2) NOT NULL,
  `tax_percentage` decimal(15,2) NOT NULL,
  `tax_total_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tax_document_line_idx` (`tax_document_id`),
  KEY `fk_document_type_idx` (`referral_document_type`),
  KEY `fk_tax_type_idx` (`tax_type_code`),
  KEY `fk_retention_type_idx` (`retention_type_code`),
  CONSTRAINT `fk_document_type` FOREIGN KEY (`referral_document_type`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_retention_type` FOREIGN KEY (`retention_type_code`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tax_document_line` FOREIGN KEY (`tax_document_id`) REFERENCES `tax_document` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tax_type` FOREIGN KEY (`tax_type_code`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.tax_document_line: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `tax_document_line` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_document_line` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
