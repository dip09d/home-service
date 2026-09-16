ALTER TABLE `pref_member_address` ADD `name` VARCHAR(100) NULL DEFAULT NULL AFTER `member_id`;
ALTER TABLE `pref_member_address` ADD `member_address-type` CHAR(10) NOT NULL AFTER `member_lng`;
ALTER TABLE `pref_member_address` CHANGE `member_address-type` `member_address_type` CHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `pref_category_subchild` ADD `category_subchild_thumb` VARCHAR(100) NULL DEFAULT NULL AFTER `category_subchild_key`;

CREATE TABLE `pref_booking_services` (
  `booking_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  `provider_caste` char(10) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `duration_hours` int(11) NOT NULL,
  `special_instructions` varchar(255) NOT NULL,
  `address_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pref_booking_services`
--
ALTER TABLE `pref_booking_services`
  ADD PRIMARY KEY (`booking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pref_booking_services`
--
ALTER TABLE `pref_booking_services`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;
  
  ALTER TABLE `pref_booking_services` CHANGE `provider_caste` `provider_religion` INT(11) NOT NULL;

  CREATE TABLE `pref_providers_unavailablity` (
  `id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pref_providers_unavailablity`
--
ALTER TABLE `pref_providers_unavailablity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pref_providers_unavailablity`
--
ALTER TABLE `pref_providers_unavailablity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  ALTER TABLE `pref_booking_services` CHANGE `status` `status` TINYINT(4) NOT NULL;

  CREATE TABLE `pref_worker_otp` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `otp` int(11) NOT NULL,
  `otp_type` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pref_worker_otp`
--
ALTER TABLE `pref_worker_otp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pref_worker_otp`
--
ALTER TABLE `pref_worker_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  CREATE TABLE `pref_worker_time_log` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pref_worker_time_log`
--
ALTER TABLE `pref_worker_time_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pref_worker_time_log`
--
ALTER TABLE `pref_worker_time_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  ALTER TABLE `pref_providers_unavailablity` ADD `booking_id` INT(11) NOT NULL AFTER `worker_id`;

  ALTER TABLE `pref_invoice` ADD `invoice_order_id` INT(11) NOT NULL AFTER `payment_type`;

ALTER TABLE `pref_member` ADD `member_wanum` INT(11) NOT NULL AFTER `is_doc_verified`, ADD `member_dob` DATE NULL DEFAULT NULL AFTER `member_wanum`;

ALTER TABLE `pref_member` CHANGE `member_wanum` `member_wanum` VARCHAR(50) NULL DEFAULT NULL;

ALTER TABLE `pref_member` ADD `member_gender` CHAR(1) NULL DEFAULT NULL AFTER `member_name`;

ALTER TABLE `pref_worker_address` ADD `worker_lat` VARCHAR(50) NULL DEFAULT NULL AFTER `worker_landmark`, ADD `worker_lng` VARCHAR(50) NULL DEFAULT NULL AFTER `worker_lat`;

INSERT INTO `pref_wallet` (`wallet_id`, `user_id`, `worker_id`, `title`, `balance`, `withdrawn`, `used_purchases`, `pending_clearance`, `month_earnings`) VALUES (NULL, '0', NULL, 'Cashfree wallet', '', '0', '0', '0', '0');

INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Cashfree Wallet', 'CASHFREE_WALLET', '140', '', '', '', '');

INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Site Commision', 'site_commision', '5', '', '', '', '');

INSERT INTO `pref_wallet_transaction_type` (`wallet_transaction_type_id`, `title_tkey`, `description_tkey`) VALUES (NULL, 'ADD_FUND_CASHFREE', 'Add Fund By Cashfree');

INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Add Fund Cashfree', 'ADD_FUND_CASHFREE', '12', '', '', '', '');

ALTER TABLE `pref_online_transaction_data` CHANGE `payment_type` `payment_type` CHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

INSERT INTO `pref_wallet_transaction_type` (`wallet_transaction_type_id`, `title_tkey`, `description_tkey`) VALUES (NULL, 'COMMISION_RELEASE', 'Commision release from site');

INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Commision release', 'COMMISION_RELEASE', '13', '', '', '', '');

INSERT INTO `pref_settings` (`id`, `title`, `setting_key`, `setting_value`, `editable`, `deletable`, `display_order`, `setting_group`) VALUES (NULL, 'Radius', 'radius', '50', '1', '', '', '');
  
