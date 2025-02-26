CREATE TABLE `companies` (
  `company_id` integer PRIMARY KEY,
  `name` varchar(255),
  `address` text,
  `phone_number` varchar(255),
  `email` varchar(255),
  `plant` varchar(255),
  `plant_name` varchar(255),
  `attention` varchar(255)
);

CREATE TABLE `employees` (
  `employee_id` integer PRIMARY KEY,
  `role_id` integer,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `email` varchar(255),
  `phone_number` varchar(255),
  `gender_id` integer,
  `status_id` integer,
  `username` varchar(255),
  `password_hash` text
);

CREATE TABLE `roles` (
  `role_id` integer PRIMARY KEY,
  `role_name` varchar(255)
);

CREATE TABLE `genders` (
  `gender_id` integer PRIMARY KEY,
  `gender_name` varchar(255)
);

CREATE TABLE `statuses` (
  `status_id` integer PRIMARY KEY,
  `status_name` varchar(255)
);

CREATE TABLE `products` (
  `product_id` integer PRIMARY KEY,
  `code` varchar(255),
  `brand` varchar(255),
  `description` text
);

CREATE TABLE `inventories` (
  `inventory_id` integer PRIMARY KEY,
  `product_id` integer,
  `date_id` integer,
  `beg_balance` integer,
  `end_balance` integer,
  `quantity` integer,
  `product_type` varchar(255),
  `check_by` integer,
  `approved_by` integer,
  `status_id` integer,
  `discrepancy` integer,
  `notes` varchar(255)
);

CREATE TABLE `transactions` (
  `transaction_id` integer PRIMARY KEY,
  `invoice_product_id` integer,
  `status_id` integer
);

CREATE TABLE `invoices` (
  `invoice_id` integer PRIMARY KEY,
  `from_company_id` integer,
  `to_company_id` integer,
  `posting_date` Date,
  `delivery_date` Date,
  `dr_id` integer,
  `po_id` integer,
  `reference_po_id` integer,
  `status_id` integer
);

CREATE TABLE `purchase_orders` (
  `po_id` integer PRIMARY KEY,
  `po_number` varchar(255) UNIQUE
);

CREATE TABLE `delivery_receipts` (
  `dr_id` integer PRIMARY KEY,
  `dr_number` varchar(255) UNIQUE
);

CREATE TABLE `reference_pos` (
  `reference_po_id` integer PRIMARY KEY,
  `reference_po` varchar(255) UNIQUE
);

CREATE TABLE `invoice_products` (
  `invoice_product_id` integer PRIMARY KEY,
  `invoice_id` integer,
  `product_id` integer,
  `quantity` integer,
  `code` varchar(255),
  `brand` varchar(255),
  `description` varchar(255)
);

CREATE TABLE `dates` (
  `date_id` integer,
  `date_value` date
);

ALTER TABLE `roles` ADD FOREIGN KEY (`role_id`) REFERENCES `employees` (`role_id`);

ALTER TABLE `genders` ADD FOREIGN KEY (`gender_id`) REFERENCES `employees` (`gender_id`);

ALTER TABLE `statuses` ADD FOREIGN KEY (`status_id`) REFERENCES `employees` (`status_id`);

ALTER TABLE `products` ADD FOREIGN KEY (`product_id`) REFERENCES `inventories` (`product_id`);

ALTER TABLE `companies` ADD FOREIGN KEY (`company_id`) REFERENCES `invoices` (`from_company_id`);

ALTER TABLE `companies` ADD FOREIGN KEY (`company_id`) REFERENCES `invoices` (`to_company_id`);

ALTER TABLE `invoices` ADD FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`po_id`);

ALTER TABLE `invoices` ADD FOREIGN KEY (`dr_id`) REFERENCES `delivery_receipts` (`dr_id`);

ALTER TABLE `invoices` ADD FOREIGN KEY (`reference_po_id`) REFERENCES `reference_pos` (`reference_po_id`);

ALTER TABLE `invoice_products` ADD FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`);

ALTER TABLE `invoice_products` ADD FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

ALTER TABLE `transactions` ADD FOREIGN KEY (`transaction_id`) REFERENCES `invoice_products` (`invoice_product_id`);

ALTER TABLE `transactions` ADD FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`);

ALTER TABLE `invoices` ADD FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`);

ALTER TABLE `inventories` ADD FOREIGN KEY (`check_by`) REFERENCES `employees` (`employee_id`);

ALTER TABLE `inventories` ADD FOREIGN KEY (`approved_by`) REFERENCES `employees` (`employee_id`);

ALTER TABLE `inventories` ADD FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`);

ALTER TABLE `inventories` ADD FOREIGN KEY (`quantity`) REFERENCES `invoice_products` (`invoice_product_id`);

ALTER TABLE `inventories` ADD FOREIGN KEY (`date_id`) REFERENCES `dates` (`date_id`);

ALTER TABLE `invoices` ADD FOREIGN KEY (`posting_date`) REFERENCES `dates` (`date_id`);

ALTER TABLE `invoices` ADD FOREIGN KEY (`delivery_date`) REFERENCES `dates` (`date_id`);
