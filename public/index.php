<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

// Router + Controllers
require_once __DIR__ . '/../src/Core/Router.php';
require_once __DIR__ . '/../src/Controller/HomeController.php';

// URL
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Lancement
$router = new Router();
$router->handle($url);