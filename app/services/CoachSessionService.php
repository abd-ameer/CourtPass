<?php
class CoachSessionService
{
    public const MAX_CAPACITY = 50;
    public const MAX_FEE = 100000;
    public const STATUS_FILTERS = ['upcoming', 'completed', 'cancelled'];

    private CoachSessionModel $sessions;
    private SessionRegistrationModel $registrations;

    public function __construct()
    {
        $this->sessions = new CoachSessionModel();
        $this->registrations = new SessionRegistrationModel();
    }

    /**
     * Creates a one-hour session on a court at one of the coach's approved venues and returns its id.
     * $data: court_id, session_date, start_time, title, description, capacity, fee, visibility.
     * The slot is taken through a coaching court block, so the shared conflict check guards it.
     */
    public function create(int $coachId, array $data): int
    {
        $details = self::validDetails($data);
        $courtId = (int) ($data['court_id'] ?? 0);
        $date = (string) ($data['session_date'] ?? '');
        $time = substr((string) ($data['start_time'] ?? ''), 0, 5);
        $visibility = (string) ($data['visibility'] ?? '');
        if (!in_array($visibility, ['public', 'private'], true)) {
            throw new ValidationException(['visibility' => 'Choose public or private.']);
        }
        if (!self::inSessionWindow($date)) {
            throw new ValidationException(['session_date' => 'Sessions can be created from today up to ' . BookingService::DAYS_AHEAD . ' days ahead.']);
        }
        if (!preg_match('/^([01]\d|2[0-3]):00$/', $time)) {
            throw new ValidationException(['slot' => 'Choose a one-hour slot.']);
        }

        $courts = new CourtService();
        if ($courts->bookableCourt($courtId) === null) {
            throw new ValidationException(['court_id' => 'This court is not open for sessions. Choose another court.']);
        }

        return Database::transaction(function () use ($courts, $coachId, $courtId, $date, $time, $visibility, $details): int {
            $courts->lockCourt($courtId);
            $court = $courts->bookableCourt($courtId);
            if ($court === null) {
                throw new ValidationException(['court_id' => 'This court is not open for sessions. Choose another court.']);
            }
            if (!(new CoachVenueApprovalModel())->isApproved($coachId, (int) $court['venue_id'])) {
                throw new ValidationException(['court_id' => 'You are not approved to run sessions at this venue.']);
            }
            if (!in_array((int) $court['sport_type_id'], (new CoachSportModel())->sportIdsFor($coachId), true)) {
                throw new ValidationException(['court_id' => 'This court is for a sport you do not coach.']);
            }

            $blockId = $courts->addBlock($courtId, $date, $time, 'coaching', null, $coachId);
            $token = $visibility === 'private' ? Token::hex(16) : null;
            $id = $this->sessions->create($coachId, $courtId, $blockId, $date, $time . ':00', $details['title'],
                $details['description'], $details['capacity'], $details['fee'], $visibility, $token);

            (new AuditService())->log($coachId, 'session.created', 'coach_session', $id, null, 'open', json_encode([
                'court_id' => $courtId, 'date' => $date, 'time' => $time, 'visibility' => $visibility,
                'capacity' => $details['capacity'], 'fee' => $details['fee'], 'block_id' => $blockId,
            ]));
            return $id;
        });
    }

