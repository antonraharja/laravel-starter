SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Table structure for table `registries`
--

DROP TABLE IF EXISTS `registries`;
CREATE TABLE `registries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class` varchar(60) NOT NULL DEFAULT 'DEFAULT',
  `group` varchar(60) NOT NULL,
  `keyword` varchar(60) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registries`
--

INSERT INTO `registries` (`id`, `class`, `group`, `keyword`, `content`, `created_at`, `updated_at`) VALUES
(1, 'DEFAULT', 'themes', 'brand_logo', 'N;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(2, 'DEFAULT', 'themes', 'favico', 'N;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(3, 'DEFAULT', 'themes', 'primary_color_scheme', 's:7:\"#696969\";', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(4, 'DEFAULT', 'themes', 'danger_color_scheme', 's:7:\"#e81717\";', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(5, 'DEFAULT', 'themes', 'gray_color_scheme', 's:7:\"#292424\";', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(6, 'DEFAULT', 'themes', 'info_color_scheme', 's:7:\"#a10da1\";', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(7, 'DEFAULT', 'themes', 'success_color_scheme', 's:7:\"#0db30d\";', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(8, 'DEFAULT', 'themes', 'warning_color_scheme', 's:7:\"#f0b32c\";', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(9, 'DEFAULT', 'themes', 'disable_top_navigation', 'b:0;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(10, 'DEFAULT', 'themes', 'revealable_passwords', 'b:1;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(11, 'DEFAULT', 'themes', 'brand_name', 'N;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(12, 'DEFAULT', 'timezones', 'system_timezone', 'N;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(13, 'DEFAULT', 'timezones', 'timezone', 'N;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(14, 'DEFAULT', 'logins', 'default_register_roles', 'a:1:{i:0;s:5:\"GUEST\";}', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(15, 'DEFAULT', 'logins', 'enable_register', 'b:1;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(16, 'DEFAULT', 'logins', 'enable_password_reset', 'b:1;', '2024-04-19 13:47:32', '2024-04-19 13:48:02'),
(17, 'DEFAULT', 'logins', 'enable_email_verification', 'b:0;', '2024-04-19 13:47:32', '2024-04-19 13:48:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registries`
--
ALTER TABLE `registries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registries_class_group_keyword_unique` (`class`,`group`,`keyword`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registries`
--
ALTER TABLE `registries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;
