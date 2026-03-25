<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

header('Content-Type: application/json');

$idUtilisateur = $_SESSION['ID_Utilisateur'] ?? 1;
$data = json_decode(file_get_contents('php://input'), true);
$idOffre = (int) ($data['id_offre'] ?? 0);

if (!$idOffre) {
    echo json_encode(['success' => false]);
    exit;
}

$pdo = getPDO();

$stmt = $pdo->prepare("
    DELETE c FROM Contenir c
    JOIN Wishlists w ON c.ID_Wishlist = w.ID_Wishlist
    WHERE w.ID_Utilisateur = :user AND c.ID_Offre = :offre
");
$stmt->execute([':user' => $idUtilisateur, ':offre' => $idOffre]);

echo json_encode(['success' => true]);