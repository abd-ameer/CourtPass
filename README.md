# CourtPass

**Sports Venue Booking & Community Platform**
UCSC 2nd Year Group Project, Group 40

CourtPass is a web platform for the Sri Lankan indoor sports market (futsal, badminton, pickleball, squash, billiards, carrom, table tennis). Customers find venues and book one-hour court slots online instead of calling or messaging owners. Venue owners manage their courts and bookings, and independent coaches run paid coaching sessions at approved venues. A reliability score rewards customers who show up and limits those who don't.

## Features

- **Venues and courts:** venue registration with admin approval, courts with operating hours and fixed one-hour slots
- **Booking:** slot availability grid, conflict-free booking, owner confirmation and a tiered cancellation policy
- **Reliability and trust:** reliability score and tiers, cash-on-arrival for reliable customers, no-show tracking
- **Payments:** online payments through PayHere Sandbox
- **Resale and flash deals:** resell a booking at up to 90% of its price, discounted last-minute slots
- **Coaching:** coach profiles, venue approval, public and private sessions with paid registration and attendance
- **Community:** Sports Diary, Most Active This Month, verified reviews for venues and coaches, announcements
- **Owner insights:** revenue tracking, court utilisation heatmap and customer profiles
- **Administration:** venue approval, coach verification, user management and dispute handling
- **Notifications:** in-app notifications for key events

## Built with

HTML, CSS and vanilla JavaScript on the frontend, PHP (no framework) on the backend, and MySQL through `mysqli`. Chart.js is used for the owner heatmap and PayHere Sandbox for payments.

## Requirements

- [XAMPP](https://www.apachefriends.org) with PHP 8.1 or newer (includes Apache and MySQL)
- [Git](https://git-scm.com/downloads)
- A modern web browser

## Setup

The commands below are for **Windows PowerShell** with XAMPP installed in `C:\xampp`.

**1. Clone the project into XAMPP's `htdocs` folder**
```powershell
cd C:\xampp\htdocs
git clone https://github.com/abd-ameer/CourtPass.git courtpass
cd courtpass
```

**2. Create the config file**
```powershell
Copy-Item config\config.example.php config\config.php
```
The default values (user `root`, no password, database `courtpass`) work with a fresh XAMPP install. Add your PayHere Sandbox merchant ID and secret here when working on payments.

**3. Start the servers**

Open the XAMPP Control Panel and click **Start** next to **Apache** and **MySQL**.

**4. Create the database**
```powershell
Get-Content database\schema.sql | C:\xampp\mysql\bin\mysql -u root
Get-Content database\seed.sql | C:\xampp\mysql\bin\mysql -u root
```
You can also import `database/schema.sql` and then `database/seed.sql` through phpMyAdmin at http://localhost/phpmyadmin.

## Running the system

| URL | What you should see |
|---|---|
| http://localhost/courtpass/ | CourtPass home page |
| http://localhost/courtpass/api/health | JSON status with `"database":"connected"` |

To run without Apache, use PHP's built-in server and open http://localhost:8000:
```powershell
C:\xampp\php\php.exe -S localhost:8000 -t public public/index.php
```

## Important notes

- **Folder name:** the project must sit at `C:\xampp\htdocs\courtpass` for the URLs above to work. Apache's `mod_rewrite` must be enabled (it is by default in XAMPP).
- **Config file:** `config/config.php` holds your local database and PayHere settings. It is ignored by Git and must never be committed.
- **Port 3306:** XAMPP's MySQL can't start if another MySQL server is already running on port 3306. Stop the other server first.
- **Payments are simulated:** PayHere runs in sandbox mode, so no real money is charged or refunded.
- **Timezone:** all dates and times use Asia/Colombo (UTC+05:30).
- **No background jobs:** automatic updates such as no-show detection and slot expiry happen when a user performs an action (for example logging in or making a booking), not on a schedule.
- **Legacy folder:** `legacy/` contains an early prototype kept for reference. It is not part of the running system and can't be opened in the browser.
