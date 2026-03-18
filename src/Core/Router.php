<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

// Controllers
require_once __DIR__ . '/../src/Controller/ControllerAccueil.php';

// Core
require_once __DIR__ . '/../src/Core/Router.php';

// Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

// URL
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Router
$router = new Router();
$router->handle($url);