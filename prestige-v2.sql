-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.36 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             12.3.0.6589
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

-- Dumping structure for table prestige.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_attempt` int(11) NOT NULL DEFAULT '0',
  `is_blocked` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activation_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  KEY `admins_full_name_index` (`full_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.admins_meta
DROP TABLE IF EXISTS `admins_meta`;
CREATE TABLE IF NOT EXISTS `admins_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admins_meta_admin_id_foreign` (`admin_id`),
  KEY `admins_meta_meta_key_index` (`meta_key`),
  CONSTRAINT `admins_meta_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.admin_roles
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE IF NOT EXISTS `admin_roles` (
  `admin_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  KEY `admin_roles_admin_id_foreign` (`admin_id`),
  KEY `admin_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `admin_roles_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`),
  CONSTRAINT `admin_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.advertisements
DROP TABLE IF EXISTS `advertisements`;
CREATE TABLE IF NOT EXISTS `advertisements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `status_id` bigint(20) unsigned DEFAULT NULL,
  `ad_type` enum('Events','Online','Banners','Fullscreen','Pop-Up','Promos') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` mediumtext COLLATE utf8mb4_unicode_ci,
  `file_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dimension` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_duration` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advertisements_company_id_index` (`company_id`),
  KEY `advertisements_brand_id_index` (`brand_id`),
  KEY `advertisements_status_id_index` (`status_id`),
  CONSTRAINT `advertisements_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `advertisements_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `advertisements_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `transaction_statuses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.amenities
DROP TABLE IF EXISTS `amenities`;
CREATE TABLE IF NOT EXISTS `amenities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amenities_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.brands
DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `logo` mediumtext COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brands_category_id_index` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7709 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.brand_products_promos
DROP TABLE IF EXISTS `brand_products_promos`;
CREATE TABLE IF NOT EXISTS `brand_products_promos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `tenant_id` bigint(20) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `type` enum('product','promo','banner') COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` mediumtext COLLATE utf8mb4_unicode_ci,
  `image_url` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `sequence` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_products_promos_brand_id_foreign` (`brand_id`),
  KEY `brand_products_promos_deleted_at_index` (`deleted_at`),
  KEY `brand_products_promos_tenant_id_index` (`tenant_id`),
  CONSTRAINT `brand_products_promos_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.brand_supplementals
