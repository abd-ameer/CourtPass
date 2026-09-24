<?php
/**
 * Operating hours per day for court_operating_hours (day_of_week 1 = Monday ... 7 = Sunday).
 * Expects $hours: [day_of_week => ['open' => 'HH:00', 'close' => 'HH:00']]; a missing day is closed.
 */
$days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
?>
<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>Day</th>
                <th>Open</th>
                <th>Opens At</th>
                <th>Closes At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($days as $num => $day): ?>
                <?php $h = $hours[$num] ?? null; ?>
                <tr>
                    <td><strong><?= e($day) ?></strong></td>
                    <td><input type="checkbox" name="hours[<?= $num ?>][open_day]" value="1" <?= $h ? 'checked' : '' ?> aria-label="Open on <?= e($day) ?>"></td>
                    <td><input type="time" name="hours[<?= $num ?>][open_time]" class="form-control" step="3600" value="<?= e($h['open'] ?? '06:00') ?>" style="width: 130px;"></td>
                    <td><input type="time" name="hours[<?= $num ?>][close_time]" class="form-control" step="3600" value="<?= e($h['close'] ?? '22:00') ?>" style="width: 130px;"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<p class="text-xs text-muted" style="margin-top: 8px;">Times are on the hour. Each open hour becomes one bookable one-hour slot.</p>
