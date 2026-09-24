<?php
class BookingService
{
    public const HOLD_MINUTES = 10;
    public const DAYS_AHEAD = 6;

    private BookingModel $bookings;
    private CourtService $courts;

    public function __construct()
    {
        $this->bookings = new BookingModel();
        $this->courts = new CourtService();
    }

    /** Court header and court tabs for the slot grid page, or null when the court cannot be booked. */
    public function courtAvailability(int $courtId): ?array
    {
        $court = $this->courts->bookableCourt($courtId);
        if ($court === null) {
            return null;
        }
        $sports = array_column((new VenueService())->sportTypes(), 'name', 'id');

        return [
            'court' => [
                'id'          => (int) $court['id'],
                'name'        => $court['name'],
                'sport'       => $sports[(int) $court['sport_type_id']] ?? '',
                'hourly_rate' => (float) $court['hourly_rate'],
                'venue_name'  => $court['venue_name'],
                'venue_slug'  => $court['venue_slug'],
            ],
            'courts'     => $this->courts->venueCourts((int) $court['venue_id']),
            'hoursToday' => $this->courts->hoursOn($courtId, date('Y-m-d')),
        ];
    }

    /**
     * One-hour slots for the date, or null when the court cannot be booked.
     * States: available, booked, blocked, unavailable. A released slot shows as available.
     */
    public function slotGrid(int $courtId, string $date): ?array
    {
        $court = $this->courts->bookableCourt($courtId);
        if ($court === null) {
            return null;
        }
        $this->expireStale();

        $now = now();
        $open = $this->inBookingWindow($date);
        $blocks = $this->courts->blockedStarts($courtId, $date);
        $held = array_flip(array_map(fn (string $t) => substr($t, 0, 5), $this->bookings->heldStarts($courtId, $date)));

        $slots = [];
        foreach ($this->courts->slotStarts($courtId, $date) as $start) {
            $block = $blocks[$start] ?? null;
            if (!$open || "{$date} {$start}:00" <= $now) {
                $state = 'unavailable';
            } elseif ($block !== null) {
                $state = 'blocked';
            } elseif (isset($held[$start])) {
                $state = 'booked';
            } else {
                $state = 'available';
            }
            $slots[] = [
                'start'       => $start,
                'state'       => $state,
                'price'       => (float) $court['hourly_rate'],
                'flash_price' => null,
                'block_id'    => $block !== null && $block['type'] === 'owner' ? $block['id'] : null,
            ];
        }
        return $slots;
    }

    /**
     * Details for the booking confirmation page, or null when the court cannot be booked.
     * Throws ValidationException(['slot' => ...]) when the slot is no longer free.
     */
    public function draft(int $customerId, int $courtId, string $date, string $time): ?array
    {
        $this->expireStale();
        $court = $this->courts->bookableCourt($courtId);
        if ($court === null) {
            return null;
        }
        $this->assertInBookingWindow($date);
        $time = substr($time, 0, 5);
        $released = Database::transaction(fn (): bool => $this->checkSlot($courtId, $date, $time, true));
        $profile = $this->profile($customerId);
        $sports = array_column((new VenueService())->sportTypes(), 'name', 'id');

        $cashNote = null;
        if ($released) {
            $cashNote = 'This slot was released by another customer, so it can only be paid online.';
        } elseif ($profile['reliability_tier'] !== 'standard') {
            $cashNote = 'Cash on arrival is available to Standard tier customers only.';
        }

        return [
            'court_id'        => $courtId,
            'court_name'      => $court['name'],
            'venue_name'      => $court['venue_name'],
            'venue_slug'      => $court['venue_slug'],
            'sport'           => $sports[(int) $court['sport_type_id']] ?? '',
            'date'            => $date,
            'start'           => $time,
            'end'             => date('H:i', strtotime("{$date} {$time}:00 +1 hour")),
            'amount'          => (float) $court['hourly_rate'],
            'tier'            => $profile['reliability_tier'],
            'score'           => $profile['reliability_score'] === null ? null : (float) $profile['reliability_score'],
            'completed_count' => (int) $profile['completed_count'],
            'cash_allowed'    => $cashNote === null,
            'cash_note'       => $cashNote,
        ];
    }

