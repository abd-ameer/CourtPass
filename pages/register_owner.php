<?php
/**
 * CourtPass — Register Venue Owner Page
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL);
    exit;
}

$pageTitle = 'Register as Venue Owner';
include __DIR__ . '/layouts/header.php';
?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-card__logo">
            <span style="font-size: 3rem;">🏢</span>
        </div>
        <h1 class="auth-card__title">Register Venue Owner</h1>
        <p class="auth-card__subtitle">List your venue, manage bookings & boost court utilization</p>

        <form id="registerOwnerForm">
            <?= csrfField() ?>
            <input type="hidden" name="role" value="owner">

            <div class="form-group">
                <label class="form-label" for="name">Full Name / Business Owner Name</label>
                <input type="text" id="name" name="name" class="form-input" placeholder="e.g. Kamal Perera" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Business Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="e.g. kamal@sportshub.lk" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Contact Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-input" placeholder="e.g. 0772345678" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="At least 6 characters" minlength="6" required>
            </div>

            <button type="submit" class="btn btn--primary btn--full btn--lg mt-4" id="submitBtn">
                Register as Owner →
            </button>
        </form>

        <div class="auth-card__footer">
            Already registered? <a href="<?= BASE_URL ?>/login" class="font-semibold">Sign in</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerOwnerForm');
    const btn = document.getElementById('submitBtn');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        btn.disabled = true;
        btn.innerHTML = 'Registering...';

        const formData = new FormData(form);

        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/auth/register', {
                method: 'POST',
                body: formData
            });

            showToast('Account created! Add your venue details next.', 'success');
            setTimeout(() => {
                window.location.href = '<?= BASE_URL ?>/owner/dashboard';
            }, 800);
        } catch (err) {
            showToast(err.message, 'error');
            btn.disabled = false;
            btn.innerHTML = 'Register as Owner →';
        }
    });
});
</script>

<?php include __DIR__ . '/layouts/footer.php'; ?>
