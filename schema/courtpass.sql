-- ============================================================
-- CourtPass — Database Schema
-- Sports venue booking & community platform
-- All timestamps in Asia/Colombo (UTC+5:30)
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+05:30";

CREATE DATABASE IF NOT EXISTS `courtpass`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `courtpass`;

-- ============================================================
-- USERS
-- ============================================================
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('customer','owner','coach','admin') NOT NULL DEFAULT 'customer',
  `status` ENUM('active','deactivated') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- VENUES
-- ============================================================
CREATE TABLE `venues` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `address` VARCHAR(500) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `image_path` VARCHAR(500) DEFAULT NULL,
  `status` ENUM('pending','approved','rejected','deactivated') NOT NULL DEFAULT 'pending',
  `rejection_reason` VARCHAR(500) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_venues_owner` (`owner_id`),
  KEY `idx_venues_status` (`status`),
  KEY `idx_venues_city` (`city`),
  CONSTRAINT `fk_venues_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- COURTS
-- ============================================================
CREATE TABLE `courts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `venue_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `sport_type` ENUM('futsal','badminton','pickleball','squash','billiards','carrom','table_tennis') NOT NULL,
  `hourly_rate` DECIMAL(10,2) NOT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_courts_venue` (`venue_id`),
  KEY `idx_courts_sport` (`sport_type`),
  CONSTRAINT `fk_courts_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- OPERATING_HOURS
-- ============================================================
CREATE TABLE `operating_hours` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `court_id` INT UNSIGNED NOT NULL,
  `day_of_week` TINYINT UNSIGNED NOT NULL COMMENT '0=Sunday, 1=Monday ... 6=Saturday',
  `open_time` TIME NOT NULL,
  `close_time` TIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ophours_court` (`court_id`),
  UNIQUE KEY `uk_ophours_court_day` (`court_id`, `day_of_week`),
  CONSTRAINT `fk_ophours_court` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- BOOKINGS
-- Slots are fixed at 1 hour. slot_end = slot_start + 1 hour.
-- UNIQUE constraint prevents double-booking at DB level.
-- ============================================================
CREATE TABLE `bookings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `court_id` INT UNSIGNED NOT NULL,
  `customer_id` INT UNSIGNED NOT NULL,
  `slot_date` DATE NOT NULL,
  `slot_start` TIME NOT NULL,
  `slot_end` TIME NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `payment_method` ENUM('online','cash_on_arrival') NOT NULL DEFAULT 'online',
  `payment_converted` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 if converted from cash to online for resale',
  `status` ENUM('pending','confirmed','completed','cancelled','no_show') NOT NULL DEFAULT 'pending',
  `cancel_reason` VARCHAR(500) DEFAULT NULL,
  `cancelled_by` INT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_bookings_slot` (`court_id`, `slot_date`, `slot_start`, `status`),
  KEY `idx_bookings_customer` (`customer_id`),
  KEY `idx_bookings_date` (`slot_date`),
  KEY `idx_bookings_status` (`status`),
  CONSTRAINT `fk_bookings_court` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_bookings_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_bookings_cancelledby` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PAYMENTS
-- ============================================================
CREATE TABLE `payments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` INT UNSIGNED DEFAULT NULL,
  `resale_listing_id` INT UNSIGNED DEFAULT NULL,
  `coach_session_reg_id` INT UNSIGNED DEFAULT NULL,
  `payhere_order_id` VARCHAR(100) DEFAULT NULL,
  `payhere_payment_id` VARCHAR(100) DEFAULT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'LKR',
  `status` ENUM('pending','completed','failed','refunded','partially_refunded') NOT NULL DEFAULT 'pending',
  `refund_amount` DECIMAL(10,2) DEFAULT NULL,
  `payhere_response` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_payments_booking` (`booking_id`),
  KEY `idx_payments_resale` (`resale_listing_id`),
  KEY `idx_payments_session` (`coach_session_reg_id`),
  KEY `idx_payments_order` (`payhere_order_id`),
  CONSTRAINT `fk_payments_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- RESALE_LISTINGS
