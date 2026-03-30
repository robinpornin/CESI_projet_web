<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getPDO();

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['action'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Requête invalide.'
        ]);
        exit;
    }

    $action = $input['action'];

    if ($action === 'create') {
        $prenom = trim($input['prenom'] ?? '');
        $nom = trim($input['nom'] ?? '');
        $email = trim($input['email'] ?? '');
        $mdp = trim($input['mdp'] ?? '');

        if ($prenom === '' || $nom === '' || $email === '' || $mdp === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Tous les champs sont obligatoires.'
            ]);
            exit;
        }

        $check = $pdo->prepare("
            SELECT COUNT(*) 
            FROM Utilisateurs 
            WHERE Email = :email
        ");
        $check->execute([
            ':email' => $email
        ]);

        if ((int)$check->fetchColumn() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Cet email existe déjà.'
            ]);
            exit;
        }

        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO Utilisateurs (Nom, Prenom, Email, Mdp, Role)
            VALUES (:nom, :prenom, :email, :mdp, :role)
        ");

        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mdp' => $mdpHash,
            ':role' => 2
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Compte pilote créé avec succès.',
            'id' => $pdo->lastInsertId()
        ]);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($input['id'] ?? 0);
        $prenom = trim($input['prenom'] ?? '');
        $nom = trim($input['nom'] ?? '');
        $email = trim($input['email'] ?? '');

        if ($id <= 0 || $prenom === '' || $nom === '' || $email === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Données invalides pour la modification.'
            ]);
            exit;
        }

        $check = $pdo->prepare("
            SELECT COUNT(*)
            FROM Utilisateurs
            WHERE Email = :email
            AND ID_Utilisateur != :id
        ");
        $check->execute([
            ':email' => $email,
            ':id' => $id
        ]);

        if ((int)$check->fetchColumn() > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Cet email est déjà utilisé.'
            ]);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE Utilisateurs
            SET Nom = :nom,
                Prenom = :prenom,
                Email = :email
            WHERE ID_Utilisateur = :id
        ");

        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':id' => $id
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Compte modifié avec succès.'
        ]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID invalide.'
            ]);
            exit;
        }

        $stmt = $pdo->prepare("
            DELETE FROM Utilisateurs
            WHERE ID_Utilisateur = :id
        ");
        $stmt->execute([
            ':id' => $id
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Compte supprimé avec succès.'
        ]);
        exit;
    }

    if ($action === 'search') {
        $terme = trim($input['terme'] ?? '');

        $stmt = $pdo->prepare("
            SELECT ID_Utilisateur, Nom, Prenom, Email, Role
            FROM Utilisateurs
            WHERE Role = 2
            AND (
                Nom LIKE :terme
                OR Prenom LIKE :terme
                OR Email LIKE :terme
            )
            ORDER BY Nom ASC, Prenom ASC
        ");

        $stmt->execute([
            ':terme' => '%' . $terme . '%'
        ]);

        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'resultats' => $resultats
        ]);
        exit;
    }

    echo json_encode([
        'success' => false,
        'message' => 'Action inconnue.'
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur : ' . $e->getMessage()
    ]);
    exit;
}
