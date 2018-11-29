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

-- Volcando estructura para tabla db_fact_quantum.aditional_field
CREATE TABLE IF NOT EXISTS `aditional_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `value` varchar(180) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_aditional_field_invoice1_idx` (`invoice_id`),
  CONSTRAINT `fk_aditional_field_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.aditional_field: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `aditional_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `aditional_field` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.audit_user
CREATE TABLE IF NOT EXISTS `audit_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concept` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `create_ad` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_audit_user_company1_idx` (`company_id`),
  KEY `fk_audit_user_users1_idx` (`users_id`),
  CONSTRAINT `fk_audit_user_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_audit_user_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.audit_user: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `audit_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_user` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.branch_office
CREATE TABLE IF NOT EXISTS `branch_office` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `phone` varchar(80) DEFAULT NULL,
  `address` varchar(80) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `emission_point` varchar(3) NOT NULL,
  `environment_type` tinyint(1) NOT NULL,
  `emission_type` tinyint(1) NOT NULL,
  `company_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_office_company1_idx` (`company_id`),
  CONSTRAINT `fk_branch_office_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.branch_office: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `branch_office` DISABLE KEYS */;
INSERT INTO `branch_office` (`id`, `name`, `phone`, `address`, `email`, `emission_point`, `environment_type`, `emission_type`, `company_id`, `is_active`, `is_deleted`) VALUES
	(1, 'GUAN YIN ART SUPPLIES', '', 'PICHINCHA / QUITO / LA MAGDALENA / GALTE S9-46 Y RODRIGO DE CHAVEZ', '', '001', 1, 1, 1, 1, 0);
/*!40000 ALTER TABLE `branch_office` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.branch_parameter
CREATE TABLE IF NOT EXISTS `branch_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_office_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `mail_configuration_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_parameter_branch_office1_idx` (`branch_office_id`),
  KEY `fk_branch_parameter_parameter1_idx` (`parameter_id`),
  KEY `fk_branch_parameter_mail_configuration1_idx` (`mail_configuration_id`),
  CONSTRAINT `fk_branch_parameter_branch_office1` FOREIGN KEY (`branch_office_id`) REFERENCES `branch_office` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_branch_parameter_mail_configuration1` FOREIGN KEY (`mail_configuration_id`) REFERENCES `mail_configuration` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_branch_parameter_parameter1` FOREIGN KEY (`parameter_id`) REFERENCES `parameter` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.branch_parameter: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `branch_parameter` DISABLE KEYS */;
/*!40000 ALTER TABLE `branch_parameter` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.client
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `social_reason` varchar(255) NOT NULL,
  `comercial_name` varchar(255) DEFAULT NULL,
  `phone` varchar(80) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client_company1_idx` (`company_id`),
  CONSTRAINT `fk_client_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.client: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` (`id`, `social_reason`, `comercial_name`, `phone`, `address`, `email`, `company_id`, `is_active`, `is_deleted`) VALUES
	(1, 'Cliente 1', 'Cliente 1', NULL, '1773 Crossfields Rd', NULL, 1, 1, 0),
	(2, 'TONNY', 'CARDENAS', '04124837191', 'San Diego', 'cardenat@gmail.com', 1, 1, 0),
	(3, 'Cliente 3', 'Cliente 3 comercial', '7866017730', 'Valencia, Valencia', '', 1, 1, 0),
	(4, 'Cliente 4', 'Cliente 4', NULL, '1773 Crossfields Rd', '', 1, 1, 0);
/*!40000 ALTER TABLE `client` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.client_identification_type
CREATE TABLE IF NOT EXISTS `client_identification_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identification` varchar(20) NOT NULL,
  `client_id` int(11) NOT NULL,
  `identification_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client_identification_type_client1_idx` (`client_id`),
  KEY `fk_client_identification_type_entity_masterdata1_idx` (`identification_type_id`),
  CONSTRAINT `fk_client_identification_type_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_client_identification_type_entity_masterdata1` FOREIGN KEY (`identification_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.client_identification_type: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `client_identification_type` DISABLE KEYS */;
