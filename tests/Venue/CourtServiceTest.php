<?php
/**
 * Court setup in CourtService. Seed ids: owners 2 kamal (approved venues 1 and 2), 3 nimal (venue 3, pending, sports 2 badminton and 3 pickleball);
 * venue 1 already has a court named Badminton Court 1.
 */
class CourtServiceTest extends DatabaseTestCase
{
    private CourtService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CourtService();
    }

    private static function week(string $open = '07:00', string $close = '21:00'): array
    {
        return array_fill_keys(range(1, 7), ['open' => $open, 'close' => $close]);
    }

    public function testCourtsCanOnlyBeAddedToApprovedVenues(): void
    {
        $this->assertSame([1, 2], array_column($this->service->ownerCourtVenues(2), 'id'));
        $this->assertSame([], $this->service->ownerCourtVenues(3));

        try {
            $this->service->addCourt(3, 3, 'Court A', 2, 1500, self::week());
            $this->fail('A pending venue cannot take courts.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('venue_id', $e->errors());
        }

        (new VenueService())->approve(1, 3);
        $this->assertSame([3], array_column($this->service->ownerCourtVenues(3), 'id'));
    }

    public function testAnApprovedVenueStaysOpenForCourtsWhileDeactivated(): void
    {
        (new VenueService())->deactivate(2, 2);

        $this->assertSame([1, 2], array_column($this->service->ownerCourtVenues(2), 'id'));
        $id = $this->service->addCourt(2, 2, 'Squash Court 2', 4, 3000, self::week());
        $this->assertNull($this->service->bookableCourt($id), 'Not bookable until the venue is active again.');
    }

    public function testNewCourtOnAnApprovedVenueIsBookableWithItsHours(): void
    {
        (new VenueService())->approve(1, 3);
        $id = $this->service->addCourt(3, 3, 'Court A', 2, 1500, self::week());

        $this->assertNotNull($this->service->bookableCourt($id));
        $starts = $this->service->slotStarts($id, self::day(1));
        $this->assertSame('07:00', $starts[0]);
        $this->assertSame('20:00', end($starts));
        $this->assertCount(14, $starts);
        $this->assertCount(14, (new BookingService())->slotGrid($id, self::day(1)));
        $this->assertSame('1500.00', $this->fetchValue("SELECT hourly_rate FROM courts WHERE id = {$id}"));
        $this->assertSame('court.created', $this->fetchValue("SELECT event_type FROM audit_log WHERE entity_type = 'court' AND entity_id = {$id}"));
    }

    public function testClosedDaysHaveNoSlotsAndMidnightCloseIsAllowed(): void
    {
        $tomorrow = (int) date('N', strtotime(self::day(1)));
        $hours = [$tomorrow => ['open' => '18:00', 'close' => '24:00']];
        $id = $this->service->addCourt(2, 1, 'Late Court', 2, 2000, $hours);

        $this->assertSame(['18:00', '19:00', '20:00', '21:00', '22:00', '23:00'], $this->service->slotStarts($id, self::day(1)));
        $this->assertSame([], $this->service->slotStarts($id, self::day(2)));
    }

    public function testCourtRulesAreEnforced(): void
    {
        $cases = [
            'other owner venue'   => [3, 1, 'Court X', 2, self::week(), 'venue_id'],
            'unknown venue'       => [2, 999, 'Court X', 2, self::week(), 'venue_id'],
            'sport not offered'   => [2, 1, 'Court X', 4, self::week(), 'sport_type_id'],
            'duplicate name'      => [2, 1, 'Badminton Court 1', 2, self::week(), 'name'],
            'close before open'   => [2, 1, 'Court X', 2, self::week('20:00', '08:00'), 'hours'],
            'not on the hour'     => [2, 1, 'Court X', 2, self::week('07:30', '21:00'), 'hours'],
            'past midnight'       => [2, 1, 'Court X', 2, self::week('07:00', '25:00'), 'hours'],
            'bad day'             => [2, 1, 'Court X', 2, [8 => ['open' => '07:00', 'close' => '21:00']], 'hours'],
            'closed all week'     => [2, 1, 'Court X', 2, [], 'hours'],
        ];
        foreach ($cases as $label => [$owner, $venue, $name, $sport, $hours, $field]) {
            try {
                $this->service->addCourt($owner, $venue, $name, $sport, 2000, $hours);
                $this->fail("Expected a refusal: {$label}.");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey($field, $e->errors(), $label);
            }
        }
        $this->assertSame(8, (int) $this->fetchValue('SELECT COUNT(*) FROM courts'));
        $this->assertSame(0, (int) $this->fetchValue("SELECT COUNT(*) FROM audit_log WHERE event_type = 'court.created'"));
    }

    public function testOwnerCourtIncludesHoursAndChecksOwnership(): void
    {
        $court = $this->service->ownerCourt(2, 3);

        $this->assertSame('Badminton Court 1', $court['name']);
        $this->assertCount(7, $court['hours']);
        $this->assertSame(['open' => '08:00', 'close' => '23:00'], $court['hours'][7]);
        $this->assertNull($this->service->ownerCourt(3, 3));
        $this->assertNull($this->service->ownerCourt(2, 999));
    }

    public function testUpdatingHoursChangesTheSlotsAndClosesMissingDays(): void
    {
        $tomorrow = (int) date('N', strtotime(self::day(1)));
        $this->service->updateHours(2, 3, [$tomorrow => ['open' => '10:00', 'close' => '12:00']]);

        $this->assertSame(['10:00', '11:00'], $this->service->slotStarts(3, self::day(1)));
        $this->assertSame([], $this->service->slotStarts(3, self::day(2)));
        $this->assertSame(1, (int) $this->fetchValue('SELECT COUNT(*) FROM court_operating_hours WHERE court_id = 3'));
        $this->assertSame('court.hours_updated', $this->fetchValue("SELECT event_type FROM audit_log WHERE entity_type = 'court' AND entity_id = 3"));
    }

    public function testOnlyTheOwnerCanUpdateHoursAndBadHoursChangeNothing(): void
    {
        foreach ([[3, 3, self::week()], [2, 3, self::week('09:00', '09:00')]] as [$owner, $court, $hours]) {
            try {
                $this->service->updateHours($owner, $court, $hours);
                $this->fail('Expected a refusal.');
            } catch (ValidationException $e) {
                $this->assertNotEmpty($e->errors());
            }
        }
        $this->assertSame(7, (int) $this->fetchValue('SELECT COUNT(*) FROM court_operating_hours WHERE court_id = 3'));
    }
}
