# 🏟️ CourtPass — Sports Venue Booking & Community Platform

**CourtPass** is a web-based sports venue booking and community platform for the Sri Lankan indoor recreational sports market (futsal, badminton, pickleball, squash, billiards, carrom, table tennis). It connects venue owners with customers for slot-based court booking, and features a reliability/trust scoring engine, a resale marketplace with a server-enforced price cap, flash deals, a coach-led training session module, and a community layer (Sports Diary, reviews, leaderboards).

Built for **UCSC Academic Capstone Project (Group 40)** under strict tech constraints: Vanilla HTML/CSS/JS, Plain PHP (no frameworks), MySQL (`mysqli`), and native session authentication.

---

## 📋 Prerequisites

To run CourtPass locally, you need:
- **XAMPP** (or any LAMP/WAMP stack with PHP 8.1+ and MySQL/MariaDB)
- **Apache Web Server** with `mod_rewrite` enabled
- **MySQL Database Server**
- Web Browser (Chrome, Firefox, Edge, Safari)
- *(Optional)* **PHPUnit** for running automated unit tests

---

## 🚀 Step-by-Step Setup Guide

### Step 1: Place Project Files in XAMPP `htdocs`

Ensure the `courtpass` project folder is located inside your XAMPP `htdocs` directory:
```text
C:\xampp\htdocs\courtpass
```
*(Or your web server's root folder)*

---

### Step 2: Start Apache and MySQL

1. Open the **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

---

### Step 3: Create & Seed the Database

1. Open your browser and navigate to **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click on **Databases** tab and create a new database named `courtpass` (utf8mb4_unicode_ci).
3. Click on the `courtpass` database.
4. Click on the **Import** tab:
   - Choose file: `schema/courtpass.sql` and click **Import** (creates all 17 tables).
   - Choose file: `schema/seed.sql` and click **Import** (populates initial test users, venues, courts, operating hours, and bookings).

*Alternatively, via MySQL CLI:*
```bash
mysql -u root -p -e "CREATE DATABASE courtpass CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p courtpass < schema/courtpass.sql
mysql -u root -p courtpass < schema/seed.sql
```

---

### Step 4: Configure `config.php`

1. Duplicate `config.php.example` and rename it to `config.php` in the project root:
   ```bash
   cp config.php.example config.php
   ```
2. Open `config.php` in your editor and verify/adjust the settings:
   ```php
   // Database Configuration
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Default XAMPP password is empty
   define('DB_NAME', 'courtpass');

   // Base Application URL
   define('BASE_URL', 'http://localhost/courtpass');

   // PayHere Sandbox Credentials (Optional for local testing)
   define('PAYHERE_MERCHANT_ID', 'YOUR_SANDBOX_MERCHANT_ID');
   define('PAYHERE_MERCHANT_SECRET', 'YOUR_SANDBOX_MERCHANT_SECRET');
   define('PAYHERE_SANDBOX_URL', 'https://sandbox.payhere.lk/pay/checkout');
   ```

---

### Step 5: Enable Apache URL Rewriting (`mod_rewrite`)

CourtPass uses clean URLs routed via `.htaccess`. Ensure Apache's `mod_rewrite` module is enabled in `xampp/apache/conf/httpd.conf`:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```
Ensure `AllowOverride All` is set for your directory in `httpd.conf`.

---

### Step 6: Launch the App in Browser

Open your browser and visit:
👉 **[http://localhost/courtpass](http://localhost/courtpass)**

---

## 🔑 Pre-configured Test Accounts

The seed database includes accounts for all 4 human roles (passwords shown below):

| Role | Email | Password | Description |
|---|---|---|---|
| **Platform Admin** | `admin@courtpass.lk` | `admin123` | Approves venues, verifies coaches, clears disputes, deactivates users |
| **Venue Owner** | `kamal@sportshub.lk` | `owner123` | Manages Colombo Sports Hub & Kandy Court Zone, confirms bookings |
| **Player / Customer** | `saman@gmail.com` | `customer123` | Books courts, views Sports Diary, lists for resale |
| **Coach** | `ashan@coach.lk` | `coach123` | Verified coach, hosts badminton training sessions |

---

## 🧪 Running Unit Tests

CourtPass includes PHPUnit test suites for the **Booking Conflict Engine** and **Reliability Scoring System**.

To run the unit tests:
```bash
# Booking Conflict Engine Tests
php vendor/bin/phpunit tests/BookingConflictTest.php

# Reliability Scoring Tests
php vendor/bin/phpunit tests/ReliabilityScoringTest.php
```

---

## 📁 Directory Structure

```text
courtpass/
├── README.md                   # Setup & execution guide
├── CLAUDE.md                   # Project requirements & build prompt
├── config.php.example          # Configuration template
├── config.php                  # Environment configuration (gitignored)
├── .htaccess                   # Apache URL rewrite rules
├── index.php                   # Front controller / router
├── schema/
│   ├── courtpass.sql           # Database schema (17 tables)
│   └── seed.sql                # Seed data for testing
├── includes/
│   ├── db.php                  # Mysqli singleton connection
│   ├── auth.php                # Session management & trigger-on-action checks
│   ├── helpers.php             # CSRF tokens, sanitization, date helpers
│   ├── audit.php               # Immutable audit log writer
│   └── notifications.php       # Inline notification helper
├── api/                        # JSON API endpoints (called via fetch())
│   ├── auth/                   # Login, register, logout, me
│   ├── venues/                 # Venue CRUD & list endpoints
│   ├── courts/                 # Court management & slot generator
│   ├── bookings/               # Booking state machine & cancellation policy
│   ├── payments/               # PayHere sandbox callback handlers
│   ├── resale/                 # Resale marketplace with 90% price cap
│   ├── flash/                  # Flash slot deals
│   ├── reliability/            # Score engine & dispute history
│   ├── coach/                  # Coach sessions, NIC upload, reviews
│   ├── admin/                  # Admin venue approval, coach verify, disputes
│   ├── community/              # Sports diary & leaderboard
│   └── dashboard/              # Owner analytics & customer intelligence
├── pages/                      # HTML template views
│   ├── home.php                # Landing page
│   ├── login.php / register.php# Auth views
│   ├── venues/                 # Venue browse & detail
│   ├── bookings/               # Booking picker & customer dashboard
│   ├── resale/                 # Resale marketplace view
│   ├── flash/                  # Flash deals view
│   ├── community/              # Sports diary & leaderboard views
│   ├── coach/                  # Coach dashboard & session management
│   ├── dashboard/              # Owner dashboard with Chart.js heatmap
│   └── admin/                  # Admin panel
├── assets/
│   ├── css/style.css           # Vanilla CSS design system
│   └── js/app.js               # Vanilla JS utilities & CSRF fetch wrapper
├── tests/                      # PHPUnit test suites
└── docs/                       # Markdown user guides & ERD diagram
```

---

## 🛡️ Key System Architecture Highlights

1. **Database-Level Conflict Checking:** `SELECT ... FOR UPDATE` inside MySQL transactions combined with a `UNIQUE` constraint on `(court_id, slot_date, slot_start, status)` prevents double-booking under concurrent traffic.
2. **Server-Side Resale Price Cap:** Resale listing prices are strictly capped at **90% of the original booking price** on the server.
3. **Trigger-on-Action No-Show Engine:** No-show detection and reliability score updates execute automatically on customer login or booking attempts (no cron jobs required).
4. **PII Privacy Compliance:** Coach identity verification stores only photo document artifacts without extracting or storing raw NIC numbers.
5. **Sanctioned Chart.js Integration:** The owner dashboard features interactive court utilization heatmaps powered by Chart.js.