    /**
     * Creates a booking and returns its id. Online bookings hold the slot for HOLD_MINUTES awaiting payment;
     * cash on arrival (Standard tier only) waits for the owner as pending.
     */
    public function create(int $customerId, int $courtId, string $date, string $time, string $paymentMethod): int
    {
        if (!in_array($paymentMethod, ['online', 'cash_on_arrival'], true)) {
            throw new ValidationException(['payment_method' => 'Choose a payment method.']);
        }
        $this->expireStale();
        if ($this->courts->bookableCourt($courtId) === null) {
            throw new ValidationException(['slot' => 'This court is not taking bookings right now.']);
        }
        $this->assertInBookingWindow($date);
        $profile = $this->profile($customerId);
        if ($paymentMethod === 'cash_on_arrival' && $profile['reliability_tier'] !== 'standard') {
            throw new ValidationException(['payment_method' => 'Cash on arrival is available to Standard tier customers only.']);
        }
        $time = substr($time, 0, 5);

        try {
            return Database::transaction(function () use ($customerId, $courtId, $date, $time, $paymentMethod): int {
                $this->courts->lockCourt($courtId);
                $court = $this->courts->bookableCourt($courtId);
                if ($court === null) {
                    throw new ValidationException(['slot' => 'This court is not taking bookings right now.']);
                }
                $released = $this->checkSlot($courtId, $date, $time, true);
                if ($released && $paymentMethod === 'cash_on_arrival') {
                    throw new ValidationException(['payment_method' => 'This slot was released by another customer, so it can only be paid online.']);
                }

                $online = $paymentMethod === 'online';
                $status = $online ? 'pending_payment' : 'pending';
                $expiresAt = $online ? date('Y-m-d H:i:s', time() + self::HOLD_MINUTES * 60) : null;
                $amount = (float) $court['hourly_rate'];
                $id = $this->bookings->create($customerId, $courtId, $date, $time . ':00', $amount, $paymentMethod, $status, $expiresAt);

                (new AuditService())->log($customerId, 'booking.created', 'booking', $id, null, $status,
                    json_encode(['payment_method' => $paymentMethod, 'amount' => $amount]));

                $slot = "{$court['name']} at {$court['venue_name']} on " . format_datetime("{$date} {$time}:00");
                $notifications = new NotificationService();
                if ($online) {
                    $notifications->notify($customerId, 'booking_created', 'Complete your payment',
                        "Your slot for {$slot} is held until " . date('H:i', strtotime($expiresAt)) . '. Pay online to confirm it.',
                        "/customer/bookings/{$id}");
                } else {
                    $customer = (new UserModel())->findById($customerId);
                    $notifications->notify($customerId, 'booking_created', 'Booking request sent',
                        "Your request for {$slot} is waiting for the venue to confirm it.", "/customer/bookings/{$id}");
                    $notifications->notify((int) $court['owner_id'], 'booking_request', 'New booking request',
                        "{$customer['name']} requested {$slot}, paying cash on arrival.", "/owner/bookings/{$id}");
                }
                return $id;
            });
        } catch (mysqli_sql_exception $e) {
            // 1062 = duplicate slot_lock: another booking took the slot first
            if ($e->getCode() === 1062) {
                throw new ValidationException(['slot' => 'This slot has just been booked. Choose another slot.']);
            }
            throw $e;
        }
    }

    /** The customer's bookings, newest slot first. */
    public function customerBookings(int $customerId): array
    {
        $this->expireStale();
        return array_map(fn (array $b): array => $this->present($b), $this->bookings->forCustomer($customerId));
    }

    /** One of the customer's own bookings with its cancellation terms, or null when it is not theirs. */
    public function customerBooking(int $customerId, int $bookingId): ?array
    {
        $this->expireStale();
        $booking = $this->bookings->find($bookingId);
        if ($booking === null || (int) $booking['customer_id'] !== $customerId) {
            return null;
        }
        return $this->present($booking);
    }

    /** Bookings at every venue the owner runs, newest slot first. */
    public function ownerBookings(int $ownerId): array
    {
        $this->expireStale();
        return array_map(fn (array $b): array => $this->present($b), $this->bookings->forOwner($ownerId));
    }

    /** A booking at one of the owner's venues, or null when the venue is not theirs. */
    public function ownerBooking(int $ownerId, int $bookingId): ?array
    {
        $this->expireStale();
        $booking = $this->bookings->find($bookingId);
        if ($booking === null || (int) $booking['owner_id'] !== $ownerId) {
            return null;
        }
        return $this->present($booking);
    }

