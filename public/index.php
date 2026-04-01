<?php

declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: http://cesi_projet_web.local");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// --- Session sécurisée ---
$isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
    'cookie_secure'   => $isHttps,  // ✅ false en HTTP local, true en prod HTTPS
]);

// --- Token CSRF ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// --- Sécurité HTTP ---
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: " . implode('; ', [
    "default-src 'self'",
    "script-src 'self' 'unsafe-inline'",
    "style-src 'self' 'unsafe-inline'",
    "font-src 'self' data:",
    "img-src 'self' data: blob:",
    "connect-src 'self'",
    "frame-src 'self'",
    "form-action 'self'",
    "base-uri 'self'",
    "object-src 'none'",
]));
// --- Autoload ---
require_once __DIR__ . '/../vendor/autoload.php';

// --- Variables d'environnement (.env) ---
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        // ✅ Supprime les guillemets simples ou doubles autour de la valeur
        $value = trim($value, '"\'');
        $_ENV[$key] = $value;
    }
}


// --- Controllers ---
require_once __DIR__ . '/../src/Controller/accueil.php';
require_once __DIR__ . '/../src/Controller/connexion.php';
require_once __DIR__ . '/../src/Controller/contactAdmin.php';
require_once __DIR__ . '/../src/Controller/invite.php';
require_once __DIR__ . '/../src/Controller/admin.php';
require_once __DIR__ . '/../src/Controller/mentionsLegales.php';
require_once __DIR__ . '/../src/Controller/FicheEntreprise.php';
require_once __DIR__ . '/../src/Controller/creationCompte.php';
require_once __DIR__ . '/../src/Controller/creationEleve.php';
require_once __DIR__ . '/../src/Controller/creationEntreprise.php';
require_once __DIR__ . '/../src/Controller/creationOffre.php';
require_once __DIR__ . '/../src/Controller/eleve.php';
require_once __DIR__ . '/../src/Controller/espaceEleve.php';
require_once __DIR__ . '/../src/Controller/formulaire.php';
require_once __DIR__ . '/../src/Controller/gestionCompte.php';
require_once __DIR__ . '/../src/Controller/gestionEntreprise.php';
require_once __DIR__ . '/../src/Controller/gestionOffre.php';
require_once __DIR__ . '/../src/Controller/gestionCompteEleveAdmin.php';
require_once __DIR__ . '/../src/Controller/gestionComptePiloteAdmin.php';
require_once __DIR__ . '/../src/Controller/offre.php';
require_once __DIR__ . '/../src/Controller/parametreEleve.php';
require_once __DIR__ . '/../src/Controller/parametreEntreprise.php';
require_once __DIR__ . '/../src/Controller/parametreOffre.php';
require_once __DIR__ . '/../src/Controller/pilote.php';
require_once __DIR__ . '/../src/Controller/rechercheEntreprise.php';
require_once __DIR__ . '/../src/Controller/rechercheOffre.php';
require_once __DIR__ . '/../src/Controller/wishlist.php';
require_once __DIR__ . '/../src/Controller/listeCandidatures.php';
require_once __DIR__ . '/../src/Controller/modificationEntreprise.php';
require_once __DIR__ . '/../src/Controller/gestionCompteElevePilote.php';
require_once __DIR__ . '/../src/Controller/listeCandidaturesPilote.php';
require_once __DIR__ . '/../src/Controller/file.php';

// --- Router ---
require_once __DIR__ . '/../src/Core/Router.php';

// --- AppUser ---
require_once __DIR__ . '/../src/Core/appUser.php';

// --- Twig ---
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig   = new \Twig\Environment($loader, ['debug' => false]);
// Via JWT
$jwtUser = \App\Core\Middleware::getUtilisateur();
$twig->addGlobal('user_role', $jwtUser?->role ?? 0);
$twig->addGlobal('user_nom',  $jwtUser ? ($jwtUser->prenom . ' ' . $jwtUser->nom) : null);
$twig->addGlobal('app_user',       AppUser::fromSession());
$twig->addGlobal('app_csrf_token', $_SESSION['csrf_token']);

// --- Récupération de la route ---
$requestUri = $_SERVER['REQUEST_URI'];
$path       = parse_url($requestUri, PHP_URL_PATH);
$route      = trim($path, '/');

if ($route === '') {
    $route = 'home';
}

// --- Lancement du router ---
$router = new \App\Core\Router($twig);
$router->handle($route);
