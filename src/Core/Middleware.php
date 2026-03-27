<?php

declare(strict_types=1);

namespace App\Core;

class Middleware
{
    private const ROUTES_PUBLIQUES = [
        'home', '', 'connexion', 'invite',
        'rechercheEntreprise', 'rechercheOffre',
        'creationCompte', 'ficheEntreprise', 'offre',
    ];

    private const ROUTES_PAR_ROLE = [
        // Rôle 1 — Étudiant
        'eleve'          => [1],
        'wishlist'       => [1],
        'formulaire'     => [1],
        'espaceEleve'    => [1],
        'parametreEleve' => [1],
        'listeCandidatures' => [1],

        // Rôles 2 & 3 — Pilote + Admin
        'pilote'                  => [2, 3],
        'gestionEntreprise'       => [2, 3],
        'gestionOffre'            => [2, 3],
        'creationEntreprise'      => [2, 3],
        'creationOffre'           => [2, 3],
        'parametreEntreprise'     => [2, 3],
        'parametreOffre'          => [2, 3],
        'creationEleve'           => [2, 3],

        // Rôle 3 — Admin uniquement
        'admin'                    => [3],
        'gestionCompteEleveAdmin'  => [3],
        'gestionComptePiloteAdmin' => [3],

        // Tous les rôles connectés
        'gestionCompte' => [1, 2, 3],
    ];

    public static function check(string $route): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Route publique → pas de vérification
        if (in_array($route, self::ROUTES_PUBLIQUES, true)) {
            return;
        }

        $utilisateur = $_SESSION['utilisateur'] ?? null;

        // Pas connecté → connexion
        if ($utilisateur === null) {
            header('Location: /connexion');
            exit;
        }

        $role = (int) ($utilisateur['role'] ?? 0);

        // Route restreinte → vérification du rôle
        if (isset(self::ROUTES_PAR_ROLE[$route])) {
            if (!in_array($role, self::ROUTES_PAR_ROLE[$route], true)) {
                self::redirectionParRole($role);
            }
        }
    }

    private static function redirectionParRole(int $role): never
    {
        $destination = match ($role) {
            1       => '/eleve',
            2       => '/pilote',
            3       => '/admin',
            default => '/connexion',
        };

        header('Location: ' . $destination);
        exit;
    }
}