    /**
     * Edits title, description, capacity and fee of an upcoming session. Capacity cannot go below the live
     * registrations, and the fee can only change while there are none. Status follows the new capacity.
     */
    public function update(int $coachId, int $sessionId, array $data): void
    {
        $details = self::validDetails($data);
        $this->completePast();

        Database::transaction(function () use ($coachId, $sessionId, $details): void {
            $row = $this->sessions->lockForUpdate($sessionId);
            if ($row === null || (int) $row['coach_id'] !== $coachId) {
                throw new ValidationException(['session' => 'Session not found.']);
            }
            if (!self::isUpcoming($row)) {
                throw new ValidationException(['session' => 'Only upcoming sessions can be edited.']);
            }

            $live = $this->registrations->countLive($sessionId);
            if ($details['capacity'] < $live) {
                throw new ValidationException(['capacity' => "Capacity cannot go below the {$live} current registrations."]);
            }
            $feeChanged = round((float) $row['fee'], 2) !== $details['fee'];
            if ($feeChanged && $live > 0) {
                throw new ValidationException(['fee' => 'The fee can only change while nobody is registered.']);
            }

            $status = $live >= $details['capacity'] ? 'full' : 'open';
            $this->sessions->updateDetails($sessionId, $details['title'], $details['description'], $details['capacity'], $details['fee']);
            $this->sessions->setOpenOrFull($sessionId, $status);

            (new AuditService())->log($coachId, 'session.updated', 'coach_session', $sessionId, $row['status'], $status, json_encode([
                'capacity' => ['from' => (int) $row['capacity'], 'to' => $details['capacity']],
                'fee'      => ['from' => (float) $row['fee'], 'to' => $details['fee']],
            ]));
        });
    }

    /**
     * Cancels an upcoming session: releases its court block, cancels live registrations and refunds paid ones in full,
     * then tells each registrant. Returns ['registrations' => n, 'refund_total' => amount].
     */
    public function cancel(int $coachId, int $sessionId, string $reason): array
    {
        $reason = trim($reason);
        if ($reason === '') {
            throw new ValidationException(['reason' => 'Give a reason for cancelling the session.']);
        }
        if (mb_strlen($reason) > 500) {
            throw new ValidationException(['reason' => 'The reason must be at most 500 characters.']);
        }
        $this->completePast();
        $session = $this->sessions->find($sessionId);
        if ($session === null || (int) $session['coach_id'] !== $coachId) {
            throw new ValidationException(['session' => 'Session not found.']);
        }

        return Database::transaction(function () use ($coachId, $session, $reason): array {
            $courts = new CourtService();
            $courts->lockCourt((int) $session['court_id']);
            $row = $this->sessions->lockForUpdate((int) $session['id']);
            if (!self::isUpcoming($row)) {
                throw new ValidationException(['session' => 'Only upcoming sessions can be cancelled.']);
            }

            $now = now();
            $this->sessions->cancel((int) $row['id'], $coachId, $reason, $now);
            $courts->removeBlock((int) $row['block_id']);

            $refunds = new RefundService();
            $audit = new AuditService();
            $notifications = new NotificationService();
            $when = format_datetime($session['session_date'] . ' ' . $session['start_time']);
            $count = 0;
            $total = 0.0;
            foreach ($this->registrations->liveForSessionForUpdate((int) $row['id']) as $reg) {
                $this->registrations->cancelBySession((int) $reg['id'], $now);
                $refund = $refunds->refundRegistration((int) $reg['id'], 100.0, 'session_cancelled', $coachId);
                $count++;
                $total += $refund['amount'] ?? 0.0;

                $audit->log($coachId, 'registration.cancelled', 'session_registration', (int) $reg['id'], $reg['status'], 'cancelled', json_encode([
                    'cancel_source' => 'session_cancelled', 'session_id' => (int) $row['id'], 'refund_amount' => $refund['amount'] ?? null,
                ]));
                $message = "{$session['coach_name']} cancelled \"{$session['title']}\" on {$when}. Reason: {$reason}"
                    . (preg_match('/[.!?]$/', $reason) ? '' : '.');
                if ($refund !== null) {
                    $message .= ' You receive a full refund of ' . lkr($refund['amount']) . '.';
                }
                $notifications->notify((int) $reg['customer_id'], 'session_cancelled', 'Coaching session cancelled', $message, '/customer/sessions');
            }

            $audit->log($coachId, 'session.cancelled', 'coach_session', (int) $row['id'], $row['status'], 'cancelled', json_encode([
                'reason' => $reason, 'registrations_cancelled' => $count, 'refund_total' => $total, 'block_id' => (int) $row['block_id'],
            ]));
            return ['registrations' => $count, 'refund_total' => $total];
        });
    }

