<?php
/**
 * Migration Script to parse courtpass_v2 pages into MVC views
 */

$sourceDir = dirname(__DIR__) . '/courtpass_v2/pages';
$destDir = dirname(__DIR__) . '/app/views';

$modules = ['public', 'customer', 'owner', 'coach', 'admin'];

function convertContent(string $content, string $module): string
{
    // If it's a dashboard page:
    if (str_contains($content, 'class="dashboard-layout"')) {
        // Strip out until after <div class="dashboard-content">
        $pos = strpos($content, '<div class="dashboard-content">');
        if ($pos !== false) {
            $content = substr($content, $pos + strlen('<div class="dashboard-content">'));
        }
        // Strip closing dashboard wrappers and includes
        // Usually ends before </div>\s*</div>\s*</div>\s*<?php\s*include_once ... modals.php
        $lastInclude = strrpos($content, 'include_once');
        if ($lastInclude !== false) {
            // Find opening <?php before this include
            $phpTag = strrpos(substr($content, 0, $lastInclude), '<?php');
            if ($phpTag !== false) {
                // Also trim trailing closing </div> tags
                $beforePhp = substr($content, 0, $phpTag);
                // Strip up to 3 trailing </div> tags
                $beforePhp = preg_replace('/(\s*<\/div>\s*){1,4}$/i', '', $beforePhp);
                $content = $beforePhp;
            }
        }
    } elseif (str_contains($content, 'components/public-nav.php')) {
        // Public page: strip up to after public-nav.php include
        $pos = strpos($content, 'components/public-nav.php\';');
        if ($pos !== false) {
            $endPhp = strpos($content, '?>', $pos);
            if ($endPhp !== false) {
                $content = substr($content, $endPhp + 2);
            }
        }
        // Strip footer and modals includes
        $lastInclude = strrpos($content, 'components/footer.php');
        if ($lastInclude !== false) {
            $phpTag = strrpos(substr($content, 0, $lastInclude), '<?php');
            if ($phpTag !== false) {
                $content = substr($content, 0, $phpTag);
            }
        }
    }

    // Replace link patterns
    // 1. Home
    $content = preg_replace('/<\?php\s+echo\s+\$base_path;\s*\?>index\.php/i', '<?= url(\'/\') ?>', $content);

    // 2. Public pages
    $publicPages = [
        'venues.php' => '/venues',
        'venue-details.php' => '/venue-details',
        'court-details.php' => '/court-details',
        'coaching.php' => '/coaching',
        'coach-profile.php' => '/coach-profile',
        'resale.php' => '/resale',
        'reliability.php' => '/reliability',
        'login.php' => '/login',
        'register-role.php' => '/register-role',
        'register-customer.php' => '/register-customer',
        'register-owner.php' => '/register-owner',
        'register-coach.php' => '/register-coach',
    ];
    foreach ($publicPages as $file => $route) {
        $pattern = '/<\?php\s+echo\s+\$base_path;\s*\?>pages\/public\/' . preg_quote($file, '/') . '(\??)/i';
        $content = preg_replace($pattern, '<?= url(\'' . $route . '\') ?>$1', $content);
    }

    // 3. Module pages: customer, owner, coach, admin
    foreach (['customer', 'owner', 'coach', 'admin'] as $mod) {
        $pattern = '/<\?php\s+echo\s+\$base_path;\s*\?>pages\/' . $mod . '\/([a-zA-Z0-9_\-]+)\.php(\??)/i';
        $content = preg_replace_callback($pattern, function ($matches) use ($mod) {
            return "<?= url('/{$mod}/{$matches[1]}') ?>" . $matches[2];
        }, $content);
    }

    // 4. Assets
    $content = preg_replace('/<\?php\s+echo\s+\$base_path;\s*\?>assets\/images\/([a-zA-Z0-9_\-\.]+)/i', '<?= asset(\'images/$1\') ?>', $content);
    $content = preg_replace('/<\?php\s+echo\s+\$base_path;\s*\?>assets\/([a-zA-Z0-9_\-\.\/]+)/i', '<?= asset(\'$1\') ?>', $content);

    // 5. Clean up any lingering $base_path references
    $content = preg_replace('/<\?php\s+echo\s+\$base_path;\s*\?>/i', '<?= url(\'/\') ?>', $content);

    return trim($content) . "\n";
}

foreach ($modules as $mod) {
    $srcModDir = $sourceDir . '/' . $mod;
    $destModDir = $destDir . '/' . $mod;
    if (!is_dir($destModDir)) {
        mkdir($destModDir, 0777, true);
    }

    $files = scandir($srcModDir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || !str_ends_with($file, '.php')) {
            continue;
        }

        $srcFile = $srcModDir . '/' . $file;
        $destFile = $destModDir . '/' . $file;

        $raw = file_get_contents($srcFile);
        $converted = convertContent($raw, $mod);
        file_put_contents($destFile, $converted);
        echo "Migrated {$mod}/{$file} (" . strlen($converted) . " bytes)\n";
    }
}

echo "All pages migrated successfully.\n";
