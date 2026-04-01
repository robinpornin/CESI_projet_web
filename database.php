<?php

declare(strict_types=1);

function getPDO(): PDO
{
    $host   = $_ENV['DB_HOST'] ?? 'localhost';
    $dbname = $_ENV['DB_NAME'] ?? '';
    $user   = $_ENV['DB_USER'] ?? '';
    $pass   = $_ENV['DB_PASS'] ?? '';

    try {
        return new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $e) {
        error_log('Erreur BDD : ' . $e->getMessage());
        http_response_code(500);
        die('Erreur interne. Veuillez réessayer plus tard.');
    }
}