-- ============================================================
CREATE TABLE `resale_listings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` INT UNSIGNED NOT NULL,
  `seller_id` INT UNSIGNED NOT NULL,
  `buyer_id` INT UNSIGNED DEFAULT NULL,
  `listing_price` DECIMAL(10,2) NOT NULL,
  `status` ENUM('active','sold','expired','revoked') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sold_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_resale_booking` (`booking_id`),
  KEY `idx_resale_seller` (`seller_id`),
  KEY `idx_resale_status` (`status`),
  CONSTRAINT `fk_resale_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_resale_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_resale_buyer` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- FLASH_SLOTS
-- ============================================================
CREATE TABLE `flash_slots` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `court_id` INT UNSIGNED NOT NULL,
  `venue_id` INT UNSIGNED NOT NULL,
  `slot_date` DATE NOT NULL,
  `slot_start` TIME NOT NULL,
  `slot_end` TIME NOT NULL,
  `original_price` DECIMAL(10,2) NOT NULL,
  `discounted_price` DECIMAL(10,2) NOT NULL,
  `status` ENUM('active','booked','expired') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_flash_court` (`court_id`),
  KEY `idx_flash_venue` (`venue_id`),
  KEY `idx_flash_date` (`slot_date`),
  CONSTRAINT `fk_flash_court` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_flash_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- REVIEWS (venue reviews)
-- ============================================================
CREATE TABLE `reviews` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `venue_id` INT UNSIGNED NOT NULL,
  `customer_id` INT UNSIGNED NOT NULL,
  `booking_id` INT UNSIGNED NOT NULL,
  `rating` TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT DEFAULT NULL,
  `owner_response` TEXT DEFAULT NULL,
  `owner_response_at` DATETIME DEFAULT NULL,
  `status` ENUM('active','removed') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reviews_venue` (`venue_id`),
  KEY `idx_reviews_customer` (`customer_id`),
  UNIQUE KEY `uk_reviews_booking` (`booking_id`),
  CONSTRAINT `fk_reviews_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_reviews_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_reviews_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- ANNOUNCEMENTS
-- ============================================================
CREATE TABLE `announcements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `venue_id` INT UNSIGNED NOT NULL,
  `owner_id` INT UNSIGNED NOT NULL,
  `type` ENUM('operational','promotional') NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `content` TEXT NOT NULL,
  `status` ENUM('active','removed') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_announcements_venue` (`venue_id`),
  CONSTRAINT `fk_announcements_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_announcements_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- RELIABILITY_SCORES
-- ============================================================
CREATE TABLE `reliability_scores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` INT UNSIGNED NOT NULL,
  `score` DECIMAL(5,2) NOT NULL DEFAULT 100.00,
  `completed_bookings` INT UNSIGNED NOT NULL DEFAULT 0,
  `no_shows` INT UNSIGNED NOT NULL DEFAULT 0,
  `irresponsible_cancellations` INT UNSIGNED NOT NULL DEFAULT 0,
  `moderate_cancellations` INT UNSIGNED NOT NULL DEFAULT 0,
  `responsible_cancellations` INT UNSIGNED NOT NULL DEFAULT 0,
  `tier` ENUM('new_member','restricted','standard_plus') NOT NULL DEFAULT 'new_member',
  `last_calculated` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_reliability_customer` (`customer_id`),
  CONSTRAINT `fk_reliability_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- RELIABILITY_HISTORY
-- ============================================================
CREATE TABLE `reliability_history` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` INT UNSIGNED NOT NULL,
  `old_score` DECIMAL(5,2) DEFAULT NULL,
  `new_score` DECIMAL(5,2) NOT NULL,
  `old_tier` ENUM('new_member','restricted','standard_plus') DEFAULT NULL,
  `new_tier` ENUM('new_member','restricted','standard_plus') NOT NULL,
  `event_type` VARCHAR(50) NOT NULL,
  `related_booking_id` INT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_relhistory_customer` (`customer_id`),
  CONSTRAINT `fk_relhistory_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- AUDIT_LOG (immutable — no UPDATE/DELETE operations on this table)
