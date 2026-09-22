<?php
/**
 * Single shared mysqli connection.
 * Errors are thrown as exceptions (mysqli_sql_exception).
 */
class Database
{
    private static ?mysqli $connection = null;

    public static function connection(): mysqli
    {
        if (self::$connection === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
            self::$connection->set_charset('utf8mb4');
            self::$connection->query("SET time_zone = '+05:30'");
        }
        return self::$connection;
    }

    /**
     * Run $work inside a transaction. Commits if it returns normally,
     * rolls back and rethrows if it throws.
     * Services use this for anything that must be all-or-nothing
     * (e.g. conflict check + booking insert with SELECT ... FOR UPDATE).
     */
    public static function transaction(callable $work): mixed
    {
        $db = self::connection();
        $db->begin_transaction();
        try {
            $result = $work($db);
            $db->commit();
            return $result;
        } catch (Throwable $e) {
            $db->rollback();
            throw $e;
        }
    }
}