DROP TABLE IF EXISTS `brand_supplementals`;
CREATE TABLE IF NOT EXISTS `brand_supplementals` (
  `brand_id` bigint(20) unsigned NOT NULL,
  `supplemental_id` bigint(20) unsigned NOT NULL,
  KEY `brand_supplementals_brand_id_foreign` (`brand_id`),
  KEY `brand_supplementals_supplemental_id_foreign` (`supplemental_id`),
  CONSTRAINT `brand_supplementals_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `brand_supplementals_supplemental_id_foreign` FOREIGN KEY (`supplemental_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.brand_tags
DROP TABLE IF EXISTS `brand_tags`;
CREATE TABLE IF NOT EXISTS `brand_tags` (
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `tag_id` bigint(20) unsigned DEFAULT NULL,
  KEY `brand_tags_brand_id_foreign` (`brand_id`),
  KEY `brand_tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `brand_tags_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `brand_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.categories
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `supplemental_category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `class_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_type` smallint(6) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.category_labels
DROP TABLE IF EXISTS `category_labels`;
CREATE TABLE IF NOT EXISTS `category_labels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `site_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_labels_category_id_foreign` (`category_id`),
  KEY `category_labels_company_id_foreign` (`company_id`),
  KEY `category_labels_site_id_foreign` (`site_id`),
  CONSTRAINT `category_labels_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `category_labels_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `category_labels_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.cinema_genre
DROP TABLE IF EXISTS `cinema_genre`;
CREATE TABLE IF NOT EXISTS `cinema_genre` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `genre_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genre_label` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cinema_genre_genre_code_index` (`genre_code`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.cinema_schedules
DROP TABLE IF EXISTS `cinema_schedules`;
CREATE TABLE IF NOT EXISTS `cinema_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `synopsis` mediumtext COLLATE utf8mb4_unicode_ci,
  `opening_date` date DEFAULT NULL,
  `rating` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `runtime` int(11) DEFAULT NULL,
  `casting` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trailer_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cinema_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cinema_id_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `screen_code` bigint(20) DEFAULT NULL,
  `screen_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `film_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genre` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genre2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genre3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=781 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.cinema_sites
DROP TABLE IF EXISTS `cinema_sites`;
CREATE TABLE IF NOT EXISTS `cinema_sites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL,
  `cinema_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cinema_sites_site_id_foreign` (`site_id`),
  CONSTRAINT `cinema_sites_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.classifications
DROP TABLE IF EXISTS `classifications`;
CREATE TABLE IF NOT EXISTS `classifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classifications_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.companies
DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `classification_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `tin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companies_classification_id_foreign` (`classification_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `companies_classification_id_foreign` FOREIGN KEY (`classification_id`) REFERENCES `classifications` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.company_categories
DROP TABLE IF EXISTS `company_categories`;
CREATE TABLE IF NOT EXISTS `company_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `sub_category_id` bigint(20) unsigned DEFAULT NULL,
  `site_id` bigint(20) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `kiosk_image_primary` mediumtext COLLATE utf8mb4_unicode_ci,
  `kiosk_image_top` mediumtext COLLATE utf8mb4_unicode_ci,
  `online_image_primary` mediumtext COLLATE utf8mb4_unicode_ci,
  `online_image_top` mediumtext COLLATE utf8mb4_unicode_ci,
  `mobile_image_primary` mediumtext COLLATE utf8mb4_unicode_ci,
  `mobile_image_top` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_categories_company_id_foreign` (`company_id`),
  KEY `company_categories_category_id_foreign` (`category_id`),
  KEY `company_categories_sub_category_id_foreign` (`sub_category_id`),
  KEY `company_categories_site_id_foreign` (`site_id`),
  CONSTRAINT `company_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `company_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `company_categories_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`),
  CONSTRAINT `company_categories_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.content_management
DROP TABLE IF EXISTS `content_management`;
CREATE TABLE IF NOT EXISTS `content_management` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `advertisement_id` bigint(20) unsigned DEFAULT NULL,
  `site_id` bigint(20) unsigned DEFAULT NULL,
  `site_tenant_id` bigint(20) unsigned DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `uom` int(11) NOT NULL DEFAULT '0',
  `status_id` bigint(20) unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_management_advertisement_id_index` (`advertisement_id`),
  KEY `content_management_site_id_index` (`site_id`),
  KEY `content_management_site_tenant_id_index` (`site_tenant_id`),
  KEY `content_management_status_id_index` (`status_id`),
  CONSTRAINT `content_management_advertisement_id_foreign` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisements` (`id`),
  CONSTRAINT `content_management_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`),
  CONSTRAINT `content_management_site_tenant_id_foreign` FOREIGN KEY (`site_tenant_id`) REFERENCES `site_tenants` (`id`),
  CONSTRAINT `content_management_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `transaction_statuses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.content_screens
DROP TABLE IF EXISTS `content_screens`;
CREATE TABLE IF NOT EXISTS `content_screens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` bigint(20) unsigned DEFAULT NULL,
  `site_screen_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `content_screens_content_id_index` (`content_id`),
  KEY `content_screens_site_screen_id_index` (`site_screen_id`),
  CONSTRAINT `content_screens_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `content_management` (`id`),
  CONSTRAINT `content_screens_site_screen_id_foreign` FOREIGN KEY (`site_screen_id`) REFERENCES `site_screens` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.last_update_ats
DROP TABLE IF EXISTS `last_update_ats`;
CREATE TABLE IF NOT EXISTS `last_update_ats` (
  `last_updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.logs
DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned DEFAULT NULL,
  `site_screen_id` bigint(20) unsigned DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `parent_category_id` bigint(20) unsigned DEFAULT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `site_tenant_id` bigint(20) unsigned DEFAULT NULL,
  `advertisement_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key_words` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `results` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_site_id_index` (`site_id`),
  KEY `logs_site_screen_id_index` (`site_screen_id`),
  KEY `logs_category_id_index` (`category_id`),
  KEY `logs_parent_category_id_index` (`parent_category_id`),
  KEY `logs_brand_id_index` (`brand_id`),
  KEY `logs_company_id_index` (`company_id`),
  KEY `logs_site_tenant_id_index` (`site_tenant_id`),
  KEY `logs_advertisement_id_index` (`advertisement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=223 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=162 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.modules
DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Portal') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modules_name_index` (`name`),
  KEY `modules_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.password_resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.permissions
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `module_id` bigint(20) unsigned NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '0',
  `can_add` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_role_id_foreign` (`role_id`),
  KEY `permissions_module_id_foreign` (`module_id`),
  KEY `permissions_deleted_at_index` (`deleted_at`),
  CONSTRAINT `permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  CONSTRAINT `permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_name_index` (`name`),
  KEY `roles_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.sites
DROP TABLE IF EXISTS `sites`;
CREATE TABLE IF NOT EXISTS `sites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `site_logo` mediumtext COLLATE utf8mb4_unicode_ci,
  `site_banner` mediumtext COLLATE utf8mb4_unicode_ci,
  `site_background` mediumtext COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sites_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.sites_meta
DROP TABLE IF EXISTS `sites_meta`;
CREATE TABLE IF NOT EXISTS `sites_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sites_meta_site_id_foreign` (`site_id`),
  KEY `sites_meta_meta_key_index` (`meta_key`),
  CONSTRAINT `sites_meta_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_ads
DROP TABLE IF EXISTS `site_ads`;
CREATE TABLE IF NOT EXISTS `site_ads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ad_type` enum('Events','Online','Banners','Fullscreen','Pop-Up') COLLATE utf8mb4_unicode_ci NOT NULL,
  `screen_type` enum('Directory','LED','LFD','LED funnel') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` mediumtext COLLATE utf8mb4_unicode_ci,
  `file_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int(11) NOT NULL,
  `display_duration` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_ads_company_id_index` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_ad_screens
DROP TABLE IF EXISTS `site_ad_screens`;
CREATE TABLE IF NOT EXISTS `site_ad_screens` (
  `site_ad_id` bigint(20) unsigned DEFAULT NULL,
  `site_screen_id` bigint(20) unsigned DEFAULT NULL,
  KEY `site_ad_screens_site_ad_id_foreign` (`site_ad_id`),
  KEY `site_ad_screens_site_screen_id_foreign` (`site_screen_id`),
  CONSTRAINT `site_ad_screens_site_ad_id_foreign` FOREIGN KEY (`site_ad_id`) REFERENCES `site_ads` (`id`),
  CONSTRAINT `site_ad_screens_site_screen_id_foreign` FOREIGN KEY (`site_screen_id`) REFERENCES `site_screens` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_ad_sites
DROP TABLE IF EXISTS `site_ad_sites`;
CREATE TABLE IF NOT EXISTS `site_ad_sites` (
  `site_ad_id` bigint(20) unsigned DEFAULT NULL,
  `site_id` bigint(20) unsigned DEFAULT NULL,
  KEY `site_ad_sites_site_ad_id_foreign` (`site_ad_id`),
  KEY `site_ad_sites_site_id_foreign` (`site_id`),
  CONSTRAINT `site_ad_sites_site_ad_id_foreign` FOREIGN KEY (`site_ad_id`) REFERENCES `site_ads` (`id`),
  CONSTRAINT `site_ad_sites_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_ad_tenants
DROP TABLE IF EXISTS `site_ad_tenants`;
CREATE TABLE IF NOT EXISTS `site_ad_tenants` (
  `site_ad_id` bigint(20) unsigned DEFAULT NULL,
  `site_tenant_id` bigint(20) unsigned DEFAULT NULL,
  KEY `site_ad_tenants_site_ad_id_foreign` (`site_ad_id`),
  KEY `site_ad_tenants_site_tenant_id_foreign` (`site_tenant_id`),
  CONSTRAINT `site_ad_tenants_site_ad_id_foreign` FOREIGN KEY (`site_ad_id`) REFERENCES `site_ads` (`id`),
  CONSTRAINT `site_ad_tenants_site_tenant_id_foreign` FOREIGN KEY (`site_tenant_id`) REFERENCES `site_tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_buildings
DROP TABLE IF EXISTS `site_buildings`;
CREATE TABLE IF NOT EXISTS `site_buildings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_buildings_site_id_foreign` (`site_id`),
  CONSTRAINT `site_buildings_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_building_levels
DROP TABLE IF EXISTS `site_building_levels`;
CREATE TABLE IF NOT EXISTS `site_building_levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL,
  `site_building_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_building_levels_site_id_foreign` (`site_id`),
  KEY `site_building_levels_site_building_id_foreign` (`site_building_id`),
  CONSTRAINT `site_building_levels_site_building_id_foreign` FOREIGN KEY (`site_building_id`) REFERENCES `site_buildings` (`id`),
  CONSTRAINT `site_building_levels_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_maps
DROP TABLE IF EXISTS `site_maps`;
CREATE TABLE IF NOT EXISTS `site_maps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL,
  `site_building_id` bigint(20) unsigned NOT NULL,
  `site_building_level_id` bigint(20) unsigned NOT NULL,
  `site_screen_id` bigint(20) unsigned NOT NULL,
  `map_file` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `map_preview` mediumtext COLLATE utf8mb4_unicode_ci,
  `descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `image_size_width` int(11) NOT NULL DEFAULT '0',
  `image_size_height` int(11) NOT NULL DEFAULT '0',
  `position_x` decimal(10,2) NOT NULL,
  `position_y` decimal(10,2) NOT NULL,
  `position_z` decimal(10,2) NOT NULL,
  `text_y_position` decimal(10,2) NOT NULL,
  `default_zoom` decimal(10,2) NOT NULL,
  `default_zoom_desktop` decimal(10,2) NOT NULL,
  `default_zoom_mobile` decimal(10,2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_maps_site_id_foreign` (`site_id`),
  KEY `site_maps_site_building_id_foreign` (`site_building_id`),
  KEY `site_maps_site_building_level_id_foreign` (`site_building_level_id`),
  KEY `site_maps_site_screen_id_foreign` (`site_screen_id`),
  CONSTRAINT `site_maps_site_building_id_foreign` FOREIGN KEY (`site_building_id`) REFERENCES `site_buildings` (`id`),
  CONSTRAINT `site_maps_site_building_level_id_foreign` FOREIGN KEY (`site_building_level_id`) REFERENCES `site_building_levels` (`id`),
  CONSTRAINT `site_maps_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`),
  CONSTRAINT `site_maps_site_screen_id_foreign` FOREIGN KEY (`site_screen_id`) REFERENCES `site_screens` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_map_paths
DROP TABLE IF EXISTS `site_map_paths`;
CREATE TABLE IF NOT EXISTS `site_map_paths` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `point_orig` bigint(20) unsigned NOT NULL,
  `point_dest` bigint(20) unsigned NOT NULL,
  `path` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance` decimal(10,2) NOT NULL,
  `site_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_map_paths_site_id_foreign` (`site_id`),
  CONSTRAINT `site_map_paths_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_points
DROP TABLE IF EXISTS `site_points`;
CREATE TABLE IF NOT EXISTS `site_points` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_map_id` bigint(20) unsigned NOT NULL,
  `tenant_id` bigint(20) unsigned NOT NULL,
  `point_type` bigint(20) unsigned NOT NULL,
  `point_x` decimal(10,2) NOT NULL,
  `point_y` decimal(10,2) NOT NULL,
  `point_z` decimal(10,2) NOT NULL,
  `rotation_z` decimal(10,2) NOT NULL,
  `text_size` decimal(10,2) NOT NULL,
  `text_width` decimal(10,2) NOT NULL,
  `is_pwd` tinyint(1) NOT NULL DEFAULT '1',
  `point_label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wrap_at` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_points_site_map_id_foreign` (`site_map_id`),
  CONSTRAINT `site_points_site_map_id_foreign` FOREIGN KEY (`site_map_id`) REFERENCES `site_maps` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=704 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_point_links
DROP TABLE IF EXISTS `site_point_links`;
CREATE TABLE IF NOT EXISTS `site_point_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_map_id` bigint(20) unsigned NOT NULL,
  `point_a` bigint(20) NOT NULL DEFAULT '0',
  `point_b` bigint(20) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_point_links_site_map_id_foreign` (`site_map_id`),
  CONSTRAINT `site_point_links_site_map_id_foreign` FOREIGN KEY (`site_map_id`) REFERENCES `site_maps` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=786 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_screens
DROP TABLE IF EXISTS `site_screens`;
CREATE TABLE IF NOT EXISTS `site_screens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL,
  `site_building_id` bigint(20) unsigned NOT NULL,
  `site_building_level_id` bigint(20) unsigned NOT NULL,
  `site_point_id` bigint(20) unsigned DEFAULT '0',
  `screen_type` enum('Directory','LED','LFD','LED Panel') COLLATE utf8mb4_unicode_ci NOT NULL,
  `orientation` enum('Landscape','Portrait') COLLATE utf8mb4_unicode_ci NOT NULL,
  `physical_size_diagonal` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_size_width` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_size_height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dimension` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kiosk_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slots` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_exclusive` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_screens_site_id_foreign` (`site_id`),
  KEY `site_screens_site_building_id_foreign` (`site_building_id`),
  KEY `site_screens_site_building_level_id_foreign` (`site_building_level_id`),
  CONSTRAINT `site_screens_site_building_id_foreign` FOREIGN KEY (`site_building_id`) REFERENCES `site_buildings` (`id`),
  CONSTRAINT `site_screens_site_building_level_id_foreign` FOREIGN KEY (`site_building_level_id`) REFERENCES `site_building_levels` (`id`),
  CONSTRAINT `site_screens_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_screen_uptime
DROP TABLE IF EXISTS `site_screen_uptime`;
CREATE TABLE IF NOT EXISTS `site_screen_uptime` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_screen_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_screen_uptime_site_screen_id_foreign` (`site_screen_id`),
  CONSTRAINT `site_screen_uptime_site_screen_id_foreign` FOREIGN KEY (`site_screen_id`) REFERENCES `site_screens` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_tenants
DROP TABLE IF EXISTS `site_tenants`;
CREATE TABLE IF NOT EXISTS `site_tenants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint(20) unsigned NOT NULL,
  `site_id` bigint(20) unsigned NOT NULL,
  `site_building_id` bigint(20) unsigned NOT NULL,
  `site_building_level_id` bigint(20) unsigned NOT NULL,
  `company_id` bigint(20) DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT '0',
  `like_count` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `is_subscriber` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_tenants_brand_id_foreign` (`brand_id`),
  KEY `site_tenants_site_id_foreign` (`site_id`),
  KEY `site_tenants_site_building_id_foreign` (`site_building_id`),
  KEY `site_tenants_site_building_level_id_foreign` (`site_building_level_id`),
  KEY `site_tenants_company_id_index` (`company_id`),
  CONSTRAINT `site_tenants_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `site_tenants_site_building_id_foreign` FOREIGN KEY (`site_building_id`) REFERENCES `site_buildings` (`id`),
  CONSTRAINT `site_tenants_site_building_level_id_foreign` FOREIGN KEY (`site_building_level_id`) REFERENCES `site_building_levels` (`id`),
  CONSTRAINT `site_tenants_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1154 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_tenant_metas
DROP TABLE IF EXISTS `site_tenant_metas`;
CREATE TABLE IF NOT EXISTS `site_tenant_metas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_tenant_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_tenant_metas_site_tenant_id_foreign` (`site_tenant_id`),
  KEY `site_tenant_metas_meta_key_index` (`meta_key`),
  CONSTRAINT `site_tenant_metas_site_tenant_id_foreign` FOREIGN KEY (`site_tenant_id`) REFERENCES `site_tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=738 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.site_tenant_products
DROP TABLE IF EXISTS `site_tenant_products`;
CREATE TABLE IF NOT EXISTS `site_tenant_products` (
  `brand_product_promo_id` bigint(20) unsigned DEFAULT NULL,
  `site_tenant_id` bigint(20) unsigned DEFAULT NULL,
  KEY `site_tenant_products_brand_product_promo_id_foreign` (`brand_product_promo_id`),
  KEY `site_tenant_products_site_tenant_id_foreign` (`site_tenant_id`),
  CONSTRAINT `site_tenant_products_brand_product_promo_id_foreign` FOREIGN KEY (`brand_product_promo_id`) REFERENCES `brand_products_promos` (`id`),
  CONSTRAINT `site_tenant_products_site_tenant_id_foreign` FOREIGN KEY (`site_tenant_id`) REFERENCES `site_tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.supplementals
DROP TABLE IF EXISTS `supplementals`;
CREATE TABLE IF NOT EXISTS `supplementals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kiosk_image_primary` mediumtext COLLATE utf8mb4_unicode_ci,
  `kiosk_image_top` mediumtext COLLATE utf8mb4_unicode_ci,
  `online_image_primary` mediumtext COLLATE utf8mb4_unicode_ci,
  `online_image_top` mediumtext COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplementals_category_id_foreign` (`category_id`),
  KEY `supplementals_deleted_at_index` (`deleted_at`),
  CONSTRAINT `supplementals_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.tags
DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tags_deleted_at_index` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1748 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.transaction_statuses
DROP TABLE IF EXISTS `transaction_statuses`;
CREATE TABLE IF NOT EXISTS `transaction_statuses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_attempt` int(11) NOT NULL DEFAULT '0',
  `is_blocked` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `activation_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_full_name_index` (`full_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.users_meta
DROP TABLE IF EXISTS `users_meta`;
CREATE TABLE IF NOT EXISTS `users_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_meta_user_id_foreign` (`user_id`),
  KEY `users_meta_meta_key_index` (`meta_key`),
  CONSTRAINT `users_meta_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table prestige.user_roles
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  KEY `user_roles_user_id_foreign` (`user_id`),
  KEY `user_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
