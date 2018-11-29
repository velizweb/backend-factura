ALTER TABLE `product` ADD UNIQUE(`principal_code`);

ALTER TABLE `product` ADD UNIQUE(`auxiliary_code`);

ALTER TABLE `product` CHANGE `name` `name` VARCHAR(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;