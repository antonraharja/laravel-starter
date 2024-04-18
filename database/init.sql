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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