    /**
     * Customer cancels their own pending cash request or confirmed booking.
     * Online refunds follow the time brackets through RefundService; cash stores a cancellation class.
     * Returns ['refund' => ?array, 'refund_percent' => ?int, 'class' => ?string].
     */
    public function cancelByCustomer(int $customerId, int $bookingId, ?string $reason): array
    {
        $this->expireStale();
        $booking = $this->bookings->find($bookingId);
        if ($booking === null || (int) $booking['customer_id'] !== $customerId) {
            throw new ValidationException(['booking' => 'Booking not found.']);
        }
        $reason = trim((string) $reason);
        $reason = $reason === '' ? null : mb_substr($reason, 0, 500);

        return Database::transaction(function () use ($customerId, $booking, $reason): array {
            $this->courts->lockCourt((int) $booking['court_id']);
            $row = $this->bookings->lockForUpdate((int) $booking['id']);
            $b = $this->present(array_merge($booking, $row));
            if (!$b['can_cancel']) {
                throw new ValidationException(['booking' => "Booking #{$b['id']} can no longer be cancelled."]);
            }

            $terms = $b['cancellation'];
            $this->bookings->cancel($b['id'], $b['status'], $customerId, now(), $reason, $terms['class']);
            $refund = $terms['refund_percent'] === null
                ? null
                : (new RefundService())->refundBooking($b['id'], (float) $terms['refund_percent'], 'customer_cancel', $customerId);

            (new AuditService())->log($customerId, 'booking.cancelled', 'booking', $b['id'], $b['status'], 'cancelled', json_encode([
                'by'                 => 'customer',
                'reason'             => $reason,
                'cancellation_class' => $terms['class'],
                'refund_percent'     => $refund['percentage'] ?? null,
                'refund_amount'      => $refund['amount'] ?? null,
            ]));

            $message = 'You cancelled your booking for ' . self::slotText($b) . '.';
            if ($refund !== null) {
                $message .= " A {$terms['refund_percent']}% refund of " . lkr($refund['amount']) . ' has been issued.';
            } elseif ($terms['class'] !== null) {
                $message .= ' It is recorded as a ' . $terms['class'] . ' cancellation.';
            } elseif ($terms['refund_percent'] === 0) {
                $message .= ' No refund applies less than 12 hours before the slot.';
            }
            $notifications = new NotificationService();
            $notifications->notify($customerId, 'booking_cancelled', 'Booking cancelled', $message, "/customer/bookings/{$b['id']}");
            $notifications->notify($b['owner_id'], 'booking_cancelled', 'Booking cancelled by customer',
                "{$b['customer_name']} cancelled booking #{$b['id']} for " . self::slotText($b) . '. The slot is free again.',
                "/owner/bookings/{$b['id']}");

            return ['refund' => $refund, 'refund_percent' => $terms['refund_percent'], 'class' => $terms['class']];
        });
    }

    /** Owner accepts a pending cash-on-arrival request. */
    public function confirm(int $ownerId, int $bookingId): void
    {
        $this->ownerAction($ownerId, $bookingId, 'pending', function (array $b) use ($ownerId): void {
            $this->bookings->confirm($b['id'], now());
            (new AuditService())->log($ownerId, 'booking.confirmed', 'booking', $b['id'], 'pending', 'confirmed');
            (new NotificationService())->notify($b['customer_id'], 'booking_confirmed', 'Booking confirmed',
                "{$b['venue_name']} confirmed your booking for " . self::slotText($b) . '. Pay ' . lkr($b['amount']) . ' at the counter when you arrive.',
                "/customer/bookings/{$b['id']}");
        });
    }

    /** Owner turns down a pending request; the reason is shown to the customer. */
    public function reject(int $ownerId, int $bookingId, string $reason): void
    {
        $reason = self::requiredReason($reason);
        $this->ownerAction($ownerId, $bookingId, 'pending', function (array $b) use ($ownerId, $reason): void {
            $this->bookings->reject($b['id'], $reason);
            (new AuditService())->log($ownerId, 'booking.rejected', 'booking', $b['id'], 'pending', 'rejected', json_encode(['reason' => $reason]));
            (new NotificationService())->notify($b['customer_id'], 'booking_rejected', 'Booking rejected',
                "{$b['venue_name']} could not accept your booking for " . self::slotText($b) . ". Reason: {$reason}",
                "/customer/bookings/{$b['id']}");
        });
    }