INSERT INTO `client_identification_type` (`id`, `identification`, `client_id`, `identification_type_id`) VALUES
	(1, '0503263303', 2, 18);
/*!40000 ALTER TABLE `client_identification_type` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.company
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `comercial_name` varchar(180) NOT NULL,
  `ruc` varchar(13) NOT NULL,
  `special_code` varchar(5) DEFAULT NULL,
  `emission_code` varchar(3) NOT NULL,
  `tax_year` varchar(6) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `phone` varchar(80) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `logo` blob,
  `email` varchar(80) DEFAULT NULL,
  `digital_certificate` blob NOT NULL,
  `digital_certificate_pass` varchar(50) NOT NULL,
  `environment_type` tinyint(1) NOT NULL,
  `emission_type` tinyint(1) NOT NULL,
  `is_accounting` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.company: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` (`id`, `name`, `comercial_name`, `ruc`, `special_code`, `emission_code`, `tax_year`, `url`, `phone`, `address`, `logo`, `email`, `digital_certificate`, `digital_certificate_pass`, `environment_type`, `emission_type`, `is_accounting`, `is_active`, `is_deleted`) VALUES
	(1, 'Quantum', 'Quantum Ecuador', '1234567890123', NULL, '1', '102018', 'http://quantum.com', '+59312345678', 'Ecuador', NULL, 'test@quantum.com', _binary '', '', 0, 0, 1, 1, NULL);
/*!40000 ALTER TABLE `company` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.company_parameter
CREATE TABLE IF NOT EXISTS `company_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `mail_configuration_id` int(11) NOT NULL,
  `emission_type` tinyint(1) NOT NULL,
  `environment_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_parameter_company1_idx` (`company_id`),
  KEY `fk_company_parameter_parameter1_idx` (`parameter_id`),
  KEY `fk_company_parameter_mail_configuration1_idx` (`mail_configuration_id`),
  CONSTRAINT `fk_company_parameter_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_parameter_mail_configuration1` FOREIGN KEY (`mail_configuration_id`) REFERENCES `mail_configuration` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_parameter_parameter1` FOREIGN KEY (`parameter_id`) REFERENCES `parameter` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.company_parameter: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `company_parameter` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_parameter` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.company_plan
CREATE TABLE IF NOT EXISTS `company_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `current_counter` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `company_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_plan_company1_idx` (`company_id`),
  KEY `fk_company_plan_plan1_idx` (`plan_id`),
  CONSTRAINT `fk_company_plan_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_plan_plan1` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.company_plan: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `company_plan` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_plan` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.company_tax_year
CREATE TABLE IF NOT EXISTS `company_tax_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `tax_year` varchar(6) NOT NULL,
  `is_opened` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_company_tax_year_company` (`company_id`),
  CONSTRAINT `FK_company_tax_year_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.company_tax_year: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `company_tax_year` DISABLE KEYS */;
INSERT INTO `company_tax_year` (`id`, `company_id`, `tax_year`, `is_opened`, `is_active`, `is_deleted`) VALUES
	(1, 1, '102018', 0, 1, 0);
/*!40000 ALTER TABLE `company_tax_year` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.correlative_document
CREATE TABLE IF NOT EXISTS `correlative_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `increment_number` int(9) NOT NULL,
  `serie` varchar(6) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_correlative_document_company1_idx` (`company_id`),
  KEY `fk_correlative_document_entity_masterdata1_idx` (`document_type_id`),
  CONSTRAINT `fk_correlative_document_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_correlative_document_entity_masterdata1` FOREIGN KEY (`document_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.correlative_document: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `correlative_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `correlative_document` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.country_currency
CREATE TABLE IF NOT EXISTS `country_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_country_currency_entity_masterdata1_idx` (`currency_id`),
  KEY `fk_country_currency_entity_masterdata2_idx` (`country_id`),
  CONSTRAINT `fk_country_currency_entity_masterdata1` FOREIGN KEY (`currency_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_country_currency_entity_masterdata2` FOREIGN KEY (`country_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.country_currency: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `country_currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `country_currency` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.country_language
CREATE TABLE IF NOT EXISTS `country_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_country_language_entity_masterdata1_idx` (`country_id`),
  KEY `fk_country_language_entity_masterdata2_idx` (`language_id`),
  CONSTRAINT `fk_country_language_entity_masterdata1` FOREIGN KEY (`country_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_country_language_entity_masterdata2` FOREIGN KEY (`language_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.country_language: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `country_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `country_language` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.country_tax
CREATE TABLE IF NOT EXISTS `country_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` decimal(15,2) NOT NULL,
  `country_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_percentage_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_country_tax_entity_masterdata1_idx` (`country_id`),
  KEY `fk_country_tax_entity_masterdata2_idx` (`tax_id`),
  KEY `fk_entitymd_tax_percentage` (`tax_percentage_id`),
  CONSTRAINT `fk_country_tax_entity_masterdata1` FOREIGN KEY (`country_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_country_tax_entity_masterdata2` FOREIGN KEY (`tax_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_entitymd_tax_percentage` FOREIGN KEY (`tax_percentage_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.country_tax: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `country_tax` DISABLE KEYS */;
