-- CourtPass database schema.
-- Run this file, then database/seed.sql. Loading it recreates the database.
-- Runs on MariaDB 10.4+ and MySQL 8.0+. All DATETIME values are Asia/Colombo time.
-- Table and column reference: docs/database-design.md

SET NAMES utf8mb4;
SET time_zone = '+05:30';

DROP DATABASE IF EXISTS courtpass;
CREATE DATABASE courtpass
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE courtpass;

-- 1. Accounts

CREATE TABLE users (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    role            ENUM('customer','owner','coach','admin') NOT NULL,
    name            VARCHAR(100)    NOT NULL,
    email           VARCHAR(191)    NOT NULL,
    phone           VARCHAR(20)     NULL,
    password_hash   VARCHAR(255)    NOT NULL,
    status          ENUM('active','deactivated') NOT NULL DEFAULT 'active',
    deactivated_at  DATETIME        NULL,
    deactivated_by  INT UNSIGNED    NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_id_role (id, role),
    KEY idx_users_role_status (role, status),
    CONSTRAINT fk_users_deactivated_by FOREIGN KEY (deactivated_by) REFERENCES users (id),
    CONSTRAINT chk_users_deactivation CHECK (
        (status = 'active' AND deactivated_at IS NULL)
        OR (status = 'deactivated' AND deactivated_at IS NOT NULL)
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1:1 with users. The (customer_id, role) FK restricts rows to customer accounts.
-- Score columns are a cache; bookings are the source of truth.
CREATE TABLE customer_profiles (
    customer_id             INT UNSIGNED    NOT NULL,
    role                    ENUM('customer','owner','coach','admin') NOT NULL DEFAULT 'customer',
    reliability_score       DECIMAL(5,2)    NULL,
    reliability_tier        ENUM('new_member','restricted','standard') NOT NULL DEFAULT 'new_member',
    completed_count         INT UNSIGNED    NOT NULL DEFAULT 0,
    no_show_count           INT UNSIGNED    NOT NULL DEFAULT 0,
    responsible_cancel_count    INT UNSIGNED NOT NULL DEFAULT 0,
    moderate_cancel_count       INT UNSIGNED NOT NULL DEFAULT 0,
    irresponsible_cancel_count  INT UNSIGNED NOT NULL DEFAULT 0,
    score_calculated_at     DATETIME        NULL,
    PRIMARY KEY (customer_id),
    CONSTRAINT fk_customer_profiles_user FOREIGN KEY (customer_id, role) REFERENCES users (id, role),
    CONSTRAINT chk_customer_profiles_role CHECK (role = 'customer'),
    CONSTRAINT chk_customer_profiles_score CHECK (reliability_score IS NULL OR reliability_score BETWEEN 0 AND 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sport_types (
    id      SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    code    VARCHAR(30)  NOT NULL,
    name    VARCHAR(50)  NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_sport_types_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1:1 with users. The (coach_id, role) FK restricts rows to coach accounts.
CREATE TABLE coach_profiles (
    coach_id            INT UNSIGNED    NOT NULL,
    role                ENUM('customer','owner','coach','admin') NOT NULL DEFAULT 'coach',
    bio                 TEXT            NULL,
    experience_level    ENUM('beginner','intermediate','advanced','professional') NOT NULL,
    certifications      TEXT            NULL,
    is_verified         TINYINT(1)      NOT NULL DEFAULT 0,
    verified_at         DATETIME        NULL,
    verified_by         INT UNSIGNED    NULL,
    PRIMARY KEY (coach_id),
    CONSTRAINT fk_coach_profiles_user FOREIGN KEY (coach_id, role) REFERENCES users (id, role),
    CONSTRAINT fk_coach_profiles_verified_by FOREIGN KEY (verified_by) REFERENCES users (id),
    CONSTRAINT chk_coach_profiles_role CHECK (role = 'coach'),
    CONSTRAINT chk_coach_profiles_verified CHECK (
        (is_verified = 0 AND verified_at IS NULL)
        OR (is_verified = 1 AND verified_at IS NOT NULL AND verified_by IS NOT NULL)
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE coach_sports (
    coach_id        INT UNSIGNED      NOT NULL,
    sport_type_id   SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (coach_id, sport_type_id),
    KEY idx_coach_sports_sport (sport_type_id),
    CONSTRAINT fk_coach_sports_coach FOREIGN KEY (coach_id) REFERENCES coach_profiles (coach_id),
    CONSTRAINT fk_coach_sports_sport FOREIGN KEY (sport_type_id) REFERENCES sport_types (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Venues and courts

-- status = admin approval lifecycle. is_active = owner temporary switch.
CREATE TABLE venues (
    id                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    owner_id            INT UNSIGNED    NOT NULL,
    name                VARCHAR(150)    NOT NULL,
    slug                VARCHAR(160)    NOT NULL,
    description         TEXT            NULL,
    address             VARCHAR(255)    NOT NULL,
    city                VARCHAR(100)    NOT NULL,
    contact_phone       VARCHAR(20)     NOT NULL,
    status              ENUM('pending','approved','rejected','deactivated') NOT NULL DEFAULT 'pending',
    rejection_reason    VARCHAR(500)    NULL,
    deactivation_reason VARCHAR(500)    NULL,
    reviewed_by         INT UNSIGNED    NULL,
    reviewed_at         DATETIME        NULL,
    is_active           TINYINT(1)      NOT NULL DEFAULT 1,
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_venues_slug (slug),
    KEY idx_venues_owner (owner_id),
    KEY idx_venues_status_city (status, city),
    CONSTRAINT fk_venues_owner FOREIGN KEY (owner_id) REFERENCES users (id),
    CONSTRAINT fk_venues_reviewed_by FOREIGN KEY (reviewed_by) REFERENCES users (id),
    CONSTRAINT chk_venues_rejection CHECK (status <> 'rejected' OR rejection_reason IS NOT NULL),
    CONSTRAINT chk_venues_deactivation CHECK (status <> 'deactivated' OR deactivation_reason IS NOT NULL),
    CONSTRAINT chk_venues_slug CHECK (slug REGEXP '^[a-z0-9]+(-[a-z0-9]+)*$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE venue_sports (
    venue_id        INT UNSIGNED      NOT NULL,
    sport_type_id   SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (venue_id, sport_type_id),
    KEY idx_venue_sports_sport (sport_type_id),
    CONSTRAINT fk_venue_sports_venue FOREIGN KEY (venue_id) REFERENCES venues (id),
    CONSTRAINT fk_venue_sports_sport FOREIGN KEY (sport_type_id) REFERENCES sport_types (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Court rows are locked (SELECT ... FOR UPDATE) before any booking, block or session insert.
CREATE TABLE courts (
    id              INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    venue_id        INT UNSIGNED      NOT NULL,
    name            VARCHAR(100)      NOT NULL,
    sport_type_id   SMALLINT UNSIGNED NOT NULL,
    hourly_rate     DECIMAL(10,2)     NOT NULL,
    is_active       TINYINT(1)        NOT NULL DEFAULT 1,
    created_at      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_courts_venue_name (venue_id, name),
    KEY idx_courts_sport (sport_type_id),
    CONSTRAINT fk_courts_venue FOREIGN KEY (venue_id) REFERENCES venues (id),
    CONSTRAINT fk_courts_sport FOREIGN KEY (sport_type_id) REFERENCES sport_types (id),
    CONSTRAINT chk_courts_rate CHECK (hourly_rate > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- day_of_week: 1 = Monday ... 7 = Sunday. Whole hours, close_time exclusive.
CREATE TABLE court_operating_hours (
    court_id        INT UNSIGNED     NOT NULL,
    day_of_week     TINYINT UNSIGNED NOT NULL,
    open_time       TIME             NOT NULL,
    close_time      TIME             NOT NULL,
    PRIMARY KEY (court_id, day_of_week),
    CONSTRAINT fk_court_hours_court FOREIGN KEY (court_id) REFERENCES courts (id),
    CONSTRAINT chk_court_hours_day CHECK (day_of_week BETWEEN 1 AND 7),
    CONSTRAINT chk_court_hours_whole CHECK (MINUTE(open_time) = 0 AND SECOND(open_time) = 0
                                        AND MINUTE(close_time) = 0 AND SECOND(close_time) = 0),
    CONSTRAINT chk_court_hours_range CHECK (open_time >= '00:00:00' AND close_time <= '24:00:00'
                                        AND close_time > open_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE court_blocks (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    court_id    INT UNSIGNED  NOT NULL,
    block_date  DATE          NOT NULL,
    start_time  TIME          NOT NULL,
    block_type  ENUM('owner','coaching') NOT NULL,
    reason      VARCHAR(255)  NULL,
    created_by  INT UNSIGNED  NOT NULL,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_court_blocks_slot (court_id, block_date, start_time),
    CONSTRAINT fk_court_blocks_court FOREIGN KEY (court_id) REFERENCES courts (id),
    CONSTRAINT fk_court_blocks_created_by FOREIGN KEY (created_by) REFERENCES users (id),
    CONSTRAINT chk_court_blocks_whole CHECK (MINUTE(start_time) = 0 AND SECOND(start_time) = 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE flash_slots (
    id                  INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    court_id            INT UNSIGNED   NOT NULL,
    slot_date           DATE           NOT NULL,
    start_time          TIME           NOT NULL,
    original_price      DECIMAL(10,2)  NOT NULL,
    discounted_price    DECIMAL(10,2)  NOT NULL,
    status              ENUM('active','booked','expired','withdrawn') NOT NULL DEFAULT 'active',
    created_by          INT UNSIGNED   NOT NULL,
    created_at          DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_flash_slots_slot (court_id, slot_date, start_time),
    KEY idx_flash_slots_status_date (status, slot_date),
    CONSTRAINT fk_flash_slots_court FOREIGN KEY (court_id) REFERENCES courts (id),
    CONSTRAINT fk_flash_slots_created_by FOREIGN KEY (created_by) REFERENCES users (id),
    CONSTRAINT chk_flash_slots_whole CHECK (MINUTE(start_time) = 0 AND SECOND(start_time) = 0),
    CONSTRAINT chk_flash_slots_price CHECK (discounted_price > 0 AND discounted_price < original_price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bookings

-- slot_lock is 1 while the booking holds the slot and NULL otherwise, so the unique
-- key blocks a second live booking on the same slot.
CREATE TABLE bookings (
    id                      INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    customer_id             INT UNSIGNED   NOT NULL,
    court_id                INT UNSIGNED   NOT NULL,
    slot_date               DATE           NOT NULL,
    start_time              TIME           NOT NULL,
    amount                  DECIMAL(10,2)  NOT NULL,
    flash_slot_id           INT UNSIGNED   NULL,
    payment_method          ENUM('online','cash_on_arrival') NOT NULL,
    status                  ENUM('pending_payment','pending','confirmed','released','resold',
                                 'completed','completed_unattended','no_show',
                                 'cancelled','rejected','expired') NOT NULL,
    slot_lock               TINYINT(1) AS (CASE WHEN status IN ('pending_payment','pending','confirmed',
                                 'completed','completed_unattended','no_show') THEN 1 ELSE NULL END) STORED,
    pending_expires_at      DATETIME       NULL,
    confirmed_at            DATETIME       NULL,
    released_at             DATETIME       NULL,
    resold_to_booking_id    INT UNSIGNED   NULL,
    cancelled_by            INT UNSIGNED   NULL,
    cancelled_at            DATETIME       NULL,
    cancel_reason           VARCHAR(500)   NULL,
    cancellation_class      ENUM('responsible','moderate','irresponsible') NULL,
    rejection_reason        VARCHAR(500)   NULL,
    created_at              DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_bookings_slot_lock (court_id, slot_date, start_time, slot_lock),
    UNIQUE KEY uq_bookings_resold_to (resold_to_booking_id),
    KEY idx_bookings_customer_status (customer_id, status),
    KEY idx_bookings_court_date (court_id, slot_date),
    KEY idx_bookings_status_date (status, slot_date),
    KEY idx_bookings_flash (flash_slot_id),
    CONSTRAINT fk_bookings_customer FOREIGN KEY (customer_id) REFERENCES customer_profiles (customer_id),
    CONSTRAINT fk_bookings_court FOREIGN KEY (court_id) REFERENCES courts (id),
    CONSTRAINT fk_bookings_flash FOREIGN KEY (flash_slot_id) REFERENCES flash_slots (id),
    CONSTRAINT fk_bookings_resold_to FOREIGN KEY (resold_to_booking_id) REFERENCES bookings (id),
    CONSTRAINT fk_bookings_cancelled_by FOREIGN KEY (cancelled_by) REFERENCES users (id),
    CONSTRAINT chk_bookings_whole CHECK (MINUTE(start_time) = 0 AND SECOND(start_time) = 0),
    CONSTRAINT chk_bookings_amount CHECK (amount > 0),
    CONSTRAINT chk_bookings_hold CHECK (status <> 'pending_payment' OR pending_expires_at IS NOT NULL),
    CONSTRAINT chk_bookings_pending_payment_online CHECK (status <> 'pending_payment' OR payment_method = 'online'),
    CONSTRAINT chk_bookings_release_online CHECK (status NOT IN ('released','resold') OR payment_method = 'online'),
    CONSTRAINT chk_bookings_resold CHECK ((status = 'resold') = (resold_to_booking_id IS NOT NULL)),
    CONSTRAINT chk_bookings_no_show_cash CHECK (status <> 'no_show' OR payment_method = 'cash_on_arrival'),
    CONSTRAINT chk_bookings_cancel_class CHECK (cancellation_class IS NULL
                                               OR (status = 'cancelled' AND payment_method = 'cash_on_arrival')),
    CONSTRAINT chk_bookings_rejected CHECK (status <> 'rejected' OR rejection_reason IS NOT NULL),
    CONSTRAINT chk_bookings_cancelled CHECK (status <> 'cancelled' OR cancelled_at IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE check_ins (
    booking_id      INT UNSIGNED NOT NULL,
    checked_in_by   INT UNSIGNED NOT NULL,
    checked_in_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (booking_id),
    CONSTRAINT fk_check_ins_booking FOREIGN KEY (booking_id) REFERENCES bookings (id),
    CONSTRAINT fk_check_ins_by FOREIGN KEY (checked_in_by) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- open_lock allows one open dispute per booking and type.
CREATE TABLE disputes (
    id              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    dispute_type    ENUM('no_show','resale') NOT NULL,
    booking_id      INT UNSIGNED  NOT NULL,
    raised_by       INT UNSIGNED  NOT NULL,
    reason          TEXT          NOT NULL,
    status          ENUM('open','upheld','dismissed') NOT NULL DEFAULT 'open',
    open_lock       TINYINT(1) AS (CASE WHEN status = 'open' THEN 1 ELSE NULL END) STORED,
    resolution_note TEXT          NULL,
    resolved_by     INT UNSIGNED  NULL,
    resolved_at     DATETIME      NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_disputes_open (booking_id, dispute_type, open_lock),
    KEY idx_disputes_status (status, dispute_type),
    CONSTRAINT fk_disputes_booking FOREIGN KEY (booking_id) REFERENCES bookings (id),
    CONSTRAINT fk_disputes_raised_by FOREIGN KEY (raised_by) REFERENCES customer_profiles (customer_id),
    CONSTRAINT fk_disputes_resolved_by FOREIGN KEY (resolved_by) REFERENCES users (id),
    CONSTRAINT chk_disputes_resolved CHECK (
        (status = 'open' AND resolved_at IS NULL)
        OR (status <> 'open' AND resolved_at IS NOT NULL AND resolved_by IS NOT NULL)
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Coach module

CREATE TABLE coach_venue_approvals (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    coach_id        INT UNSIGNED NOT NULL,
    venue_id        INT UNSIGNED NOT NULL,
    status          ENUM('pending','approved','declined','revoked') NOT NULL DEFAULT 'pending',
    decline_reason  VARCHAR(500) NULL,
    requested_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    decided_by      INT UNSIGNED NULL,
    decided_at      DATETIME     NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_coach_venue (coach_id, venue_id),
    KEY idx_coach_venue_venue_status (venue_id, status),
    CONSTRAINT fk_cva_coach FOREIGN KEY (coach_id) REFERENCES coach_profiles (coach_id),
    CONSTRAINT fk_cva_venue FOREIGN KEY (venue_id) REFERENCES venues (id),
    CONSTRAINT fk_cva_decided_by FOREIGN KEY (decided_by) REFERENCES users (id),
    CONSTRAINT chk_cva_decline CHECK (status <> 'declined' OR decline_reason IS NOT NULL),
    CONSTRAINT chk_cva_decided CHECK (status = 'pending' OR decided_at IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- block_id is cleared and the block deleted when the session is cancelled.
CREATE TABLE coach_sessions (
    id              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    coach_id        INT UNSIGNED  NOT NULL,
    court_id        INT UNSIGNED  NOT NULL,
    block_id        INT UNSIGNED  NULL,
    session_date    DATE          NOT NULL,
    start_time      TIME          NOT NULL,
    title           VARCHAR(100)  NOT NULL,
    description     TEXT          NULL,
    capacity        SMALLINT UNSIGNED NOT NULL,
    fee             DECIMAL(10,2) NOT NULL,
    visibility      ENUM('public','private') NOT NULL,
    access_token    CHAR(32)      NULL,
    status          ENUM('open','full','completed','cancelled') NOT NULL DEFAULT 'open',
    cancelled_by    INT UNSIGNED  NULL,
    cancel_reason   VARCHAR(500)  NULL,
    cancelled_at    DATETIME      NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_coach_sessions_block (block_id),
    UNIQUE KEY uq_coach_sessions_token (access_token),
    KEY idx_coach_sessions_coach_date (coach_id, session_date),
    KEY idx_coach_sessions_court_date (court_id, session_date),
    KEY idx_coach_sessions_listing (visibility, status, session_date),
    CONSTRAINT fk_coach_sessions_coach FOREIGN KEY (coach_id) REFERENCES coach_profiles (coach_id),
    CONSTRAINT fk_coach_sessions_court FOREIGN KEY (court_id) REFERENCES courts (id),
    CONSTRAINT fk_coach_sessions_block FOREIGN KEY (block_id) REFERENCES court_blocks (id),
    CONSTRAINT fk_coach_sessions_cancelled_by FOREIGN KEY (cancelled_by) REFERENCES users (id),
    CONSTRAINT chk_coach_sessions_whole CHECK (MINUTE(start_time) = 0 AND SECOND(start_time) = 0),
    CONSTRAINT chk_coach_sessions_capacity CHECK (capacity >= 1),
    CONSTRAINT chk_coach_sessions_fee CHECK (fee >= 0),
    CONSTRAINT chk_coach_sessions_token CHECK (
        (visibility = 'public' AND access_token IS NULL)
        OR (visibility = 'private' AND access_token IS NOT NULL AND access_token REGEXP '^[0-9a-f]{32}$')
    ),
    CONSTRAINT chk_coach_sessions_block CHECK (status = 'cancelled' OR block_id IS NOT NULL),
    CONSTRAINT chk_coach_sessions_cancelled CHECK (status <> 'cancelled' OR (cancelled_at IS NOT NULL AND block_id IS NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- active_lock allows one live registration per customer per session.
CREATE TABLE session_registrations (
    id                  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    session_id          INT UNSIGNED  NOT NULL,
    customer_id         INT UNSIGNED  NOT NULL,
    status              ENUM('pending_payment','registered','attended','absent','cancelled') NOT NULL DEFAULT 'pending_payment',
    active_lock         TINYINT(1) AS (CASE WHEN status <> 'cancelled' THEN 1 ELSE NULL END) STORED,
    amount              DECIMAL(10,2) NOT NULL,
    pending_expires_at  DATETIME      NULL,
    cancel_source       ENUM('customer','session_cancelled','payment_failed','payment_expired') NULL,
    cancelled_at        DATETIME      NULL,
    attendance_marked_at DATETIME     NULL,
    created_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_session_reg_active (session_id, customer_id, active_lock),
    KEY idx_session_reg_session_status (session_id, status),
    KEY idx_session_reg_customer (customer_id, status),
    CONSTRAINT fk_session_reg_session FOREIGN KEY (session_id) REFERENCES coach_sessions (id),
    CONSTRAINT fk_session_reg_customer FOREIGN KEY (customer_id) REFERENCES customer_profiles (customer_id),
    CONSTRAINT chk_session_reg_amount CHECK (amount >= 0),
    CONSTRAINT chk_session_reg_hold CHECK (status <> 'pending_payment' OR pending_expires_at IS NOT NULL),
    CONSTRAINT chk_session_reg_cancel CHECK ((status = 'cancelled') = (cancel_source IS NOT NULL AND cancelled_at IS NOT NULL)),
    CONSTRAINT chk_session_reg_attendance CHECK (status NOT IN ('attended','absent') OR attendance_marked_at IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Payments and refunds

-- order_id and payhere_payment_id are unique so repeated callbacks are safe.
CREATE TABLE payments (
    id                  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    purpose             ENUM('booking','conversion','registration') NOT NULL,
    booking_id          INT UNSIGNED  NULL,
    registration_id     INT UNSIGNED  NULL,
    order_id            VARCHAR(50)   NOT NULL,
    amount              DECIMAL(10,2) NOT NULL,
    currency            CHAR(3)       NOT NULL DEFAULT 'LKR',
    status              ENUM('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
    payhere_payment_id  VARCHAR(50)   NULL,
    payhere_status_code SMALLINT      NULL,
    payhere_method      VARCHAR(20)   NULL,
    raw_notify          TEXT          NULL,
    created_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    paid_at             DATETIME      NULL,
    updated_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_payments_order (order_id),
    UNIQUE KEY uq_payments_payhere_id (payhere_payment_id),
    KEY idx_payments_booking (booking_id),
    KEY idx_payments_registration (registration_id),
    KEY idx_payments_status (status),
    CONSTRAINT fk_payments_booking FOREIGN KEY (booking_id) REFERENCES bookings (id),
    CONSTRAINT fk_payments_registration FOREIGN KEY (registration_id) REFERENCES session_registrations (id),
    CONSTRAINT chk_payments_target CHECK (
        (purpose IN ('booking','conversion') AND booking_id IS NOT NULL AND registration_id IS NULL)
        OR (purpose = 'registration' AND registration_id IS NOT NULL AND booking_id IS NULL)
    ),
    CONSTRAINT chk_payments_amount CHECK (amount > 0),
    CONSTRAINT chk_payments_paid CHECK (status <> 'paid' OR (paid_at IS NOT NULL AND payhere_payment_id IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- One refund per payment.
CREATE TABLE refunds (
    id              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    payment_id      INT UNSIGNED  NOT NULL,
    reason          ENUM('customer_cancel','owner_cancel','owner_reject','resale',
                         'session_cancelled','registration_cancel','venue_deactivated',
                         'coach_deactivated','late_payment') NOT NULL,
    percentage      DECIMAL(5,2)  NOT NULL,
    amount          DECIMAL(10,2) NOT NULL,
    fee_amount      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_by      INT UNSIGNED  NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_refunds_payment (payment_id),
    CONSTRAINT fk_refunds_payment FOREIGN KEY (payment_id) REFERENCES payments (id),
    CONSTRAINT fk_refunds_created_by FOREIGN KEY (created_by) REFERENCES users (id),
    CONSTRAINT chk_refunds_percentage CHECK (percentage > 0 AND percentage <= 100),
    CONSTRAINT chk_refunds_amount CHECK (amount > 0 AND fee_amount >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Community

-- Venue reviews set venue_id + booking_id, coach reviews set coach_id + registration_id.
CREATE TABLE reviews (
    id              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    reviewer_id     INT UNSIGNED     NOT NULL,
    venue_id        INT UNSIGNED     NULL,
    booking_id      INT UNSIGNED     NULL,
    coach_id        INT UNSIGNED     NULL,
    registration_id INT UNSIGNED     NULL,
    rating          TINYINT UNSIGNED NOT NULL,
    comment         TEXT             NULL,
    response_text   TEXT             NULL,
    responded_by    INT UNSIGNED     NULL,
    responded_at    DATETIME         NULL,
    status          ENUM('active','removed') NOT NULL DEFAULT 'active',
    removed_by      INT UNSIGNED     NULL,
    removed_reason  VARCHAR(500)     NULL,
    created_at      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_reviews_booking (booking_id),
    UNIQUE KEY uq_reviews_registration (registration_id),
    KEY idx_reviews_venue (venue_id, status),
    KEY idx_reviews_coach (coach_id, status),
    KEY idx_reviews_reviewer (reviewer_id),
    CONSTRAINT fk_reviews_reviewer FOREIGN KEY (reviewer_id) REFERENCES customer_profiles (customer_id),
    CONSTRAINT fk_reviews_venue FOREIGN KEY (venue_id) REFERENCES venues (id),
    CONSTRAINT fk_reviews_booking FOREIGN KEY (booking_id) REFERENCES check_ins (booking_id),
    CONSTRAINT fk_reviews_coach FOREIGN KEY (coach_id) REFERENCES coach_profiles (coach_id),
    CONSTRAINT fk_reviews_registration FOREIGN KEY (registration_id) REFERENCES session_registrations (id),
    CONSTRAINT fk_reviews_responded_by FOREIGN KEY (responded_by) REFERENCES users (id),
    CONSTRAINT fk_reviews_removed_by FOREIGN KEY (removed_by) REFERENCES users (id),
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5),
    CONSTRAINT chk_reviews_target CHECK (
        (venue_id IS NOT NULL AND booking_id IS NOT NULL AND coach_id IS NULL AND registration_id IS NULL)
        OR (coach_id IS NOT NULL AND registration_id IS NOT NULL AND venue_id IS NULL AND booking_id IS NULL)
    ),
    CONSTRAINT chk_reviews_response CHECK ((response_text IS NULL) = (responded_at IS NULL)),
    CONSTRAINT chk_reviews_removed CHECK (status <> 'removed' OR removed_by IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- An owner reports a review to the admin. open_lock allows one open flag per review.
CREATE TABLE review_flags (
    id              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    review_id       INT UNSIGNED  NOT NULL,
    flagged_by      INT UNSIGNED  NOT NULL,
    reason          VARCHAR(500)  NOT NULL,
    status          ENUM('open','dismissed','upheld') NOT NULL DEFAULT 'open',
    open_lock       TINYINT(1) AS (CASE WHEN status = 'open' THEN 1 ELSE NULL END) STORED,
    resolved_by     INT UNSIGNED  NULL,
    resolved_at     DATETIME      NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_review_flags_open (review_id, open_lock),
    KEY idx_review_flags_status (status),
    CONSTRAINT fk_review_flags_review FOREIGN KEY (review_id) REFERENCES reviews (id),
    CONSTRAINT fk_review_flags_by FOREIGN KEY (flagged_by) REFERENCES users (id),
    CONSTRAINT fk_review_flags_resolved_by FOREIGN KEY (resolved_by) REFERENCES users (id),
    CONSTRAINT chk_review_flags_resolved CHECK (
        (status = 'open' AND resolved_at IS NULL AND resolved_by IS NULL)
        OR (status <> 'open' AND resolved_at IS NOT NULL AND resolved_by IS NOT NULL)
    )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE announcements (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    venue_id    INT UNSIGNED  NOT NULL,
    posted_by   INT UNSIGNED  NOT NULL,
    type        ENUM('operational','promotional') NOT NULL,
    title       VARCHAR(150)  NOT NULL,
    body        TEXT          NOT NULL,
    status      ENUM('active','removed') NOT NULL DEFAULT 'active',
    removed_by  INT UNSIGNED  NULL,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_announcements_venue (venue_id, status, created_at),
    CONSTRAINT fk_announcements_venue FOREIGN KEY (venue_id) REFERENCES venues (id),
    CONSTRAINT fk_announcements_posted_by FOREIGN KEY (posted_by) REFERENCES users (id),
    CONSTRAINT fk_announcements_removed_by FOREIGN KEY (removed_by) REFERENCES users (id),
    CONSTRAINT chk_announcements_removed CHECK (status <> 'removed' OR removed_by IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Platform services

CREATE TABLE notifications (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED  NOT NULL,
    type        VARCHAR(50)   NOT NULL,
    title       VARCHAR(150)  NOT NULL,
    message     VARCHAR(500)  NOT NULL,
    link_url    VARCHAR(255)  NULL,
    is_read     TINYINT(1)    NOT NULL DEFAULT 0,
    read_at     DATETIME      NULL,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_notifications_user_read (user_id, is_read, created_at),
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE audit_log (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    actor_id    INT UNSIGNED    NULL,
    event_type  VARCHAR(60)     NOT NULL,
    entity_type VARCHAR(40)     NOT NULL,
    entity_id   INT UNSIGNED    NOT NULL,
    old_status  VARCHAR(30)     NULL,
    new_status  VARCHAR(30)     NULL,
    details     TEXT            NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_audit_entity (entity_type, entity_id),
    KEY idx_audit_actor (actor_id),
    KEY idx_audit_event_time (event_type, created_at),
    CONSTRAINT fk_audit_actor FOREIGN KEY (actor_id) REFERENCES users (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER //
CREATE TRIGGER trg_audit_log_no_update BEFORE UPDATE ON audit_log
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'audit_log is append-only';
END//

CREATE TRIGGER trg_audit_log_no_delete BEFORE DELETE ON audit_log
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'audit_log is append-only';
END//
DELIMITER ;
