<?php
/**
 * CourtPass — Header Layout
 * Included at the top of every page.
 * Expects $pageTitle to be set before including.
 */

require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/helpers.php';

$isLoggedIn = isLoggedIn();
$userRole = currentUserRole();
$userName = currentUserName();
$userId = currentUserId();

// Get notification count for logged in users
$notifCount = 0;
if ($isLoggedIn) {
    require_once __DIR__ . '/../../includes/notifications.php';
    $notifCount = getUnreadCount($userId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CourtPass — Book indoor sports courts across Sri Lanka. Futsal, badminton, pickleball, squash, billiards, carrom, and table tennis.">
    <title><?= sanitize($pageTitle ?? 'CourtPass') ?> — CourtPass</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <meta name="csrf-token" content="<?= generateCsrfToken() ?>">
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Header -->
    <header class="header" id="mainHeader">
        <div class="header__inner">
            <a href="<?= BASE_URL ?>" class="header__logo">
                <div class="header__logo-icon">🏟️</div>
                <span>CourtPass</span>
            </a>

            <button class="nav__toggle" id="navToggle" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="nav" id="mainNav">
                <a href="<?= BASE_URL ?>/venues" class="nav__link">
                    🏢 Venues
                </a>
                <a href="<?= BASE_URL ?>/flash-deals" class="nav__link">
                    ⚡ Flash Deals
                </a>
                <a href="<?= BASE_URL ?>/resale" class="nav__link">
                    🔄 Resale
                </a>

                <?php if ($isLoggedIn): ?>
                    <?php if ($userRole === 'customer'): ?>
                        <a href="<?= BASE_URL ?>/my-bookings" class="nav__link">
                            📅 My Bookings
                        </a>
                        <a href="<?= BASE_URL ?>/community/diary" class="nav__link">
                            📔 Diary
                        </a>
                    <?php elseif ($userRole === 'owner'): ?>
                        <a href="<?= BASE_URL ?>/owner/dashboard" class="nav__link">
                            📊 Dashboard
                        </a>
                    <?php elseif ($userRole === 'coach'): ?>
                        <a href="<?= BASE_URL ?>/coach/dashboard" class="nav__link">
                            🎯 Dashboard
                        </a>
                    <?php elseif ($userRole === 'admin'): ?>
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="nav__link">
                            ⚙️ Admin
                        </a>
                    <?php endif; ?>

                    <a href="<?= BASE_URL ?>/notifications" class="nav__link" style="position:relative;">
                        🔔
                        <?php if ($notifCount > 0): ?>
                            <span class="nav__notification-badge"><?= $notifCount > 9 ? '9+' : $notifCount ?></span>
                        <?php endif; ?>
                    </a>

                    <div class="nav__user" style="display:flex;align-items:center;gap:0.5rem;margin-left:0.5rem;">
                        <span class="text-sm font-medium"><?= sanitize($userName) ?></span>
                        <a href="<?= BASE_URL ?>/api/auth/logout" class="btn btn--ghost btn--sm" id="logoutBtn">
                            Logout
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="nav__link">
                        Log In
                    </a>
                    <a href="<?= BASE_URL ?>/register" class="btn btn--primary btn--sm">
                        Sign Up
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="main-content">