INSERT INTO `country_tax` (`id`, `value`, `country_id`, `tax_id`, `tax_percentage_id`) VALUES
	(1, 0.00, 28, 29, 24),
	(2, 12.00, 28, 29, 23),
	(3, 14.00, 28, 29, 25),
	(4, 0.00, 28, 29, 26),
	(5, 0.00, 28, 29, 27);
/*!40000 ALTER TABLE `country_tax` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.dispatcher
CREATE TABLE IF NOT EXISTS `dispatcher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(180) DEFAULT NULL,
  `last_name` varchar(180) DEFAULT NULL,
  `phone` varchar(80) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `dni` varchar(50) DEFAULT NULL,
  `passport` varchar(50) DEFAULT NULL,
  `ruc` varchar(50) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `identificacion_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dispatcher_company1_idx` (`company_id`),
  KEY `fk_dispatcher_entity_masterdata1_idx` (`identificacion_type_id`),
  CONSTRAINT `fk_dispatcher_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_dispatcher_entity_masterdata1` FOREIGN KEY (`identificacion_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.dispatcher: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `dispatcher` DISABLE KEYS */;
/*!40000 ALTER TABLE `dispatcher` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.entity
CREATE TABLE IF NOT EXISTS `entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.entity: ~19 rows (aproximadamente)
/*!40000 ALTER TABLE `entity` DISABLE KEYS */;
INSERT INTO `entity` (`id`, `name`) VALUES
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
	(19, 'Time Unit');
