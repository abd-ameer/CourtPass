<?php
/**
 * CourtPass — Login Page
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL);
    exit;
}

$pageTitle = 'Login';
include __DIR__ . '/layouts/header.php';
?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__logo">
            <span style="font-size: 3rem;">🏟️</span>
        </div>
        <h1 class="auth-card__title">Welcome Back</h1>
        <p class="auth-card__subtitle">Sign in to manage your bookings and activities</p>

        <form id="loginForm">
            <?= csrfField() ?>
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="e.g. saman@gmail.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn--primary btn--full btn--lg mt-4" id="submitBtn">
                Sign In →
            </button>
        </form>

        <div class="auth-card__divider">or</div>

        <div class="auth-card__footer">
            Don't have an account? <a href="<?= BASE_URL ?>/register" class="font-semibold">Sign up as a Player</a><br>
            <div class="mt-2 text-xs">
                Are you a venue owner? <a href="<?= BASE_URL ?>/register/owner">Register your venue</a> · 
                Coach? <a href="<?= BASE_URL ?>/register/coach">Join as Coach</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('submitBtn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        btn.disabled = true;
        btn.innerHTML = 'Signing in...';

        const formData = new FormData(form);

        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/auth/login', {
                method: 'POST',
                body: formData
            });

            showToast('Login successful!', 'success');
            setTimeout(() => {
                window.location.href = '<?= BASE_URL ?>';
            }, 600);
        } catch (err) {
            showToast(err.message, 'error');
            btn.disabled = false;
            btn.innerHTML = 'Sign In →';
        }
    });
});
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
