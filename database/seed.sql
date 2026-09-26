-- CourtPass seed data. Run after database/schema.sql.
-- Dates are relative to the day the file is loaded.
--
-- Accounts (email / password):
--   admin@courtpass.lk   Admin@123     admin
--   kamal@sportshub.lk   Owner@123     owner, two approved venues
--   nimal@courtzone.lk   Owner@123     owner, one pending venue
--   saman@gmail.com      Customer@123  customer, standard tier
--   ruwan@gmail.com      Customer@123  customer, new member
--   dinesh@gmail.com     Customer@123  customer, restricted tier
--   ashan@coach.lk       Coach@123     coach, verified
--   dilani@coach.lk      Coach@123     coach, request pending

SET NAMES utf8mb4;
SET time_zone = '+05:30';
USE courtpass;

-- Sport types
INSERT INTO sport_types (id, code, name) VALUES
    (1, 'futsal',       'Futsal'),
    (2, 'badminton',    'Badminton'),
    (3, 'pickleball',   'Pickleball'),
    (4, 'squash',       'Squash'),
    (5, 'billiards',    'Billiards'),
    (6, 'carrom',       'Carrom'),
    (7, 'table_tennis', 'Table Tennis');

-- Users and profiles
INSERT INTO users (id, role, name, email, phone, password_hash) VALUES
    (1, 'admin',    'System Admin',      'admin@courtpass.lk', '0771234567', '$2y$12$TEqvQbcv7hdXUJvXIJzBRuU/hi/PJiVORbQkSlfpFDo0r7kvM1Hf2'),
    (2, 'owner',    'Kamal Perera',      'kamal@sportshub.lk', '0772345678', '$2y$12$B3KXAQHvdgXghpAanoIvTeHqu4Cy.rHrzKeoA3hVErpMwSumYzcZa'),
    (3, 'owner',    'Nimal Silva',       'nimal@courtzone.lk', '0773456789', '$2y$12$B3KXAQHvdgXghpAanoIvTeHqu4Cy.rHrzKeoA3hVErpMwSumYzcZa'),
    (4, 'customer', 'Saman Fernando',    'saman@gmail.com',    '0774567890', '$2y$12$tqdjvBKJIAowVhWEfKT0Puo9Tn4988sR7wbI2bQnVzsSeQQLVXBVW'),
    (5, 'customer', 'Ruwan Jayasinghe',  'ruwan@gmail.com',    '0775678901', '$2y$12$tqdjvBKJIAowVhWEfKT0Puo9Tn4988sR7wbI2bQnVzsSeQQLVXBVW'),
    (6, 'customer', 'Dinesh Kumara',     'dinesh@gmail.com',   '0776789012', '$2y$12$tqdjvBKJIAowVhWEfKT0Puo9Tn4988sR7wbI2bQnVzsSeQQLVXBVW'),
    (7, 'coach',    'Ashan Weerasinghe', 'ashan@coach.lk',     '0777890123', '$2y$12$vv/cGZi/Fq/JJV7GWFkMlOAHEXlgNaS4kNzf60i1KmDJ/H2dR0Vfe'),
    (8, 'coach',    'Dilani Rathnayake', 'dilani@coach.lk',    '0778901234', '$2y$12$vv/cGZi/Fq/JJV7GWFkMlOAHEXlgNaS4kNzf60i1KmDJ/H2dR0Vfe');

INSERT INTO customer_profiles (customer_id, reliability_score, reliability_tier,
        completed_count, no_show_count, responsible_cancel_count,
        moderate_cancel_count, irresponsible_cancel_count, score_calculated_at) VALUES
    (4, 92.00, 'standard',   7, 0, 1, 0, 0, NOW()),
    (5, NULL,  'new_member', 1, 0, 0, 0, 0, NOW()),
    (6, 55.00, 'restricted', 6, 2, 0, 1, 1, NOW());

INSERT INTO coach_profiles (coach_id, bio, experience_level, certifications,
        is_verified, verified_at, verified_by) VALUES
    (7, 'Former national-level badminton player. Coaching juniors and adults for 8 years.',
        'professional', 'BWF Level 1 Coach', 1, NOW(), 1),
    (8, 'Futsal coach focusing on school teams and beginners.',
        'intermediate', NULL, 0, NULL, NULL);

INSERT INTO coach_sports (coach_id, sport_type_id) VALUES
    (7, 2), (7, 3),
    (8, 1);