/*!40000 ALTER TABLE `entity` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.entity_masterdata
CREATE TABLE IF NOT EXISTS `entity_masterdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `field` varchar(5) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_entity_masterdata_entity1_idx` (`entity_id`),
  CONSTRAINT `fk_entity_masterdata_entity1` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.entity_masterdata: ~46 rows (aproximadamente)
/*!40000 ALTER TABLE `entity_masterdata` DISABLE KEYS */;
INSERT INTO `entity_masterdata` (`id`, `code`, `name`, `description`, `field`, `is_active`, `entity_id`, `is_deleted`) VALUES
	(1, '01', 'Sin utilización del Sistema Financiero', 'Sin utilización del Sistema Financiero', NULL, 1, 1, 0),
	(2, '15', 'Compensación de Deudas', 'Compensación de deudas', NULL, 1, 1, 0),
	(3, '16', 'Tarjeta de Débito', 'Tarjeta de Debito', NULL, 1, 1, 0),
	(4, 'CO', 'Colombia', 'Colombia', NULL, 0, 11, 1),
	(5, 'IMAP', 'IMAP', 'IMAP', NULL, 1, 2, 0),
	(6, 'POP', 'POP', 'POP', NULL, 1, 2, 0),
	(7, 'Ninguna', 'Ninguna', 'Ninguna', NULL, 1, 3, 0),
	(8, 'STARTTLS', 'STARTTLS', 'STARTTLS', NULL, 1, 3, 0),
	(9, 'SSL/TLS', 'SSL/TLS', 'SSL/TLS', NULL, 1, 3, 0),
	(10, '1', 'Contraseña normal', 'Contraseña normal', NULL, 1, 4, 0),
	(11, '2', 'Contraseña Cifrada', 'Contraseña Cifrada', NULL, 1, 4, 0),
	(12, '3', 'Kerberos / GSSAPI', 'Kerberos / GSSAPI', NULL, 1, 4, 0),
	(13, 'NTLM', 'NTLM', 'NTLM', NULL, 1, 4, 0),
	(14, 'TLS', 'Certificado TLS', 'Certificado TLS', NULL, 1, 4, 0),
	(15, 'OAuth2', 'OAuth2', 'OAuth2', NULL, 1, 4, 0),
	(16, 'MOD-01', 'Modulo 01', 'Modulo 01', NULL, 1, 7, 0),
	(17, '04', 'RUC', 'RUC', NULL, 1, 14, 0),
	(18, '05', 'CEDULA', 'CEDULA', NULL, 1, 14, 0),
	(19, '06', 'PASAPORTE', 'PASAPORTE', NULL, 1, 14, 0),
	(20, '07', 'VENTA A CONSUMIDOR FINAL', 'VENTA A CONSUMIDOR FINAL', NULL, 1, 14, 0),
	(21, '08', 'IDENTIFICACION DEL EXTERIOR', 'IDENTIFICACION DEL EXTERIOR', NULL, 1, 14, 0),
	(22, '09', 'PLACA', 'PLACA', NULL, 1, 14, 0),
	(23, '2', 'IVA 12 %', 'IVA 12 %', NULL, 1, 17, 0),
	(24, '0', 'IVA 0%', 'IVA 0%', NULL, 1, 17, 0),
	(25, '3', 'IVA 14%', 'IVA 14%', NULL, 1, 17, 0),
	(26, '6', 'no objeto de impuesto', 'no objeto de impuesto', NULL, 1, 17, 0),
	(27, '7', 'Exento de IVA', 'Exento de IVA', NULL, 1, 17, 0),
	(28, 'EC', 'Ecuador', 'Ecuador', NULL, 1, 11, 0),
	(29, '2', 'IVA', 'IVA', NULL, 1, 9, 0),
	(30, '3', 'ICE', 'ICE', NULL, 1, 9, 0),
	(31, '5', 'IRBPNR', 'IRBPNR', NULL, 1, 9, 0),
	(32, '01', 'Banco A', 'Banco A', NULL, 1, 18, 0),
	(33, '17', 'Dinero Electrónico', 'Dinero Electrónico', NULL, 1, 1, 0),
	(34, '18', 'Tarjeta Prepago', 'Tarjeta Prepago', NULL, 1, 1, 0),
	(35, '19', 'Tarjeta de Crédito', 'Tarjeta de Crédito', NULL, 1, 1, 0),
	(36, '20', 'Otros con utilización Sistema Financiero', 'Otros con Utilización Sistema Financiero', NULL, 1, 1, 0),
	(37, '21', 'Endoso de Titulos', 'Endoso de Titulos', NULL, 1, 1, 0),
	(38, 'DD', 'Dias', 'Dias', NULL, 1, 19, 0),
	(39, 'SE', 'Semanas', 'Semanas', NULL, 1, 19, 0),
	(40, 'ME', 'Meses', 'Meses', NULL, 1, 19, 0),
	(41, 'AN', 'Años', 'Años', NULL, 1, 19, 0),
	(42, '01', 'Factura', 'Factura', NULL, 1, 8, 0),
	(43, '04', 'Nota de Crédito', 'Nota de Crédito', NULL, 1, 8, 0),
	(44, '05', 'Nota de Debito', 'Nota de Debito', NULL, 1, 8, 0),
	(45, '06', 'Guía de Remisión', 'Guía de Remisión', NULL, 1, 8, 0),
	(46, '07', 'Comprobante de Retención', 'Comprobante de Retención', NULL, 1, 8, 0);
/*!40000 ALTER TABLE `entity_masterdata` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.guide
CREATE TABLE IF NOT EXISTS `guide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  `principal_code` int(10) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_guide_company1_idx` (`company_id`),
  CONSTRAINT `fk_guide_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.guide: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `guide` DISABLE KEYS */;
