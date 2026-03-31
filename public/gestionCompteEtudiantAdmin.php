<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = getPDO();

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input) || !isset($input['action'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Requête invalide.'
        ]);
        exit;
    }

    $action = (string)$input['action'];

    if ($action === 'create') {
        $prenom = trim((string)($input['prenom'] ?? ''));
        $nom    = trim((string)($input['nom'] ?? ''));
        $email  = trim((string)($input['email'] ?? ''));
        $mdp    = trim((string)($input['mdp'] ?? ''));

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
            ':nom'    => $nom,
            ':prenom' => $prenom,
            ':email'  => $email,
            ':mdp'    => $mdpHash,
            ':role'   => 1
        ]);

        $id = (int)$pdo->lastInsertId();

        $etudiant = [
            'ID_Utilisateur' => $id,
            'Nom'            => $nom,
            'Prenom'         => $prenom,
            'Email'          => $email,
            'Role'           => 1
        ];

        echo json_encode([
            'success'  => true,
            'message'  => 'Compte étudiant créé avec succès.',
            'etudiant' => $etudiant
        ]);
        exit;
    }

    if ($action === 'update') {
        $id     = (int)($input['id'] ?? 0);
        $prenom = trim((string)($input['prenom'] ?? ''));
        $nom    = trim((string)($input['nom'] ?? ''));
        $email  = trim((string)($input['email'] ?? ''));

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
            ':id'    => $id
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
              AND Role = 1
        ");

        $stmt->execute([
            ':nom'    => $nom,
            ':prenom' => $prenom,
            ':email'  => $email,
            ':id'     => $id
        ]);

        if ($stmt->rowCount() === 0) {
            $exists = $pdo->prepare("
                SELECT COUNT(*)
                FROM Utilisateurs
                WHERE ID_Utilisateur = :id
                  AND Role = 1
            ");
            $exists->execute([
                ':id' => $id
            ]);

            if ((int)$exists->fetchColumn() === 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Aucun étudiant trouvé à modifier.'
                ]);
                exit;
            }
        }

        $etudiant = [
            'ID_Utilisateur' => $id,
            'Nom'            => $nom,
            'Prenom'         => $prenom,
            'Email'          => $email,
            'Role'           => 1
        ];

        echo json_encode([
            'success'  => true,
            'message'  => 'Compte étudiant modifié avec succès.',
            'etudiant' => $etudiant
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
              AND Role = 1
        ");
        $stmt->execute([
            ':id' => $id
        ]);

        if ($stmt->rowCount() === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Aucun étudiant trouvé à supprimer.'
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Compte étudiant supprimé avec succès.'
        ]);
        exit;
    }

    if ($action === 'search') {
        $terme = trim((string)($input['terme'] ?? ''));

        $stmt = $pdo->prepare("
            SELECT ID_Utilisateur, Nom, Prenom, Email, Role
            FROM Utilisateurs
            WHERE Role = 1
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
            'success'   => true,
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
