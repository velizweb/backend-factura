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
  `adress` varchar(80) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `emission_point` int(3) NOT NULL,
  `company_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_delete` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_office_company1_idx` (`company_id`),
  CONSTRAINT `fk_branch_office_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.branch_office: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `branch_office` DISABLE KEYS */;
INSERT INTO `branch_office` (`id`, `name`, `phone`, `adress`, `email`, `emission_point`, `company_id`, `is_active`, `is_delete`) VALUES
	(1, 'Quantum Branch', '+59387654321', 'Ecuador', 'branch@quantum.com', 2, 1, 0, 0);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.client: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` (`id`, `social_reason`, `comercial_name`, `phone`, `address`, `email`, `company_id`, `is_active`, `is_deleted`) VALUES
	(1, 'Cardenas', 'Tonny', NULL, '1773 Crossfields Rd', NULL, 1, 1, 0),
	(2, 'TONNY', 'CARDENAS', NULL, '1773 Crossfields Rd', 'cardenat@gmail.com', 1, 1, 0);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.client_identification_type: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `client_identification_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_identification_type` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.company
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `comercial_name` varchar(180) NOT NULL,
  `emission_code` int(3) NOT NULL,
  `tax_year` int(6) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `phone` varchar(80) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `is_accounting` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.company: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` (`id`, `name`, `comercial_name`, `emission_code`, `tax_year`, `url`, `phone`, `address`, `email`, `is_accounting`, `is_active`, `is_deleted`) VALUES
	(1, 'Quantum', 'Quantum Ecuador', 1, 10118, 'http://quantum.com', '+59312345678', 'Ecuador', 'test@quantum.com', 0, 0, NULL);
/*!40000 ALTER TABLE `company` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.company_parameter
CREATE TABLE IF NOT EXISTS `company_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `mail_configuration_id` int(11) NOT NULL,
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
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
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

-- Volcando estructura para tabla db_fact_quantum.correlative_document
CREATE TABLE IF NOT EXISTS `correlative_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `increment_number` int(10) NOT NULL,
  `serie` varchar(10) DEFAULT NULL,
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
  `value` decimal(15,4) NOT NULL,
  `country_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_country_tax_entity_masterdata1_idx` (`country_id`),
  KEY `fk_country_tax_entity_masterdata2_idx` (`tax_id`),
  CONSTRAINT `fk_country_tax_entity_masterdata1` FOREIGN KEY (`country_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_country_tax_entity_masterdata2` FOREIGN KEY (`tax_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.country_tax: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `country_tax` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.entity: ~14 rows (aproximadamente)
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
	(16, 'Environment Type');
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
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_entity_masterdata_entity1_idx` (`entity_id`),
  CONSTRAINT `fk_entity_masterdata_entity1` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.entity_masterdata: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `entity_masterdata` DISABLE KEYS */;