/*!40000 ALTER TABLE `guide` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.guide_product
CREATE TABLE IF NOT EXISTS `guide_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_guide_product_guide1_idx` (`guide_id`),
  KEY `fk_guide_product_product1_idx` (`product_id`),
  CONSTRAINT `fk_guide_product_guide1` FOREIGN KEY (`guide_id`) REFERENCES `guide` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_guide_product_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.guide_product: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `guide_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `guide_product` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.invoice
CREATE TABLE IF NOT EXISTS `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `principal_code` varchar(9) NOT NULL,
  `invoice_date` date NOT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `referral_code` varchar(15) DEFAULT NULL,
  `total_discount` decimal(15,2) DEFAULT NULL,
  `total_ice` decimal(15,2) DEFAULT NULL,
  `total_iva` decimal(15,2) DEFAULT NULL,
  `total_invoice` decimal(15,2) DEFAULT NULL,
  `tip` decimal(15,2) DEFAULT NULL,
  `xml_generated` blob,
  `auth_code` varchar(49) NOT NULL,
  `auth_date` datetime DEFAULT NULL,
  `emission_type` tinyint(1) NOT NULL,
  `environment_type` tinyint(1) NOT NULL,
  `status` varchar(1) NOT NULL,
  `branch_office_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_invoice_branch_office1_idx` (`branch_office_id`),
  KEY `fk_invoice_company1_idx` (`company_id`),
  KEY `fk_invoice_client1_idx` (`client_id`),
  CONSTRAINT `fk_invoice_branch_office1` FOREIGN KEY (`branch_office_id`) REFERENCES `branch_office` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_client1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.invoice: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.invoice_line
CREATE TABLE IF NOT EXISTS `invoice_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` decimal(15,6) NOT NULL,
  `unit_price` decimal(15,6) NOT NULL,
  `discount` decimal(15,2) DEFAULT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_line_invoice1_idx` (`invoice_id`),
  KEY `fk_invoice_line_product1_idx` (`product_id`),
  CONSTRAINT `fk_invoice_line_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_line_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.invoice_line: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `invoice_line` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_line` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.invoice_payment
CREATE TABLE IF NOT EXISTS `invoice_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_number` varchar(20) NOT NULL,
  `mount` decimal(15,2) NOT NULL,
  `time_limit` int(3) DEFAULT NULL,
  `unit_time_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_paiment_invoice1_idx` (`invoice_id`),
  KEY `fk_invoice_paiment_entity_masterdata1_idx` (`payment_type_id`),
  KEY `fk_invoice_paiment_entity_masterdata2_idx` (`bank_id`),
  KEY `FK_invoice_payment_time_unit` (`unit_time_id`),
  CONSTRAINT `FK_invoice_payment_time_unit` FOREIGN KEY (`unit_time_id`) REFERENCES `entity_masterdata` (`id`),
  CONSTRAINT `fk_invoice_paiment_entity_masterdata1` FOREIGN KEY (`payment_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_paiment_entity_masterdata2` FOREIGN KEY (`bank_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_paiment_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.invoice_payment: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `invoice_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_payment` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.invoice_tax
CREATE TABLE IF NOT EXISTS `invoice_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `country_tax_id` int(11) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `subtotal_tax` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_invoice_tax_invoice` (`invoice_id`),
  KEY `FK_invoice_tax_country_tax` (`country_tax_id`),
  CONSTRAINT `FK_invoice_tax_country_tax` FOREIGN KEY (`country_tax_id`) REFERENCES `country_tax` (`id`),
  CONSTRAINT `FK_invoice_tax_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.invoice_tax: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `invoice_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_tax` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.mail_configuration
CREATE TABLE IF NOT EXISTS `mail_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `legend` varchar(255) DEFAULT NULL,
  `incoming_host_server` varchar(60) NOT NULL,
  `outgoing_host_server` varchar(60) NOT NULL,
  `incoming_port` int(5) NOT NULL,
  `outgoing_port` int(5) NOT NULL,
  `user` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `incoming_server_type_id` int(11) NOT NULL,
  `incoming_security_type_id` int(11) NOT NULL,
  `outgoing_server_type_id` int(11) NOT NULL,
  `outgoing_security_type_id` int(11) NOT NULL,
  `incoming_identification_type_id` int(11) NOT NULL,
  `outgoing_identification_type_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mail_configuration_entity_masterdata1_idx` (`incoming_server_type_id`),
  KEY `fk_mail_configuration_entity_masterdata2_idx` (`incoming_security_type_id`),
  KEY `fk_mail_configuration_entity_masterdata3_idx` (`outgoing_server_type_id`),
  KEY `fk_mail_configuration_entity_masterdata4_idx` (`outgoing_security_type_id`),
  KEY `fk_mail_configuration_entity_masterdata5_idx` (`incoming_identification_type_id`),
  KEY `FK_mail_configuration_company` (`company_id`),
  CONSTRAINT `FK_mail_configuration_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  CONSTRAINT `fk_mail_configuration_entity_masterdata1` FOREIGN KEY (`incoming_server_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_configuration_entity_masterdata2` FOREIGN KEY (`incoming_security_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_configuration_entity_masterdata3` FOREIGN KEY (`outgoing_server_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_configuration_entity_masterdata4` FOREIGN KEY (`outgoing_security_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_configuration_entity_masterdata5` FOREIGN KEY (`incoming_identification_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.mail_configuration: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `mail_configuration` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail_configuration` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.parameter
CREATE TABLE IF NOT EXISTS `parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) NOT NULL,
  `system_current_version` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.parameter: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `parameter` DISABLE KEYS */;
