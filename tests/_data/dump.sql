-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.29 - MySQL Community Server (GPL)
-- Server OS:                    Linux
-- HeidiSQL Version:             10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for stenup_test
DROP DATABASE IF EXISTS `stenup_test`;
CREATE DATABASE IF NOT EXISTS `stenup_test` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `stenup_test`;

-- Dumping structure for table stenup_test.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.migrations: ~4 rows (approximately)
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2018_08_08_100000_create_telescope_entries_table', 1),
	(4, '2019_10_09_234222_create_permission_tables', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table stenup_test.model_has_permissions
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.model_has_permissions: ~0 rows (approximately)
DELETE FROM `model_has_permissions`;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;

-- Dumping structure for table stenup_test.model_has_roles
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.model_has_roles: ~7 rows (approximately)
DELETE FROM `model_has_roles`;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(1, 'App\\Models\\User', 2),
	(2, 'App\\Models\\User', 3),
	(3, 'App\\Models\\User', 4),
	(3, 'App\\Models\\User', 5),
	(3, 'App\\Models\\User', 6),
	(3, 'App\\Models\\User', 7);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;

-- Dumping structure for table stenup_test.password_resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.password_resets: ~0 rows (approximately)
DELETE FROM `password_resets`;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Dumping structure for table stenup_test.permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.permissions: ~10 rows (approximately)
DELETE FROM `permissions`;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view-dashboard', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(2, 'view-users', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(3, 'create-users', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(4, 'update-users', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(5, 'delete-users', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(6, 'view-roles-permissions', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(7, 'create-roles', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(8, 'update-roles', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(9, 'delete-roles', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(10, 'update-permissions', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Dumping structure for table stenup_test.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.roles: ~3 rows (approximately)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'administrator', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(2, 'moderator', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40'),
	(3, 'member', 'web', '2020-03-01 17:47:40', '2020-03-01 17:47:40');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table stenup_test.role_has_permissions
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.role_has_permissions: ~12 rows (approximately)
DELETE FROM `role_has_permissions`;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(1, 2),
	(2, 2);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;

-- Dumping structure for table stenup_test.telescope_entries
DROP TABLE IF EXISTS `telescope_entries`;
CREATE TABLE IF NOT EXISTS `telescope_entries` (
  `sequence` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`),
  KEY `telescope_entries_family_hash_index` (`family_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=605 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.telescope_entries: ~0 rows (approximately)
DELETE FROM `telescope_entries`;
/*!40000 ALTER TABLE `telescope_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries` ENABLE KEYS */;

-- Dumping structure for table stenup_test.telescope_entries_tags
DROP TABLE IF EXISTS `telescope_entries_tags`;
CREATE TABLE IF NOT EXISTS `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.telescope_entries_tags: ~0 rows (approximately)
DELETE FROM `telescope_entries_tags`;
/*!40000 ALTER TABLE `telescope_entries_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries_tags` ENABLE KEYS */;

-- Dumping structure for table stenup_test.telescope_monitoring
DROP TABLE IF EXISTS `telescope_monitoring`;
CREATE TABLE IF NOT EXISTS `telescope_monitoring` (
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.telescope_monitoring: ~0 rows (approximately)
DELETE FROM `telescope_monitoring`;
/*!40000 ALTER TABLE `telescope_monitoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_monitoring` ENABLE KEYS */;

-- Dumping structure for table stenup_test.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `activation_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table stenup_test.users: ~7 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `email_verified_at`, `password`, `status`, `activation_token`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'dansharisan@gmail.com', '2019-01-01 17:47:40', '$2y$10$kONctkXHfXNakypS40w6S.k/WIitrpFDng3ObG8O9fmiH8yEC1uWu', 1, NULL, 'euhfEUlxeb', '2019-01-01 17:47:40', '2019-01-01 17:47:40', NULL),
	(2, 'administrator@test.com', '2020-02-02 17:50:20', '$2y$10$EmgLlPenWPcGkTg876M/x.WifvJi3VlyBw8eCYDH1PkcsFEq2RNEK', 1, NULL, NULL, '2020-02-02 17:50:20', '2020-02-02 17:50:20', NULL),
	(3, 'moderator@test.com', '2020-02-03 17:51:29', '$2y$10$ywrcw.TXaNeuZC.3J19yNOlrMShg3kZ.p1KPVkMo8H/S0nJBCwlKu', 1, NULL, NULL, '2020-02-03 17:51:29', '2020-02-03 17:51:29', NULL),
	(4, 'member@test.com', '2020-02-12 17:51:30', '$2y$10$sgIfgsSxHTVLrQ8HRPnz4O1R/klnxZJ5QtrTFNWlWgwuHT/JtHJ3q', 1, NULL, NULL, '2020-02-12 17:51:30', '2020-02-12 17:51:30', NULL),
	(5, 'unverified_member@test.com', NULL, '$2y$10$G1f380xLy5k8u0eQha.pBuu5nBwjTX4cLsEXa93nwIHwCuMcq/WIW', 1, 'UHnGP0MCsbmq9NrWTg6Y3nhTaX4greBxoykd8lf85WIU2AXEiRbKNSy8XL70', NULL, '2020-03-07 17:52:24', '2020-03-07 17:52:24', NULL),
	(6, 'inactive_member@test.com', '2020-03-01 17:55:36', '$2y$10$eliY1oi7sH/y7bKA4sr5B.Onz/Zj6pefGHVl5moTf2uRw/075vPk.', 0, NULL, NULL, '2020-03-01 17:55:36', '2020-03-01 17:55:36', NULL),
	(7, 'banned_member@test.com', '2019-03-03 17:55:45', '$2y$10$3HuYgpUe.zQlzSygujmMTe7guxnct5RVnvCDo3FXxZ2Wn/CPtLcYy', 2, NULL, NULL, '2019-03-03 17:55:45', '2019-03-03 17:55:45', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
