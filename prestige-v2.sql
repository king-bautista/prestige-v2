-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.36 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for prestige
CREATE DATABASE IF NOT EXISTS `prestige` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `prestige`;

-- Dumping structure for table prestige.modules
CREATE TABLE IF NOT EXISTS `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modules_name_index` (`name`),
  KEY `modules_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table prestige.modules: ~43 rows (approximately)
INSERT INTO `modules` (`id`, `parent_id`, `name`, `link`, `class_name`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, NULL, 'Admin Management', '#', 'nav-icon fas fa-users-cog', 1, '2022-08-08 01:26:43', '2022-08-08 01:26:43', NULL),
	(3, 1, 'Roles', '/admin/roles', 'nav-icon fas fa-user-tag', 1, '2022-08-08 18:49:58', '2022-08-08 18:49:58', NULL),
	(4, 1, 'Modules', '/admin/modules', 'nav-icon fas fa-link', 1, '2022-08-08 18:51:00', '2022-08-08 18:51:00', NULL),
	(5, NULL, 'Company Management', '#', 'nav-icon fas fa-users', 1, '2022-08-08 18:52:38', '2022-10-17 21:31:09', NULL),
	(6, NULL, 'Look-up Management', '#', 'nav-icon fas fa-clipboard-list', 1, '2022-08-08 18:58:42', '2022-08-18 17:49:30', NULL),
	(7, 6, 'Categories', '/admin/categories', 'nav-icon fas fa-th-list', 1, '2022-08-08 18:59:53', '2022-08-08 18:59:53', NULL),
	(8, 6, 'Supplementals', '/admin/supplementals', 'nav-icon fas fa-list-ul', 1, '2022-08-08 19:05:44', '2022-08-08 19:05:44', NULL),
	(9, 5, 'Classifications', '/admin/classifications', 'nav-icon fas fa-list-ol', 1, '2022-08-08 19:06:11', '2022-10-17 21:38:00', NULL),
	(10, NULL, 'Brand Management', '/admin/brands', 'nav-icon fas fa-copyright', 1, '2022-08-08 19:11:32', '2022-08-08 19:11:32', NULL),
	(11, 1, 'User', '/admin/users', 'nav-icon fas fa-user-secret', 1, '2022-08-08 01:32:54', '2022-08-08 18:50:08', NULL),
	(12, NULL, 'Tenant Management', '#', 'nav-icon fas fa-store-alt', 1, '2022-08-11 22:51:31', '2022-08-23 22:30:10', '2022-08-23 22:30:10'),
	(13, NULL, 'Sites Management', '#', 'nav-icon fas fa-city', 1, '2022-08-11 22:51:51', '2022-10-19 21:56:15', NULL),
	(14, NULL, 'Content Master', '#', 'nav-icon fas fa-photo-video', 1, '2022-08-11 22:52:09', '2023-01-19 07:00:33', NULL),
	(15, 14, 'Online', '/admin/advertisements/online', 'nav-icon fas fa-images', 1, '2022-08-11 22:52:33', '2022-09-18 23:14:54', NULL),
	(16, 14, 'Banners', '/admin/advertisements/banner', 'nav-icon fas fa-images', 1, '2022-08-11 22:52:53', '2022-09-19 23:58:20', NULL),
	(17, 14, 'Fullscreens', '/admin/advertisements/fullscreen', 'nav-icon fas fa-images', 1, '2022-08-11 22:53:17', '2022-09-19 23:58:32', NULL),
	(18, 14, 'Pop-Ups', '/admin/advertisements/popups', 'nav-icon fas fa-image', 1, '2022-08-11 22:53:37', '2022-09-19 23:58:47', NULL),
	(19, 14, 'Events', '/admin/advertisements/events', 'nav-icon fas fa-calendar-alt', 1, '2022-08-11 22:53:54', '2022-09-19 23:58:58', NULL),
	(20, NULL, 'Reports Management', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 22:54:30', '2022-08-11 22:54:30', NULL),
	(21, 20, 'Merchant Population', '/admin/reports/merchant-population', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:03:59', '2023-02-07 06:27:50', NULL),
	(22, 20, 'Top Tenant Searches', '/admin/reports/top-tenant-search', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:04:18', '2023-02-13 06:54:33', NULL),
	(23, 20, 'Most Searched Keywords', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:04:44', '2022-08-11 23:04:44', NULL),
	(24, 20, 'Merchant Usage', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:05:00', '2022-08-11 23:05:00', NULL),
	(25, 20, 'Monthly Usage', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:05:14', '2022-08-11 23:05:14', NULL),
	(26, 20, 'Sites with Highest Usage', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:05:33', '2022-08-11 23:05:33', NULL),
	(27, 20, 'Kiosk Uptime History', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:05:50', '2022-08-11 23:05:50', NULL),
	(28, 20, 'System activity logs', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:06:05', '2022-08-11 23:06:05', NULL),
	(29, 20, 'Sales and Revenue', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:06:21', '2022-08-11 23:06:21', NULL),
	(30, 20, 'Earnings overview', '#', 'nav-icon fas fa-chart-area', 1, '2022-08-11 23:06:37', '2022-08-11 23:06:37', NULL),
	(31, 6, 'Amenities', '/admin/amenities', 'nav-icon fas fa-restroom', 1, '2022-08-18 22:50:23', '2022-08-18 22:51:50', NULL),
	(32, 6, 'Tags', '/admin/tags', 'nav-icon fa fa-tags', 1, '2022-08-22 17:03:10', '2022-08-22 17:03:22', NULL),
	(33, 5, 'Companies', '/admin/companies', 'nav-icon  fa fa-copyright', 1, '2022-10-17 21:33:27', '2022-10-17 22:22:12', NULL),
	(34, 6, 'illustration', '/admin/Illustrations', 'nav-icon fas fa-images', 1, '2022-10-18 21:40:55', '2022-10-18 23:11:12', NULL),
	(35, 13, 'Sites / Buildings', '/admin/sites', 'nav-icon fa fa-building', 1, '2022-10-19 21:54:25', '2022-10-19 21:54:44', NULL),
	(36, 13, 'Tenants', '/admin/site/tenants', 'nav-icon fa fa-address-card', 1, '2022-10-19 21:59:24', '2022-10-24 23:11:38', NULL),
	(37, 13, 'Screens', '/admin/site/screens', 'nav-icon fa fa-desktop', 1, '2022-10-19 22:00:09', '2022-12-29 03:38:42', '2022-12-29 03:38:42'),
	(38, NULL, 'Cinema Schedules', '#', 'nav-icon fa fa-film', 1, '2022-11-20 18:51:40', '2022-11-20 18:53:37', NULL),
	(39, 38, 'Genre', '/admin/cinema/genres', 'nav-icon fa fa-film', 1, '2022-11-20 18:55:29', '2022-11-20 21:55:17', NULL),
	(40, 38, 'Site Code', '/admin/cinema/site-codes', 'nav-icon fa fa-film', 1, '2022-11-20 18:56:17', '2022-11-20 22:44:57', NULL),
	(41, 38, 'Schedules', '/admin/cinema/schedules', 'nav-icon fa fa-calendar', 1, '2022-11-20 18:57:27', '2022-11-20 18:59:23', NULL),
	(42, NULL, 'Screens', '/admin/site/screens', 'nav-icon fa fa-desktop', 1, '2022-12-29 03:38:30', '2022-12-29 03:38:30', NULL),
	(43, 14, 'Promos', '/admin/advertisements/promos', 'nav-icon fa fa-tags', 1, '2023-01-19 07:10:54', '2023-01-19 07:11:17', NULL),
	(44, NULL, 'Content Management', '/admin/content-management', 'nav-icon fas fa-photo-video', 1, '2023-01-19 09:09:57', '2023-01-20 05:49:12', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
