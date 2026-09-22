<?php
/**
 * Base model. One model per table, prepared statements only.
 * Models do data access and nothing else: no business rules, no sessions, no output.
 *
 * Types string follows mysqli bind_param: i = int, d = double, s = string, b = blob.
 */
abstract class Model
{
    protected mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /** Returns all matching rows as associative arrays. */
    protected function select(string $sql, string $types = '', array $params = []): array
    {
        $stmt = $this->run($sql, $types, $params);
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    /** Returns the first matching row or null. */
    protected function selectOne(string $sql, string $types = '', array $params = []): ?array
    {
        $stmt = $this->run($sql, $types, $params);
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** Runs INSERT and returns the new id. */
    protected function insert(string $sql, string $types = '', array $params = []): int
    {
        $stmt = $this->run($sql, $types, $params);
        $id = (int) $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    /** Runs UPDATE / DELETE and returns the number of affected rows. */
    protected function execute(string $sql, string $types = '', array $params = []): int
    {
        $stmt = $this->run($sql, $types, $params);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }

    private function run(string $sql, string $types, array $params): mysqli_stmt
    {
        $stmt = $this->db->prepare($sql);
        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt;
    }
}
