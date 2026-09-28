<?php
class AnalyticsController extends Controller
{
    /** The seeded demo owner. Until owner analytics is built, the sample figures belong to this account only. */
    private const SAMPLE_OWNER_ID = 2;

    public function revenue(): void
    {
        $this->view('owner/revenue', ['title' => 'Revenue', 'sample' => $this->isSampleOwner()], 'dashboard');
    }

    public function utilisation(): void
    {
        $this->view('owner/utilisation', ['title' => 'Court Utilisation', 'sample' => $this->isSampleOwner()], 'dashboard');
    }

    public function customers(): void
    {
        $this->view('owner/customers', ['title' => 'Customer Intelligence', 'sample' => $this->isSampleOwner()], 'dashboard');
    }

    private function isSampleOwner(): bool
    {
        return Auth::id() === self::SAMPLE_OWNER_ID;
    }
}
