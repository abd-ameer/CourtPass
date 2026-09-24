<?php
class CustomerProfileModel extends Model
{
    /** New customers start as new_member with no score; the table defaults cover the rest. */
    public function create(int $customerId): void
    {
        $this->insert('INSERT INTO customer_profiles (customer_id) VALUES (?)', 'i', [$customerId]);
    }
}
