<?php

declare(strict_types=1);

function getPDO(): PDO
{
    $host = 'localhost';
    $dbname = 'database';
    $user = 'root';
    $pass = '';

    try {
        return new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données.');
    }

