<?php
use PHPUnit\Framework\TestCase;

/**
 * Base class for tests that use the database.
 * Tests run against courtpass_test, built from database/schema.sql once per run,
 * and every test starts from a fresh copy of database/seed.sql.
 * The development database (courtpass) is never written to.
 */
abstract class DatabaseTestCase extends TestCase
{
    public const TEST_DATABASE = 'courtpass_test';

    /** Called once from tests/bootstrap.php. Leaves the shared connection on the test database. */
    public static function createTestDatabase(): void
    {
        $db = Database::connection();
        self::runSqlFile($db, ROOT_PATH . '/database/schema.sql');
        $db->select_db(self::TEST_DATABASE);
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::resetData();
    }

    /** Empties every table and loads the seed data again, so dates stay relative to today. */
    protected static function resetData(): void
    {
        $db = Database::connection();
        $current = $db->query('SELECT DATABASE()')->fetch_row()[0];
        if ($current !== self::TEST_DATABASE) {
            throw new RuntimeException("Refusing to reset data: the connection is on '{$current}', not " . self::TEST_DATABASE . '.');
        }

        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($db->query('SHOW TABLES')->fetch_all() as [$table]) {
            $db->query("TRUNCATE TABLE `{$table}`");
        }
        $db->query('SET FOREIGN_KEY_CHECKS = 1');
        self::runSqlFile($db, ROOT_PATH . '/database/seed.sql');
    }

    /** First row of a query, or null. */
    protected function fetchRow(string $sql): ?array
    {
        return Database::connection()->query($sql)->fetch_assoc();
    }

    /** First column of the first row, or null. */
    protected function fetchValue(string $sql): mixed
    {
        $row = Database::connection()->query($sql)->fetch_row();
        return $row[0] ?? null;
    }

    /** Runs a write used to set up a test case (for example a booking at a chosen time). */
    protected function execute(string $sql): void
    {
        Database::connection()->query($sql);
    }

    /** Date $offset days from today as Y-m-d. */
    protected static function day(int $offset): string
    {
        return date('Y-m-d', strtotime("{$offset} days"));
    }

    /**
     * Runs a .sql file with the database name switched to courtpass_test.
     * Handles DELIMITER blocks (the audit_log triggers), which mysqli does not understand.
     */
    private static function runSqlFile(mysqli $db, string $path): void
    {
        $sql = preg_replace(
            '/^(DROP DATABASE IF EXISTS|CREATE DATABASE|USE)\s+`?courtpass`?(?=[\s;])/mi',
            '$1 ' . self::TEST_DATABASE,
            (string) file_get_contents($path)
        );
        if (preg_match('/(?<![@\w])`?courtpass`?\s*\.\s*`?\w/i', $sql)) {
            throw new RuntimeException("{$path} names the courtpass database directly; update tests/DatabaseTestCase.php before running tests.");
        }

        $delimiter = ';';
        $chunk = '';
        foreach (preg_split('/\R/', $sql) as $line) {
            if (preg_match('/^\s*DELIMITER\s+(\S+)\s*$/i', $line, $m)) {
                self::runChunk($db, $chunk, $delimiter);
                $chunk = '';
                $delimiter = $m[1];
                continue;
            }
            if (!preg_match('/^\s*--/', $line)) {
                $chunk .= $line . "\n";
            }
        }
        self::runChunk($db, $chunk, $delimiter);
    }

    private static function runChunk(mysqli $db, string $sql, string $delimiter): void
    {
        if (trim($sql) === '') {
            return;
        }
        if ($delimiter === ';') {
            $db->multi_query($sql);
            do {
                $result = $db->store_result();
                if ($result instanceof mysqli_result) {
                    $result->free();
                }
            } while ($db->more_results() && $db->next_result());
            return;
        }
        foreach (explode($delimiter, $sql) as $statement) {
            if (trim($statement) !== '') {
                $db->query($statement);
            }
        }
    }
}
