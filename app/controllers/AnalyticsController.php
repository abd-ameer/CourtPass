<?php
class AnalyticsController extends Controller
{
    public function revenue(): void
    {
        $this->view('owner/revenue', ['title' => 'Revenue'], 'dashboard');
    }

    public function utilisation(): void
    {
        $this->view('owner/utilisation', ['title' => 'Court Utilisation'], 'dashboard');
    }

    public function customers(): void
    {
        $this->view('owner/customers', ['title' => 'Customer Intelligence'], 'dashboard');
    }
}
