<?php
/**
 * CourtPass — Register Page (Customer)
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL);
    exit;
}

$pageTitle = 'Sign Up as Player';
include __DIR__ . '/layouts/header.php';
?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__logo">
            <span style="font-size: 3rem;">🏃</span>
        </div>
        <h1 class="auth-card__title">Create Player Account</h1>
        <p class="auth-card__subtitle">Book courts, track your games, and join flash deals</p>

        <form id="registerForm">
            <?= csrfField() ?>
            <input type="hidden" name="role" value="customer">

            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="e.g. Saman Fernando" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="e.g. saman@gmail.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-input" placeholder="e.g. 0771234567" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="At least 6 characters" minlength="6" required>
            </div>

            <button type="submit" class="btn btn--primary btn--full btn--lg mt-4" id="submitBtn">
                Create Account →
            </button>
        </form>

        <div class="auth-card__footer">
            Already have an account? <a href="<?= BASE_URL ?>/login" class="font-semibold">Sign in</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    const btn = document.getElementById('submitBtn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        btn.disabled = true;
        btn.innerHTML = 'Creating account...';

        const formData = new FormData(form);

        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/auth/register', {
                method: 'POST',
                body: formData
            });

            showToast('Welcome to CourtPass!', 'success');
            setTimeout(() => {
                window.location.href = '<?= BASE_URL ?>';
            }, 800);
        } catch (err) {
            showToast(err.message, 'error');
            btn.disabled = false;
            btn.innerHTML = 'Create Account →';
        }
    });
});
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
