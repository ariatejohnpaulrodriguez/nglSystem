/*
Navicat MySQL Data Transfer

Source Server         : localhost_3308
Source Server Version : 50505
Source Host           : localhost:3308
Source Database       : ngldb6

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-03-03 11:07:38
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
AUTO_INCREMENT=9

;

-- ----------------------------
-- Records of companies
-- ----------------------------
BEGIN;
INSERT INTO `companies` VALUES ('1', 'Admin, Inc', '795 Folsom Ave, Suite 600\r\nSan Francisco, CA 94107', '(804) 123-5432', 'info@almasaeedstudio.com', 'plant1', 'asdwd', 'attention1', null), ('3', 'New Generation Link', '795 Folsom Ave, Suite 600\r\nSan Francisco, CA 94107', '(02) 8546 1184', 'newgenlink.com.ph', 'plant3', 'New Gen Plant 3', 'attention3', null), ('5', 'PLDT Inc.', '795 Folsom Ave, Suite 600\r\nSan Francisco, CA 94107sssssssssssss', '(804) 123-5432', 'info@almasaeedstudio.com', 'plant5', 'PLDT plant', 'plant5', null), ('6', 'Amkor Technology Philippines', '119 N Science Ave LTI SPEZ Binan Laguna', '09178321932', '', 'Plant6', 'Amkor Plant', 'plant6', null), ('7', 'Trends and Technologies Inc', '6F Trafalgar Plaza, 105 HV Dela Costa St 1727 Salcedo Village, Makati City Philippines', '09178321932', '', 'P3', 'Pldt', 'Michael John Sumalinog', null), ('8', 'Nico RObin', 'Ohara', '09123456789', 'nico@gmail.com', 'Plant Nico', 'Nico Plant', 'Luffy', 'dist/img/companies/1740644959_Amkor_Technology-Logo.wine.png');
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
AUTO_INCREMENT=9

;

-- ----------------------------
-- Records of dates
-- ----------------------------
BEGIN;
INSERT INTO `dates` VALUES ('1', '2025-02-28'), ('2', '2025-02-28'), ('3', '2025-03-01'), ('4', '2025-03-01'), ('5', '2025-03-01'), ('6', '2025-03-02'), ('7', '2025-03-03'), ('8', '2025-03-04');
COMMIT;

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
AUTO_INCREMENT=5

;

-- ----------------------------
-- Records of delivery_receipts
-- ----------------------------
BEGIN;
INSERT INTO `delivery_receipts` VALUES ('1', '1'), ('4', '12331'), ('2', '3'), ('3', '5');
COMMIT;

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
AUTO_INCREMENT=5

;

-- ----------------------------
-- Records of employees
-- ----------------------------
BEGIN;
INSERT INTO `employees` VALUES ('3', '7', 'Trafalgar', 'Law', 'law@gmail.com', '09123456789', '1', '2', 'president', '$2y$10$M337xoC34uHL0MxxKpqyCOO.FbK5c0jnKFJ0Z1LPMXCOpgn7/STpe'), ('4', '9', 'Israel', 'New Gen', 'israel@gmail.com', '09123456789', '1', '2', 'warehouse', '$2y$10$ZQxKc0wEMtikW2C9.SN5g..h8/hZrqhaBbemVX4djUN4oFvzRKS9G');
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
AUTO_INCREMENT=4

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
AUTO_INCREMENT=4

;

-- ----------------------------
-- Records of invoice_products
-- ----------------------------
BEGIN;
INSERT INTO `invoice_products` VALUES ('1', '1', '1', '59', 'CJ688TGBL', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 BLACK'), ('2', '2', '13', '1', 'CJ5E88TGBU', 'PANE-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, BLUE'), ('3', '3', '1', '20', 'CJ688TGBL', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 BLACK');
COMMIT;

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
AUTO_INCREMENT=4

;

-- ----------------------------
-- Records of invoices
-- ----------------------------
BEGIN;
INSERT INTO `invoices` VALUES ('1', '5', '3', '1', '2', '1', '1', '1'), ('2', '5', '6', '3', '4', '2', '2', '2'), ('3', '7', '5', '7', '8', '4', '4', '4');
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
PRIMARY KEY (`product_id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=33

;

-- ----------------------------
-- Records of products
-- ----------------------------
BEGIN;
INSERT INTO `products` VALUES ('1', 'CJ688TGBL', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 BLACK'), ('4', 'C688TGWH', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 WHITE'), ('5', 'CJ688TPYL', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 YELLOW'), ('6', 'CJ688TPYL', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 YELLOW'), ('7', 'CJ688TPBU', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 BLUE'), ('8', 'CJ688TGBU', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 BLUE'), ('9', 'CJ688TGRD', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, RED'), ('10', 'CJ688TGIW', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, WHITE'), ('11', 'CJ688TPRD', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, RED'), ('12', 'CJ688TPIU', 'PAN-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, WHITE'), ('13', 'CJ5E88TGBU', 'PANE-NET', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, BLUE'), ('14', 'CFP1LW', 'PAN-NET', 'MINI - COM FACEPLATE CLASSIC SERIES SINGLE GANG 1 MODULE SPACE'), ('15', 'CFP2LW', 'PAN-NET', 'MINI - COM FACEPLATE CLASSIC SERIES SINGLE GANG 2 MODULE SPACE'), ('16', 'CFP4LW', 'PAN-NET', 'MINI - COM FACEPLATE CLASSIC SERIES GANG 4 MODULE SPACE'), ('17', 'CFPE2WHY', 'PAN-NET', 'MINI - COM FACEPLATE C EXECUTIVE SERIES SINGLE GANG 2 MODULE SPACE'), ('18', 'CFPE6WHY', 'PAN-NET', 'MINI - COM FACEPLATE EXECUTIVE SERIES SINGLE GANG 8 MODULE SPACE'), ('19', 'FX1BN1NNNSNM001', 'PANDUIT', 'OM3 MULTIMODE PATCH CORD - 1 FIBER SIMPLEX LC TO PIGTAIL - STD 1L 900M BUFFERED FIBER'), ('20', 'UTP28C43MBU', 'PAN-NET', 'CAT5E PERFORMANCE UTP BATCH CORD T568A, CM/LSZH, 28 AWG STRANDED BLUE'), ('21', 'UTP28CHZMRD', 'PAN-NET', 'CAT5E PERFORMANCE UTP PATCH CORD T568A, CM/LSZH, 28AWG STRANDED RED'), ('22', 'UTPSP3MBUY', 'PAN-NET', 'TX6 PLUS CABLE UTP PATCH CORD T568, CM, 24 AWG STRANDED BLUE'), ('23', 'CPPLZ4WMBLY', 'PAN-NET', 'MINI - COM MODULAR PATCH PANEL SUPPLIED WITH 6 CFFPL TYPE SWAP - IN FACEPLATE W/LABELS'), ('24', 'CFAPPBL1A', 'PAN-NET', 'FIBER ADAPTER PATCH PANEL ANGLED 1 RV FOR FMT1 A'), ('25', '1-1375055-3', 'COMMSCOPE', 'NET CONNECT JACK, SL110, RJ45 CAT6, A WHITE'), ('26', '1375055-1', 'COMMSCOPE', 'NET CONNECT JACK, SL110, RJ45, CAT6, L. ALD'), ('27', '1-1375191-1', 'COMMSCOPE', 'SL SERIES RJ45 JACK, CAT5E 568 A/B, A. WHITE'), ('28', '2-1427030-2', 'COMMSCOPE', 'FACEPLATE KIT SHUTTER, 2P, LABEL'), ('29', '2-1427030-1', 'COMMSCOPE', 'FACEPLATE KIT, SHUTTER, 1P, LABEL'), ('30', '272368-1', 'COMMSCOPE', 'FACEPLATE KIT, SHUTTER, 1P'), ('31', '2111011-1', 'COMMSCOPE', 'FACEPLATE, SINGLE GANG 4 PORT LT. ALMOND'), ('32', '1375055-2', 'COMMSCOPE', 'NET CONNECT JACK, SL110, RJ45, CAT6, BLACK');
COMMIT;

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
AUTO_INCREMENT=5

;

-- ----------------------------
-- Records of purchase_orders
-- ----------------------------
BEGIN;
INSERT INTO `purchase_orders` VALUES ('1', '1'), ('2', '3'), ('4', '31231'), ('3', '6');
COMMIT;

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
AUTO_INCREMENT=5

;

-- ----------------------------
-- Records of reference_pos
-- ----------------------------
BEGIN;
INSERT INTO `reference_pos` VALUES ('1', '1'), ('2', '3'), ('4', '31231'), ('3', '37');
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
AUTO_INCREMENT=13

;

-- ----------------------------
-- Records of roles
-- ----------------------------
BEGIN;
INSERT INTO `roles` VALUES ('3', 'Vice President'), ('4', 'Secretary'), ('6', 'Clerk'), ('7', 'President'), ('8', 'Master Key'), ('9', 'Warehouse Man'), ('10', 'Manager'), ('12', 'Programmer');
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
AUTO_INCREMENT=8

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
`current_quantity`  int(11) NOT NULL ,
PRIMARY KEY (`stock_id`),
FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
INDEX `products.stocks` (`product_id`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8mb4 COLLATE=utf8mb4_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of stocks
-- ----------------------------
BEGIN;
INSERT INTO `stocks` VALUES ('1', '1', '30'), ('2', '13', '1');
COMMIT;

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
AUTO_INCREMENT=2

;

-- ----------------------------
-- Records of transfer_products
-- ----------------------------
BEGIN;
INSERT INTO `transfer_products` VALUES ('1', '1', '1', '49', 'PAN-NET', 'CJ688TGBL', 'TX6 PLUS JACK MODULE WITH ENHANCED GIGA TX TERMINATION STYLE T567 A/B WIRING, RJ45 PATENT #RE38, 519 BLACK');
COMMIT;

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
AUTO_INCREMENT=2

;

-- ----------------------------
-- Records of transfers
-- ----------------------------
BEGIN;
INSERT INTO `transfers` VALUES ('1', '6', '7', '5', '6', '3', '3', '3', '6');
COMMIT;

-- ----------------------------
-- Auto increment value for `companies`
-- ----------------------------
ALTER TABLE `companies` AUTO_INCREMENT=9;

-- ----------------------------
-- Auto increment value for `dates`
-- ----------------------------
ALTER TABLE `dates` AUTO_INCREMENT=9;

-- ----------------------------
-- Auto increment value for `delivery_receipts`
-- ----------------------------
ALTER TABLE `delivery_receipts` AUTO_INCREMENT=5;

-- ----------------------------
-- Auto increment value for `employees`
-- ----------------------------
ALTER TABLE `employees` AUTO_INCREMENT=5;

-- ----------------------------
-- Auto increment value for `genders`
-- ----------------------------
ALTER TABLE `genders` AUTO_INCREMENT=4;

-- ----------------------------
-- Auto increment value for `invoice_products`
-- ----------------------------
ALTER TABLE `invoice_products` AUTO_INCREMENT=4;

-- ----------------------------
-- Auto increment value for `invoices`
-- ----------------------------
ALTER TABLE `invoices` AUTO_INCREMENT=4;

-- ----------------------------
-- Auto increment value for `products`
-- ----------------------------
ALTER TABLE `products` AUTO_INCREMENT=33;

-- ----------------------------
-- Auto increment value for `purchase_orders`
-- ----------------------------
ALTER TABLE `purchase_orders` AUTO_INCREMENT=5;

-- ----------------------------
-- Auto increment value for `reference_pos`
-- ----------------------------
ALTER TABLE `reference_pos` AUTO_INCREMENT=5;

-- ----------------------------
-- Auto increment value for `roles`
-- ----------------------------
ALTER TABLE `roles` AUTO_INCREMENT=13;

-- ----------------------------
-- Auto increment value for `statuses`
-- ----------------------------
ALTER TABLE `statuses` AUTO_INCREMENT=8;

-- ----------------------------
-- Auto increment value for `stocks`
-- ----------------------------
ALTER TABLE `stocks` AUTO_INCREMENT=3;

-- ----------------------------
-- Auto increment value for `transfer_products`
-- ----------------------------
ALTER TABLE `transfer_products` AUTO_INCREMENT=2;

-- ----------------------------
-- Auto increment value for `transfers`
-- ----------------------------
ALTER TABLE `transfers` AUTO_INCREMENT=2;
