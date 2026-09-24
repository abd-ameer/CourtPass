<?php
$roleTitles = ['customer' => 'Customer', 'owner' => 'Venue Owner', 'coach' => 'Coach', 'admin' => 'Platform Admin'];
$roleTitle = $roleTitles[Auth::role()] ?? '';
$userName = Auth::user()['name'] ?? '';
$initials = strtoupper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), array_slice(preg_split('/\s+/', trim($userName)) ?: [], 0, 2))));
?>
<header class="dashboard-header">
    <div class="dashboard-header-left">
        <button type="button" class="sidebar-toggle-btn" onclick="CourtPassApp.toggleSidebar()" aria-label="Toggle Sidebar" title="Toggle Sidebar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <div style="font-size: var(--font-size-sm); color: var(--color-text-muted); display: flex; align-items: center; gap: 10px;">
            <a href="<?= url('/') ?>" style="display: flex; align-items: center; text-decoration: none;">
                <img src="<?= asset('img/courtpasslogo.png') ?>" alt="<?= e(APP_NAME) ?>" class="brand-logo-img" style="height: 32px; max-width: 140px; object-fit: contain;">
            </a>
            <span style="color: var(--color-border-strong);">/</span>
            <span style="text-transform: capitalize; font-weight: 600; color: var(--color-text-muted); font-size: 13px;"><?= e($roleTitle) ?> Portal</span>
        </div>
    </div>

    <div class="dashboard-header-right">
        <!-- Notifications Bell Dropdown -->
        <div class="dropdown">
            <button type="button" class="btn btn-icon btn-ghost" onclick="CourtPassApp.toggleDropdown('notifDropdown')" style="position: relative;" aria-label="Notifications">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
            </button>

            <div class="dropdown-menu" id="notifDropdown" style="width: 320px; padding: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid var(--color-border); padding-bottom: 8px;">
                    <span style="font-size: 13px; font-weight: 700;">In-App Notifications</span>
                    
                </div>
                <div class="text-sm" style="color: var(--color-text-muted); padding: 8px 0;">No new notifications.</div>
                <div style="margin-top: 10px; text-align: center; border-top: 1px solid var(--color-border); padding-top: 8px;">
                    <a href="<?= url('/notifications') ?>" style="font-size: 12px; font-weight: 600;">View All Notifications &rarr;</a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button type="button" class="btn btn-ghost" onclick="CourtPassApp.toggleDropdown('userProfileDropdown')" style="padding: 4px 10px; border-radius: var(--radius-full); display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-primary-light); color: var(--color-primary-active); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                    <?= e($initials) ?>
                </div>
                <div style="text-align: left; line-height: 1.2;" class="desktop-nav">
                    <div style="font-size: 13px; font-weight: 700; color: var(--color-text-title);"><?= e($userName) ?></div>
                    <div style="font-size: 11px; color: var(--color-text-muted);"><?= e($roleTitle) ?></div>
                </div>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>

            <div class="dropdown-menu" id="userProfileDropdown">
                <div style="padding: 8px 12px; border-bottom: 1px solid var(--color-border); margin-bottom: 4px;">
                    <div style="font-weight: 700; font-size: 13px;"><?= e($userName) ?></div>
                    <div style="font-size: 11px; color: var(--color-text-muted);"><?= e($roleTitle) ?></div>
                </div>
                <a href="<?= url('/account') ?>" class="dropdown-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Account Settings
                </a>
                <a href="<?= url('/') ?>" class="dropdown-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                    Public Homepage
                </a>
                <div style="border-top: 1px solid var(--color-border); margin-top: 4px;"></div>
                <form method="POST" action="<?= url('/logout') ?>" class="inline-form">
                    <?= csrf_field() ?>
                    <button type="submit" class="dropdown-item" style="color: var(--color-danger); width: 100%; background: none; border: 0; text-align: left; cursor: pointer;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Sign Out
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>
