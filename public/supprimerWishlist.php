<?php
declare(strict_types=1);

// chemin correct vers database.php
require_once __DIR__ . '/../database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// récupération ID utilisateur
$idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;

if (!$idUtilisateur) {
    echo json_encode([
        'success' => false,
        'message' => 'Utilisateur non connecté'
    ]);
    exit;
}

// récupération données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);
$idOffre = (int) ($data['id_offre'] ?? 0);

if (!$idOffre) {
    echo json_encode([
        'success' => false,
        'message' => 'ID offre manquant'
    ]);
    exit;
}

$pdo = getPDO();

// suppression de l'offre dans la wishlist de CET utilisateur
$stmt = $pdo->prepare("
    DELETE c FROM Contenir c
    JOIN Wishlists w ON c.ID_Wishlist = w.ID_Wishlist
    WHERE w.ID_Utilisateur = :user
      AND c.ID_Offre = :offre
");

$stmt->execute([
    ':user'  => $idUtilisateur,
    ':offre' => $idOffre
]);

echo json_encode([
    'success' => true,
    'message' => 'Offre retirée de la wishlist'
]);