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


-- Volcando estructura de base de datos para db_fact_quantum
CREATE DATABASE IF NOT EXISTS `db_fact_quantum` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `db_fact_quantum`;

-- Volcando estructura para tabla db_fact_quantum.tax_document
CREATE TABLE IF NOT EXISTS `tax_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `principal_code` varchar(9) NOT NULL,
  `referral_code` varchar(15) NOT NULL,
  `emission_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `xml_generated` blob,
  `auth_code` varchar(49) NOT NULL,
  `auth_date` datetime DEFAULT NULL,
  `emission_type` int(11) NOT NULL,
  `environment_type` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `document_type_id` int(11) NOT NULL,
  `branch_office_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `is_processed` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_tax_document_invoice1_idx` (`invoice_id`),
  KEY `fk_tax_document_entity_masterdata1_idx` (`document_type_id`),
  KEY `fk_emission_type_idx` (`emission_type`),
  KEY `fk_environment_type_idx` (`environment_type`),
  KEY `fk_supplier_idx` (`supplier_id`),
  KEY `fk_company_idx` (`branch_office_id`),
  CONSTRAINT `FK_tax_document_branch_office` FOREIGN KEY (`branch_office_id`) REFERENCES `branch_office` (`id`),
  CONSTRAINT `fk_emission_type` FOREIGN KEY (`emission_type`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_environment_type` FOREIGN KEY (`environment_type`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tax_document_entity_masterdata1` FOREIGN KEY (`document_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tax_document_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.tax_document: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `tax_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_document` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.tax_document_line
CREATE TABLE IF NOT EXISTS `tax_document_line` (
  `id` int(11) NOT NULL,
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
