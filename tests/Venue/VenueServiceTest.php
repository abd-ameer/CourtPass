<?php
/**
 * VenueService against the seed data. Seed ids used here:
 * admin 1; owners 2 kamal (venues 1 Colombo Sports Hub and 2 Kandy Court Zone, both approved), 3 nimal (venue 3, pending, no courts);
 * sport types 1 futsal, 2 badminton, 3 pickleball, 4 squash, 7 table tennis; venue 1 has courts 1 to 5, venue 2 has courts 6 to 8.
 */
class VenueServiceTest extends DatabaseTestCase
{
    private VenueService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VenueService();
    }

    private static function input(array $overrides = []): array
    {
        return $overrides + [
            'name'           => 'Nugegoda Smash Arena',
            'city'           => 'Nugegoda',
            'address'        => '12 Stanley Road',
            'contact_phone'  => '0112233445',
            'description'    => 'Two badminton courts.',
            'sport_type_ids' => ['2', '3'],
        ];
    }

    // Create

    public function testCreatedVenueIsPendingWithItsSports(): void
    {
        $id = $this->service->create(3, self::input());

        $venue = $this->fetchRow("SELECT * FROM venues WHERE id = {$id}");
        $this->assertSame('pending', $venue['status']);
        $this->assertSame('3', $venue['owner_id']);
        $this->assertSame('nugegoda-smash-arena', $venue['slug']);
        $this->assertSame('1', $venue['is_active']);
        $this->assertSame('2,3', $this->fetchValue("SELECT GROUP_CONCAT(sport_type_id ORDER BY sport_type_id) FROM venue_sports WHERE venue_id = {$id}"));
        $this->assertSame('pending', $this->fetchValue("SELECT new_status FROM audit_log WHERE event_type = 'venue.created' AND entity_id = {$id}"));
    }

    public function testSlugGetsANumberWhenTheNameIsTaken(): void
    {
        $first = $this->service->create(3, self::input(['name' => 'Colombo Sports Hub']));
        $second = $this->service->create(3, self::input(['name' => 'Colombo  Sports  Hub!']));

        $this->assertSame('colombo-sports-hub-2', $this->fetchValue("SELECT slug FROM venues WHERE id = {$first}"));
        $this->assertSame('colombo-sports-hub-3', $this->fetchValue("SELECT slug FROM venues WHERE id = {$second}"));
    }

    public function testNameWithoutLatinLettersGetsAGenericSlug(): void
    {
        $id = $this->service->create(3, self::input(['name' => 'ක්‍රීඩා පිටිය']));

        $this->assertSame('venue', $this->fetchValue("SELECT slug FROM venues WHERE id = {$id}"));
    }

    public function testSlugStaysFixedWhenTheNameChanges(): void
    {
        $this->service->update(2, 1, self::input(['name' => 'Colombo Sports Hub Plus', 'sport_type_ids' => ['1', '2', '7']]));

        $this->assertSame('colombo-sports-hub', $this->fetchValue('SELECT slug FROM venues WHERE id = 1'));
        $this->assertSame('Colombo Sports Hub Plus', $this->fetchValue('SELECT name FROM venues WHERE id = 1'));
    }

    public function testCreateNeedsAtLeastOneKnownSport(): void
    {
        foreach ([[], ['99'], 'not-a-list'] as $sports) {
            try {
                $this->service->create(3, self::input(['sport_type_ids' => $sports]));
                $this->fail('Expected a sport type error.');
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('sport_type_ids', $e->errors());
            }
        }
        $this->assertSame(3, (int) $this->fetchValue('SELECT COUNT(*) FROM venues'));
    }

    // Read

    public function testOwnersSeeOnlyTheirOwnVenues(): void
    {
        $this->assertSame([2, 1], array_column($this->service->ownerVenues(2), 'id'));
        $this->assertSame([3], array_column($this->service->ownerVenues(3), 'id'));
        $this->assertNull($this->service->ownerVenue(3, 1));
        $this->assertNull($this->service->ownerVenue(2, 999));
    }

    public function testOwnerVenueHasSportsCourtsAndFlags(): void
    {
        $venue = $this->service->ownerVenue(2, 1);

        $this->assertSame(['Futsal', 'Badminton', 'Table Tennis'], array_column($venue['sports'], 'name'));
        $this->assertSame([1, 2, 3, 4, 5], array_column($venue['courts'], 'id'));
        $this->assertSame(5, $venue['court_count']);
        $this->assertSame('/venue/colombo-sports-hub', $venue['public_path']);
        $this->assertTrue($venue['listed']);
        $this->assertTrue($venue['can_add_court']);
        $this->assertTrue($venue['can_deactivate']);
        $this->assertFalse($venue['can_activate']);

        $pending = $this->service->ownerVenue(3, 3);
        $this->assertNull($pending['public_path']);
        $this->assertFalse($pending['can_add_court']);
        $this->assertFalse($pending['can_deactivate']);
    }

    // Update

    public function testEditingAnApprovedVenueKeepsItApproved(): void
    {
        $resubmitted = $this->service->update(2, 2, self::input([
            'name' => 'Kandy Court Zone', 'city' => 'Kandy', 'sport_type_ids' => ['4', '5', '6', '2'],
        ]));

        $this->assertFalse($resubmitted);
        $venue = $this->fetchRow('SELECT status, contact_phone, city FROM venues WHERE id = 2');
        $this->assertSame(['status' => 'approved', 'contact_phone' => '0112233445', 'city' => 'Kandy'], $venue);
        $this->assertSame('2,4,5,6', $this->fetchValue('SELECT GROUP_CONCAT(sport_type_id ORDER BY sport_type_id) FROM venue_sports WHERE venue_id = 2'));
    }

    public function testEditingARejectedVenueResubmitsIt(): void
    {
        $this->service->reject(1, 3, 'Address could not be verified.');

        $this->assertTrue($this->service->update(3, 3, self::input(['name' => 'Galle Racquet Club'])));
        $venue = $this->fetchRow('SELECT status, rejection_reason, reviewed_by, reviewed_at FROM venues WHERE id = 3');
        $this->assertSame(['status' => 'pending', 'rejection_reason' => null, 'reviewed_by' => null, 'reviewed_at' => null], $venue);
        $this->assertSame([3], array_column($this->service->pendingVenues(), 'id'));
    }

    public function testUpdateAuditRecordsRemovedAndAddedSports(): void
    {
        $this->service->update(3, 3, self::input(['name' => 'Galle Racquet Club', 'sport_type_ids' => ['2', '7']]));

        $details = json_decode($this->fetchValue(
            "SELECT details FROM audit_log WHERE event_type = 'venue.updated' AND entity_id = 3"
        ), true);
        $this->assertSame([2, 7], $details['sport_type_ids']);
        $this->assertSame([3], $details['removed_sport_type_ids']);
        $this->assertSame([7], $details['added_sport_type_ids']);
    }

    public function testASportUsedByACourtCannotBeRemoved(): void
    {
        try {
            $this->service->update(2, 1, self::input(['sport_type_ids' => ['2', '7']]));
            $this->fail('Expected a sport type error.');
        } catch (ValidationException $e) {
            $this->assertStringContainsString('Futsal', $e->errors()['sport_type_ids']);
        }
        $this->assertSame('1,2,7', $this->fetchValue('SELECT GROUP_CONCAT(sport_type_id ORDER BY sport_type_id) FROM venue_sports WHERE venue_id = 1'));
        $this->assertSame('Colombo Sports Hub', $this->fetchValue('SELECT name FROM venues WHERE id = 1'), 'Nothing is saved when the update fails.');
    }

    public function testAnotherOwnersVenueCannotBeUpdated(): void
    {
        $this->expectException(ValidationException::class);
        $this->service->update(3, 1, self::input());
    }

    // Deactivate and activate

    public function testDeactivatingHidesTheVenueAndStopsBookingsUntilActivated(): void
    {
        $this->service->deactivate(2, 2);

        $this->assertSame('0', $this->fetchValue('SELECT is_active FROM venues WHERE id = 2'));
        $this->assertSame('approved', $this->fetchValue('SELECT status FROM venues WHERE id = 2'));
        $this->assertNull((new BookingService())->slotGrid(6, self::day(1)));
        $this->assertSame([1], array_column($this->service->publicVenues(), 'id'));
        $this->assertSame('venue.deactivated', $this->fetchValue("SELECT event_type FROM audit_log WHERE entity_type = 'venue' AND entity_id = 2 ORDER BY id DESC LIMIT 1"));

        $this->service->activate(2, 2);
        $this->assertSame('1', $this->fetchValue('SELECT is_active FROM venues WHERE id = 2'));
        $this->assertNotNull((new BookingService())->slotGrid(6, self::day(1)));
    }

    public function testDeactivationKeepsExistingBookings(): void
    {
        $before = $this->fetchValue("SELECT GROUP_CONCAT(id, status ORDER BY id) FROM bookings");
        $this->service->deactivate(2, 1);

        $this->assertSame($before, $this->fetchValue("SELECT GROUP_CONCAT(id, status ORDER BY id) FROM bookings"));
    }

    public function testOnlyTheOwnerOfAnApprovedVenueCanSwitchIt(): void
    {
        $cases = [
            'pending venue'   => fn () => $this->service->deactivate(3, 3),
            'other owner'     => fn () => $this->service->deactivate(3, 1),
            'already active'  => fn () => $this->service->activate(2, 1),
        ];
        foreach ($cases as $label => $case) {
            try {
                $case();
                $this->fail("Expected a refusal: {$label}.");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('venue', $e->errors(), $label);
            }
        }
        $this->service->deactivate(2, 1);
        $this->expectException(ValidationException::class);
        $this->service->deactivate(2, 1);
    }

    // Admin decisions

    public function testApprovingAPendingVenueListsItAndNotifiesTheOwner(): void
    {
        $this->assertSame([3], array_column($this->service->pendingVenues(), 'id'));

        $this->service->approve(1, 3);

        $venue = $this->fetchRow('SELECT status, reviewed_by, reviewed_at FROM venues WHERE id = 3');
        $this->assertSame('approved', $venue['status']);
        $this->assertSame('1', $venue['reviewed_by']);
        $this->assertNotNull($venue['reviewed_at']);
        $this->assertSame([], $this->service->pendingVenues());
        $this->assertSame('venue_approved', $this->fetchValue('SELECT type FROM notifications WHERE user_id = 3 ORDER BY id DESC LIMIT 1'));
        $this->assertSame('pending', $this->fetchValue("SELECT old_status FROM audit_log WHERE event_type = 'venue.approved' AND entity_id = 3"));
    }

    public function testRejectingStoresTheReasonAndTellsTheOwner(): void
    {
        $this->service->reject(1, 3, 'Photos show an outdoor court.');

        $this->assertSame(['status' => 'rejected', 'rejection_reason' => 'Photos show an outdoor court.'],
            $this->fetchRow('SELECT status, rejection_reason FROM venues WHERE id = 3'));
        $this->assertStringContainsString('Photos show an outdoor court.',
            $this->fetchValue("SELECT message FROM notifications WHERE user_id = 3 AND type = 'venue_rejected'"));
    }

    public function testOnlyPendingVenuesCanBeDecided(): void
    {
        foreach ([fn () => $this->service->approve(1, 1), fn () => $this->service->reject(1, 2, 'No.'), fn () => $this->service->approve(1, 999)] as $case) {
            try {
                $case();
                $this->fail('Expected a refusal.');
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('venue', $e->errors());
            }
        }
        $this->assertSame('approved', $this->fetchValue('SELECT status FROM venues WHERE id = 2'));
    }

    public function testAdminCanReadAnyVenueWithItsOwner(): void
    {
        $venue = $this->service->adminVenue(3);

        $this->assertSame('Galle Racquet Club', $venue['name']);
        $this->assertSame('nimal@courtzone.lk', $venue['owner_email']);
        $this->assertTrue($venue['can_decide']);
        $this->assertSame([], $venue['courts']);
        $this->assertNull($this->service->adminVenue(999));
    }

    // Public reads for discovery

    public function testPublicListingShowsOnlyApprovedActiveVenues(): void
    {
        $this->assertSame([1, 2], array_column($this->service->publicVenues(), 'id'));
        $this->assertSame([1], array_column($this->service->publicVenues(2), 'id'));
        $this->assertSame([2], array_column($this->service->publicVenues(null, 'Kandy'), 'id'));
        $this->assertSame([1], array_column($this->service->publicVenues(null, ' ', 'galle road'), 'id'));
        $this->assertSame([], $this->service->publicVenues(null, null, 'Racquet'), 'Pending venues are not listed.');

        $hub = $this->service->publicVenues()[0];
        $this->assertSame(5, $hub['court_count']);
        $this->assertSame(1000.0, $hub['min_rate']);
    }

    public function testPublicVenueBySlugListsOnlyActiveCourts(): void
    {
        $this->execute('UPDATE courts SET is_active = 0 WHERE id = 2');

        $venue = $this->service->publicVenueBySlug('colombo-sports-hub');
        $this->assertSame([1, 3, 4, 5], array_column($venue['courts'], 'id'));
        $this->assertSame(['Futsal', 'Badminton', 'Table Tennis'], array_column($venue['sports'], 'name'));

        $this->assertNull($this->service->publicVenueBySlug('galle-racquet-club'));
        $this->service->deactivate(2, 1);
        $this->assertNull($this->service->publicVenueBySlug('colombo-sports-hub'));
    }
}
