<?php
declare(strict_types=1);

/**
 * Vérifie qu'un mot de passe respecte la politique :
 *   - au moins 8 caractères
 *   - au moins une lettre majuscule
 *   - au moins un chiffre
 *   - au moins un caractère spécial (non alphanumérique)
 *
 * Retourne null si le mot de passe est valide, sinon un message d'erreur.
 */
function validateNewPassword(string $password): ?string
{
    if (mb_strlen($password) < 8) {
        return 'Le mot de passe doit contenir au moins 8 caractères.';
    }
    if (!preg_match('/[A-Z]/u', $password)) {
        return 'Le mot de passe doit contenir au moins une lettre majuscule.';
    }
    if (!preg_match('/[a-z]/u', $password)) {
        return 'Le mot de passe doit contenir au moins une lettre minuscule.';
    }
    if (!preg_match('/\d/', $password)) {
        return 'Le mot de passe doit contenir au moins un chiffre.';
    }
    if (!preg_match('/[^A-Za-z0-9]/u', $password)) {
        return 'Le mot de passe doit contenir au moins un caractère spécial.';
    }
    return null;
}