    /**
     * The coach's sessions for My Sessions: ['sessions' => filtered list, 'counts' => per filter, 'venues' => [id => name]].
     * $status is '' for all, or one of STATUS_FILTERS.
     */
    public function coachSessions(int $coachId, string $status = '', ?int $venueId = null): array
    {
        $this->completePast();
        $all = array_map(fn (array $s) => $this->present($s), $this->sessions->forCoach($coachId));

        $counts = ['' => count($all), 'upcoming' => 0, 'completed' => 0, 'cancelled' => 0];
        $venues = [];
        foreach ($all as $s) {
            $counts[$s['group']]++;
            $venues[$s['venue_id']] = $s['venue_name'];
        }
        asort($venues);

        $sessions = array_values(array_filter($all, fn (array $s) =>
            ($status === '' || $s['group'] === $status) && ($venueId === null || $s['venue_id'] === $venueId)));
        if ($status === 'upcoming') {
            $sessions = array_reverse($sessions);
        }
        return ['sessions' => $sessions, 'counts' => $counts, 'venues' => $venues];
    }

    /** One of the coach's sessions with its registrations, or null when it is not theirs. */
    public function coachSession(int $coachId, int $sessionId): ?array
    {
        $this->completePast();
        $session = $this->sessions->find($sessionId);
        if ($session === null || (int) $session['coach_id'] !== $coachId) {
            return null;
        }
        $session = $this->present($session);
        $session['registrations'] = array_map(function (array $r): array {
            $r['id'] = (int) $r['id'];
            $r['amount'] = (float) $r['amount'];
            $r['paid_amount'] = $r['paid_amount'] === null ? null : (float) $r['paid_amount'];
            $r['refund_amount'] = $r['refund_amount'] === null ? null : (float) $r['refund_amount'];
            $r['live'] = in_array($r['status'], ['registered', 'pending_payment'], true);
            return $r;
        }, $this->registrations->forSession($sessionId));
        return $session;
    }

    /** The coach's approved venues with the courts they can use: [['id', 'name', 'courts' => [['id', 'name', 'sport']]]]. */
    public function sessionVenues(int $coachId): array
    {
        $venues = [];
        foreach ((new CoachVenueApprovalModel())->approvedCourtsFor($coachId) as $row) {
            $id = (int) $row['venue_id'];
            $venues[$id] ??= ['id' => $id, 'name' => $row['venue_name'], 'courts' => []];
            $venues[$id]['courts'][] = ['id' => (int) $row['court_id'], 'name' => $row['court_name'], 'sport' => $row['sport_name']];
        }
        return array_values($venues);
    }

    /** Upcoming public sessions for the Coaching page, soonest first. */
    public function publicSessions(?string $sportCode = null, ?int $venueId = null, ?string $date = null): array
    {
        $this->completePast();
        return array_map(fn (array $s) => $this->present($s), $this->sessions->publicUpcoming(now(), $sportCode, $venueId, $date));
    }

    /** A public session by id in any status, or null for an unknown or private session. */
    public function publicSession(int $sessionId): ?array
    {
        $this->completePast();
        $session = $this->sessions->find($sessionId);
        return $session === null || $session['visibility'] !== 'public' ? null : $this->present($session);
    }

    /** A private session by its link token, or null. */
    public function privateSession(string $token): ?array
    {
        $this->completePast();
        $session = $this->sessions->findByToken($token);
        return $session === null ? null : $this->present($session);
    }

    /**
     * Marks open and full sessions whose start time has passed as completed (trigger-on-action).
     * Opens one short transaction per session, so never call it inside another transaction.
     */
    public function completePast(): int
    {
        $count = 0;
        $audit = new AuditService();
        foreach ($this->sessions->pastOpen(now()) as $row) {
            $count += Database::transaction(function () use ($row, $audit): int {
                if ($this->sessions->complete((int) $row['id'], now()) !== 1) {
                    return 0;
                }
                $audit->log(null, 'session.completed', 'coach_session', (int) $row['id'], $row['status'], 'completed');
                return 1;
            });
        }
        return $count;
    }

