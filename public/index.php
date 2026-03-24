<?php

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controller/accueil.php';
require_once __DIR__ . '/../src/Controller/invite.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$requestUri = $_SERVER['REQUEST_URI'];
$scriptDir  = dirname($_SERVER['SCRIPT_NAME']);
$route      = trim(str_replace($scriptDir, '', parse_url($requestUri, PHP_URL_PATH)), '/');

if ($route === '') {
    $route = 'home';
}

switch ($route) {

    case 'home':
    case '':
        $controller = new PageAccueil($twig);
        $controller->render();
        break;

    case 'invite':
        $controller = new PageInvite($twig);
        $controller->render();
        break;

    default:
        http_response_code(404);
        echo "404 - Page non trouvée : " . htmlspecialchars($route);
        break;
}