/*!40000 ALTER TABLE `parameter` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.plan
CREATE TABLE IF NOT EXISTS `plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `document_count` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `price` decimal(15,4) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.plan: ~11 rows (aproximadamente)
/*!40000 ALTER TABLE `plan` DISABLE KEYS */;
INSERT INTO `plan` (`id`, `name`, `document_count`, `duration`, `price`, `is_active`, `is_deleted`) VALUES
	(1, 'Plan A', 1500, 890, 150.0000, 0, 0),
	(2, 'Plan B', 2000, 250, 250.0000, 0, 0),
	(3, 'Plan C', 1500, 150, 500.0000, 1, 0),
	(4, 'Plan C', 1500, 150, 500.0000, 1, 0),
	(5, 'Plan D', 150, 200, 150.0000, 1, 0),
	(6, 'Plan F', 150, 200, 150.0000, 1, 0),
	(7, 'Plan G', 1500, 150, 300.0000, 1, 0),
	(8, 'Plan H', 1500, 150, 300.0000, 1, 0),
	(9, 'Plan I', 233, 12, 123.0000, 1, 0),
	(10, 'Plan B', 1500, 88, 150.0000, 1, 0),
	(11, 'Plan 11', 1500, 888, 150.0000, 0, 1),
	(12, 'Plan AA', 1234, 355, 1567.0000, 1, NULL);
/*!40000 ALTER TABLE `plan` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `principal_code` varchar(50) NOT NULL,
  `auxiliary_code` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `unit_price` decimal(15,6) NOT NULL,
  `unit_cost` decimal(15,2) DEFAULT NULL,
  `is_purchase_active` tinyint(1) NOT NULL,
  `is_sale_active` tinyint(1) NOT NULL,
  `min_stock` decimal(15,6) NOT NULL,
  `max_stock` decimal(15,6) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_product_company1_idx` (`company_id`),
  CONSTRAINT `fk_product_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.product: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`id`, `name`, `principal_code`, `auxiliary_code`, `description`, `unit_price`, `unit_cost`, `is_purchase_active`, `is_sale_active`, `min_stock`, `max_stock`, `company_id`, `is_active`, `is_deleted`) VALUES
	(1, 'Producto A', '123456789-0', NULL, 'Producto A', 60.000000, 50.00, 1, 1, 100.000000, 150.000000, 1, 1, 0),
	(2, 'Producto B', '123456789-1', NULL, 'Producto B', 70.000000, 50.00, 1, 0, 100.000000, 50.000000, 1, 1, 0),
	(3, 'Producto C', '123456789-2', NULL, 'Producto C', 80.000000, 50.00, 1, 1, 100.000000, 150.000000, 1, 1, 0),
	(4, 'Plan hosting', '05', NULL, 'Hosting plan basico', 60.000000, 60.00, 1, 1, 1.000000, 150.000000, 1, 1, 0);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.product_tax
CREATE TABLE IF NOT EXISTS `product_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `country_tax_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_tax_product1_idx` (`product_id`),
  KEY `fk_product_tax_country_tax1_idx` (`country_tax_id`),
  CONSTRAINT `fk_product_tax_country_tax1` FOREIGN KEY (`country_tax_id`) REFERENCES `country_tax` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_tax_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.product_tax: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `product_tax` DISABLE KEYS */;