    /** Title 1 to 100, description 1 to 2000, capacity 1 to MAX_CAPACITY, fee 0 to MAX_FEE (0 is a free session). */
    private static function validDetails(array $data): array
    {
        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $capacity = filter_var($data['capacity'] ?? null, FILTER_VALIDATE_INT);
        $fee = is_numeric($data['fee'] ?? null) ? round((float) $data['fee'], 2) : null;

        $errors = [];
        if ($title === '' || mb_strlen($title) > 100) {
            $errors['title'] = 'Enter a title of up to 100 characters.';
        }
        if ($description === '' || mb_strlen($description) > 2000) {
            $errors['description'] = 'Enter a description of up to 2000 characters.';
        }
        if ($capacity === false || $capacity < 1 || $capacity > self::MAX_CAPACITY) {
            $errors['capacity'] = 'Capacity must be between 1 and ' . self::MAX_CAPACITY . '.';
        }
        if ($fee === null || $fee < 0 || $fee > self::MAX_FEE) {
            $errors['fee'] = 'The fee must be between LKR 0 (free) and ' . lkr(self::MAX_FEE) . '.';
        }
        if ($errors !== []) {
            throw new ValidationException($errors);
        }
        return ['title' => $title, 'description' => $description, 'capacity' => $capacity, 'fee' => $fee];
    }

    /** Sessions use the booking window: today to BookingService::DAYS_AHEAD days ahead. */
    private static function inSessionWindow(string $date): bool
    {
        $d = DateTime::createFromFormat('!Y-m-d', $date);
        if ($d === false || $d->format('Y-m-d') !== $date) {
            return false;
        }
        return $date >= date('Y-m-d') && $date <= date('Y-m-d', strtotime('+' . BookingService::DAYS_AHEAD . ' days'));
    }

    private static function isUpcoming(?array $row): bool
    {
        return $row !== null && in_array($row['status'], ['open', 'full'], true)
            && $row['session_date'] . ' ' . $row['start_time'] > now();
    }

    /** Adds typed values, times, labels and what the coach may do next. */
    private function present(array $s): array
    {
        foreach (['id', 'coach_id', 'court_id', 'venue_id', 'capacity', 'live_count', 'registration_count'] as $key) {
            $s[$key] = (int) $s[$key];
        }
        $s['block_id'] = $s['block_id'] === null ? null : (int) $s['block_id'];
        $s['fee'] = (float) $s['fee'];
        $s['coach_verified'] = (int) $s['coach_verified'] === 1;
        $s['coach_rating'] = $s['coach_rating'] === null ? null : round((float) $s['coach_rating'], 1);

        $startsAt = $s['session_date'] . ' ' . $s['start_time'];
        $upcoming = self::isUpcoming($s);
        $s['starts_at'] = $startsAt;
        $s['start'] = substr($s['start_time'], 0, 5);
        $s['end'] = date('H:i', strtotime($startsAt . ' +1 hour'));
        $s['fee_label'] = $s['fee'] > 0 ? lkr($s['fee']) : 'Free';
        $s['sport'] = $s['sport_name'];
        $s['registered_count'] = $s['live_count'];
        $s['spots_left'] = max(0, $s['capacity'] - $s['live_count']);
        $s['group'] = $s['status'] === 'cancelled' ? 'cancelled' : ($upcoming ? 'upcoming' : 'completed');
        $s['can_edit'] = $upcoming;
        $s['can_cancel'] = $upcoming;
        $s['can_change_fee'] = $upcoming && $s['live_count'] === 0;
        $s['min_capacity'] = max(1, $s['live_count']);
        $s['share_path'] = $s['visibility'] === 'private' ? '/sessions/private/' . $s['access_token'] : '/sessions/' . $s['id'];
        return $s;
    }
}
