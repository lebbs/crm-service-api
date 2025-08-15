<?php
declare(strict_types=1);

/**
 * Simple PDO factory with retry to handle MySQL not-ready-yet on container start.
 */
function make_pdo(): PDO {
    $host = 'db';
    $db   = 'simple_api';
    $user = 'apiuser';
    $pass = 'apipassword';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $attempts = 0;
    $lastErr = null;
    while ($attempts < 20) {
        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (Throwable $e) {
            $lastErr = $e->getMessage();
            // Wait a bit then retry
            usleep(500000); // 0.5s
            $attempts++;
        }
    }

    // If still failing, throw a clear error
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed', 'details' => $lastErr]);
    exit;
}