INSERT INTO `product_tax` (`id`, `product_id`, `country_tax_id`) VALUES
	(1, 4, 2),
	(2, 3, 3),
	(3, 2, 2),
	(4, 1, 1),
	(5, 1, 3);
/*!40000 ALTER TABLE `product_tax` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.role_module_permission
CREATE TABLE IF NOT EXISTS `role_module_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_role_module_permission_entity_masterdata1_idx` (`role_id`),
  KEY `fk_role_module_permission_entity_masterdata2_idx` (`module_id`),
  KEY `fk_role_module_permission_entity_masterdata3_idx` (`permission_id`),
  CONSTRAINT `fk_role_module_permission_entity_masterdata1` FOREIGN KEY (`role_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_module_permission_entity_masterdata2` FOREIGN KEY (`module_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_module_permission_entity_masterdata3` FOREIGN KEY (`permission_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.role_module_permission: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `role_module_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_module_permission` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.system_configuration
CREATE TABLE IF NOT EXISTS `system_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `description` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.system_configuration: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `system_configuration` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_configuration` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.tax_document
CREATE TABLE IF NOT EXISTS `tax_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `denomination_invoice_doc` int(2) NOT NULL,
  `denomination_retention` int(2) DEFAULT NULL,
  `tax_document_retention` int(10) DEFAULT NULL,
  `emission_date` datetime NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `xml_generated` blob,
  `auth_code` varchar(49) NOT NULL,
  `auth_date` datetime DEFAULT NULL,
  `emission_type` tinyint(1) NOT NULL,
  `environment_type` tinyint(1) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tax_document_invoice1_idx` (`invoice_id`),
  KEY `fk_tax_document_entity_masterdata1_idx` (`document_type_id`),
  CONSTRAINT `fk_tax_document_entity_masterdata1` FOREIGN KEY (`document_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tax_document_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.tax_document: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `tax_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_document` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.trasportation
CREATE TABLE IF NOT EXISTS `trasportation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `principal_code` varchar(10) DEFAULT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `dispatcher_id` int(11) NOT NULL,
  `trasportation_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_trasportation_company1_idx` (`company_id`),
  KEY `fk_trasportation_entity_masterdata1_idx` (`trasportation_type_id`),
  KEY `fk_trasportation_dispatcher1_idx` (`dispatcher_id`),
  CONSTRAINT `fk_trasportation_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_trasportation_dispatcher1` FOREIGN KEY (`dispatcher_id`) REFERENCES `dispatcher` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_trasportation_entity_masterdata1` FOREIGN KEY (`trasportation_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.trasportation: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `trasportation` DISABLE KEYS */;
/*!40000 ALTER TABLE `trasportation` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `first_name` varchar(180) DEFAULT NULL,
  `last_name` varchar(180) DEFAULT NULL,
  `phone_number` varchar(80) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `branch_office_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_users_company_idx` (`company_id`),
  KEY `fk_users_branch_office1_idx` (`branch_office_id`),
  CONSTRAINT `fk_users_branch_office1` FOREIGN KEY (`branch_office_id`) REFERENCES `branch_office` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.users: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_role_users1_idx` (`users_id`),
  KEY `fk_user_role_entity_masterdata1_idx` (`role_id`),
  CONSTRAINT `fk_user_role_entity_masterdata1` FOREIGN KEY (`role_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_role_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.user_role: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
