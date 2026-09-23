<?php
/**
 * Add clean variable defaults to views that access query parameters or flags
 */

$fixes = [
    'customer/booking-details.php' => "<?php\n\$booking_id = \$booking_id ?? \$_GET['id'] ?? 'BK-9021';\n\$is_cash = \$is_cash ?? (str_contains(\$booking_id, '78') || isset(\$_GET['cash']));\n?>\n",
    'customer/cancel-booking.php' => "<?php\n\$booking_id = \$booking_id ?? \$_GET['id'] ?? 'BK-9021';\n\$is_cash = \$is_cash ?? isset(\$_GET['cash']);\n?>\n",
    'customer/reschedule-booking.php' => "<?php\n\$booking_id = \$booking_id ?? \$_GET['id'] ?? 'BK-9021';\n?>\n",
    'customer/my-resales.php' => "<?php\n\$list_booking = \$list_booking ?? \$_GET['list'] ?? '';\n\$convert_booking = \$convert_booking ?? \$_GET['convert'] ?? '';\n?>\n",
    'customer/create-review.php' => "<?php\n\$is_edit = \$is_edit ?? isset(\$_GET['edit']);\n?>\n",
    'owner/edit-court.php' => "<?php\n\$is_edit = \$is_edit ?? (isset(\$_GET['id']) || isset(\$_GET['edit']));\n?>\n",
    'owner/booking-details.php' => "<?php\n\$booking_id = \$booking_id ?? \$_GET['id'] ?? 'BK-9021';\n?>\n",
    'owner/check-in.php' => "<?php\n\$search_query = \$search_query ?? \$_GET['q'] ?? '';\n?>\n",
];

$viewDir = dirname(__DIR__) . '/app/views/';

foreach ($fixes as $file => $header) {
    $path = $viewDir . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (!str_contains($content, '$booking_id =') && !str_contains($content, '$is_edit =') && !str_contains($content, '$list_booking =') && !str_contains($content, '$search_query =')) {
            file_put_contents($path, $header . $content);
            echo "Fixed {$file}\n";
        }
    }
}
