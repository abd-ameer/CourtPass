<section class="card">
    <h1>Something went wrong</h1>
    <p><?= e($message ?? 'Please try again.') ?></p>
    <a class="btn" href="<?= url('/') ?>">Back to home</a>
</section>
