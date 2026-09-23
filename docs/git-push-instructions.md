# CourtPass — UI Migration Git Push Instructions by Member

> **Repository:** `https://github.com/abd-ameer/CourtPass.git`  
> **Base Branch:** `feature/v2-ui-migration`  
> **Integration Workflow:** Each member creates their assigned feature branch from `feature/v2-ui-migration`, stages only their designated files, commits, pushes, and opens a Pull Request into `feature/v2-ui-migration`.

---

## 1. Branch Name & Effort Breakdown Summary

| Member | Target Share | Branch Name | UI & File Scope |
|---|---|---|---|
| **D** | **60%** | `feature/ui-shared-infra` | Shared layouts, header, footer, sidebars, design system (CSS/JS/assets), home page, public venue discovery, and admin UI |
| **A** | **~13.3%** | `feature/ui-customer-booking` | Customer pages and booking UI, including login and customer sign-up screens |
| **B** | **~13.3%** | `feature/ui-owner-venue` | Owner pages, venue and court forms, and owner sign-up screen |
| **C** | **~13.3%** | `feature/ui-coach-sessions` | Coach pages, coaching session forms, and coach sign-up screen |

---

## 2. Prerequisites for All Members

```bash
# 1. Clone the repository (if not already cloned)
git clone https://github.com/abd-ameer/CourtPass.git
cd CourtPass

# 2. Fetch and checkout the latest feature base
git checkout feature/v2-ui-migration
git pull origin feature/v2-ui-migration
```

> [!IMPORTANT]
> - **Never commit `config/config.php`** (git-ignored, local DB credentials).
> - **Never commit or modify `courtpass_v2/`** (git-ignored, UI prototype reference only).

---

## 3. Step-by-Step Push Instructions

### Member D — Shared Infrastructure & Admin UI (60%)
**Branch:** `feature/ui-shared-infra`

```bash
# 1. Start from the updated base
git checkout feature/v2-ui-migration
git pull origin feature/v2-ui-migration

# 2. Create your branch
git checkout -b feature/ui-shared-infra

# 3. Stage only your assigned files
git add public/assets/css/
git add public/assets/js/
git add public/assets/images/
git add app/views/layouts/
git add app/views/partials/
git add app/views/home/
git add app/views/public/venues.php
git add app/views/public/venue-details.php
git add app/views/public/court-details.php
git add app/views/public/help.php
git add app/views/public/coaching.php
git add app/views/public/coach-profile.php
git add app/views/admin/
git add app/controllers/AdminController.php
git add app/controllers/HomeController.php
git add app/controllers/PublicController.php
git add config/routes.php
git add app/helpers/functions.php

# 4. Commit and push
git commit -m "feat(ui-shared): shared layout, design system, home, public discovery & admin UI"
git push -u origin feature/ui-shared-infra
```
*Open GitHub PR:* `feature/ui-shared-infra` &rarr; `feature/v2-ui-migration`  
*Title:* `[D] Shared layout, design system, public discovery & admin UI`

---

### Member A — Customer Pages & Booking UI (~13.3%)
**Branch:** `feature/ui-customer-booking`

```bash
# 1. Start from the updated base
git checkout feature/v2-ui-migration
git pull origin feature/v2-ui-migration

# 2. Create your branch
git checkout -b feature/ui-customer-booking

# 3. Stage only your assigned files
git add app/views/public/login.php
git add app/views/public/register-role.php
git add app/views/public/register-customer.php
git add app/views/customer/
git add app/controllers/AuthController.php
git add app/controllers/CustomerController.php

# 4. Commit and push
git commit -m "feat(ui-customer): customer portal pages, booking flow, login & customer registration"
git push -u origin feature/ui-customer-booking
```
*Open GitHub PR:* `feature/ui-customer-booking` &rarr; `feature/v2-ui-migration`  
*Title:* `[A] Customer pages, booking UI, login & customer registration`

---

### Member B — Owner Pages & Venue Forms (~13.3%)
**Branch:** `feature/ui-owner-venue`

```bash
# 1. Start from the updated base
git checkout feature/v2-ui-migration
git pull origin feature/v2-ui-migration

# 2. Create your branch
git checkout -b feature/ui-owner-venue

# 3. Stage only your assigned files
git add app/views/public/register-owner.php
git add app/views/owner/
git add app/controllers/OwnerController.php

# 4. Commit and push
git commit -m "feat(ui-owner): owner portal pages, venue/court management & owner registration"
git push -u origin feature/ui-owner-venue
```
*Open GitHub PR:* `feature/ui-owner-venue` &rarr; `feature/v2-ui-migration`  
*Title:* `[B] Owner pages, venue/court management & owner registration`

---

### Member C — Coach Pages & Session Forms (~13.3%)
**Branch:** `feature/ui-coach-sessions`

```bash
# 1. Start from the updated base
git checkout feature/v2-ui-migration
git pull origin feature/v2-ui-migration

# 2. Create your branch
git checkout -b feature/ui-coach-sessions

# 3. Stage only your assigned files
git add app/views/public/register-coach.php
git add app/views/coach/
git add app/controllers/CoachController.php

# 4. Commit and push
git commit -m "feat(ui-coach): coach portal pages, session management & coach registration"
git push -u origin feature/ui-coach-sessions
```
*Open GitHub PR:* `feature/ui-coach-sessions` &rarr; `feature/v2-ui-migration`  
*Title:* `[C] Coach pages, session management & coach registration`

---

## 4. Integration & Merge Order (Led by D)

Once PRs are reviewed and approved, merge in this order:
1. `feature/ui-shared-infra` &rarr; `feature/v2-ui-migration`
2. `feature/ui-customer-booking` &rarr; `feature/v2-ui-migration`
3. `feature/ui-owner-venue` &rarr; `feature/v2-ui-migration`
4. `feature/ui-coach-sessions` &rarr; `feature/v2-ui-migration`

Final merge into `main`:
```bash
git checkout main
git pull origin main
git merge feature/v2-ui-migration
git push origin main
```

---

## 5. File Ownership Quick-Reference

```
app/
├── controllers/
│   ├── AdminController.php       → D
│   ├── AuthController.php        → A
│   ├── CoachController.php       → C
│   ├── CustomerController.php    → A
│   ├── HomeController.php        → D
│   ├── OwnerController.php       → B
│   └── PublicController.php      → D
├── helpers/functions.php         → D
└── views/
    ├── admin/                    → D (all files)
    ├── coach/                    → C (all files)
    ├── customer/                 → A (all files)
    ├── home/index.php            → D
    ├── layouts/                  → D (main.php, dashboard.php)
    ├── owner/                    → B (all files)
    ├── partials/                 → D (all files)
    └── public/
        ├── coach-profile.php     → D
        ├── coaching.php          → D
        ├── court-details.php     → D
        ├── help.php              → D
        ├── login.php             → A
        ├── register-coach.php    → C
        ├── register-customer.php → A
        ├── register-owner.php    → B
        ├── register-role.php     → A
        ├── venue-details.php     → D
        └── venues.php            → D

config/routes.php                 → D
public/assets/css/                → D (all files)
public/assets/js/                 → D (all files)
public/assets/images/             → D (all files)
```
