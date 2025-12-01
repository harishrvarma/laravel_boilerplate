-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql8.0volume:3306
-- Generation Time: Dec 01, 2025 at 08:51 AM
-- Server version: 8.0.27
-- PHP Version: 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_boilerplate_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_resource`
--

CREATE TABLE `admin_resource` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `path_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_role`
--

CREATE TABLE `admin_role` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_resource`
--

CREATE TABLE `admin_role_resource` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `resource_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE `admin_user` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_role`
--

CREATE TABLE `admin_user_role` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_resource`
--

CREATE TABLE `api_resource` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `path_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_role`
--

CREATE TABLE `api_role` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_role_resource`
--

CREATE TABLE `api_role_resource` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `resource_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_user`
--

CREATE TABLE `api_user` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_user_role`
--

CREATE TABLE `api_user_role` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_registry`
--

CREATE TABLE `cache_registry` (
  `id` bigint UNSIGNED NOT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_generated` timestamp NULL DEFAULT NULL,
  `builder_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category1_entity`
--

CREATE TABLE `category1_entity` (
  `entity_id` bigint UNSIGNED NOT NULL,
  `entity_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category1_entity_attribute_value`
--

CREATE TABLE `category1_entity_attribute_value` (
  `value_id` bigint UNSIGNED NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `lang_id` int DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_entity`
--

CREATE TABLE `category_entity` (
  `entity_id` bigint UNSIGNED NOT NULL,
  `entity_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_entity_attribute_value`
--

CREATE TABLE `category_entity_attribute_value` (
  `value_id` bigint UNSIGNED NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `lang_id` int DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config_groups`
--

CREATE TABLE `config_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config_keys`
--

CREATE TABLE `config_keys` (
  `id` bigint UNSIGNED NOT NULL,
  `key_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input_type` enum('text','number','select','radio','checkbox','textarea','boolean','file','date') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `default_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validation_rule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_user_config` tinyint(1) NOT NULL DEFAULT '1',
  `position` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config_key_group`
--

CREATE TABLE `config_key_group` (
  `id` bigint UNSIGNED NOT NULL,
  `config_key_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config_options`
--

CREATE TABLE `config_options` (
  `id` bigint UNSIGNED NOT NULL,
  `config_key_id` bigint UNSIGNED NOT NULL,
  `option_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config_values`
--

CREATE TABLE `config_values` (
  `id` bigint UNSIGNED NOT NULL,
  `config_key_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crons`
--

CREATE TABLE `crons` (
  `cron_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `command` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expression` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '* * * * *',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_run_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_schedules`
--

CREATE TABLE `cron_schedules` (
  `schedule_id` bigint UNSIGNED NOT NULL,
  `cron_id` bigint UNSIGNED NOT NULL,
  `scheduled_for` datetime DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0=Pending,1=Success,2=Failure',
  `log` longtext COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_listener`
--

CREATE TABLE `event_listener` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `component` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'handle',
  `order_no` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` enum('admin','api','frontend') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `item_type` enum('folder','file') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'file',
  `resource_id` bigint UNSIGNED DEFAULT NULL,
  `path_ids` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `level` int NOT NULL DEFAULT '0',
  `order_no` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_uris` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `grant_types` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_device_codes`
--

CREATE TABLE `oauth_device_codes` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_code` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `user_approved_at` datetime DEFAULT NULL,
  `last_polled_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `category_id` int NOT NULL,
  `product_id` int NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_parent_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_category_type`
--

CREATE TABLE `product_category_type` (
  `category_type_id` int NOT NULL,
  `category_id` int NOT NULL,
  `category_type_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_type_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_entity`
--

CREATE TABLE `product_entity` (
  `entity_id` bigint UNSIGNED NOT NULL,
  `entity_type_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_entity_attribute_value`
--

CREATE TABLE `product_entity_attribute_value` (
  `value_id` bigint UNSIGNED NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `lang_id` int DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_attribute_group`
--

CREATE TABLE `table_eav_attribute_group` (
  `group_id` bigint UNSIGNED NOT NULL,
  `entity_type_id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_attribute_group_translation`
--

CREATE TABLE `table_eav_attribute_group_translation` (
  `translation_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `lang_id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_entity`
--

CREATE TABLE `table_eav_entity` (
  `entity_id` bigint UNSIGNED NOT NULL,
  `entity_type_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_entity_attribute`
--

CREATE TABLE `table_eav_entity_attribute` (
  `attribute_id` bigint UNSIGNED NOT NULL,
  `entity_type_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `position` int NOT NULL DEFAULT '0',
  `default_value` text COLLATE utf8mb4_unicode_ci,
  `lang_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether this attribute is language-specific',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_entity_attribute_option`
--

CREATE TABLE `table_eav_entity_attribute_option` (
  `option_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `position` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_entity_attribute_option_translation`
--

CREATE TABLE `table_eav_entity_attribute_option_translation` (
  `translation_id` bigint UNSIGNED NOT NULL,
  `option_id` bigint UNSIGNED NOT NULL,
  `lang_id` int UNSIGNED NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_entity_attribute_translation`
--

CREATE TABLE `table_eav_entity_attribute_translation` (
  `translation_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `lang_id` int UNSIGNED NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_entity_attribute_value`
--

CREATE TABLE `table_eav_entity_attribute_value` (
  `value_id` bigint UNSIGNED NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `lang_id` int UNSIGNED DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_eav_entity_type`
--

CREATE TABLE `table_eav_entity_type` (
  `entity_type_id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_entity_attribute_config`
--

CREATE TABLE `table_entity_attribute_config` (
  `config_id` bigint UNSIGNED NOT NULL,
  `entity_type_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `show_in_grid` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Show this attribute in grid',
  `default_in_grid` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'default Show this attribute in grid',
  `is_sortable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Can this column be sorted',
  `is_filterable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Can this column be filtered',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` bigint UNSIGNED NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale_id` bigint UNSIGNED DEFAULT NULL,
  `group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translations_locale`
--

CREATE TABLE `translations_locale` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_resource`
--
ALTER TABLE `admin_resource`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_role`
--
ALTER TABLE `admin_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_role_resource`
--
ALTER TABLE `admin_role_resource`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_role_resource_role_id_resource_id_unique` (`role_id`,`resource_id`),
  ADD KEY `admin_role_resource_resource_id_foreign` (`resource_id`);

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_user_role`
--
ALTER TABLE `admin_user_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_user_role_admin_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `admin_user_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `api_resource`
--
ALTER TABLE `api_resource`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_resource_code_unique` (`code`);

--
-- Indexes for table `api_role`
--
ALTER TABLE `api_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_role_name_unique` (`name`);

--
-- Indexes for table `api_role_resource`
--
ALTER TABLE `api_role_resource`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_role_resource_role_id_resource_id_unique` (`role_id`,`resource_id`),
  ADD KEY `api_role_resource_resource_id_foreign` (`resource_id`);

--
-- Indexes for table `api_user`
--
ALTER TABLE `api_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_user_email_unique` (`email`);

--
-- Indexes for table `api_user_role`
--
ALTER TABLE `api_user_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_user_role_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `api_user_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_registry`
--
ALTER TABLE `cache_registry`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cache_registry_area_type_key_unique` (`area`,`type`,`key`);

--
-- Indexes for table `category1_entity`
--
ALTER TABLE `category1_entity`
  ADD PRIMARY KEY (`entity_id`);

--
-- Indexes for table `category1_entity_attribute_value`
--
ALTER TABLE `category1_entity_attribute_value`
  ADD PRIMARY KEY (`value_id`),
  ADD KEY `category1_entity_attribute_value_entity_id_foreign` (`entity_id`),
  ADD KEY `category1_entity_attribute_value_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `category_entity`
--
ALTER TABLE `category_entity`
  ADD PRIMARY KEY (`entity_id`);

--
-- Indexes for table `category_entity_attribute_value`
--
ALTER TABLE `category_entity_attribute_value`
  ADD PRIMARY KEY (`value_id`),
  ADD KEY `category_entity_attribute_value_entity_id_foreign` (`entity_id`),
  ADD KEY `category_entity_attribute_value_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `config_groups`
--
ALTER TABLE `config_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_keys`
--
ALTER TABLE `config_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `config_keys_key_name_unique` (`key_name`);

--
-- Indexes for table `config_key_group`
--
ALTER TABLE `config_key_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `config_key_group_config_key_id_foreign` (`config_key_id`),
  ADD KEY `config_key_group_group_id_foreign` (`group_id`);

--
-- Indexes for table `config_options`
--
ALTER TABLE `config_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `config_options_config_key_id_foreign` (`config_key_id`);

--
-- Indexes for table `config_values`
--
ALTER TABLE `config_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `config_values_config_key_id_foreign` (`config_key_id`),
  ADD KEY `config_values_admin_id_foreign` (`user_id`);

--
-- Indexes for table `crons`
--
ALTER TABLE `crons`
  ADD PRIMARY KEY (`cron_id`);

--
-- Indexes for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `cron_schedules_cron_id_foreign` (`cron_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_code_unique` (`code`);

--
-- Indexes for table `event_listener`
--
ALTER TABLE `event_listener`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listeners_event_id_foreign` (`event_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_parent_id_index` (`parent_id`),
  ADD KEY `menus_resource_id_index` (`resource_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_owner_type_owner_id_index` (`owner_type`,`owner_id`);

--
-- Indexes for table `oauth_device_codes`
--
ALTER TABLE `oauth_device_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `oauth_device_codes_user_code_unique` (`user_code`),
  ADD KEY `oauth_device_codes_user_id_index` (`user_id`),
  ADD KEY `oauth_device_codes_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `product_category_type`
--
ALTER TABLE `product_category_type`
  ADD PRIMARY KEY (`category_type_id`);

--
-- Indexes for table `product_entity`
--
ALTER TABLE `product_entity`
  ADD PRIMARY KEY (`entity_id`);

--
-- Indexes for table `product_entity_attribute_value`
--
ALTER TABLE `product_entity_attribute_value`
  ADD PRIMARY KEY (`value_id`),
  ADD KEY `product_entity_attribute_value_entity_id_foreign` (`entity_id`),
  ADD KEY `product_entity_attribute_value_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `table_eav_attribute_group`
--
ALTER TABLE `table_eav_attribute_group`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `table_eav_attribute_group_entity_type_id_code_unique` (`entity_type_id`,`code`);

--
-- Indexes for table `table_eav_attribute_group_translation`
--
ALTER TABLE `table_eav_attribute_group_translation`
  ADD PRIMARY KEY (`translation_id`),
  ADD UNIQUE KEY `table_eav_attribute_group_translation_group_id_lang_id_unique` (`group_id`,`lang_id`);

--
-- Indexes for table `table_eav_entity`
--
ALTER TABLE `table_eav_entity`
  ADD PRIMARY KEY (`entity_id`),
  ADD KEY `table_eav_entity_entity_type_id_foreign` (`entity_type_id`);

--
-- Indexes for table `table_eav_entity_attribute`
--
ALTER TABLE `table_eav_entity_attribute`
  ADD PRIMARY KEY (`attribute_id`),
  ADD UNIQUE KEY `table_eav_entity_attribute_entity_type_id_code_unique` (`entity_type_id`,`code`),
  ADD KEY `fk_attr_group` (`group_id`);

--
-- Indexes for table `table_eav_entity_attribute_option`
--
ALTER TABLE `table_eav_entity_attribute_option`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `table_eav_entity_attribute_option_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `table_eav_entity_attribute_option_translation`
--
ALTER TABLE `table_eav_entity_attribute_option_translation`
  ADD PRIMARY KEY (`translation_id`),
  ADD UNIQUE KEY `eav_opt_lang_uq` (`option_id`,`lang_id`);

--
-- Indexes for table `table_eav_entity_attribute_translation`
--
ALTER TABLE `table_eav_entity_attribute_translation`
  ADD PRIMARY KEY (`translation_id`),
  ADD UNIQUE KEY `eav_attr_lang_unique` (`attribute_id`,`lang_id`);

--
-- Indexes for table `table_eav_entity_attribute_value`
--
ALTER TABLE `table_eav_entity_attribute_value`
  ADD PRIMARY KEY (`value_id`),
  ADD UNIQUE KEY `eav_val_unique` (`entity_id`,`attribute_id`,`lang_id`),
  ADD KEY `table_eav_entity_attribute_value_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `table_eav_entity_type`
--
ALTER TABLE `table_eav_entity_type`
  ADD PRIMARY KEY (`entity_type_id`),
  ADD UNIQUE KEY `table_eav_entity_type_code_unique` (`code`);

--
-- Indexes for table `table_entity_attribute_config`
--
ALTER TABLE `table_entity_attribute_config`
  ADD PRIMARY KEY (`config_id`),
  ADD UNIQUE KEY `uniq_entity_attribute` (`entity_type_id`,`attribute_id`),
  ADD KEY `table_entity_attribute_config_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `translations_module_locale_group_key_unique` (`module`,`group`,`key`),
  ADD KEY `translations_locale_id_foreign` (`locale_id`);

--
-- Indexes for table `translations_locale`
--
ALTER TABLE `translations_locale`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `translations_locale_code_unique` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_resource`
--
ALTER TABLE `admin_resource`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_role`
--
ALTER TABLE `admin_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_role_resource`
--
ALTER TABLE `admin_role_resource`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_user_role`
--
ALTER TABLE `admin_user_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_resource`
--
ALTER TABLE `api_resource`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_role`
--
ALTER TABLE `api_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_role_resource`
--
ALTER TABLE `api_role_resource`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_user`
--
ALTER TABLE `api_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_user_role`
--
ALTER TABLE `api_user_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cache_registry`
--
ALTER TABLE `cache_registry`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category1_entity`
--
ALTER TABLE `category1_entity`
  MODIFY `entity_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category1_entity_attribute_value`
--
ALTER TABLE `category1_entity_attribute_value`
  MODIFY `value_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_entity`
--
ALTER TABLE `category_entity`
  MODIFY `entity_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_entity_attribute_value`
--
ALTER TABLE `category_entity_attribute_value`
  MODIFY `value_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_groups`
--
ALTER TABLE `config_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_keys`
--
ALTER TABLE `config_keys`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_key_group`
--
ALTER TABLE `config_key_group`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_options`
--
ALTER TABLE `config_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config_values`
--
ALTER TABLE `config_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crons`
--
ALTER TABLE `crons`
  MODIFY `cron_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  MODIFY `schedule_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_listener`
--
ALTER TABLE `event_listener`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_entity`
--
ALTER TABLE `product_entity`
  MODIFY `entity_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_entity_attribute_value`
--
ALTER TABLE `product_entity_attribute_value`
  MODIFY `value_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_attribute_group`
--
ALTER TABLE `table_eav_attribute_group`
  MODIFY `group_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_attribute_group_translation`
--
ALTER TABLE `table_eav_attribute_group_translation`
  MODIFY `translation_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_entity`
--
ALTER TABLE `table_eav_entity`
  MODIFY `entity_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_entity_attribute`
--
ALTER TABLE `table_eav_entity_attribute`
  MODIFY `attribute_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_entity_attribute_option`
--
ALTER TABLE `table_eav_entity_attribute_option`
  MODIFY `option_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_entity_attribute_option_translation`
--
ALTER TABLE `table_eav_entity_attribute_option_translation`
  MODIFY `translation_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_entity_attribute_translation`
--
ALTER TABLE `table_eav_entity_attribute_translation`
  MODIFY `translation_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_entity_attribute_value`
--
ALTER TABLE `table_eav_entity_attribute_value`
  MODIFY `value_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_eav_entity_type`
--
ALTER TABLE `table_eav_entity_type`
  MODIFY `entity_type_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_entity_attribute_config`
--
ALTER TABLE `table_entity_attribute_config`
  MODIFY `config_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translations_locale`
--
ALTER TABLE `translations_locale`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_role_resource`
--
ALTER TABLE `admin_role_resource`
  ADD CONSTRAINT `admin_role_resource_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `admin_resource` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_role_resource_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `admin_role` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_user_role`
--
ALTER TABLE `admin_user_role`
  ADD CONSTRAINT `admin_user_role_admin_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_user_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `admin_role` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `api_role_resource`
--
ALTER TABLE `api_role_resource`
  ADD CONSTRAINT `api_role_resource_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `api_resource` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `api_role_resource_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `api_role` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `api_user_role`
--
ALTER TABLE `api_user_role`
  ADD CONSTRAINT `api_user_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `api_role` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `api_user_role_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `api_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `category1_entity_attribute_value`
--
ALTER TABLE `category1_entity_attribute_value`
  ADD CONSTRAINT `category1_entity_attribute_value_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `table_eav_entity_attribute` (`attribute_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category1_entity_attribute_value_entity_id_foreign` FOREIGN KEY (`entity_id`) REFERENCES `category1_entity` (`entity_id`) ON DELETE CASCADE;

--
-- Constraints for table `category_entity_attribute_value`
--
ALTER TABLE `category_entity_attribute_value`
  ADD CONSTRAINT `category_entity_attribute_value_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `table_eav_entity_attribute` (`attribute_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_entity_attribute_value_entity_id_foreign` FOREIGN KEY (`entity_id`) REFERENCES `category_entity` (`entity_id`) ON DELETE CASCADE;

--
-- Constraints for table `config_key_group`
--
ALTER TABLE `config_key_group`
  ADD CONSTRAINT `config_key_group_config_key_id_foreign` FOREIGN KEY (`config_key_id`) REFERENCES `config_keys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `config_key_group_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `config_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `config_options`
--
ALTER TABLE `config_options`
  ADD CONSTRAINT `config_options_config_key_id_foreign` FOREIGN KEY (`config_key_id`) REFERENCES `config_keys` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `config_values`
--
ALTER TABLE `config_values`
  ADD CONSTRAINT `config_values_admin_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `config_values_config_key_id_foreign` FOREIGN KEY (`config_key_id`) REFERENCES `config_keys` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  ADD CONSTRAINT `cron_schedules_cron_id_foreign` FOREIGN KEY (`cron_id`) REFERENCES `crons` (`cron_id`) ON DELETE CASCADE;

--
-- Constraints for table `event_listener`
--
ALTER TABLE `event_listener`
  ADD CONSTRAINT `listeners_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menus_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `admin_resource` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_entity_attribute_value`
--
ALTER TABLE `product_entity_attribute_value`
  ADD CONSTRAINT `product_entity_attribute_value_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `table_eav_entity_attribute` (`attribute_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_entity_attribute_value_entity_id_foreign` FOREIGN KEY (`entity_id`) REFERENCES `product_entity` (`entity_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_eav_attribute_group`
--
ALTER TABLE `table_eav_attribute_group`
  ADD CONSTRAINT `table_eav_attribute_group_entity_type_id_foreign` FOREIGN KEY (`entity_type_id`) REFERENCES `table_eav_entity_type` (`entity_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_eav_attribute_group_translation`
--
ALTER TABLE `table_eav_attribute_group_translation`
  ADD CONSTRAINT `table_eav_attribute_group_translation_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `table_eav_attribute_group` (`group_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_eav_entity`
--
ALTER TABLE `table_eav_entity`
  ADD CONSTRAINT `table_eav_entity_entity_type_id_foreign` FOREIGN KEY (`entity_type_id`) REFERENCES `table_eav_entity_type` (`entity_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_eav_entity_attribute`
--
ALTER TABLE `table_eav_entity_attribute`
  ADD CONSTRAINT `fk_attr_group` FOREIGN KEY (`group_id`) REFERENCES `table_eav_attribute_group` (`group_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `table_eav_entity_attribute_entity_type_id_foreign` FOREIGN KEY (`entity_type_id`) REFERENCES `table_eav_entity_type` (`entity_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_eav_entity_attribute_option`
--
ALTER TABLE `table_eav_entity_attribute_option`
  ADD CONSTRAINT `table_eav_entity_attribute_option_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `table_eav_entity_attribute` (`attribute_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_eav_entity_attribute_option_translation`
--
ALTER TABLE `table_eav_entity_attribute_option_translation`
  ADD CONSTRAINT `table_eav_entity_attribute_option_translation_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `table_eav_entity_attribute_option` (`option_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_eav_entity_attribute_translation`
--
ALTER TABLE `table_eav_entity_attribute_translation`
  ADD CONSTRAINT `table_eav_entity_attribute_translation_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `table_eav_entity_attribute` (`attribute_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_eav_entity_attribute_value`
--
ALTER TABLE `table_eav_entity_attribute_value`
  ADD CONSTRAINT `table_eav_entity_attribute_value_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `table_eav_entity_attribute` (`attribute_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `table_eav_entity_attribute_value_entity_id_foreign` FOREIGN KEY (`entity_id`) REFERENCES `table_eav_entity` (`entity_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_entity_attribute_config`
--
ALTER TABLE `table_entity_attribute_config`
  ADD CONSTRAINT `table_entity_attribute_config_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `table_eav_entity_attribute` (`attribute_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `table_entity_attribute_config_entity_type_id_foreign` FOREIGN KEY (`entity_type_id`) REFERENCES `table_eav_entity_type` (`entity_type_id`) ON DELETE CASCADE;

--
-- Constraints for table `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_locale_id_foreign` FOREIGN KEY (`locale_id`) REFERENCES `translations_locale` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
