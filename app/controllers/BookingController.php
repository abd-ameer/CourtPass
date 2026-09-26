<?php
class BookingController extends Controller
{
    public const CANCEL_REASONS = [
        'Personal / Work Emergency',
        'Teammates unavailable / Injury',
        'Double booking / Scheduling clash',
        'Bad weather / Transport issues',
    ];

    public function dashboard(): void
    {
        $this->view('customer/dashboard', ['title' => 'Customer Dashboard'], 'dashboard');
    }

    /** Slot grid for one court. Guests see it read-only. */
    public function availability(string $id): void
    {
        $page = (new BookingService())->courtAvailability((int) $id);
        if ($page === null) {
            $this->notFound();
        }
        $this->view('public/court-availability', $page + [
            'title'   => $page['court']['name'] . ' Availability',
            'isGuest' => !Auth::hasRole('customer'),
        ]);
    }

    /** JSON for the slot grid: states are available, booked, blocked, unavailable; block_id is set for owner blocks. */
    public function slots(string $id): void
    {
        $date = $this->request->query('date', date('Y-m-d'));
        if (!is_string($date) || (new Validator(['date' => $date]))->date('date')->fails()) {
            $this->json(['error' => 'Invalid date.'], 422);
        }

        $slots = (new BookingService())->slotGrid((int) $id, $date);
        if ($slots === null) {
            $this->notFound();
        }
        $this->json(['court_id' => (int) $id, 'date' => $date, 'slots' => $slots]);
    }

    public function index(): void
    {
        $this->view('customer/bookings', [
            'title'      => 'My Bookings',
            'bookings'   => (new BookingService())->customerBookings(Auth::id()),
            'reviewable' => (new ReviewService())->reviewableBookingIds(Auth::id()),
        ], 'dashboard');
    }

    public function create(): void
    {
        $slot = self::slotInput([
            'court_id'   => $this->request->query('court'),
            'slot_date'  => $this->request->query('date'),
            'start_time' => $this->request->query('start'),
        ]);
        if ($slot === null) {
            Session::flash('error', 'Choose a slot from a court\'s availability grid.');
            $this->redirect('/venues');
        }
        $this->showDraft($slot, 'online', []);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $data = $this->request->all();
        $slot = self::slotInput($data);
        if ($slot === null) {
            Session::flash('error', 'Choose a slot from a court\'s availability grid.');
            $this->redirect('/venues');
        }

        $method = is_string($data['payment_method'] ?? null) ? trim($data['payment_method']) : '';
        $v = (new Validator(['payment_method' => $method]))
            ->required('payment_method', 'Payment method')->in('payment_method', ['online', 'cash_on_arrival']);
        if ($v->fails()) {
            http_response_code(422);
            $this->showDraft($slot, 'online', $v->errors());
            return;
        }

        try {
            $id = (new BookingService())->create(Auth::id(), $slot['court_id'], $slot['slot_date'], $slot['start_time'], $method);
        } catch (ValidationException $e) {
            if (isset($e->errors()['payment_method'])) {
                http_response_code(422);
                $this->showDraft($slot, $method, $e->errors());
                return;
            }
            Session::flash('error', $e->getMessage());
            $this->redirect('/courts/' . $slot['court_id']);
        }

        Session::flash('success', $method === 'online'
            ? 'Your slot is held for ' . BookingService::HOLD_MINUTES . ' minutes while the online payment is completed.'
            : 'Booking request sent. The venue will confirm it before your slot.');
        $this->redirect('/customer/bookings/' . $id);
    }

    public function show(string $id): void
    {
        $booking = (new BookingService())->customerBooking(Auth::id(), (int) $id);
        if ($booking === null) {
            $this->notFound();
        }
        $this->view('customer/booking-details', ['title' => 'Booking #' . $booking['id'], 'booking' => $booking], 'dashboard');
    }

    public function confirmCancel(string $id): void
    {
        $booking = (new BookingService())->customerBooking(Auth::id(), (int) $id);
        if ($booking === null) {
            $this->notFound();
        }
        if (!$booking['can_cancel']) {
            Session::flash('error', "Booking #{$booking['id']} can no longer be cancelled.");
            $this->redirect('/customer/bookings/' . $booking['id']);
        }
        $this->view('customer/cancel-booking', [
            'title'   => 'Cancel Booking',
            'booking' => $booking,
            'reasons' => self::CANCEL_REASONS,
        ], 'dashboard');
    }

