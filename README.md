# CourtPass

Sports venue booking and community platform for Sri Lankan indoor sports (futsal, badminton, pickleball, squash, billiards, carrom, table tennis). UCSC 2nd year group project, Group 40.

**Stack:** HTML, CSS, vanilla JS (`fetch()`), raw PHP (no framework), MySQL with `mysqli`. Only third-party additions: Chart.js (owner heatmap) and PayHere Sandbox. No other libraries, frameworks or packages.

## Setup (XAMPP)

1. Clone into `htdocs` so the path is `C:\xampp\htdocs\courtpass`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel. Make sure `mod_rewrite` is on (it is by default).
3. Copy `config/config.example.php` to `config/config.php` and set your DB details and PayHere sandbox keys.
4. In phpMyAdmin, import `database/schema.sql` then `database/seed.sql`.
5. Open http://localhost/courtpass/ for the site and http://localhost/courtpass/api/health to check routing and the DB connection.

Without XAMPP you can also run `php -S localhost:8000 -t public public/index.php` and open http://localhost:8000.

## Folder structure (layered MVC)

```
courtpass/
├── public/              only folder exposed to the browser
│   ├── index.php        front controller, every request starts here
│   ├── .htaccess        sends all requests to index.php
│   └── assets/          css/, js/, img/, vendor/ (Chart.js)
├── app/
│   ├── bootstrap.php    config, autoloader, error handling, session
│   ├── core/            Router, Controller, Model, Database, Request, Response, View, Session, Auth
│   ├── controllers/     XxxController.php  (thin: input -> service -> view/JSON)
│   ├── services/        XxxService.php     (business rules, transactions, audit, notifications)
│   ├── models/          XxxModel.php       (one per table, prepared statements)
│   ├── views/           layouts/, partials/, one folder per module
│   └── helpers/         functions.php, Validator, Token
├── config/              config.php (git-ignored), config.example.php, routes.php
├── database/            schema.sql, seed.sql
├── tests/               PHPUnit tests (dev only)
├── docs/                architecture.md and project docs
└── legacy/              first prototype, reference only, not served
```

See [docs/architecture.md](docs/architecture.md) for the layer rules and how to add a feature.

## Git workflow

Branch per member (`member-a`, `member-b`, `member-c`, `member-d`). Work on your own branch and open a pull request into `main`. Never commit `config/config.php`.
