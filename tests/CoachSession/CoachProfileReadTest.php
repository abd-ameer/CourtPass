<?php
/**
 * Read methods behind the coach profile, dashboard, owner coach pages and My Sessions.
 * Seed ids: coaches 7 ashan (verified, approved at venue 1) and 8 dilani (pending at venue 1); owners 2 kamal, 3 nimal;
 * customers 4 saman (registrations 1 attended and reviewed, 3 registered) and 5 ruwan (registration 2 absent).
 */
class CoachProfileReadTest extends DatabaseTestCase
{
    public function testProfileReadsTheCoachsOwnRows(): void
    {
        $service = new CoachService();

        $ashan = $service->profile(7);
        $this->assertTrue($ashan['is_verified']);
        $this->assertSame('Badminton, Pickleball', $ashan['sport_names']);
        $this->assertSame(4.0, $ashan['avg_rating']);
        $this->assertSame(1, $ashan['review_count']);
        $this->assertSame(['Colombo Sports Hub'], array_column($ashan['venues'], 'name'));

        $dilani = $service->profile(8);
        $this->assertFalse($dilani['is_verified']);
        $this->assertSame('Futsal', $dilani['sport_names']);
        $this->assertSame([1], $dilani['sport_type_ids']);
        $this->assertSame([], $dilani['venues'], 'A pending request is not an approved venue.');
        $this->assertNull($dilani['avg_rating']);

        $this->assertNull($service->profile(4), 'A customer has no coach profile.');
    }

    public function testCoachReviewsAndAdminList(): void
    {
        $service = new CoachService();
        $reviews = $service->reviews(7);
        $this->assertSame([2], array_column($reviews, 'id'));
        $this->assertSame('Beginner Badminton Basics', $reviews[0]['session_title']);
        $this->assertSame([], $service->reviews(8));

        $this->assertSame([8, 7], array_column($service->adminCoaches(), 'coach_id'), 'Unverified coaches first.');
    }

    public function testOwnerRequestsAreScopedToTheOwnersVenues(): void
    {
        $kamal = (new CoachService())->ownerRequests(2);
        $this->assertSame(['Dilani Rathnayake'], array_column($kamal['pending'], 'coach_name'));
        $this->assertSame(['Ashan Weerasinghe'], array_column($kamal['approved'], 'coach_name'));
        $this->assertSame(2, $kamal['approved'][0]['upcoming_count']);

        $this->assertSame(['pending' => [], 'approved' => []], (new CoachService())->ownerRequests(3));
    }

    public function testOwnerSessionsListUpcomingFirst(): void
    {
        $service = new CoachSessionService();
        $this->assertSame([2, 3, 1], array_column($service->ownerSessions(2), 'id'));
        $this->assertSame([], $service->ownerSessions(3));
    }

    public function testCustomerRegistrationsShowReviewState(): void
    {
        $service = new CoachSessionService();

        $saman = array_column($service->customerRegistrations(4), null, 'id');
        $this->assertSame([3, 1], array_keys($saman));
        $this->assertSame(2, $saman[1]['review_id']);
        $this->assertFalse($saman[1]['can_review'], 'Already reviewed.');
        $this->assertTrue($saman[3]['can_cancel']);

        $ruwan = $service->customerRegistrations(5);
        $this->assertSame([2], array_column($ruwan, 'id'));
        $this->assertFalse($ruwan[0]['can_review'], 'Absent students cannot review.');

        $this->execute("DELETE FROM reviews WHERE id = 2");
        $this->assertTrue(array_column($service->customerRegistrations(4), null, 'id')[1]['can_review']);
    }

    public function testCoachStatsSumPaidRegistrationsAndStudents(): void
    {
        $stats = (new CoachSessionService())->coachStats(7);
        $this->assertSame(4800.0, $stats['revenue_total']);
        $this->assertSame(2, $stats['students']);
        $this->assertSame(0, $stats['regulars']);
    }
}
