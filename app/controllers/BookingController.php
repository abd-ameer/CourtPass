<?php
class BookingController extends Controller
{
    public function dashboard(): void
    {
        $this->view('customer/dashboard', ['title' => 'Customer Dashboard'], 'dashboard');
    }

    /** Slot grid for one court. Guests see it read-only. */
    public function availability(string $id): void
    {
        $this->view('public/court-availability', [
            'title'   => 'Court Availability',
            'courtId' => (int) $id,
            'isGuest' => !Auth::hasRole('customer'),
        ]);
    }

    /** JSON for the slot grid: states are available, booked, blocked, unavailable; block_id is set for owner blocks. */
    public function slots(string $id): void
    {
        $date = (string) $this->request->query('date', date('Y-m-d'));
        if ((new Validator(['date' => $date]))->date('date')->fails()) {
            $this->json(['error' => 'Invalid date.'], 422);
        }

        // TODO: build the grid from operating hours, bookings, court blocks and flash slots.
        $sample = ['07:00' => 'booked', '10:00' => 'blocked', '12:00' => 'booked', '15:00' => 'blocked', '17:00' => 'booked', '18:00' => 'booked'];
        $slots = [];
        for ($h = 6; $h < 23; $h++) {
            $start = sprintf('%02d:00', $h);
            $slots[] = [
                'start'       => $start,
                'state'       => $sample[$start] ?? 'available',
                'price'       => 5000.00,
                'flash_price' => $h >= 21 ? 3500.00 : null,
                'block_id'    => $start === '10:00' ? 1 : null,
            ];
        }
        $this->json(['court_id' => (int) $id, 'date' => $date, 'slots' => $slots]);
    }

    public function index(): void
    {
        $this->view('customer/bookings', ['title' => 'My Bookings'], 'dashboard');
    }

    public function create(): void
    {
        $this->view('customer/create-booking', [
            'title'   => 'Confirm Booking',
            'courtId' => (int) $this->request->query('court', 0),
            'date'    => (string) $this->request->query('date', ''),
            'start'   => (string) $this->request->query('start', ''),
        ], 'dashboard');
    }

    public function store(): void
    {
        $this->verifyCsrf();
        // TODO: conflict check and insert in one transaction; the price comes from the court, never the form.
        Session::flash('info', 'Booking is not built yet.');
        $this->redirect('/customer/bookings');
    }

    public function show(string $id): void
    {
        $this->view('customer/booking-details', ['title' => 'Booking Details', 'bookingId' => (int) $id], 'dashboard');
    }

    public function confirmCancel(string $id): void
    {
        $this->view('customer/cancel-booking', ['title' => 'Cancel Booking', 'bookingId' => (int) $id], 'dashboard');
    }

    public function cancel(string $id): void
    {
        $this->verifyCsrf();
        // TODO: cancel the customer's own booking and apply the refund tier or cash classification.
        Session::flash('info', 'Cancelling bookings is not built yet.');
        $this->redirect('/customer/bookings/' . (int) $id);
    }

    public function ownerIndex(): void
    {
        $this->view('owner/bookings', ['title' => 'Booking Requests'], 'dashboard');
    }

    public function ownerShow(string $id): void
    {
        $this->view('owner/booking-details', ['title' => 'Booking Details', 'bookingId' => (int) $id], 'dashboard');
    }

    public function confirm(string $id): void
    {
        $this->verifyCsrf();
        // TODO: confirm a pending booking at the owner's own venue.
        Session::flash('info', 'Confirming bookings is not built yet.');
        $this->redirect('/owner/bookings');
    }

    public function reject(string $id): void
    {
        $this->verifyCsrf();
        // TODO: reject a pending booking; the reason is mandatory.
        Session::flash('info', 'Rejecting bookings is not built yet.');
        $this->redirect('/owner/bookings');
    }

    public function ownerCancel(string $id): void
    {
        $this->verifyCsrf();
        // TODO: owner cancels a confirmed booking; the reason is mandatory.
        Session::flash('info', 'Cancelling bookings is not built yet.');
        $this->redirect('/owner/bookings/' . (int) $id);
    }
}