-- ============================================================
CREATE TABLE `audit_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `actor_id` INT UNSIGNED DEFAULT NULL,
  `entity_type` VARCHAR(50) NOT NULL,
  `entity_id` INT UNSIGNED NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `details` JSON DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_entity` (`entity_type`, `entity_id`),
  KEY `idx_audit_actor` (`actor_id`),
  KEY `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- NOTIFICATIONS
-- ============================================================
CREATE TABLE `notifications` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `related_entity_id` INT UNSIGNED DEFAULT NULL,
  `related_entity_type` VARCHAR(50) DEFAULT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notif_user` (`user_id`),
  KEY `idx_notif_read` (`user_id`, `is_read`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- COACH_PROFILES
-- NIC document stored as photo only — no NIC number extracted or stored.
-- Verification is a binary admin flag. This is a deliberate data-retention
-- decision to avoid PII exposure. See CLAUDE.md coach module notes.
-- ============================================================
CREATE TABLE `coach_profiles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `bio` TEXT DEFAULT NULL,
  `specializations` VARCHAR(500) DEFAULT NULL,
  `nic_document_path` VARCHAR(500) DEFAULT NULL,
  `verification_status` ENUM('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coach_user` (`user_id`),
  CONSTRAINT `fk_coach_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- COACH_VENUE_APPROVALS
-- ============================================================
CREATE TABLE `coach_venue_approvals` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `coach_id` INT UNSIGNED NOT NULL,
  `venue_id` INT UNSIGNED NOT NULL,
  `status` ENUM('pending','approved','declined') NOT NULL DEFAULT 'pending',
  `decline_reason` VARCHAR(500) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coach_venue` (`coach_id`, `venue_id`),
  KEY `idx_cva_venue` (`venue_id`),
  CONSTRAINT `fk_cva_coach` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cva_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- COACH_SESSIONS
-- Uses the same court/time slot space as bookings.
-- Conflict checking must verify against both bookings AND coach_sessions.
-- ============================================================
CREATE TABLE `coach_sessions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `coach_id` INT UNSIGNED NOT NULL,
  `court_id` INT UNSIGNED NOT NULL,
  `venue_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `session_date` DATE NOT NULL,
  `session_start` TIME NOT NULL,
  `session_end` TIME NOT NULL,
  `capacity` INT UNSIGNED NOT NULL,
  `registered_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `price_per_student` DECIMAL(10,2) NOT NULL,
  `visibility` ENUM('public','private') NOT NULL DEFAULT 'public',
  `private_token` VARCHAR(64) DEFAULT NULL COMMENT 'Random unguessable token for private sessions',
  `status` ENUM('open','full','cancelled','completed') NOT NULL DEFAULT 'open',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_csess_coach` (`coach_id`),
  KEY `idx_csess_court` (`court_id`),
  KEY `idx_csess_venue` (`venue_id`),
  KEY `idx_csess_date` (`session_date`),
  KEY `idx_csess_token` (`private_token`),
  CONSTRAINT `fk_csess_coach` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_csess_court` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_csess_venue` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SESSION_REGISTRATIONS
-- ============================================================
CREATE TABLE `session_registrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` INT UNSIGNED NOT NULL,
  `customer_id` INT UNSIGNED NOT NULL,
  `payment_status` ENUM('pending','paid','refunded') NOT NULL DEFAULT 'pending',
  `attendance` ENUM('registered','attended','no_show') NOT NULL DEFAULT 'registered',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sessreg_customer` (`session_id`, `customer_id`),
  KEY `idx_sessreg_customer` (`customer_id`),
  CONSTRAINT `fk_sessreg_session` FOREIGN KEY (`session_id`) REFERENCES `coach_sessions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_sessreg_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- COACH_REVIEWS
-- Review submission requires attendance verification.
-- Reviewer must self-select a Parent or Player tag, enforced server-side.
-- Coach session attendance is separate from court-booking reliability.
-- ============================================================
CREATE TABLE `coach_reviews` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `coach_id` INT UNSIGNED NOT NULL,
  `customer_id` INT UNSIGNED NOT NULL,
  `session_id` INT UNSIGNED NOT NULL,
  `rating` TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT DEFAULT NULL,
  `reviewer_tag` ENUM('parent','player') NOT NULL,
  `coach_response` TEXT DEFAULT NULL,
  `coach_response_at` DATETIME DEFAULT NULL,
  `status` ENUM('active','removed') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_coachrev_session_customer` (`session_id`, `customer_id`),
  KEY `idx_coachrev_coach` (`coach_id`),
  CONSTRAINT `fk_coachrev_coach` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_coachrev_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_coachrev_session` FOREIGN KEY (`session_id`) REFERENCES `coach_sessions` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign keys for payments that reference later tables
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_resale` FOREIGN KEY (`resale_listing_id`) REFERENCES `resale_listings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_payments_sessreg` FOREIGN KEY (`coach_session_reg_id`) REFERENCES `session_registrations` (`id`) ON DELETE SET NULL;
