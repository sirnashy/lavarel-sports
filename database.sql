-- ===================================
-- SportStream Database Schema & Seed Data
-- ===================================
-- This SQL file contains all table definitions and initial data
-- Import this file into your MySQL/MariaDB database
-- ===================================

-- ─────────────────────────────────────
-- 1. USERS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100),
  `is_admin` boolean DEFAULT false,
  `is_active` boolean DEFAULT true,
  `avatar` varchar(255),
  `last_login_at` timestamp NULL,
  `last_login_ip` varchar(255),
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 2. PASSWORD RESET TOKENS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL PRIMARY KEY,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 3. SESSIONS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL PRIMARY KEY,
  `user_id` BIGINT UNSIGNED,
  `ip_address` varchar(45),
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` INT NOT NULL,
  KEY `user_id_index` (`user_id`),
  KEY `last_activity_index` (`last_activity`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 4. CACHE TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL PRIMARY KEY,
  `value` mediumtext NOT NULL,
  `expiration` BIGINT NOT NULL,
  KEY `expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 5. CACHE LOCKS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL PRIMARY KEY,
  `owner` varchar(255) NOT NULL,
  `expiration` BIGINT NOT NULL,
  KEY `expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 6. JOBS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` SMALLINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  KEY `queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 7. JOB BATCHES TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` INT,
  `created_at` INT NOT NULL,
  `finished_at` INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 8. FAILED JOBS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` varchar(255) NOT NULL UNIQUE,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  KEY `connection_queue_failed_at_index` (`connection`, `queue`, `failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 9. ROLES TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL UNIQUE,
  `display_name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 10. PERMISSIONS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL UNIQUE,
  `display_name` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT 'general',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 11. ROLE_USER PIVOT TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 12. PERMISSION_ROLE PIVOT TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `permission_role` (
  `role_id` BIGINT UNSIGNED NOT NULL,
  `permission_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 13. PERMISSION_USER PIVOT TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `permission_user` (
  `user_id` BIGINT UNSIGNED NOT NULL,
  `permission_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `permission_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 14. FEATURED MATCHES TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `featured_matches` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `match_id` varchar(255) NOT NULL,
  `title` varchar(255),
  `description` text,
  `thumbnail` varchar(255),
  `sort_order` INT DEFAULT 0,
  `is_active` boolean DEFAULT true,
  `match_starts_at` timestamp NULL,
  `created_by` BIGINT UNSIGNED NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `match_id_index` (`match_id`),
  KEY `is_active_index` (`is_active`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 15. ADVERTISEMENTS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `advertisements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `slot_key` varchar(255) NOT NULL,
  `code` longtext NOT NULL,
  `position` varchar(255) DEFAULT 'any',
  `sort_order` INT DEFAULT 0,
  `is_active` boolean DEFAULT true,
  `starts_at` timestamp NULL,
  `ends_at` timestamp NULL,
  `impressions` INT DEFAULT 0,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `slot_key_index` (`slot_key`),
  KEY `is_active_index` (`is_active`),
  KEY `starts_at_index` (`starts_at`),
  KEY `ends_at_index` (`ends_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 16. SITE PAGES TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `site_pages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `content` longtext NOT NULL,
  `meta_title` varchar(255),
  `meta_description` text,
  `og_image` varchar(255),
  `is_active` boolean DEFAULT true,
  `show_in_nav` boolean DEFAULT false,
  `sort_order` INT DEFAULT 0,
  `created_by` BIGINT UNSIGNED NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `slug_index` (`slug`),
  KEY `is_active_index` (`is_active`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 17. NAVIGATION MENUS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `navigation_menus` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `target` varchar(255) DEFAULT '_self',
  `icon` varchar(255),
  `location` varchar(255) DEFAULT 'header',
  `parent_id` BIGINT UNSIGNED,
  `sort_order` INT DEFAULT 0,
  `is_active` boolean DEFAULT true,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `parent_id_index` (`parent_id`),
  KEY `is_active_index` (`is_active`),
  FOREIGN KEY (`parent_id`) REFERENCES `navigation_menus`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 18. SEO SETTINGS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `page_key` varchar(255) NOT NULL UNIQUE,
  `meta_title_template` varchar(255) NOT NULL,
  `meta_description_template` text NOT NULL,
  `og_image` varchar(255),
  `twitter_card` varchar(255) DEFAULT 'summary_large_image',
  `extra_meta` json,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `page_key_index` (`page_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 19. SITE SETTINGS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `key` varchar(255) NOT NULL UNIQUE,
  `value` text,
  `type` varchar(255) DEFAULT 'text',
  `group` varchar(255) DEFAULT 'general',
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `key_index` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 20. ACTIVITY LOGS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255),
  `subject_id` BIGINT UNSIGNED,
  `description` text,
  `properties` json,
  `ip_address` varchar(255),
  `user_agent` text,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `user_id_index` (`user_id`),
  KEY `subject_type_subject_id_index` (`subject_type`, `subject_id`),
  KEY `action_index` (`action`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 21. VISITORS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `visitors` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `session_id` varchar(255) NOT NULL,
  `ip_address` varchar(255),
  `user_agent` text,
  `page_url` varchar(255) NOT NULL,
  `referer` varchar(255),
  `country` varchar(255),
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `session_id_index` (`session_id`),
  KEY `created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────
-- 22. STREAM VIEWS TABLE
-- ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `stream_views` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `match_id` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `ip_address` varchar(255),
  `duration_seconds` INT DEFAULT 0,
  `stream_source` varchar(255),
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `match_id_index` (`match_id`),
  KEY `session_id_index` (`session_id`),
  KEY `created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================
-- SEED DATA
-- ===================================

-- ─────────────────────────────────────
-- PERMISSIONS DATA
-- ─────────────────────────────────────
INSERT INTO `permissions` (`name`, `display_name`, `group`, `created_at`, `updated_at`) VALUES
('manage-users', 'Manage Users', 'users', NOW(), NOW()),
('manage-featured', 'Manage Featured Matches', 'content', NOW(), NOW()),
('manage-pages', 'Manage CMS Pages', 'content', NOW(), NOW()),
('manage-advertisements', 'Manage Advertisements', 'monetization', NOW(), NOW()),
('manage-settings', 'Manage Site Settings', 'system', NOW(), NOW()),
('view-logs', 'View Activity Logs', 'system', NOW(), NOW());

-- ─────────────────────────────────────
-- ROLES DATA
-- ─────────────────────────────────────
INSERT INTO `roles` (`name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
('admin', 'Super Administrator', 'Full system access and configurations.', NOW(), NOW()),
('editor', 'Content Editor', 'Can manage featured matches and CMS pages.', NOW(), NOW()),
('advertiser', 'Monetization Manager', 'Can manage advertisement slots and tracking.', NOW(), NOW());

-- ─────────────────────────────────────
-- PERMISSION_ROLE ASSOCIATIONS
-- ─────────────────────────────────────
-- Admin role permissions (all permissions)
INSERT INTO `permission_role` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6),
-- Editor role permissions
(2, 2), (2, 3),
-- Advertiser role permissions
(3, 4);

-- ─────────────────────────────────────
-- SITE SETTINGS DATA
-- ─────────────────────────────────────
INSERT INTO `site_settings` (`key`, `value`, `type`, `group`, `label`, `created_at`, `updated_at`) VALUES
('site_name', 'SportStream', 'text', 'general', 'Site Name', NOW(), NOW()),
('site_tagline', 'Watch Live Sports Streams Free', 'text', 'general', 'Site Tagline', NOW(), NOW()),
('contact_email', 'support@sportstream.com', 'text', 'general', 'Support Email', NOW(), NOW()),
('maintenance_mode', '0', 'boolean', 'general', 'Maintenance Mode', NOW(), NOW()),
('primary_color', '#ff3b30', 'text', 'styling', 'Primary Brand Color', NOW(), NOW()),
('default_og_image', 'https://sportstream.com/images/default-og.jpg', 'text', 'styling', 'Default OG Image URL', NOW(), NOW()),
('google_analytics_id', '', 'text', 'analytics', 'Google Analytics Tracking ID', NOW(), NOW()),
('custom_header_code', '', 'textarea', 'analytics', 'Custom Header Code (e.g. tracking scripts)', NOW(), NOW()),
('enable_ads', '1', 'boolean', 'monetization', 'Enable Advertisements Globals', NOW(), NOW());

-- ─────────────────────────────────────
-- SEO SETTINGS DATA
-- ─────────────────────────────────────
INSERT INTO `seo_settings` (`page_key`, `meta_title_template`, `meta_description_template`, `twitter_card`, `created_at`, `updated_at`) VALUES
('home', 'Live Sports Streaming - Watch Football, Basketball, Tennis Free | @{{ site_name }}', 'Watch free live sports streams in high definition. Football, basketball, tennis and more. Realtime matches, no signups required.', 'summary_large_image', NOW(), NOW()),
('match', 'Watch @{{ title }} Live Stream | @{{ site_name }}', 'Watch @{{ team_home }} vs @{{ team_away }} live streaming online. Enjoy HD sports stream coverage, live events, lineups, statistics and scores on @{{ site_name }}.', 'summary_large_image', NOW(), NOW()),
('search', 'Search Results for "@{{ query }}" | @{{ site_name }}', 'Find live streaming schedules, upcoming games, and highlights for "@{{ query }}" on @{{ site_name }}.', 'summary_large_image', NOW(), NOW()),
('sport', 'Watch Live @{{ sport_name }} Streams | @{{ site_name }}', 'Get the complete schedule of live and upcoming @{{ sport_name }} matches. Watch HD streams, check standings and results on @{{ site_name }}.', 'summary_large_image', NOW(), NOW()),
('standings', '@{{ competition }} Standings & Table | @{{ site_name }}', 'View the latest standings, table, and statistics for @{{ competition }} on @{{ site_name }}.', 'summary_large_image', NOW(), NOW());

-- ===================================
-- END OF DATABASE SCHEMA
-- ===================================
-- All tables created and initial seed data inserted.
-- You can now manually create an admin user or use the /install route.
