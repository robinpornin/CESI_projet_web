<?php

declare(strict_types=1);

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Middleware
{
    private const ROUTES_PUBLIQUES = [
        'home', '', 'connexion', 'invite',
        'rechercheEntreprise', 'rechercheOffre',
        'creationCompte', 'ficheEntreprise', 'offre',
        'deconnexion', 'mentionsLegales',
        'contactAdmin',
    ];

    private const ROUTES_PAR_ROLE = [
        // Rôle 1 — Étudiant
        'eleve'              => [1],
        'wishlist'           => [1],
        'formulaire'         => [1],
        'espaceEleve'        => [1],
        'parametreEleve'     => [1],
        'listeCandidatures'  => [1],
        'wishlist/ajouter'   => [1],
        'wishlist/supprimer' => [1],

        // Rôles 2 & 3 — Pilote + Admin
        'pilote'                  => [2, 3],
        'gestionEntreprise'       => [2, 3],
        'gestionOffre'            => [2, 3],
        'creationEntreprise'      => [2, 3],
        'creationOffre'           => [2, 3],
        'parametreEntreprise'     => [2, 3],
        'parametreOffre'          => [2, 3],
        'creationEleve'           => [2, 3],
        'modificationEntreprise'  => [2, 3],
        'listeCandidaturesPilote' => [2, 3],

        // Rôle 3 — Admin uniquement
        'admin'                    => [3],
        'gestionCompteEleveAdmin'  => [3],
        'gestionComptePiloteAdmin' => [3],

        // Tous les rôles connectés
        'gestionCompte'     => [1, 2, 3],
        'suppressionCompte' => [1, 2],
    ];

    /**
     * Décode le JWT depuis le cookie et retourne le payload.
     * Retourne null si absent ou invalide.
     */
    public static function getUtilisateur(): ?object
        {
            // 1. Cookie JWT
            $token = $_COOKIE['auth_token'] ?? null;
            if ($token) {
                try {
                    return JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
                } catch (\Exception $e) {}
            }

            // 2. Fallback session (première requête après login)
            $sess = $_SESSION['utilisateur'] ?? null;
            if ($sess) {
                return (object) $sess;
            }

            return null;
        }


    public static function check(string $route): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Routes publiques → pas de vérification
        if (in_array($route, self::ROUTES_PUBLIQUES, true)) {
            return;
        }

        // Décoder le JWT
        $utilisateur = self::getUtilisateur();

        if ($utilisateur === null) {
            header('Location: /connexion');
            exit;
        }

        // Vérification du rôle
        if (isset(self::ROUTES_PAR_ROLE[$route])) {
            $rolesAutorises = self::ROUTES_PAR_ROLE[$route];

            if (!in_array((int) $utilisateur->role, $rolesAutorises, true)) {
                http_response_code(403);
                die('Accès interdit.');
            }
        }
    }
}