INSERT INTO `entity_masterdata` (`id`, `code`, `name`, `description`, `field`, `is_active`, `entity_id`, `is_deleted`) VALUES
	(1, '1', 'Efectivo', 'Efectivo', NULL, 1, 1, 1),
	(2, '2', 'Debito', 'Tarjeta de Debito', NULL, 1, 1, 1),
	(3, '3', 'Crédito', 'Tarjeta de Crédito', NULL, 1, 1, 1);
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
  `principal_code` int(10) DEFAULT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `referral_code` int(15) DEFAULT NULL,
  `amount` decimal(15,4) NOT NULL,
  `sub_total` decimal(15,4) DEFAULT NULL,
  `total_discount` decimal(15,4) DEFAULT NULL,
  `sub_total_discount` decimal(15,4) DEFAULT NULL,
  `ice_total` decimal(15,4) DEFAULT NULL,
  `total_iva` decimal(15,4) DEFAULT NULL,
  `invoice_total` decimal(15,4) DEFAULT NULL,
  `xml_generated` blob,
  `auth_code` varchar(49) DEFAULT NULL,
  `auth_date` datetime DEFAULT NULL,
  `emission_type` int(1) NOT NULL,
  `environment_type` int(1) NOT NULL,
  `status` varchar(1) NOT NULL,
  `branch_office_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
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
  `count` decimal(15,4) NOT NULL,
  `unit_price` decimal(15,4) NOT NULL,
  `discount` decimal(15,4) DEFAULT NULL,
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
  `mount` decimal(15,4) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_paiment_invoice1_idx` (`invoice_id`),
  KEY `fk_invoice_paiment_entity_masterdata1_idx` (`payment_type`),
  KEY `fk_invoice_paiment_entity_masterdata2_idx` (`bank_id`),
  CONSTRAINT `fk_invoice_paiment_entity_masterdata1` FOREIGN KEY (`payment_type`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_paiment_entity_masterdata2` FOREIGN KEY (`bank_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_paiment_invoice1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.invoice_payment: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `invoice_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_payment` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.mail_configuration
CREATE TABLE IF NOT EXISTS `mail_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_server` varchar(60) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `incoming_port` int(5) NOT NULL,
  `outgoing_port` int(5) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `leyend` varchar(255) DEFAULT NULL,
  `incoming_server_type_id` int(11) NOT NULL,
  `incoming_security_type_id` int(11) NOT NULL,
  `outgoing_server_type_id` int(11) NOT NULL,
  `outgoing_security_type_id` int(11) NOT NULL,
  `mail_identification_type_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mail_configuration_entity_masterdata1_idx` (`incoming_server_type_id`),
  KEY `fk_mail_configuration_entity_masterdata2_idx` (`incoming_security_type_id`),
  KEY `fk_mail_configuration_entity_masterdata3_idx` (`outgoing_server_type_id`),
  KEY `fk_mail_configuration_entity_masterdata4_idx` (`outgoing_security_type_id`),
  KEY `fk_mail_configuration_entity_masterdata5_idx` (`mail_identification_type_id`),
  CONSTRAINT `fk_mail_configuration_entity_masterdata1` FOREIGN KEY (`incoming_server_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_configuration_entity_masterdata2` FOREIGN KEY (`incoming_security_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_configuration_entity_masterdata3` FOREIGN KEY (`outgoing_server_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_configuration_entity_masterdata4` FOREIGN KEY (`outgoing_security_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mail_configuration_entity_masterdata5` FOREIGN KEY (`mail_identification_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.plan: ~11 rows (aproximadamente)
/*!40000 ALTER TABLE `plan` DISABLE KEYS */;
INSERT INTO `plan` (`id`, `name`, `document_count`, `duration`, `price`, `is_active`, `is_deleted`) VALUES
	(1, 'Plan A', 1500, 890, 150.0000, 0, 0),
	(2, 'Plan B', 2000, 250, 250.0000, 1, 0),
	(3, 'Plan C', 1500, 150, 500.0000, 1, 0),
	(4, 'Plan C', 1500, 150, 500.0000, 1, 0),
	(5, 'Plan D', 150, 200, 150.0000, 1, 0),
	(6, 'Plan F', 150, 200, 150.0000, 1, 0),
	(7, 'Plan G', 1500, 150, 300.0000, 1, 0),
	(8, 'Plan H', 1500, 150, 300.0000, 1, 0),
	(9, 'Plan I', 233, 12, 123.0000, 1, 0),
	(10, 'Plan B', 1500, 88, 150.0000, 1, 0),
	(11, 'Plan 11', 1500, 888, 150.0000, 1, 0);
/*!40000 ALTER TABLE `plan` ENABLE KEYS */;

-- Volcando estructura para tabla db_fact_quantum.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  `principal_code` varchar(50) NOT NULL,
  `auxiliary_code` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `unit_price` decimal(15,4) DEFAULT NULL,
  `unit_cost` decimal(15,4) DEFAULT NULL,
  `is_purchase_active` tinyint(1) NOT NULL,
  `is_sale_active` tinyint(1) NOT NULL,
  `min_stock` decimal(15,4) NOT NULL,
  `max_stock` decimal(15,4) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_company1_idx` (`company_id`),
  CONSTRAINT `fk_product_company1` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.product: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla db_fact_quantum.product_tax: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `product_tax` DISABLE KEYS */;
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
  `denomination_invoce_doc` int(2) NOT NULL,
  `denomination_retention` int(2) DEFAULT NULL,
  `tax_document_retention` int(10) DEFAULT NULL,
  `emission_date` datetime NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `concept` varchar(255) DEFAULT NULL,
  `xml_generated` blob,
  `auth_code` varchar(49) DEFAULT NULL,
  `auth_date` datetime DEFAULT NULL,
  `emission_type` int(1) NOT NULL,
  `environment_type` int(1) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `documetn_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tax_document_invoice1_idx` (`invoice_id`),
  KEY `fk_tax_document_entity_masterdata1_idx` (`documetn_type_id`),
  CONSTRAINT `fk_tax_document_entity_masterdata1` FOREIGN KEY (`documetn_type_id`) REFERENCES `entity_masterdata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
  `username_canonical` varchar(180) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `email_canonical` varchar(80) DEFAULT NULL,
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
