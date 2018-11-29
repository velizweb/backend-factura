/*
SQLyog Community Edition- MySQL GUI
Host - 5.5.5-10.1.30-MariaDB 
*********************************************************************
Server version : 5.5.5-10.1.30-MariaDB
*/
/*!40101 SET NAMES utf8 */;

create table `supplier` (
	`id` double ,
	`identification_type_id` double ,
	`identification_number` varchar (39),
	`social_reason` varchar (765),
	`comercial_name` varchar (765),
	`phone` varchar (240),
	`address` varchar (765),
	`email` varchar (240),
	`company_id` double ,
	`tax_period` varchar (18),
	`is_active` tinyint (1),
	`is_deleted` tinyint (1)
); 
insert into `supplier` (`id`, `identification_type_id`, `identification_number`, `social_reason`, `comercial_name`, `phone`, `address`, `email`, `company_id`, `tax_period`, `is_active`, `is_deleted`) values('1','17','1234567890123','Proveedor A','Proveedor A','4124837191','Urb. Santa Cecilia, Av. 5ta. Casa 7','yelyssalinas@hotmail.com','1','2018-0','1','0');
insert into `supplier` (`id`, `identification_type_id`, `identification_number`, `social_reason`, `comercial_name`, `phone`, `address`, `email`, `company_id`, `tax_period`, `is_active`, `is_deleted`) values('2','17','2345678912342','Proveedor B','Proveedor B','4124542337','Valencia, Valencia','yelyssalinas@hotmail.com','1','2018-0','1','0');
