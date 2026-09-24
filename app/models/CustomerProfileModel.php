<?php
class CustomerProfileModel extends Model
{
    /** New customers start as new_member with no score; the table defaults cover the rest. */
    public function create(int $customerId): void
    {
        $this->insert('INSERT INTO customer_profiles (customer_id) VALUES (?)', 'i', [$customerId]);
    }

    /** Cached reliability figures; a NULL score means not rated yet. */
    public function find(int $customerId): ?array
    {
        return $this->selectOne(
            'SELECT customer_id, reliability_score, reliability_tier, completed_count, no_show_count
             FROM customer_profiles WHERE customer_id = ?',
            'i',
            [$customerId]
        );
    }
}