-- Venues, courts and operating hours
INSERT INTO venues (id, owner_id, name, slug, description, address, city, contact_phone,
        status, reviewed_by, reviewed_at) VALUES
    (1, 2, 'Colombo Sports Hub', 'colombo-sports-hub',
        'Indoor futsal, badminton and table tennis courts. Air-conditioned, open 7 days.',
        '45 Galle Road, Colombo 03', 'Colombo', '0112345678', 'approved', 1, NOW()),
    (2, 2, 'Kandy Court Zone', 'kandy-court-zone',
        'Squash courts, billiard tables and a carrom lounge near Kandy town.',
        '12 Peradeniya Road, Kandy', 'Kandy', '0812345678', 'approved', 1, NOW()),
    (3, 3, 'Galle Racquet Club', 'galle-racquet-club',
        'Badminton and pickleball centre in Galle.',
        '78 Matara Road, Galle', 'Galle', '0912345678', 'pending', NULL, NULL);

INSERT INTO venue_sports (venue_id, sport_type_id) VALUES
    (1, 1), (1, 2), (1, 7),
    (2, 4), (2, 5), (2, 6),
    (3, 2), (3, 3);

INSERT INTO courts (id, venue_id, name, sport_type_id, hourly_rate) VALUES
    (1, 1, 'Futsal Court A',    1, 5000.00),
    (2, 1, 'Futsal Court B',    1, 5000.00),
    (3, 1, 'Badminton Court 1', 2, 2000.00),
    (4, 1, 'Badminton Court 2', 2, 2000.00),
    (5, 1, 'Table Tennis Room', 7, 1000.00),
    (6, 2, 'Squash Court 1',    4, 3000.00),
    (7, 2, 'Billiards Table 1', 5, 1500.00),
    (8, 2, 'Carrom Lounge',     6,  800.00);

INSERT INTO court_operating_hours (court_id, day_of_week, open_time, close_time)
SELECT c.id, d.dow,
       CASE WHEN d.dow <= 5 THEN '06:00:00' ELSE '08:00:00' END,
       CASE WHEN d.dow <= 5 THEN '22:00:00' ELSE '23:00:00' END
FROM courts c
CROSS JOIN (SELECT 1 AS dow UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
            UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7) d;

-- Owner block and flash deal
INSERT INTO court_blocks (id, court_id, block_date, start_time, block_type, reason, created_by) VALUES
    (1, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '18:00:00', 'owner', 'School tournament', 2);

INSERT INTO flash_slots (id, court_id, slot_date, start_time, original_price, discounted_price, created_by) VALUES
    (1, 4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:00:00', 2000.00, 1400.00, 2);

-- Bookings and check-in
INSERT INTO bookings (id, customer_id, court_id, slot_date, start_time, amount, payment_method,
        status, confirmed_at, released_at, cancelled_by, cancelled_at, cancel_reason, cancellation_class) VALUES
    (1, 4, 3, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '17:00:00', 2000.00, 'online',
        'completed', DATE_SUB(NOW(), INTERVAL 5 DAY), NULL, NULL, NULL, NULL, NULL),
    (2, 6, 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '19:00:00', 5000.00, 'cash_on_arrival',
        'no_show', DATE_SUB(NOW(), INTERVAL 4 DAY), NULL, NULL, NULL, NULL, NULL),
    (3, 4, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '19:00:00', 5000.00, 'online',
        'confirmed', NOW(), NULL, NULL, NULL, NULL, NULL),
    (4, 4, 6, DATE_ADD(CURDATE(), INTERVAL 4 DAY), '10:00:00', 3000.00, 'cash_on_arrival',
        'pending', NULL, NULL, NULL, NULL, NULL, NULL),
    (5, 5, 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '20:00:00', 2000.00, 'online',
        'released', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW(), NULL, NULL, NULL, NULL),
    (6, 6, 5, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '16:00:00', 1000.00, 'cash_on_arrival',
        'cancelled', DATE_SUB(NOW(), INTERVAL 3 DAY), NULL, 6, DATE_SUB(NOW(), INTERVAL 1 DAY),
        'Could not make it', 'irresponsible');

INSERT INTO check_ins (booking_id, checked_in_by, checked_in_at) VALUES
    (1, 2, TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL 3 DAY), '16:55:00'));

-- Coach module
INSERT INTO coach_venue_approvals (id, coach_id, venue_id, status, requested_at, decided_by, decided_at) VALUES
    (1, 7, 1, 'approved', DATE_SUB(NOW(), INTERVAL 10 DAY), 2, DATE_SUB(NOW(), INTERVAL 9 DAY)),
    (2, 8, 1, 'pending',  DATE_SUB(NOW(), INTERVAL 1 DAY),  NULL, NULL);

INSERT INTO court_blocks (id, court_id, block_date, start_time, block_type, reason, created_by) VALUES
    (2, 4, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '08:00:00', 'coaching', NULL, 7),
    (3, 4, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '08:00:00', 'coaching', NULL, 7),
    (4, 3, DATE_ADD(CURDATE(), INTERVAL 6 DAY), '08:00:00', 'coaching', NULL, 7);

