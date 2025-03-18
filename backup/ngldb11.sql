/*
Navicat MySQL Data Transfer

Source Server         : localhost_3308
Source Server Version : 50505
Source Host           : localhost:3308
Source Database       : ngldb9

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-03-18 11:22:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `companies`
-- ----------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies` (
`company_id`  int(11) NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`address`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`phone_number`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`email`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`plant`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`plant_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`attention`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`image`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' ,
PRIMARY KEY (`company_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=11

;

-- ----------------------------
-- Records of companies
-- ----------------------------
BEGIN;
INSERT INTO `companies` VALUES ('1', 'Admin, Inc', '795 Folsom Ave, Suite 600\r\nSan Francisco, CA 94107', '(804) 123-5432', 'info@almasaeedstudio.com', 'plant1', 'asdwd', 'attention1', 'dist/img/companies/1740975524_520.jpg'), ('3', 'New Generation Link', '795 Folsom Ave, Suite 600\r\nSan Francisco, CA 94107', '(02) 8546 1184', 'newgenlink.com.ph', 'plant3', 'New Gen Plant 3', 'attention3', 'dist/img/companies/1740988756_photo_6210951326733550250_m.jpg'), ('5', 'PLDT Inc.', '795 Folsom Ave, Suite 600\r\nSan Francisco, CA 94107sssssssssssss', '(804) 123-5432', 'info@almasaeedstudio.com', 'plant5', 'PLDT plant', 'plant5', 'dist/img/companies/1740975617_pldt-logo.png'), ('6', 'Amkor Technology Philippines', '119 N Science Ave LTI SPEZ Binan Laguna', '09178321932', '', 'Plant6', 'Amkor Plant', 'plant6', 'dist/img/companies/1741409720_resizeAmkor.png'), ('7', 'Trends and Technologies Inc', '6F Trafalgar Plaza, 105 HV Dela Costa St 1727 Salcedo Village, Makati City Philippines', '09178321932', '', 'P3', 'Pldt', 'Michael John Sumalinog', 'dist/img/companies/1740975674_trends_and_technologies_inc.png'), ('8', 'Nico RObin', 'Ohara', '09123456789', 'nico@gmail.com', 'Plant Nico', 'Nico Plant', 'Luffy', 'dist/img/companies/1740644959_Amkor_Technology-Logo.wine.png');
COMMIT;

-- ----------------------------
-- Table structure for `dates`
-- ----------------------------
DROP TABLE IF EXISTS `dates`;
CREATE TABLE `dates` (
`date_id`  int(11) NOT NULL AUTO_INCREMENT ,
`date_value`  date NOT NULL ,
PRIMARY KEY (`date_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of dates
-- ----------------------------
TRUNCATE TABLE `dates`;

-- ----------------------------
-- Table structure for `delivery_receipts`
-- ----------------------------
DROP TABLE IF EXISTS `delivery_receipts`;
CREATE TABLE `delivery_receipts` (
`dr_id`  int(11) NOT NULL AUTO_INCREMENT ,
`dr_number`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`dr_id`),
UNIQUE INDEX `dr_number` (`dr_number`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of delivery_receipts
-- ----------------------------
TRUNCATE TABLE `delivery_receipts`;

-- ----------------------------
-- Table structure for `employees`
-- ----------------------------
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
`employee_id`  int(11) NOT NULL AUTO_INCREMENT ,
`role_id`  int(11) NOT NULL ,
`first_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`last_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`email`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' ,
`phone_number`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' ,
`gender_id`  int(11) NOT NULL ,
`status_id`  int(11) NOT NULL ,
`username`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`password_hash`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`employee_id`),
FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
INDEX `roless.employees` (`role_id`) USING BTREE ,
INDEX `genders.employees` (`gender_id`) USING BTREE ,
INDEX `statuses.employees` (`status_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=9

;

-- ----------------------------
-- Records of employees
-- ----------------------------
BEGIN;
INSERT INTO `employees` VALUES ('3', '7', 'Trafalgar', 'Law', 'law@gmail.com', '09123456789', '1', '2', 'president', '$2y$10$M337xoC34uHL0MxxKpqyCOO.FbK5c0jnKFJ0Z1LPMXCOpgn7/STpe'), ('4', '9', 'Israel', 'New Gen', 'israel@gmail.com', '09123456789', '1', '2', 'warehouse', '$2y$10$ZQxKc0wEMtikW2C9.SN5g..h8/hZrqhaBbemVX4djUN4oFvzRKS9G'), ('5', '12', 'John Paul', 'Ariate', 'pongjep1@gmail.com', '09123456789', '1', '2', 'programmer', '$2y$10$uFUd9q.HA/m7aKkT3PGj6.bEuMGfYUWW3S/WOIdoZwlIf8V9UYypS'), ('6', '13', 'Firstname', 'Lastname', 'saNGL@gmail.com', '09123456789', '1', '2', 'sa', '$2y$10$KKbYgQoNpDVwi1Jobr.2cefmujVcPC32XYuq47ntqggcVXzEnn0cK'), ('7', '14', 'Firstname', 'Lastname', 'admin123@gmail.com', '09123456789', '2', '2', 'admin', '$2y$10$Oe779G3Us8nbj1.rADsMvuKtH2xsxfqK8oBem6GRAjv5Jfxl8c1be');
COMMIT;

-- ----------------------------
-- Table structure for `genders`
-- ----------------------------
DROP TABLE IF EXISTS `genders`;
CREATE TABLE `genders` (
`gender_id`  int(11) NOT NULL AUTO_INCREMENT ,
`gender_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' ,
PRIMARY KEY (`gender_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=9

;

-- ----------------------------
-- Records of genders
-- ----------------------------
BEGIN;
INSERT INTO `genders` VALUES ('1', 'Male'), ('2', 'Female'), ('3', 'Undefined');
COMMIT;

-- ----------------------------
-- Table structure for `invoice_products`
-- ----------------------------
DROP TABLE IF EXISTS `invoice_products`;
CREATE TABLE `invoice_products` (
`invoice_product_id`  int(11) NOT NULL AUTO_INCREMENT ,
`invoice_id`  int(11) NOT NULL ,
`product_id`  int(11) NOT NULL ,
`quantity`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`code`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`brand`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`description`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`invoice_product_id`),
FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
INDEX `invoice_id.invoice_products` (`invoice_id`) USING BTREE ,
INDEX `product.invoice_products` (`product_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of invoice_products
-- ----------------------------
TRUNCATE TABLE `invoice_products`;

-- ----------------------------
-- Table structure for `invoices`
-- ----------------------------
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
`invoice_id`  int(11) NOT NULL AUTO_INCREMENT ,
`from_company_id`  int(11) NOT NULL ,
`to_company_id`  int(11) NOT NULL ,
`posting_date`  int(11) NOT NULL ,
`delivery_date`  int(11) NOT NULL ,
`dr_id`  int(11) NOT NULL ,
`po_id`  int(11) NOT NULL ,
`reference_po_id`  int(11) NOT NULL ,
PRIMARY KEY (`invoice_id`),
FOREIGN KEY (`from_company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`to_company_id`) REFERENCES `companies` (`company_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`posting_date`) REFERENCES `dates` (`date_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`delivery_date`) REFERENCES `dates` (`date_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`dr_id`) REFERENCES `delivery_receipts` (`dr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`po_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`reference_po_id`) REFERENCES `reference_pos` (`reference_po_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
INDEX `posting_date` (`posting_date`) USING BTREE ,
INDEX `delivery_date` (`delivery_date`) USING BTREE ,
INDEX `dr_id` (`dr_id`) USING BTREE ,
INDEX `po_id` (`po_id`) USING BTREE ,
INDEX `reference_po_id` (`reference_po_id`) USING BTREE ,
INDEX `companiesFrom.invoices` (`from_company_id`) USING BTREE ,
INDEX `companiesTo.invoices` (`to_company_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of invoices
-- ----------------------------
TRUNCATE TABLE `invoices`;

-- ----------------------------
-- Table structure for `permissions`
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
`permission_id`  int(11) NOT NULL AUTO_INCREMENT ,
`permission_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`permission_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=19

;

-- ----------------------------
-- Records of permissions
-- ----------------------------
BEGIN;
INSERT INTO `permissions` VALUES ('1', 'Create Roles'), ('2', 'Roles'), ('3', 'Create Gender'), ('4', 'Gender'), ('5', 'Create Status'), ('6', 'Status'), ('7', 'Create Employee'), ('8', 'Employee'), ('9', 'Register Product'), ('10', 'Product'), ('11', 'Transaction Status'), ('12', 'Register Company'), ('13', 'Company'), ('14', 'Register Permission'), ('15', 'Manage Permission'), ('16', 'Transaction Form'), ('17', 'Transaction History'), ('18', 'Create Unit Type');
COMMIT;

-- ----------------------------
-- Table structure for `products`
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
`product_id`  int(11) NOT NULL AUTO_INCREMENT ,
`code`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`brand`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`description`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`unit_id`  int(11) NOT NULL ,
PRIMARY KEY (`product_id`),
FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
INDEX `units.products` (`unit_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of products
-- ----------------------------
TRUNCATE TABLE `products`;

-- ----------------------------
-- Table structure for `purchase_orders`
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders` (
`po_id`  int(11) NOT NULL AUTO_INCREMENT ,
`po_number`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`po_id`),
UNIQUE INDEX `po_number` (`po_number`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of purchase_orders
-- ----------------------------
TRUNCATE TABLE `purchase_orders`;

-- ----------------------------
-- Table structure for `reference_pos`
-- ----------------------------
DROP TABLE IF EXISTS `reference_pos`;
CREATE TABLE `reference_pos` (
`reference_po_id`  int(11) NOT NULL AUTO_INCREMENT ,
`reference_po`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' ,
PRIMARY KEY (`reference_po_id`),
UNIQUE INDEX `reference_po` (`reference_po`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of reference_pos
-- ----------------------------
TRUNCATE TABLE `reference_pos`;

-- ----------------------------
-- Table structure for `rolepermissions`
-- ----------------------------
DROP TABLE IF EXISTS `rolepermissions`;
CREATE TABLE `rolepermissions` (
`role_permission_id`  int(11) NOT NULL AUTO_INCREMENT ,
`role_id`  int(11) NOT NULL ,
`permission_id`  int(11) NOT NULL ,
PRIMARY KEY (`role_permission_id`),
FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
INDEX `roles.rolePermission` (`role_id`) USING BTREE ,
INDEX `permissions.rolePermissions` (`permission_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=50

;

-- ----------------------------
-- Records of rolepermissions
-- ----------------------------
BEGIN;
INSERT INTO `rolepermissions` VALUES ('22', '9', '16'), ('23', '9', '17'), ('25', '13', '1'), ('26', '13', '2'), ('27', '13', '3'), ('28', '13', '4'), ('29', '13', '5'), ('30', '13', '6'), ('31', '13', '7'), ('32', '13', '8'), ('33', '13', '9'), ('34', '13', '10'), ('35', '13', '11'), ('36', '13', '12'), ('37', '13', '13'), ('38', '13', '14'), ('39', '13', '15'), ('40', '13', '16'), ('41', '13', '17'), ('42', '14', '16'), ('43', '14', '17'), ('49', '13', '18');
COMMIT;

-- ----------------------------
-- Table structure for `roles`
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
`role_id`  int(11) NOT NULL AUTO_INCREMENT ,
`role_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`role_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=25

;

-- ----------------------------
-- Records of roles
-- ----------------------------
BEGIN;
INSERT INTO `roles` VALUES ('3', 'Vice President'), ('4', 'Secretary'), ('6', 'Clerk'), ('7', 'President'), ('8', 'Master Key'), ('9', 'Warehouse Man'), ('10', 'Manager'), ('12', 'Programmer'), ('13', 'Super Admin'), ('14', 'Admin');
COMMIT;

-- ----------------------------
-- Table structure for `statuses`
-- ----------------------------
DROP TABLE IF EXISTS `statuses`;
CREATE TABLE `statuses` (
`status_id`  int(11) NOT NULL AUTO_INCREMENT ,
`status_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`status_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=10

;

-- ----------------------------
-- Records of statuses
-- ----------------------------
BEGIN;
INSERT INTO `statuses` VALUES ('2', 'Active'), ('3', 'Pending'), ('4', 'Cancelled'), ('5', 'No Status'), ('6', 'Approved'), ('7', 'Rejected');
COMMIT;

-- ----------------------------
-- Table structure for `stocks`
-- ----------------------------
DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
`stock_id`  int(11) NOT NULL AUTO_INCREMENT ,
`product_id`  int(11) NOT NULL ,
`current_quantity`  decimal(10,2) NOT NULL ,
`unit_id`  int(11) NOT NULL ,
PRIMARY KEY (`stock_id`),
FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
INDEX `products.stocks` (`product_id`) USING BTREE ,
INDEX `units.stocks` (`unit_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of stocks
-- ----------------------------
TRUNCATE TABLE `stocks`;

-- ----------------------------
-- Table structure for `transfer_products`
-- ----------------------------
DROP TABLE IF EXISTS `transfer_products`;
CREATE TABLE `transfer_products` (
`transfer_product_id`  int(11) NOT NULL AUTO_INCREMENT ,
`transfer_id`  int(11) NOT NULL ,
`product_id`  int(11) NOT NULL ,
`quantity`  int(11) NOT NULL ,
`code`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`brand`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`description`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`transfer_product_id`),
FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`transfer_id`) REFERENCES `transfers` (`transfer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
INDEX `transfers.transfer_products` (`transfer_id`) USING BTREE ,
INDEX `products.transfer_products` (`product_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of transfer_products
-- ----------------------------
TRUNCATE TABLE `transfer_products`;

-- ----------------------------
-- Table structure for `transfers`
-- ----------------------------
DROP TABLE IF EXISTS `transfers`;
CREATE TABLE `transfers` (
`transfer_id`  int(11) NOT NULL AUTO_INCREMENT ,
`from_company_id`  int(11) NOT NULL ,
`to_company_id`  int(11) NOT NULL ,
`posting_date`  int(11) NOT NULL ,
`delivery_date`  int(11) NOT NULL ,
`dr_id`  int(11) NOT NULL ,
`po_id`  int(11) NOT NULL ,
`reference_po_id`  int(11) NOT NULL ,
`status_id`  int(11) NOT NULL ,
PRIMARY KEY (`transfer_id`),
FOREIGN KEY (`delivery_date`) REFERENCES `dates` (`date_id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`posting_date`) REFERENCES `dates` (`date_id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`to_company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`from_company_id`) REFERENCES `companies` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
INDEX `companies.transfers` (`from_company_id`) USING BTREE ,
INDEX `comapnies.transfers` (`to_company_id`) USING BTREE ,
INDEX `statuses.transfers` (`status_id`) USING BTREE ,
INDEX `postingDate.transfers` (`posting_date`) USING BTREE ,
INDEX `deliveryDate.transfers` (`delivery_date`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of transfers
-- ----------------------------
TRUNCATE TABLE `transfers`;
-- ----------------------------
-- Table structure for `unit_computations`
-- ----------------------------
DROP TABLE IF EXISTS `unit_computations`;
CREATE TABLE `unit_computations` (
`computation_rule_id`  int(11) NOT NULL AUTO_INCREMENT ,
`base_unit_id`  int(11) NOT NULL ,
`target_unit_id`  int(11) NOT NULL ,
`operation`  enum('+','-','*','/') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`transaction_type`  enum('incoming','outgoing','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`formula`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`computation_rule_id`),
FOREIGN KEY (`base_unit_id`) REFERENCES `units` (`unit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
FOREIGN KEY (`target_unit_id`) REFERENCES `units` (`unit_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
INDEX `base_unit_id` (`base_unit_id`) USING BTREE ,
INDEX `target_unit_id` (`target_unit_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=6

;

-- ----------------------------
-- Records of unit_computations
-- ----------------------------
BEGIN;
INSERT INTO `unit_computations` VALUES ('4', '4', '4', '+', 'incoming', 'base_value + target_value'), ('5', '3', '3', '+', 'incoming', 'base_value + target_value');
COMMIT;

-- ----------------------------
-- Table structure for `unit_conversions`
-- ----------------------------
DROP TABLE IF EXISTS `unit_conversions`;
CREATE TABLE `unit_conversions` (
`rule_id`  int(11) NOT NULL AUTO_INCREMENT ,
`base_unit_id`  int(11) NOT NULL ,
`target_unit_id`  int(11) NOT NULL ,
`conversion_factor`  decimal(10,0) NOT NULL ,
`rule_description`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`rule_id`),
FOREIGN KEY (`base_unit_id`) REFERENCES `units` (`unit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (`target_unit_id`) REFERENCES `units` (`unit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
INDEX `units.base` (`base_unit_id`) USING BTREE ,
INDEX `units.target` (`target_unit_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Records of unit_conversions
-- ----------------------------
BEGIN;
INSERT INTO `unit_conversions` VALUES ('2', '4', '1', '305', ''), ('3', '2', '1', '305', '');
COMMIT;

-- ----------------------------
-- Table structure for `units`
-- ----------------------------
DROP TABLE IF EXISTS `units`;
CREATE TABLE `units` (
`unit_id`  int(11) NOT NULL AUTO_INCREMENT ,
`unit_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
PRIMARY KEY (`unit_id`),
UNIQUE INDEX `unique_unit_name` (`unit_name`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=13

;

-- ----------------------------
-- Records of units
-- ----------------------------
BEGIN;
INSERT INTO `units` VALUES ('4', 'box'), ('1', 'meter(s)'), ('3', 'piece(s)'), ('2', 'roll(s)');
COMMIT;

-- ----------------------------
-- Auto increment value for `companies`
-- ----------------------------
ALTER TABLE `companies` AUTO_INCREMENT=11;

-- ----------------------------
-- Auto increment value for `dates`
-- ----------------------------
ALTER TABLE `dates` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `delivery_receipts`
-- ----------------------------
ALTER TABLE `delivery_receipts` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `employees`
-- ----------------------------
ALTER TABLE `employees` AUTO_INCREMENT=9;

-- ----------------------------
-- Auto increment value for `genders`
-- ----------------------------
ALTER TABLE `genders` AUTO_INCREMENT=9;

-- ----------------------------
-- Auto increment value for `invoice_products`
-- ----------------------------
ALTER TABLE `invoice_products` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `invoices`
-- ----------------------------
ALTER TABLE `invoices` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `permissions`
-- ----------------------------
ALTER TABLE `permissions` AUTO_INCREMENT=19;

-- ----------------------------
-- Auto increment value for `products`
-- ----------------------------
ALTER TABLE `products` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `purchase_orders`
-- ----------------------------
ALTER TABLE `purchase_orders` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `reference_pos`
-- ----------------------------
ALTER TABLE `reference_pos` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `rolepermissions`
-- ----------------------------
ALTER TABLE `rolepermissions` AUTO_INCREMENT=50;

-- ----------------------------
-- Auto increment value for `roles`
-- ----------------------------
ALTER TABLE `roles` AUTO_INCREMENT=25;

-- ----------------------------
-- Auto increment value for `statuses`
-- ----------------------------
ALTER TABLE `statuses` AUTO_INCREMENT=10;

-- ----------------------------
-- Auto increment value for `stocks`
-- ----------------------------
ALTER TABLE `stocks` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `transfer_products`
-- ----------------------------
ALTER TABLE `transfer_products` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `transfers`
-- ----------------------------
ALTER TABLE `transfers` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `unit_computations`
-- ----------------------------
ALTER TABLE `unit_computations` AUTO_INCREMENT=6;

-- ----------------------------
-- Auto increment value for `unit_conversions`
-- ----------------------------
ALTER TABLE `unit_conversions` AUTO_INCREMENT=4;

-- ----------------------------
-- Auto increment value for `units`
-- ----------------------------
ALTER TABLE `units` AUTO_INCREMENT=13;
