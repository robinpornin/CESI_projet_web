<?php
declare(strict_types=1);
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../database.php';

$idUtilisateur = $_SESSION['utilisateur']['id'] ?? 1;

if (!$idUtilisateur) {
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$idOffre = (int) ($data['id_offre'] ?? 0);

if (!$idOffre) {
    echo json_encode(['success' => false, 'message' => 'ID offre invalide']);
    exit;
}

try {
    $pdo = getPDO();

    // Récupérer la wishlist
    $stmt = $pdo->prepare("SELECT ID_Wishlist FROM Wishlists WHERE ID_Utilisateur = :id");
    $stmt->execute([':id' => $idUtilisateur]);
    $wishlist = $stmt->fetch();

    if (!$wishlist) {
        echo json_encode(['success' => false, 'message' => 'Wishlist introuvable']);
        exit;
    }

    $idWishlist = $wishlist['ID_Wishlist'];

    // Vérifier doublon
    $check = $pdo->prepare("SELECT 1 FROM Contenir WHERE ID_Offre = :offre AND ID_Wishlist = :wishlist");
    $check->execute([':offre' => $idOffre, ':wishlist' => $idWishlist]);

    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Déjà dans la wishlist']);
        exit;
    }

    // Insérer
    $insert = $pdo->prepare("INSERT INTO Contenir (ID_Offre, ID_Wishlist) VALUES (:offre, :wishlist)");
    $insert->execute([':offre' => $idOffre, ':wishlist' => $idWishlist]);

    echo json_encode(['success' => true, 'message' => 'Offre ajoutée à la wishlist']);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}