INSERT INTO coach_sessions (id, coach_id, court_id, block_id, session_date, start_time, title,
        description, capacity, fee, visibility, access_token, status) VALUES
    (1, 7, 4, 2, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '08:00:00', 'Beginner Badminton Basics',
        'Grip, footwork and basic strokes.', 6, 1500.00, 'public', NULL, 'completed'),
    (2, 7, 4, 3, DATE_ADD(CURDATE(), INTERVAL 5 DAY), '08:00:00', 'Intermediate Rally Drills',
        'Consistency and shot placement drills.', 4, 1800.00, 'public', NULL, 'open'),
    (3, 7, 3, 4, DATE_ADD(CURDATE(), INTERVAL 6 DAY), '08:00:00', 'Private Family Session',
        'Private session for one family.', 3, 2500.00, 'private', '9f2c4e6a8b0d1f3e5a7c9e1b3d5f7a90', 'open');

INSERT INTO session_registrations (id, session_id, customer_id, status, amount, attendance_marked_at) VALUES
    (1, 1, 4, 'attended',   1500.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
    (2, 1, 5, 'absent',     1500.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
    (3, 2, 4, 'registered', 1800.00, NULL);

-- Payments
INSERT INTO payments (id, purpose, booking_id, registration_id, order_id, amount, status,
        payhere_payment_id, payhere_status_code, payhere_method, paid_at) VALUES
    (1, 'booking',      1, NULL, 'BKG-1',  2000.00, 'paid', '320027150501', 2, 'VISA', DATE_SUB(NOW(), INTERVAL 5 DAY)),
    (2, 'booking',      3, NULL, 'BKG-3',  5000.00, 'paid', '320027150502', 2, 'VISA', NOW()),
    (3, 'booking',      5, NULL, 'BKG-5',  2000.00, 'paid', '320027150503', 2, 'MASTER', DATE_SUB(NOW(), INTERVAL 1 DAY)),
    (4, 'registration', NULL, 1, 'REG-1',  1500.00, 'paid', '320027150504', 2, 'VISA', DATE_SUB(NOW(), INTERVAL 6 DAY)),
    (5, 'registration', NULL, 2, 'REG-2',  1500.00, 'paid', '320027150505', 2, 'VISA', DATE_SUB(NOW(), INTERVAL 6 DAY)),
    (6, 'registration', NULL, 3, 'REG-3',  1800.00, 'paid', '320027150506', 2, 'VISA', NOW());

-- Reviews, announcements, notifications and audit entries
INSERT INTO reviews (id, reviewer_id, venue_id, booking_id, rating, comment, response_text, responded_by, responded_at) VALUES
    (1, 4, 1, 1, 5, 'Clean courts and staff were helpful.', 'Thank you, see you again!', 2, NOW());

INSERT INTO reviews (id, reviewer_id, coach_id, registration_id, rating, comment) VALUES
    (2, 4, 7, 1, 4, 'Very clear explanations, good for beginners.');

INSERT INTO announcements (id, venue_id, posted_by, type, title, body) VALUES
    (1, 1, 2, 'operational', 'Court B maintenance', 'Futsal Court B will get new turf next week.'),
    (2, 1, 2, 'promotional', 'Weekday morning discount', 'Book before 10 am on weekdays and look out for flash deals.');

INSERT INTO notifications (user_id, type, title, message, link_url) VALUES
    (4, 'booking_confirmed', 'Booking confirmed', 'Your Futsal Court A booking is confirmed.', '/bookings/3'),
    (7, 'review_posted',     'New review',        'You received a new 4-star review.',       '/coach/dashboard'),
    (2, 'review_posted',     'New review',        'Colombo Sports Hub received a 5-star review.', '/owner/reviews');

INSERT INTO audit_log (actor_id, event_type, entity_type, entity_id, old_status, new_status) VALUES
    (1, 'VENUE_APPROVED',    'venue',   1, 'pending',   'approved'),
    (1, 'VENUE_APPROVED',    'venue',   2, 'pending',   'approved'),
    (4, 'BOOKING_CREATED',   'booking', 3, NULL,        'pending_payment'),
    (NULL, 'BOOKING_CONFIRMED', 'booking', 3, 'pending_payment', 'confirmed'),
    (2, 'CHECK_IN_RECORDED', 'booking', 1, 'confirmed', 'completed'),
    (NULL, 'NO_SHOW_DETECTED', 'booking', 2, 'confirmed', 'no_show'),
    (5, 'BOOKING_RELEASED',  'booking', 5, 'confirmed', 'released');
