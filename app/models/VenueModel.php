<?php
class VenueModel extends Model
{
    private const DETAIL_SELECT = "
        SELECT v.id, v.owner_id, v.name, v.slug, v.description, v.address, v.city, v.contact_phone,
               v.status, v.rejection_reason, v.deactivation_reason, v.reviewed_by, v.reviewed_at,
               v.is_active, v.created_at, v.updated_at,
               o.name AS owner_name, o.email AS owner_email, o.phone AS owner_phone,
               r.name AS reviewer_name,
               (SELECT COUNT(*) FROM courts c WHERE c.venue_id = v.id) AS court_count
        FROM venues v
        JOIN users o ON o.id = v.owner_id
        LEFT JOIN users r ON r.id = v.reviewed_by";

    public function create(int $ownerId, string $name, string $slug, ?string $description, string $address, string $city, string $phone): int
    {
        return $this->insert(
            'INSERT INTO venues (owner_id, name, slug, description, address, city, contact_phone) VALUES (?, ?, ?, ?, ?, ?, ?)',
            'issssss',
            [$ownerId, $name, $slug, $description, $address, $city, $phone]
        );
    }

    public function find(int $id): ?array
    {
        return $this->selectOne(self::DETAIL_SELECT . ' WHERE v.id = ?', 'i', [$id]);
    }

    public function forOwner(int $ownerId): array
    {
        return $this->select(self::DETAIL_SELECT . ' WHERE v.owner_id = ? ORDER BY v.created_at DESC, v.id DESC', 'i', [$ownerId]);
    }

    public function pending(): array
    {
        return $this->select(self::DETAIL_SELECT . " WHERE v.status = 'pending' ORDER BY v.created_at, v.id");
    }

    /** Must run inside a transaction. */
    public function lockForUpdate(int $id): ?array
    {
        return $this->selectOne('SELECT id, owner_id, status, is_active FROM venues WHERE id = ? FOR UPDATE', 'i', [$id]);
    }

    /** Slugs equal to $base or $base-N, for picking a free one. */
    public function slugsLike(string $base): array
    {
        $rows = $this->select('SELECT slug FROM venues WHERE slug = ? OR slug LIKE ?', 'ss', [$base, $base . '-%']);
        return array_column($rows, 'slug');
    }

    public function updateDetails(int $id, string $name, ?string $description, string $address, string $city, string $phone): int
    {
        return $this->execute(
            'UPDATE venues SET name = ?, description = ?, address = ?, city = ?, contact_phone = ? WHERE id = ?',
            'sssssi',
            [$name, $description, $address, $city, $phone, $id]
        );
    }

    /** Rejected venue edited by its owner goes back into the approval queue. */
    public function resubmit(int $id): int
    {
        return $this->execute(
            "UPDATE venues SET status = 'pending', rejection_reason = NULL, reviewed_by = NULL, reviewed_at = NULL
             WHERE id = ? AND status = 'rejected'",
            'i',
            [$id]
        );
    }

    public function setActive(int $id, bool $active): int
    {
        return $this->execute(
            "UPDATE venues SET is_active = ? WHERE id = ? AND status = 'approved' AND is_active = ?",
            'iii',
            [$active ? 1 : 0, $id, $active ? 0 : 1]
        );
    }

    public function approve(int $id, int $adminId, string $now): int
    {
        return $this->execute(
            "UPDATE venues SET status = 'approved', rejection_reason = NULL, reviewed_by = ?, reviewed_at = ?
             WHERE id = ? AND status = 'pending'",
            'isi',
            [$adminId, $now, $id]
        );
    }

    public function reject(int $id, int $adminId, string $reason, string $now): int
    {
        return $this->execute(
            "UPDATE venues SET status = 'rejected', rejection_reason = ?, reviewed_by = ?, reviewed_at = ?
             WHERE id = ? AND status = 'pending'",
            'sisi',
            [$reason, $adminId, $now, $id]
        );
    }

    /** Approved, switched-on venues for public listings, optionally filtered by sport, city and a name or address search. */
    public function publicList(?int $sportTypeId, ?string $city, ?string $search): array
    {
        $sql = "SELECT v.id, v.name, v.slug, v.description, v.address, v.city, v.contact_phone,
                       (SELECT COUNT(*) FROM courts c WHERE c.venue_id = v.id AND c.is_active = 1) AS court_count,
                       (SELECT MIN(c.hourly_rate) FROM courts c WHERE c.venue_id = v.id AND c.is_active = 1) AS min_rate
                FROM venues v
                WHERE v.status = 'approved' AND v.is_active = 1";
        $types = '';
        $params = [];
        if ($sportTypeId !== null) {
            $sql .= ' AND EXISTS (SELECT 1 FROM venue_sports vs WHERE vs.venue_id = v.id AND vs.sport_type_id = ?)';
            $types .= 'i';
            $params[] = $sportTypeId;
        }
        if ($city !== null) {
            $sql .= ' AND v.city = ?';
            $types .= 's';
            $params[] = $city;
        }
        if ($search !== null) {
            $sql .= ' AND (v.name LIKE ? OR v.address LIKE ?)';
            $like = '%' . addcslashes($search, '%_\\') . '%';
            $types .= 'ss';
            array_push($params, $like, $like);
        }
        return $this->select($sql . ' ORDER BY v.name', $types, $params);
    }

    public function findPublicBySlug(string $slug): ?array
    {
        return $this->selectOne(
            "SELECT id, name, slug, description, address, city, contact_phone
             FROM venues WHERE slug = ? AND status = 'approved' AND is_active = 1",
            's',
            [$slug]
        );
    }
}
