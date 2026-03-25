<?php
declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../database.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$pdo = getPDO();

// Récupération avec note moyenne
$stmt = $pdo->query("
    SELECT 
        e.ID_Entreprise,
        e.Nom_entreprise,
        e.Secteur,
        e.Type,
        e.Ville_CP,
        e.Email_entreprise,
        e.Telephone,
        e.Description_entreprise,
        e.Nb_stagiaires,
        ROUND(AVG(ev.Note), 1) AS Note_moyenne
    FROM Entreprises e
    LEFT JOIN Evaluations ev ON e.ID_Entreprise = ev.ID_Entreprise
    GROUP BY e.ID_Entreprise
    ORDER BY e.Nom_entreprise ASC
");
$entreprises = $stmt->fetchAll();

$loader = new FilesystemLoader(__DIR__ . '/../../templates');
$twig = new Environment($loader);

echo $twig->render('rechercheEntreprise.html.twig', [
    'entreprises' => $entreprises
]);