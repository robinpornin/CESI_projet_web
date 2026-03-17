<?php
// 1. Charger Twig
require_once __DIR__ . '/../vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader);

// 2. Choisir la page à afficher
// On regarde dans l'URL si on a ?page=page1 ou ?page=page2
$page = $_GET['page'] ?? 'home'; // Si rien, on prend 'home'

// 3. Afficher la page correspondante
switch ($page) {
    case 'page1':
        echo $twig->render('page1.html.twig', ['title' => 'Page 1']);
        break;
    case 'page2':
        echo $twig->render('page2.html.twig', ['title' => 'Page 2']);
        break;
    default:
        echo $twig->render('home.html.twig', ['title' => 'Accueil']);
        break;
}
