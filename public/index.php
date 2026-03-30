<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

// Controllers
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

// Router
require_once __DIR__ . '/../src/Core/Router.php';

// AppUser
require_once __DIR__ . '/../src/Core/appUser.php';


// Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig   = new \Twig\Environment($loader, ['debug' => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addGlobal('user_role', $_SESSION['utilisateur']['role'] ?? 0);
$twig->addGlobal('user_nom',  $_SESSION['user_nom']  ?? null);
$twig->addGlobal('app_user', AppUser::fromSession());

// Récupération de la route
$requestUri = $_SERVER['REQUEST_URI'];
$path       = parse_url($requestUri, PHP_URL_PATH);
$route      = trim($path, '/');

if ($route === '') {
    $route = 'home';
}

// Lancement du router
$router = new \App\Core\Router($twig);
$router->handle($route);