    /** Owner cancels a confirmed booking with a reason; an online-paid booking is refunded in full. */
    public function cancelByOwner(int $ownerId, int $bookingId, string $reason): void
    {
        $reason = self::requiredReason($reason);
        $this->ownerAction($ownerId, $bookingId, 'confirmed', function (array $b) use ($ownerId, $reason): void {
            $this->bookings->cancel($b['id'], 'confirmed', $ownerId, now(), $reason, null);
            $refund = $b['payment_method'] === 'online'
                ? (new RefundService())->refundBooking($b['id'], 100.0, 'owner_cancel', $ownerId)
                : null;

            (new AuditService())->log($ownerId, 'booking.cancelled', 'booking', $b['id'], 'confirmed', 'cancelled', json_encode([
                'by'            => 'owner',
                'reason'        => $reason,
                'refund_amount' => $refund['amount'] ?? null,
            ]));
            $message = "{$b['venue_name']} cancelled your booking for " . self::slotText($b) . ". Reason: {$reason}";
            if ($refund !== null) {
                $message .= ' You receive a full refund of ' . lkr($refund['amount']) . '.';
            }
            (new NotificationService())->notify($b['customer_id'], 'booking_cancelled', 'Booking cancelled by the venue', $message, "/customer/bookings/{$b['id']}");
        });
    }

    /**
     * Shared conflict check for coaching sessions and owner blocks.
     * Call inside the caller's transaction after CourtService::lockCourt(); it never opens a transaction.
     * Coaching sessions are covered by their linked court block. A released booking still holds the slot here.
     */
    public function assertSlotFree(int $courtId, string $date, string $time): void
    {
        $this->checkSlot($courtId, $date, $time, false);
    }

    /**
     * Expires unpaid holds past HOLD_MINUTES and cash requests the owner did not answer before the slot started.
     * Opens one short transaction per booking, so never call it inside another transaction.
     */
    public function expireStale(): int
    {
        $count = 0;
        foreach ($this->bookings->staleHolds(now()) as $row) {
            $count += Database::transaction(fn (): int => $this->expireIfStale($row) ? 1 : 0);
        }
        return $count;
    }

    /**
     * Throws ValidationException(['slot' => ...]) when the slot cannot be taken.
     * Returns true when the slot is free only because its booking was released for resale.
     */
    private function checkSlot(int $courtId, string $date, string $time, bool $customerBooking): bool
    {
        $time = substr($time, 0, 5);
        if (!in_array($time, $this->courts->slotStarts($courtId, $date), true)) {
            throw new ValidationException(['slot' => 'The court is not open at that time.']);
        }
        if ("{$date} {$time}:00" <= now()) {
            throw new ValidationException(['slot' => 'This slot has already started. Choose a later slot.']);
        }
        if ($this->courts->isBlocked($courtId, $date, $time . ':00')) {
            throw new ValidationException(['slot' => 'This slot is blocked by the venue or a coaching session.']);
        }

        $released = false;
        foreach ($this->bookings->onSlotForUpdate($courtId, $date, $time . ':00') as $row) {
            if ($this->expireIfStale($row)) {
                continue;
            }
            if ($row['status'] !== 'released') {
                throw new ValidationException(['slot' => 'This slot has just been booked. Choose another slot.']);
            }
            $released = true;
        }
        if ($released && !$customerBooking) {
            throw new ValidationException(['slot' => 'This slot is held by a booking released for resale.']);
        }
        return $released;
    }

    /** Runs in the caller's transaction. Returns true when this call expired the booking. */
    private function expireIfStale(array $row): bool
    {
        $now = now();
        if (!self::isStale($row, $now) || $this->bookings->expire((int) $row['id'], $row['status'], $now) !== 1) {
            return false;
        }

        $id = (int) $row['id'];
        (new AuditService())->log(null, 'booking.expired', 'booking', $id, $row['status'], 'expired');
        $message = $row['status'] === 'pending_payment'
            ? "Booking #{$id} expired because the online payment was not completed within " . self::HOLD_MINUTES . ' minutes.'
            : "Booking #{$id} expired because the venue did not confirm it before the slot started.";
        (new NotificationService())->notify((int) $row['customer_id'], 'booking_expired', 'Booking expired', $message, "/customer/bookings/{$id}");
        return true;
    }

    private static function isStale(array $row, string $now): bool
    {
        if ($row['status'] === 'pending_payment') {
            return $row['pending_expires_at'] !== null && $row['pending_expires_at'] <= $now;
        }
        return $row['status'] === 'pending' && $row['slot_date'] . ' ' . $row['start_time'] <= $now;
    }