    public function cancel(string $id): void
    {
        $this->verifyCsrf();
        $service = new BookingService();
        if ($service->customerBooking(Auth::id(), (int) $id) === null) {
            $this->notFound();
        }
        $reason = $this->request->input('reason', '');
        if (!is_string($reason) || ($reason !== '' && !in_array($reason, self::CANCEL_REASONS, true))) {
            Session::flash('error', 'Choose a reason from the list, or leave it blank.');
            $this->redirect('/customer/bookings/' . (int) $id . '/cancel');
        }

        try {
            $result = $service->cancelByCustomer(Auth::id(), (int) $id, $reason);
        } catch (ValidationException $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('/customer/bookings/' . (int) $id);
        }

        $outcome = '';
        if ($result['refund'] !== null) {
            $outcome = ' A ' . (int) $result['refund']['percentage'] . '% refund of ' . lkr($result['refund']['amount']) . ' has been issued.';
        } elseif ($result['class'] !== null) {
            $outcome = ' It is recorded as a ' . $result['class'] . ' cancellation.';
        } elseif ($result['refund_percent'] === 0) {
            $outcome = ' No refund applies less than 12 hours before the slot.';
        }
        Session::flash('success', "Booking #{$id} cancelled.{$outcome}");
        $this->redirect('/customer/bookings/' . (int) $id);
    }

    public function ownerIndex(): void
    {
        $bookings = (new BookingService())->ownerBookings(Auth::id());
        $this->view('owner/bookings', [
            'title'    => 'Booking Requests',
            'bookings' => $bookings,
            'counts'   => array_count_values(array_column($bookings, 'status')),
        ], 'dashboard');
    }

    public function ownerShow(string $id): void
    {
        $booking = (new BookingService())->ownerBooking(Auth::id(), (int) $id);
        if ($booking === null) {
            $this->notFound();
        }
        $this->view('owner/booking-details', ['title' => 'Booking #' . $booking['id'], 'booking' => $booking], 'dashboard');
    }

    public function confirm(string $id): void
    {
        $this->verifyCsrf();
        $this->ownerDecision((int) $id, fn (BookingService $s) => $s->confirm(Auth::id(), (int) $id),
            "Booking #{$id} confirmed. The customer has been notified.");
        $this->redirect('/owner/bookings');
    }

    public function reject(string $id): void
    {
        $this->verifyCsrf();
        $reason = $this->reasonInput();
        if ($reason !== null) {
            $this->ownerDecision((int) $id, fn (BookingService $s) => $s->reject(Auth::id(), (int) $id, $reason),
                "Booking #{$id} rejected. The customer has been notified.");
        }
        $this->redirect('/owner/bookings');
    }

    public function ownerCancel(string $id): void
    {
        $this->verifyCsrf();
        $reason = $this->reasonInput();
        if ($reason !== null) {
            $this->ownerDecision((int) $id, fn (BookingService $s) => $s->cancelByOwner(Auth::id(), (int) $id, $reason),
                "Booking #{$id} cancelled. The customer has been notified.");
        }
        $this->redirect('/owner/bookings/' . (int) $id);
    }

    /** 404 unless the booking is at one of the owner's venues; business rule failures become an error flash. */
    private function ownerDecision(int $bookingId, callable $action, string $success): void
    {
        $service = new BookingService();
        if ($service->ownerBooking(Auth::id(), $bookingId) === null) {
            $this->notFound();
        }
        try {
            $action($service);
            Session::flash('success', $success);
        } catch (ValidationException $e) {
            Session::flash('error', $e->getMessage());
        }
    }

    /** Mandatory reason from the reason modal, or null after flashing the error. */
    private function reasonInput(): ?string
    {
        $reason = $this->request->input('reason');
        $reason = is_string($reason) ? trim($reason) : '';
        if ($reason === '') {
            Session::flash('error', 'A reason is required.');
            return null;
        }
        if (mb_strlen($reason) > 500) {
            Session::flash('error', 'The reason must be at most 500 characters.');
            return null;
        }
        return $reason;
    }

    /** Booking confirmation page for a slot; a slot that is no longer free goes back to the grid. */
    private function showDraft(array $slot, string $method, array $errors): void
    {
        try {
            $draft = (new BookingService())->draft(Auth::id(), $slot['court_id'], $slot['slot_date'], $slot['start_time']);
        } catch (ValidationException $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('/courts/' . $slot['court_id']);
        }
        if ($draft === null) {
            $this->notFound();
        }
        if (!$draft['cash_allowed']) {
            $method = 'online';
        }
        $this->view('customer/create-booking', [
            'title'  => 'Confirm Booking',
            'draft'  => $draft,
            'method' => $method,
            'errors' => $errors,
        ], 'dashboard');
    }

    /** court_id, slot_date and start_time from a form or query string, or null when any is missing or malformed. */
    private static function slotInput(array $data): ?array
    {
        $fields = [];
        foreach (['court_id', 'slot_date', 'start_time'] as $key) {
            if (!is_string($data[$key] ?? null)) {
                return null;
            }
            $fields[$key] = trim($data[$key]);
        }
        $v = (new Validator($fields))->integer('court_id', 1)->date('slot_date')->hour('start_time');
        if ($v->fails()) {
            return null;
        }
        return [
            'court_id'   => (int) $fields['court_id'],
            'slot_date'  => $fields['slot_date'],
            'start_time' => substr($fields['start_time'], 0, 5),
        ];
    }
}
