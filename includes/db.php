<?php
/**
 * CourtPass — Database Connection
 * Singleton mysqli connection with error handling.
 */

require_once __DIR__ . '/../config.php';

/**
 * Get the mysqli database connection (singleton).
 * @return mysqli
 */
function getDB(): mysqli {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            error_log('CourtPass DB connection failed: ' . $conn->connect_error);
            http_response_code(500);
            die(json_encode(['error' => 'Database connection failed. Please try again later.']));
        }
        
        // Set charset to utf8mb4
        $conn->set_charset('utf8mb4');
        
        // Set timezone to match PHP
        $conn->query("SET time_zone = '+05:30'");
    }
    
    return $conn;
}

/**
 * Execute a prepared statement and return the result.
 * Uses prepared statements exclusively — never concatenates user input into SQL.
 *
 * @param string $sql    SQL query with ? placeholders
 * @param string $types  Parameter types string (e.g., 'ssi' for string, string, int)
 * @param array  $params Array of parameters to bind
 * @return mysqli_result|bool
 */
function dbQuery(string $sql, string $types = '', array $params = []): mysqli_result|bool {
    $db = getDB();
    $stmt = $db->prepare($sql);
    
    if (!$stmt) {
        error_log("CourtPass DB prepare failed: {$db->error} | SQL: {$sql}");
        throw new RuntimeException('Database query preparation failed.');
    }
    
    if ($types && $params) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        error_log("CourtPass DB execute failed: {$stmt->error} | SQL: {$sql}");
        throw new RuntimeException('Database query execution failed.');
    }
    
    $result = $stmt->get_result();
    
    // For INSERT/UPDATE/DELETE, return true/false
    if ($result === false && $stmt->errno === 0) {
        return true;
    }
    
    return $result;
}

/**
 * Execute a prepared statement and return all rows as an associative array.
 *
 * @param string $sql
 * @param string $types
 * @param array  $params
 * @return array
 */
function dbFetchAll(string $sql, string $types = '', array $params = []): array {
    $result = dbQuery($sql, $types, $params);
    
    if ($result instanceof mysqli_result) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $rows;
    }
    
    return [];
}

/**
 * Execute a prepared statement and return a single row.
 *
 * @param string $sql
 * @param string $types
 * @param array  $params
 * @return array|null
 */
function dbFetchOne(string $sql, string $types = '', array $params = []): ?array {
    $result = dbQuery($sql, $types, $params);
    
    if ($result instanceof mysqli_result) {
        $row = $result->fetch_assoc();
        $result->free();
        return $row;
    }
    
    return null;
}

/**
 * Get the last insert ID.
 * @return int
 */
function dbLastInsertId(): int {
    return (int) getDB()->insert_id;
}

/**
 * Get the number of affected rows from the last query.
 * @return int
 */
function dbAffectedRows(): int {
    return getDB()->affected_rows;
}

/**
 * Begin a database transaction.
 */
function dbBeginTransaction(): void {
    getDB()->begin_transaction();
}

/**
 * Commit the current transaction.
 */
function dbCommit(): void {
    getDB()->commit();
}

/**
 * Roll back the current transaction.
 */
function dbRollback(): void {
    getDB()->rollback();
}