    /**
     * Runs an owner decision on a booking at one of the owner's venues, inside one transaction:
     * court lock, then booking row lock, then a status and start-time check before $change runs.
     */
    private function ownerAction(int $ownerId, int $bookingId, string $requiredStatus, callable $change): void
    {
        $this->expireStale();
        $booking = $this->bookings->find($bookingId);
        if ($booking === null || (int) $booking['owner_id'] !== $ownerId) {
            throw new ValidationException(['booking' => 'Booking not found.']);
        }

        Database::transaction(function () use ($booking, $requiredStatus, $change): void {
            $this->courts->lockCourt((int) $booking['court_id']);
            $row = $this->bookings->lockForUpdate((int) $booking['id']);
            if ($row['status'] !== $requiredStatus) {
                throw new ValidationException(['booking' => "Booking #{$row['id']} is " . strtolower(status_label($row['status'])) . ', so this action is no longer available.']);
            }
            if ($row['slot_date'] . ' ' . $row['start_time'] <= now()) {
                throw new ValidationException(['booking' => "Booking #{$row['id']} has already started, so this action is no longer available."]);
            }
            $change($this->present($booking));
        });
    }

    private static function requiredReason(string $reason): string
    {
        $reason = trim($reason);
        if ($reason === '') {
            throw new ValidationException(['reason' => 'A reason is required.']);
        }
        return mb_substr($reason, 0, 500);
    }

    /** "Futsal Court A on Tue 29 Sep 2026, 19:00" for notifications. */
    private static function slotText(array $b): string
    {
        return "{$b['court_name']} on " . format_datetime($b['starts_at']);
    }

    /** Adds typed values, slot times and what each party may do next. */
    private function present(array $b): array
    {
        $startsAt = $b['slot_date'] . ' ' . $b['start_time'];
        $upcoming = $startsAt > now();
        $cash = $b['payment_method'] === 'cash_on_arrival';

        foreach (['id', 'customer_id', 'court_id', 'venue_id', 'owner_id', 'completed_count', 'no_show_count'] as $key) {
            $b[$key] = (int) $b[$key];
        }
        foreach (['amount', 'reliability_score', 'refund_percent', 'refund_amount'] as $key) {
            $b[$key] = $b[$key] === null ? null : (float) $b[$key];
        }
        $b['start'] = substr($b['start_time'], 0, 5);
        $b['end'] = date('H:i', strtotime($startsAt . ' +1 hour'));
        $b['starts_at'] = $startsAt;
        $b['group'] = in_array($b['status'], ['cancelled', 'rejected', 'expired', 'resold'], true) ? 'closed' : ($upcoming ? 'upcoming' : 'past');
        $b['can_cancel'] = $upcoming && ($b['status'] === 'confirmed' || ($b['status'] === 'pending' && $cash));
        $b['can_decide'] = $upcoming && $b['status'] === 'pending';
        $b['can_owner_cancel'] = $upcoming && $b['status'] === 'confirmed';
        $b['cancellation'] = $b['can_cancel'] ? self::cancellationTerms($startsAt, $cash, (float) $b['amount']) : null;
        return $b;
    }

    /**
     * Customer cancellation terms by hours left: online-paid 100% over 48h, 50% from 12 to 48h, none under 12h;
     * cash on arrival is classed responsible, moderate or irresponsible on the same brackets.
     */
    private static function cancellationTerms(string $startsAt, bool $cash, float $amount): array
    {
        $hours = (strtotime($startsAt) - time()) / 3600;
        $bracket = $hours > 48 ? 'over_48' : ($hours >= 12 ? '12_to_48' : 'under_12');
        $percent = ['over_48' => 100, '12_to_48' => 50, 'under_12' => 0][$bracket];

        return [
            'hours'          => $hours,
            'hours_left'     => (int) floor($hours),
            'bracket'        => $bracket,
            'refund_percent' => $cash ? null : $percent,
            'refund_amount'  => $cash ? null : round($amount * $percent / 100, 2),
            'resale_refund'  => round($amount * 0.9, 2),
            'class'          => $cash ? ['over_48' => 'responsible', '12_to_48' => 'moderate', 'under_12' => 'irresponsible'][$bracket] : null,
        ];
    }

    private function assertInBookingWindow(string $date): void
    {
        if (!$this->inBookingWindow($date)) {
            throw new ValidationException(['slot' => 'Bookings are open from today up to ' . self::DAYS_AHEAD . ' days ahead.']);
        }
    }

    private function profile(int $customerId): array
    {
        $profile = (new CustomerProfileModel())->find($customerId);
        if ($profile === null) {
            throw new RuntimeException("Customer profile {$customerId} not found.");
        }
        return $profile;
    }

    /** Bookings open from today to DAYS_AHEAD days ahead. */
    private function inBookingWindow(string $date): bool
    {
        $today = date('Y-m-d');
        return $date >= $today && $date <= date('Y-m-d', strtotime('+' . self::DAYS_AHEAD . ' days'));
    }
}
