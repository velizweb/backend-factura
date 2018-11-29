ALTER TABLE `entity_masterdata` ADD `is_active` BIT(1) NOT NULL AFTER `field`;
ALTER TABLE `entity_masterdata` ADD `is_deleted` BIT(1) NULL AFTER `entity_id`;