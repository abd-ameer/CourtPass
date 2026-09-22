-- ============================================================
-- CourtPass — Seed Data
-- Sample data for development and testing
-- ============================================================

USE `courtpass`;

-- ============================================================
-- ADMIN USER (password: admin123)
-- ============================================================
INSERT INTO `users` (`name`, `email`, `phone`, `password_hash`, `role`, `status`) VALUES
('System Admin', 'admin@courtpass.lk', '0771234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- ============================================================
-- VENUE OWNERS (password: owner123)
-- ============================================================
INSERT INTO `users` (`name`, `email`, `phone`, `password_hash`, `role`, `status`) VALUES
('Kamal Perera', 'kamal@sportshub.lk', '0772345678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', 'active'),
('Nimal Silva', 'nimal@courtzone.lk', '0773456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', 'active');

-- ============================================================
-- CUSTOMERS (password: customer123)
-- ============================================================
INSERT INTO `users` (`name`, `email`, `phone`, `password_hash`, `role`, `status`) VALUES
('Saman Fernando', 'saman@gmail.com', '0774567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active'),
('Ruwan Jayasinghe', 'ruwan@gmail.com', '0775678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active'),
('Dinesh Kumara', 'dinesh@gmail.com', '0776789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active');

-- ============================================================
-- COACH (password: coach123)
-- ============================================================
INSERT INTO `users` (`name`, `email`, `phone`, `password_hash`, `role`, `status`) VALUES
('Ashan Weerasinghe', 'ashan@coach.lk', '0777890123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'coach', 'active');

-- ============================================================
-- VENUES
-- ============================================================
INSERT INTO `venues` (`owner_id`, `name`, `description`, `address`, `city`, `phone`, `status`) VALUES
(2, 'Colombo Sports Hub', 'Premier indoor sports facility in Colombo with futsal, badminton, and table tennis courts. Air-conditioned, well-maintained, and open 7 days a week.', '45 Galle Road, Colombo 03', 'Colombo', '0112345678', 'approved'),
(2, 'Kandy Court Zone', 'Modern multi-sport facility near Kandy city center. Features squash courts, billiard tables, and a carrom lounge.', '12 Peradeniya Road, Kandy', 'Kandy', '0812345678', 'approved'),
(3, 'Galle Racquet Club', 'Badminton and pickleball center in the heart of Galle. Family-friendly atmosphere with coaching available.', '78 Matara Road, Galle', 'Galle', '0912345678', 'pending');

-- ============================================================
-- COURTS
-- ============================================================
INSERT INTO `courts` (`venue_id`, `name`, `sport_type`, `hourly_rate`, `status`) VALUES
-- Colombo Sports Hub
(1, 'Futsal Court A', 'futsal', 5000.00, 'active'),
(1, 'Futsal Court B', 'futsal', 5000.00, 'active'),
(1, 'Badminton Court 1', 'badminton', 2000.00, 'active'),
(1, 'Badminton Court 2', 'badminton', 2000.00, 'active'),
(1, 'Table Tennis Room', 'table_tennis', 1000.00, 'active'),
-- Kandy Court Zone
(2, 'Squash Court 1', 'squash', 3000.00, 'active'),
(2, 'Squash Court 2', 'squash', 3000.00, 'active'),
(2, 'Billiards Table 1', 'billiards', 1500.00, 'active'),
(2, 'Carrom Lounge', 'carrom', 800.00, 'active'),
-- Galle Racquet Club (pending venue)
(3, 'Badminton Court A', 'badminton', 1800.00, 'active'),
(3, 'Pickleball Court 1', 'pickleball', 2500.00, 'active');

-- ============================================================
-- OPERATING HOURS (Colombo Sports Hub — 7 days, 6 AM to 10 PM)
-- ============================================================
INSERT INTO `operating_hours` (`court_id`, `day_of_week`, `open_time`, `close_time`) VALUES
-- Futsal Court A (all days)
(1, 0, '06:00:00', '22:00:00'),
(1, 1, '06:00:00', '22:00:00'),
(1, 2, '06:00:00', '22:00:00'),
(1, 3, '06:00:00', '22:00:00'),
(1, 4, '06:00:00', '22:00:00'),
(1, 5, '06:00:00', '22:00:00'),
(1, 6, '06:00:00', '22:00:00'),
-- Futsal Court B (all days)
(2, 0, '06:00:00', '22:00:00'),
(2, 1, '06:00:00', '22:00:00'),
(2, 2, '06:00:00', '22:00:00'),
(2, 3, '06:00:00', '22:00:00'),
(2, 4, '06:00:00', '22:00:00'),
(2, 5, '06:00:00', '22:00:00'),
(2, 6, '06:00:00', '22:00:00'),
-- Badminton Court 1 (weekdays 7-21, weekends 8-20)
(3, 1, '07:00:00', '21:00:00'),
(3, 2, '07:00:00', '21:00:00'),
(3, 3, '07:00:00', '21:00:00'),
(3, 4, '07:00:00', '21:00:00'),
(3, 5, '07:00:00', '21:00:00'),
(3, 0, '08:00:00', '20:00:00'),
(3, 6, '08:00:00', '20:00:00'),
-- Badminton Court 2 (same as Court 1)
(4, 1, '07:00:00', '21:00:00'),
(4, 2, '07:00:00', '21:00:00'),
(4, 3, '07:00:00', '21:00:00'),
(4, 4, '07:00:00', '21:00:00'),
(4, 5, '07:00:00', '21:00:00'),
(4, 0, '08:00:00', '20:00:00'),
(4, 6, '08:00:00', '20:00:00'),
-- Table Tennis Room (all days 9-21)
(5, 0, '09:00:00', '21:00:00'),
(5, 1, '09:00:00', '21:00:00'),
(5, 2, '09:00:00', '21:00:00'),
(5, 3, '09:00:00', '21:00:00'),
(5, 4, '09:00:00', '21:00:00'),
(5, 5, '09:00:00', '21:00:00'),
(5, 6, '09:00:00', '21:00:00'),
-- Kandy: Squash Court 1 (Mon-Sat 7-21)
(6, 1, '07:00:00', '21:00:00'),
(6, 2, '07:00:00', '21:00:00'),
(6, 3, '07:00:00', '21:00:00'),
(6, 4, '07:00:00', '21:00:00'),
(6, 5, '07:00:00', '21:00:00'),
(6, 6, '07:00:00', '21:00:00'),
-- Kandy: Squash Court 2
(7, 1, '07:00:00', '21:00:00'),
(7, 2, '07:00:00', '21:00:00'),
(7, 3, '07:00:00', '21:00:00'),
(7, 4, '07:00:00', '21:00:00'),
(7, 5, '07:00:00', '21:00:00'),
(7, 6, '07:00:00', '21:00:00'),
-- Kandy: Billiards Table 1 (all days 10-22)
(8, 0, '10:00:00', '22:00:00'),
(8, 1, '10:00:00', '22:00:00'),
(8, 2, '10:00:00', '22:00:00'),
(8, 3, '10:00:00', '22:00:00'),
(8, 4, '10:00:00', '22:00:00'),
(8, 5, '10:00:00', '22:00:00'),
(8, 6, '10:00:00', '22:00:00'),
-- Kandy: Carrom Lounge (all days 10-20)
(9, 0, '10:00:00', '20:00:00'),
(9, 1, '10:00:00', '20:00:00'),
(9, 2, '10:00:00', '20:00:00'),
(9, 3, '10:00:00', '20:00:00'),
(9, 4, '10:00:00', '20:00:00'),
(9, 5, '10:00:00', '20:00:00'),
(9, 6, '10:00:00', '20:00:00');

-- ============================================================
-- RELIABILITY SCORES (initial entries for customers)
-- ============================================================
INSERT INTO `reliability_scores` (`customer_id`, `score`, `completed_bookings`, `no_shows`, `tier`) VALUES
(4, 100.00, 0, 0, 'new_member'),
(5, 100.00, 0, 0, 'new_member'),
(6, 100.00, 0, 0, 'new_member');

-- ============================================================
-- COACH PROFILE
-- ============================================================
INSERT INTO `coach_profiles` (`user_id`, `bio`, `specializations`, `verification_status`) VALUES
(7, 'Professional badminton coach with 10 years of experience. Former national-level player. Specializing in singles technique and footwork training.', 'badminton,footwork,singles', 'verified');

-- ============================================================
-- COACH VENUE APPROVAL (Ashan approved at Colombo Sports Hub)
-- ============================================================
INSERT INTO `coach_venue_approvals` (`coach_id`, `venue_id`, `status`) VALUES
(7, 1, 'approved');

-- ============================================================
-- SAMPLE BOOKINGS (various states for testing)
-- ============================================================
INSERT INTO `bookings` (`court_id`, `customer_id`, `slot_date`, `slot_start`, `slot_end`, `amount`, `payment_method`, `status`) VALUES
-- Saman: completed booking at Futsal Court A
(1, 4, CURDATE() - INTERVAL 7 DAY, '10:00:00', '11:00:00', 5000.00, 'online', 'completed'),
-- Saman: confirmed booking tomorrow at Badminton Court 1
(3, 4, CURDATE() + INTERVAL 1 DAY, '14:00:00', '15:00:00', 2000.00, 'online', 'confirmed'),
-- Ruwan: pending booking at Futsal Court B
(2, 5, CURDATE() + INTERVAL 2 DAY, '18:00:00', '19:00:00', 5000.00, 'online', 'pending'),
-- Dinesh: cancelled booking
(1, 6, CURDATE() - INTERVAL 3 DAY, '16:00:00', '17:00:00', 5000.00, 'online', 'cancelled');

-- ============================================================
-- AUDIT LOG ENTRIES for sample bookings
-- ============================================================
INSERT INTO `audit_log` (`actor_id`, `entity_type`, `entity_id`, `action`, `details`) VALUES
(4, 'booking', 1, 'booking_created', '{"court_id":1,"slot_date":"sample","amount":5000}'),
(4, 'booking', 1, 'booking_completed', '{"previous_status":"confirmed"}'),
(4, 'booking', 2, 'booking_created', '{"court_id":3,"slot_date":"sample","amount":2000}'),
(2, 'booking', 2, 'booking_confirmed', '{"confirmed_by":"owner"}'),
(5, 'booking', 3, 'booking_created', '{"court_id":2,"slot_date":"sample","amount":5000}'),
(6, 'booking', 4, 'booking_created', '{"court_id":1,"slot_date":"sample","amount":5000}'),
(6, 'booking', 4, 'booking_cancelled', '{"reason":"Schedule conflict","cancelled_by":6}');

-- ============================================================
-- SAMPLE PAYMENTS
-- ============================================================
INSERT INTO `payments` (`booking_id`, `payhere_order_id`, `amount`, `currency`, `status`) VALUES
(1, 'CP-BK-0001', 5000.00, 'LKR', 'completed'),
(2, 'CP-BK-0002', 2000.00, 'LKR', 'completed'),
(3, 'CP-BK-0003', 5000.00, 'LKR', 'pending');

-- ============================================================
-- SAMPLE ANNOUNCEMENT
-- ============================================================
INSERT INTO `announcements` (`venue_id`, `owner_id`, `type`, `title`, `content`) VALUES
(1, 2, 'promotional', 'Weekend Special! 🏸', 'Book any badminton court this weekend and get 20% off. Use code WEEKEND20 at checkout. Limited slots available!'),
(1, 2, 'operational', 'Maintenance Notice', 'Futsal Court B will be closed for maintenance on Monday. All bookings for that day have been rescheduled. We apologize for the inconvenience.');
