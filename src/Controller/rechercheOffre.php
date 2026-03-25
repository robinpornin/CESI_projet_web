<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../database.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$pdo = getPDO();

// Récupération des offres avec le nom de l'entreprise
$stmt = $pdo->query("
    SELECT 
        o.ID_Offre,
        o.Titre,
        o.Remuneration,
        o.Date_,
        o.Description,
        o.Duree,
        o.Ville_CP,
        e.Nom_entreprise,
        e.Secteur
    FROM Offres o
    JOIN Entreprises e ON o.ID_Entreprise = e.ID_Entreprise
    ORDER BY o.Date_ DESC
");
$offres = $stmt->fetchAll();

// Villes distinctes pour le filtre
$stmtVilles = $pdo->query("SELECT DISTINCT Ville_CP FROM Offres ORDER BY Ville_CP ASC");
$villes = $stmtVilles->fetchAll(PDO::FETCH_COLUMN);

$loader = new FilesystemLoader(__DIR__ . '/../../templates');
$twig = new Environment($loader);

echo $twig->render('rechercheOffre.html.twig', [
    'offres'  => $offres,
    'villes'  => $villes,
]);