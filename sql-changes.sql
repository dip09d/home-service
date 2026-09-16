CREATE TABLE `pref_state` (
  `state_id` int(11) NOT NULL,
  `state_key` varchar(100) NOT NULL,
  `state_thumb` varchar(150) NOT NULL,
  `country_code` char(3) NOT NULL,
  `state_status` tinyint(1) NOT NULL,
  `state_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `pref_state` (`state_id`, `state_key`, `state_thumb`, `country_code`, `state_status`, `state_order`) VALUES
(1, 'wp', '', 'IND', 1, 0);
CREATE TABLE `pref_state_names` (
  `state_id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL,
  `state_lang` char(3) NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `pref_state_names` (`state_id`, `state_name`, `state_lang`) VALUES
(1, 'West Bengal', 'en');
ALTER TABLE `pref_state` ADD PRIMARY KEY (`state_id`);
ALTER TABLE `pref_state_names` ADD UNIQUE KEY `state_id` (`state_id`,`state_lang`);
ALTER TABLE `pref_state` MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `pref_agency_address` CHANGE `agency_state` `agency_state` INT NULL DEFAULT NULL;
ALTER TABLE `pref_worker_address` CHANGE `worker_state` `worker_state` INT NULL DEFAULT NULL;
ALTER TABLE `pref_member_address` CHANGE `member_state` `member_state` INT NULL DEFAULT NULL;
INSERT INTO `pref_invoice_type` (`invoice_type_id`, `name_tkey`, `description_tkey`) VALUES (NULL, 'onboarding-invoice', 'Service Provider Onboarding Invoice');

ALTER TABLE `pref_invoice` ADD `attachment` VARCHAR(200) NULL AFTER `change_reason`;
CREATE TABLE `pref_worker_icard` ( `icard_id` INT NOT NULL AUTO_INCREMENT , `worker_id` INT NOT NULL , `employee_id` VARCHAR(75) NULL , `date_of_joining` DATE NULL , `designation` VARCHAR(150) NULL , `worker_name` VARCHAR(150) NULL , `worker_email` VARCHAR(90) NULL , `worker_phone` VARCHAR(75) NULL , `worker_address` VARCHAR(200) NULL , `icard_logo` VARCHAR(100) NULL , `status` BOOLEAN NOT NULL , `reg_date` DATETIME NOT NULL , PRIMARY KEY (`icard_id`)) ENGINE = InnoDB;

ALTER TABLE `pref_invoice` ADD `payment_type` CHAR(10) NULL DEFAULT NULL AFTER `invoice_status`;

CREATE TABLE `pref_blog` (
  `blog_id` int(11) NOT NULL,
  `blog_slug` varchar(100) NOT NULL,
  `blog_thumb` varchar(150) DEFAULT NULL,
  `blog_reg_date` datetime NOT NULL,
  `blog_for` char(2) DEFAULT NULL,
  `blog_status` tinyint(1) NOT NULL,
  `blog_views` int(11) NOT NULL,
  `is_featured` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pref_blog`
--
ALTER TABLE `pref_blog`
  ADD PRIMARY KEY (`blog_id`),
  ADD UNIQUE KEY `blog_slug` (`blog_slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pref_blog`
--
ALTER TABLE `pref_blog`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `pref_blog_names` (
  `blog_id` int(11) NOT NULL,
  `blog_title` varchar(255) DEFAULT NULL,
  `blog_short_description` varchar(150) DEFAULT NULL,
  `blog_description` text DEFAULT NULL,
  `blog_lang` char(3) NOT NULL,
  `meta_title` varchar(150) DEFAULT NULL,
  `meta_keys` varchar(150) DEFAULT NULL,
  `meta_description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `pref_blog_category` (
  `blog_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pref_blog_category`
--
ALTER TABLE `pref_blog_category`
  ADD UNIQUE KEY `blog_id` (`blog_id`,`category_id`);

CREATE TABLE `pref_blog_images` (
  `blog_id` int(11) NOT NULL,
  `blog_image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `pref_blog_tags` ( `blog_id` int(11) NOT NULL, `name` varchar(150) DEFAULT NULL, `lang` char(3) NOT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `pref_settings` ( `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES
( 'I-card Phone', 'icard_phone', '+91 79033 71185', 1, 0, 0, 'general'),
( 'I-card Email', 'icard_email', 'maidfortkolkata@gmail.com', 1, 0, 0, 'general'),
( 'I-card Address', 'icard_address', '48 / 93 / 115, KRISHNA NAGAR, <br>KOLKATA - 700104', 1, 0, 0, 'general');




CREATE TABLE `pref_chat` (
  `conversations_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `last_message_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pref_chat_message` (
  `message_id` int(11) NOT NULL,
  `conversations_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `sending_date` datetime NOT NULL,
  `message` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `pref_chat` ADD PRIMARY KEY (`conversations_id`);
ALTER TABLE `pref_chat_message` ADD PRIMARY KEY (`message_id`);
ALTER TABLE `pref_chat` MODIFY `conversations_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pref_chat_message` MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `pref_member_device` (
  `member_id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `device_type` enum('android','ios') NOT NULL,
  `device_token` varchar(250) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pref_member_device` ADD UNIQUE KEY `member_id` (`member_id`,`worker_id`,`device_token`);

ALTER TABLE `pref_category_subchild` ADD `ot_price_per_minute` FLOAT(6,2) NOT NULL AFTER `price`;

CREATE TABLE `pref_member_cancel_charges` ( `booking_id` INT NOT NULL , `member_id` INT NOT NULL , `amount` DECIMAL(10,2) NOT NULL , `status` BOOLEAN NOT NULL , `reg_date` DATETIME NOT NULL , UNIQUE (`booking_id`)) ENGINE = InnoDB;
ALTER TABLE `pref_invoice` ADD `cancel_charges` DECIMAL(10,2) NOT NULL AFTER `payment_type`;
ALTER TABLE `pref_member_cancel_charges` ADD `invoice_id` INT NULL AFTER `reg_date`;

INSERT INTO `pref_wallet_transaction_type` (`wallet_transaction_type_id`, `title_tkey`, `description_tkey`) VALUES (NULL, 'CANCEL_PAID', 'Cancel Booking Payment');
INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Cancel Payment Transaction', 'CANCEL_PAID', '14', '0', '0', '0', 'constant');
ALTER TABLE `pref_worker` ADD `is_offline` BOOLEAN NOT NULL AFTER `login_status`;

INSERT INTO `pref_wallet_transaction_type` (`wallet_transaction_type_id`, `title_tkey`, `description_tkey`) VALUES (NULL, 'PENALTY_PAYMENT', 'Penalty for Booking');
INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Penalty Booking Transaction', 'PENALTY_PAYMENT', '15', '0', '0', '0', 'constant');

ALTER TABLE `pref_category_subchild` CHANGE `ot_price_per_minute` `next_hour_price` FLOAT(6,2) NOT NULL;
ALTER TABLE `pref_category_subchild` ADD `late_night_price` FLOAT(6,2) NOT NULL AFTER `next_hour_price`;
ALTER TABLE `pref_invoice` ADD `platform_fee` FLOAT(6,2) NOT NULL AFTER `invoice_order_id`, ADD `tax_amount` FLOAT(6,2) NOT NULL AFTER `platform_fee`;
CREATE TABLE `pref_booking_calculation_data` (`invoice_id` INT NOT NULL , `booking_id` INT NOT NULL , `payment_data` TEXT NULL , UNIQUE (`invoice_id`)) ENGINE = InnoDB;
ALTER TABLE `pref_invoice_row` CHANGE `invoice_row_amount` `invoice_row_amount` DECIMAL(10,4) NOT NULL;

INSERT INTO `pref_wallet_transaction_type` (`wallet_transaction_type_id`, `title_tkey`, `description_tkey`) VALUES (NULL, 'TAX_PAYMENT', 'Tax Payment for Booking');
INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Tax Payment  Transaction', 'TAX_PAYMENT', '16', '0', '0', '0', 'constant');

INSERT INTO `pref_wallet` (`wallet_id`, `user_id`, `worker_id`, `title`, `balance`, `withdrawn`, `used_purchases`, `pending_clearance`, `month_earnings`) VALUES (NULL, '0', NULL, 'Tax wallet', '0', '0', '0', '0', '0');
INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Tax Payment Wallet', 'TAX_PAYMENT_WALLET', '195', '0', '0', '0', '');

INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Provider Cancel Charge', 'pro_cancel_charge', '50', '1', '', '', '');

ALTER TABLE `pref_booking_services` ADD `cancelled_by` CHAR(5) NULL DEFAULT NULL AFTER `status`;

ALTER TABLE `pref_booking_services` ADD `provider_gender` CHAR(10) NOT NULL AFTER `provider_religion`;