<?php

declare(strict_types=1);

class AppUser
{
    public static function fromSession(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $utilisateur = $_SESSION['utilisateur'] ?? null;

        if ($utilisateur === null) {
            return null;
        }

        $role = (int) ($utilisateur['role'] ?? 0);

        return [
            'nom'    => ($utilisateur['prenom'] ?? '') . ' ' . ($utilisateur['nom'] ?? ''),
            'role'   => $role,
            'badge'  => match ($role) {
                1       => 'Étudiant',
                2       => 'Pilote',
                3       => 'Administrateur',
                default => 'Utilisateur',
            },
            'avatar' => match ($role) {
                1       => '🎓',
                2       => '🧑‍✈️',
                3       => '🛠️',
                default => '👤',
            },
        ];
    }
}
