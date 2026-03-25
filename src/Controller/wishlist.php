<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../database.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$pdo = getPDO();

// À remplacer par la session utilisateur connecté
$idUtilisateur = $_SESSION['ID_Utilisateur'] ?? 1;

// Récupération des offres de la wishlist de l'utilisateur
$stmt = $pdo->prepare("
    SELECT 
        o.ID_Offre,
        o.Titre,
        o.Remuneration,
        o.Duree,
        o.Ville_CP,
        e.Nom_entreprise
    FROM Contenir c
    JOIN Wishlists w   ON c.ID_Wishlist  = w.ID_Wishlist
    JOIN Offres o      ON c.ID_Offre     = o.ID_Offre
    JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
    WHERE w.ID_Utilisateur = :id
    ORDER BY o.Titre ASC
");
$stmt->execute([':id' => $idUtilisateur]);
$offres = $stmt->fetchAll();

// Infos utilisateur
$stmtUser = $pdo->prepare("SELECT Nom, Prenom FROM Utilisateurs WHERE ID_Utilisateur = :id");
$stmtUser->execute([':id' => $idUtilisateur]);
$utilisateur = $stmtUser->fetch();

$loader = new FilesystemLoader(__DIR__ . '/../../templates');
$twig = new Environment($loader);

echo $twig->render('wishlist.html.twig', [
    'offres'      => $offres,
    'utilisateur' => $utilisateur,
]);