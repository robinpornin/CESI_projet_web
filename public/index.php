<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/pages/HomePage.php';
require_once __DIR__ . '/src/pages/GuestPage.php';

// --- Initialisation Twig ---
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    // 'cache' => __DIR__ . '/cache', // décommenter en production
    'debug' => true,
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

// --- Récupération de la route ---
// Ex: /mon-projet/guest → "guest"
$requestUri  = $_SERVER['REQUEST_URI'];
$scriptDir   = dirname($_SERVER['SCRIPT_NAME']); // gère les sous-dossiers
$route = trim(str_replace($scriptDir, '', parse_url($requestUri, PHP_URL_PATH)), '/');

if ($route === '') {
    $route = 'home';
}

// --- Router ---
switch ($route) {

    case 'home':
    case '':
        $controller = new PageAccueil($twig);
        $controller->render();
        break;

    case 'guest':
        $controller = new PageInvite($twig);
        $controller->render();
        break;

    // Ajoute tes routes ici :
    // case 'contact':
    //     $controller = new ContactPage($twig);
    //     $controller->render();
    //     break;

    default:
        http_response_code(404);
        echo $twig->render('404.html.twig', ['route' => $route]);
        break;
}