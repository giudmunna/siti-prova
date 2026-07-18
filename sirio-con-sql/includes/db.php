<?php
require_once __DIR__ . '/../config.php';

function db_connect(): ?mysqli
{
    static $conn = null;
    if ($conn instanceof mysqli) {
        return $conn;
    }

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        error_log('DB connect error: ' . $conn->connect_error);
        return null;
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}

function db_query(string $sql, array $params = []): mysqli_result|bool
{
    $conn = db_connect();
    if ($conn === null) {
        return false;
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log('DB prepare error: ' . $conn->error);
        return false;
    }

    if ($params) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_double($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }

    $ok = $stmt->execute();
    if ($ok === false) {
        $stmt->close();
        error_log('DB execute error: ' . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function db_insert(string $sql, array $params = []): int
{
    $conn = db_connect();
    if ($conn === null) {
        return 0;
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log('DB insert prepare error: ' . $conn->error);
        return 0;
    }

    if ($params) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_double($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }

    $ok = $stmt->execute();
    $insertId = $stmt->insert_id;
    $stmt->close();

    return $ok ? $insertId : 0;
}

function db_fetch_all(string $sql, array $params = []): array
{
    $result = db_query($sql, $params);
    if ($result === false) {
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function db_fetch_one(string $sql, array $params = []): ?array
{
    $result = db_query($sql, $params);
    if ($result === false) {
        return null;
    }
    $row = $result->fetch_assoc();
    if ($row === null) {
        return null;
    }
    return $row;
}

function db_escape(string $value): string
{
    $conn = db_connect();
    return $conn ? $conn->real_escape_string($value) : addslashes($value);
}
