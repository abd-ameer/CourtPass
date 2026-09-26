<?php
/**
 * CoachSessionService against the seed data. Seed ids used here:
 * coaches 7 ashan (verified, badminton and pickleball, approved at venue 1) and 8 dilani (futsal, pending at venue 1);
 * venue 1 courts 1 and 2 futsal, 3 and 4 badminton; venue 2 court 6 squash; customers 4 saman, 5 ruwan;
 * session 1 completed; session 2 public on court 4, day +5 08:00, capacity 4, fee 1800, block 3, with saman's
 * paid registration 3 (payment 6); session 3 private on court 3, day +6 08:00, block 4; booking 5 released on court 3, day +2 20:00.
 */
class CoachSessionServiceTest extends DatabaseTestCase
{
    private const TOKEN = '9f2c4e6a8b0d1f3e5a7c9e1b3d5f7a90';

    private CoachSessionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CoachSessionService();
    }

    private static function input(array $overrides = []): array
    {
        return $overrides + [
            'court_id'     => 3,
            'session_date' => self::day(1),
            'start_time'   => '10:00',
            'title'        => 'Net Play Clinic',
            'description'  => 'Short serves and net kills.',
            'capacity'     => '6',
            'fee'          => '1500',
            'visibility'   => 'public',
        ];
    }

    private function slotState(int $courtId, int $day, string $start): string
    {
        return array_column((new BookingService())->slotGrid($courtId, self::day($day)), 'state', 'start')[$start];
    }

    private function addRegistration(int $sessionId, int $customerId, string $status = 'registered'): int
    {
        $extra = match ($status) {
            'pending_payment' => ", pending_expires_at = '" . date('Y-m-d H:i:s', time() + 900) . "'",
            'cancelled'       => ", cancel_source = 'customer', cancelled_at = '" . now() . "'",
            default           => '',
        };
        $this->execute("INSERT INTO session_registrations SET session_id = {$sessionId}, customer_id = {$customerId},
            status = '{$status}', amount = 1800{$extra}");
        return (int) $this->fetchValue('SELECT MAX(id) FROM session_registrations');
    }

    // Create

    public function testCreatedPublicSessionTakesTheSlotThroughACoachingBlock(): void
    {
        $id = $this->service->create(7, self::input());

        $row = $this->fetchRow("SELECT * FROM coach_sessions WHERE id = {$id}");
        $this->assertSame(['open', 'public', null, '10:00:00', '6', '1500.00'],
            [$row['status'], $row['visibility'], $row['access_token'], $row['start_time'], $row['capacity'], $row['fee']]);
        $block = $this->fetchRow("SELECT court_id, block_date, start_time, block_type, created_by FROM court_blocks WHERE id = {$row['block_id']}");
        $this->assertSame(['court_id' => '3', 'block_date' => self::day(1), 'start_time' => '10:00:00', 'block_type' => 'coaching', 'created_by' => '7'], $block);
        $this->assertSame('blocked', $this->slotState(3, 1, '10:00'));
        $this->assertSame('open', $this->fetchValue("SELECT new_status FROM audit_log WHERE event_type = 'session.created' AND entity_id = {$id}"));
    }

    public function testPrivateSessionGetsATokenAndIsOnlyReachableByIt(): void
    {
        $id = $this->service->create(7, self::input(['visibility' => 'private']));

        $token = $this->fetchValue("SELECT access_token FROM coach_sessions WHERE id = {$id}");
        $this->assertMatchesRegularExpression('/^[0-9a-f]{32}$/', $token);
        $this->assertSame($id, $this->service->privateSession($token)['id']);
        $this->assertNull($this->service->publicSession($id));
        $this->assertSame('/sessions/private/' . $token, $this->service->privateSession($token)['share_path']);
    }

    public function testFreeSessionIsAllowedAndLabelledFree(): void
    {
        $id = $this->service->create(7, self::input(['fee' => '0']));

        $session = $this->service->publicSession($id);
        $this->assertSame(0.0, $session['fee']);
        $this->assertSame('Free', $session['fee_label']);
        $this->assertSame('LKR 1,800', $this->service->publicSession(2)['fee_label']);
    }

    public function testCreateRulesAreEnforcedAndNothingIsLeftBehind(): void
    {
        $this->execute("INSERT INTO bookings (customer_id, court_id, slot_date, start_time, amount, payment_method, status, confirmed_at)
            VALUES (4, 3, '" . self::day(1) . "', '12:00:00', 2000, 'cash_on_arrival', 'confirmed', NOW())");
        $cases = [
            'coach not approved at venue' => [8, ['court_id' => 1], 'court_id'],
            'sport not coached'           => [7, ['court_id' => 1], 'court_id'],
            'venue not approved for coach' => [7, ['court_id' => 6], 'court_id'],
            'unknown court'               => [7, ['court_id' => 999], 'court_id'],
            'booked slot'                 => [7, ['start_time' => '12:00'], 'slot'],
            'released booking'            => [7, ['session_date' => self::day(2), 'start_time' => '20:00'], 'slot'],
            'existing coaching session'   => [7, ['court_id' => 4, 'session_date' => self::day(5), 'start_time' => '08:00'], 'slot'],
            'court closed'                => [7, ['start_time' => '03:00'], 'slot'],
            'not on the hour'             => [7, ['start_time' => '10:30'], 'slot'],
            'yesterday'                   => [7, ['session_date' => self::day(-1)], 'session_date'],
            'beyond the window'           => [7, ['session_date' => self::day(7)], 'session_date'],
            'bad date'                    => [7, ['session_date' => '2026-02-30'], 'session_date'],
            'bad visibility'              => [7, ['visibility' => 'hidden'], 'visibility'],
            'no title'                    => [7, ['title' => '  '], 'title'],
            'long title'                  => [7, ['title' => str_repeat('a', 101)], 'title'],
            'no description'              => [7, ['description' => ''], 'description'],
            'zero capacity'               => [7, ['capacity' => '0'], 'capacity'],
            'capacity too high'           => [7, ['capacity' => '51'], 'capacity'],
            'negative fee'                => [7, ['fee' => '-1'], 'fee'],
            'fee too high'                => [7, ['fee' => '100001'], 'fee'],
        ];
        foreach ($cases as $label => [$coach, $overrides, $field]) {
            try {
                $this->service->create($coach, self::input($overrides));
                $this->fail("Expected a refusal: {$label}.");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey($field, $e->errors(), $label);
            }
        }
        $this->assertSame(3, (int) $this->fetchValue('SELECT COUNT(*) FROM coach_sessions'));
        $this->assertSame(4, (int) $this->fetchValue('SELECT COUNT(*) FROM court_blocks'));
    }

    public function testInactiveCourtOrVenueRefusesNewSessions(): void
    {
        $this->execute('UPDATE courts SET is_active = 0 WHERE id = 3');
        try {
            $this->service->create(7, self::input());
            $this->fail('An inactive court cannot take sessions.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('court_id', $e->errors());
        }

        $this->execute('UPDATE venues SET is_active = 0 WHERE id = 1');
        $this->expectException(ValidationException::class);
        $this->service->create(7, self::input(['court_id' => 4]));
    }

    public function testAFailureAfterTheBlockInsertLeavesNoBlock(): void
    {
        $this->execute("CREATE TRIGGER trg_test_fail_session BEFORE INSERT ON coach_sessions FOR EACH ROW
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'test failure'");
        try {
            $this->service->create(7, self::input());
            $this->fail('The session insert should fail.');
        } catch (mysqli_sql_exception $e) {
            $this->assertStringContainsString('test failure', $e->getMessage());
        } finally {
            $this->execute('DROP TRIGGER trg_test_fail_session');
        }
        $this->assertSame(4, (int) $this->fetchValue('SELECT COUNT(*) FROM court_blocks'));
        $this->assertSame('available', $this->slotState(3, 1, '10:00'));
    }

    // Update

    public function testEditingChangesDetailsAndFollowsCapacity(): void
    {
        $this->service->update(7, 2, self::input(['title' => 'Rally Drills Plus', 'capacity' => '1', 'fee' => '1800']));

        $row = $this->fetchRow('SELECT title, capacity, fee, status FROM coach_sessions WHERE id = 2');
        $this->assertSame(['title' => 'Rally Drills Plus', 'capacity' => '1', 'fee' => '1800.00', 'status' => 'full'], $row);

        $this->service->update(7, 2, self::input(['capacity' => '5', 'fee' => '1800']));
        $this->assertSame('open', $this->fetchValue('SELECT status FROM coach_sessions WHERE id = 2'));
        $this->assertSame('full', $this->fetchValue("SELECT old_status FROM audit_log WHERE event_type = 'session.updated' ORDER BY id DESC LIMIT 1"));
    }

    public function testCapacityCannotGoBelowLiveRegistrations(): void
    {
        $this->addRegistration(2, 5, 'pending_payment');

        try {
            $this->service->update(7, 2, self::input(['capacity' => '1', 'fee' => '1800']));
            $this->fail('Two live registrations need capacity 2.');
        } catch (ValidationException $e) {
            $this->assertStringContainsString('2 current registrations', $e->errors()['capacity']);
        }
        $this->assertSame('4', $this->fetchValue('SELECT capacity FROM coach_sessions WHERE id = 2'));
        $this->assertSame(2, $this->service->coachSession(7, 2)['min_capacity']);
    }

    public function testFeeIsLockedOnlyWhileSomeoneIsRegistered(): void
    {
        try {
            $this->service->update(7, 2, self::input(['capacity' => '4', 'fee' => '2000']));
            $this->fail('Session 2 has a live registration.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('fee', $e->errors());
        }
        $this->assertFalse($this->service->coachSession(7, 2)['can_change_fee']);

        $this->addRegistration(3, 5, 'cancelled');
        $this->assertTrue($this->service->coachSession(7, 3)['can_change_fee']);
        $this->service->update(7, 3, self::input(['capacity' => '3', 'fee' => '0']));
        $this->assertSame('0.00', $this->fetchValue('SELECT fee FROM coach_sessions WHERE id = 3'));
    }

    public function testOnlyTheCoachsUpcomingSessionsCanBeEdited(): void
    {
        foreach ([[8, 2], [7, 1], [7, 999]] as [$coach, $session]) {
            try {
                $this->service->update($coach, $session, self::input());
                $this->fail("Expected a refusal for coach {$coach}, session {$session}.");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('session', $e->errors());
            }
        }
        $this->assertSame('Beginner Badminton Basics', $this->fetchValue('SELECT title FROM coach_sessions WHERE id = 1'));
    }

    // Cancel

    public function testCancellingReleasesTheSlotAndRefundsRegistrantsInFull(): void
    {
        $result = $this->service->cancel(7, 2, 'Coach unwell');

        $this->assertSame(['registrations' => 1, 'refund_total' => 1800.0], $result);
        $row = $this->fetchRow('SELECT status, block_id, cancelled_by, cancel_reason, cancelled_at FROM coach_sessions WHERE id = 2');
        $this->assertSame(['cancelled', null, '7', 'Coach unwell'], [$row['status'], $row['block_id'], $row['cancelled_by'], $row['cancel_reason']]);
        $this->assertNotNull($row['cancelled_at']);
        $this->assertSame(0, (int) $this->fetchValue('SELECT COUNT(*) FROM court_blocks WHERE id = 3'));
        $this->assertSame('available', $this->slotState(4, 5, '08:00'));

        $this->assertSame(['status' => 'cancelled', 'cancel_source' => 'session_cancelled'],
            $this->fetchRow('SELECT status, cancel_source FROM session_registrations WHERE id = 3'));
        $this->assertSame(['payment_id' => '6', 'reason' => 'session_cancelled', 'percentage' => '100.00', 'amount' => '1800.00'],
            $this->fetchRow('SELECT payment_id, reason, percentage, amount FROM refunds WHERE payment_id = 6'));
        $message = $this->fetchValue("SELECT message FROM notifications WHERE user_id = 4 AND type = 'session_cancelled'");
        $this->assertStringContainsString('Coach unwell', $message);
        $this->assertStringContainsString('LKR 1,800', $message);
        $this->assertSame('1', $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'registration.cancelled' AND entity_id = 3"));
        $this->assertSame('open', $this->fetchValue("SELECT old_status FROM audit_log WHERE event_type = 'session.cancelled' AND entity_id = 2"));
    }

    public function testCancellingAnUnpaidOrFreeRegistrationRefundsNothing(): void
    {
        $reg = $this->addRegistration(3, 5, 'pending_payment');

        $result = $this->service->cancel(7, 3, 'Family could not make it');

        $this->assertSame(['registrations' => 1, 'refund_total' => 0.0], $result);
        $this->assertSame('cancelled', $this->fetchValue("SELECT status FROM session_registrations WHERE id = {$reg}"));
        $this->assertSame(0, (int) $this->fetchValue('SELECT COUNT(*) FROM refunds'));
        $this->assertStringNotContainsString('refund', $this->fetchValue("SELECT message FROM notifications WHERE user_id = 5 AND type = 'session_cancelled'"));
    }

    public function testCancelNeedsAReasonAndAnUpcomingSessionOfTheCoach(): void
    {
        $cases = [
            'no reason'      => [7, 2, '   ', 'reason'],
            'long reason'    => [7, 2, str_repeat('a', 501), 'reason'],
            'other coach'    => [8, 2, 'Busy', 'session'],
            'completed'      => [7, 1, 'Busy', 'session'],
            'unknown'        => [7, 999, 'Busy', 'session'],
        ];
        foreach ($cases as $label => [$coach, $session, $reason, $field]) {
            try {
                $this->service->cancel($coach, $session, $reason);
                $this->fail("Expected a refusal: {$label}.");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey($field, $e->errors(), $label);
            }
        }
        $this->assertSame('open', $this->fetchValue('SELECT status FROM coach_sessions WHERE id = 2'));
        $this->assertSame(4, (int) $this->fetchValue('SELECT COUNT(*) FROM court_blocks'));

        $this->service->cancel(7, 2, 'Coach unwell');
        $this->expectException(ValidationException::class);
        $this->service->cancel(7, 2, 'Again');
    }

    // Reads and trigger-on-action completion

    public function testPastOpenSessionsBecomeCompletedOnRead(): void
    {
        $this->execute("UPDATE coach_sessions SET session_date = '" . self::day(-1) . "' WHERE id = 2");

        $this->assertSame([], array_column($this->service->publicSessions(), 'id'));
        $this->assertSame('completed', $this->fetchValue('SELECT status FROM coach_sessions WHERE id = 2'));
        $audit = $this->fetchRow("SELECT actor_id, old_status FROM audit_log WHERE event_type = 'session.completed' AND entity_id = 2");
        $this->assertSame(['actor_id' => null, 'old_status' => 'open'], $audit);
        $this->assertSame(0, $this->service->completePast());
    }

    public function testPublicListingShowsUpcomingPublicSessionsWithFilters(): void
    {
        $this->assertSame([2], array_column($this->service->publicSessions(), 'id'));
        $this->assertSame([2], array_column($this->service->publicSessions('badminton', 1, self::day(5)), 'id'));
        $this->assertSame([], $this->service->publicSessions('futsal'));
        $this->assertSame([], $this->service->publicSessions(null, 2));
        $this->assertSame([], $this->service->publicSessions(null, null, self::day(4)));

        $card = $this->service->publicSessions()[0];
        $this->assertSame([1, 4, 3, 'Badminton', 4.0, true],
            [$card['registered_count'], $card['capacity'], $card['spots_left'], $card['sport'], $card['coach_rating'], $card['coach_verified']]);
    }

    public function testPrivateSessionIsHiddenByIdAndFoundByToken(): void
    {
        $this->assertNull($this->service->publicSession(3));
        $this->assertNull($this->service->publicSession(999));
        $this->assertSame(3, $this->service->privateSession(self::TOKEN)['id']);
        $this->assertNull($this->service->privateSession(str_repeat('0', 32)));
        $this->assertSame('completed', $this->service->publicSession(1)['status'], 'Past public sessions stay viewable.');
    }

    public function testCoachSeesOnlyOwnSessionsWithCountsAndFilters(): void
    {
        $all = $this->service->coachSessions(7);
        $this->assertSame([3, 2, 1], array_column($all['sessions'], 'id'));
        $this->assertSame(['' => 3, 'upcoming' => 2, 'completed' => 1, 'cancelled' => 0], $all['counts']);
        $this->assertSame([1 => 'Colombo Sports Hub'], $all['venues']);

        $this->assertSame([2, 3], array_column($this->service->coachSessions(7, 'upcoming')['sessions'], 'id'));
        $this->assertSame([1], array_column($this->service->coachSessions(7, 'completed')['sessions'], 'id'));
        $this->assertSame([], $this->service->coachSessions(7, '', 2)['sessions']);
        $this->assertSame(0, $this->service->coachSessions(8)['counts']['']);
        $completed = $this->service->coachSessions(7, 'completed')['sessions'][0];
        $this->assertSame([0, 2], [$completed['live_count'], $completed['registration_count']], 'Attended and absent still count as taken places.');
    }

    public function testCoachSessionIncludesRegistrationsAndChecksOwnership(): void
    {
        $session = $this->service->coachSession(7, 2);

        $this->assertSame(['Saman Fernando'], array_column($session['registrations'], 'customer_name'));
        $this->assertSame(1800.0, $session['registrations'][0]['paid_amount']);
        $this->assertTrue($session['can_edit']);
        $this->assertSame('/sessions/2', $session['share_path']);
        $this->assertFalse($this->service->coachSession(7, 1)['can_cancel']);
        $this->assertNull($this->service->coachSession(8, 2));
    }

    public function testSessionVenuesListOnlyApprovedVenuesAndCoachedSports(): void
    {
        $venues = $this->service->sessionVenues(7);
        $this->assertSame([1], array_column($venues, 'id'));
        $this->assertSame([3, 4], array_column($venues[0]['courts'], 'id'));
        $this->assertSame([], $this->service->sessionVenues(8));

        $this->execute("UPDATE coach_venue_approvals SET status = 'approved', decided_by = 2, decided_at = NOW() WHERE id = 2");
        $this->assertSame([1, 2], array_column($this->service->sessionVenues(8)[0]['courts'], 'id'));
    }
}
