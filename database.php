<?php

declare(strict_types=1);

/**
 * Retourne un objet PDO pour se connecter à la base de données.
 *
 * @return PDO
 */
function getPDO(): PDO
{
    // Paramètres de connexion : à adapter selon ton environnement
    $host = 'localhost';
    $dbname = 'CESI_projet_web'; // Nom de ta base
    $user = 'phpmyadmin';              // Utilisateur MySQL
    $pass = 'A2#DevWeb!';                  // Mot de passe MySQL (vide si aucun)

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // Affiche les erreurs SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retourne des tableaux associatifs
                PDO::ATTR_EMULATE_PREPARES => false,             // Requêtes préparées réelles
            ]
        );

        return $pdo;

    } catch (PDOException $e) {
        // Arrête le script avec un message d’erreur clair
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}