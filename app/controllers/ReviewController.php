<?php
class ReviewController extends Controller
{
    public function index(): void
    {
        $this->view('customer/reviews', ['title' => 'My Reviews'], 'dashboard');
    }

    public function create(string $id): void
    {
        $this->view('customer/create-review', [
            'title'          => 'Review Venue',
            'target'         => 'venue',
            'bookingId'      => (int) $id,
            'registrationId' => null,
            'reviewId'       => null,
        ], 'dashboard');
    }

    public function store(string $id): void
    {
        $this->verifyCsrf();
        // TODO: only with a check-in for this booking, within 7 days, once per booking.
        Session::flash('info', 'Venue reviews are not built yet.');
        $this->redirect('/customer/reviews');
    }

    public function edit(string $id): void
    {
        $this->view('customer/create-review', [
            'title'          => 'Edit Review',
            'target'         => 'venue',
            'bookingId'      => null,
            'registrationId' => null,
            'reviewId'       => (int) $id,
        ], 'dashboard');
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        // TODO: edit the customer's own review inside the 7-day window.
        Session::flash('info', 'Editing reviews is not built yet.');
        $this->redirect('/customer/reviews');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        // TODO: delete the customer's own review.
        Session::flash('info', 'Deleting reviews is not built yet.');
        $this->redirect('/customer/reviews');
    }

    public function moderation(): void
    {
        $this->view('admin/review-moderation', ['title' => 'Review Moderation'], 'dashboard');
    }

    public function remove(string $id): void
    {
        $this->verifyCsrf();
        // TODO: set the review status to removed.
        Session::flash('info', 'Review removal is not built yet.');
        $this->redirect('/admin/reviews');
    }
}
