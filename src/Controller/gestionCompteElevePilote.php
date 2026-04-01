<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageGestionCompteElevePilote
{
    private \Twig\Environment $twig;
    private PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo  = getPDO();
    }

    public function render(): void
    {
        $stmt = $this->pdo->query("
            SELECT ID_Utilisateur, Nom, Prenom, Email
            FROM Utilisateurs
            WHERE Role = 1
            ORDER BY Nom ASC, Prenom ASC
        ");
        $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo $this->twig->render('gestionCompteEleve_Pilote.html.twig', [
            'page'      => 'gestion_eleve_pilote',
            'title'     => 'Gestion des comptes Étudiants',
            'etudiants' => $etudiants,
            'app_user'  => AppUser::fromSession(),
        ]);
    }

    public function handleAction(): void
    {
        header('Content-Type: application/json');

        $body   = file_get_contents('php://input');
        $data   = json_decode($body, true);
        $action = $data['action'] ?? '';

        match ($action) {
            'create' => $this->creer($data),
            'update' => $this->modifier($data),
            'delete' => $this->supprimer($data),
            default  => $this->json(['success' => false, 'message' => 'Action inconnue.']),
        };
    }

    // ── Créer ──────────────────────────────────────────────────────────────
    private function creer(array $data): void
    {
        $prenom = trim($data['prenom'] ?? '');
        $nom    = trim($data['nom']    ?? '');
        $email  = trim($data['email']  ?? '');
        $mdp    = trim($data['mdp']    ?? '');

        if (!$prenom || !$nom || !$email || !$mdp) {
            $this->json(['success' => false, 'message' => 'Tous les champs sont obligatoires.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Email invalide.']);
            return;
        }

        // Vérif doublon
        $check = $this->pdo->prepare("SELECT ID_Utilisateur FROM Utilisateurs WHERE Email = :email");
        $check->execute(['email' => $email]);
        if ($check->fetch()) {
            $this->json(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
            return;
        }

        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("
            INSERT INTO Utilisateurs (Nom, Prenom, Email, Mdp, Role)
            VALUES (:nom, :prenom, :email, :mdp, 1)
        ");
        $stmt->execute([
            'nom'    => $nom,
            'prenom' => $prenom,
            'email'  => $email,
            'mdp'    => $hash,
        ]);

        $id = (int) $this->pdo->lastInsertId();

        $this->json([
            'success'  => true,
            'message'  => 'Compte étudiant créé avec succès.',
            'etudiant' => [
                'ID_Utilisateur' => $id,
                'Nom'            => $nom,
                'Prenom'         => $prenom,
                'Email'          => $email,
            ],
        ]);
    }

    // ── Modifier ───────────────────────────────────────────────────────────
    private function modifier(array $data): void
    {
        $id     = (int) ($data['id']     ?? 0);
        $prenom = trim($data['prenom']   ?? '');
        $nom    = trim($data['nom']      ?? '');
        $email  = trim($data['email']    ?? '');

        if (!$id || !$prenom || !$nom || !$email) {
            $this->json(['success' => false, 'message' => 'Données manquantes.']);
            return;
        }

        $stmt = $this->pdo->prepare("
            UPDATE Utilisateurs
            SET Nom = :nom, Prenom = :prenom, Email = :email
            WHERE ID_Utilisateur = :id AND Role = 1
        ");
        $stmt->execute([
            'nom'    => $nom,
            'prenom' => $prenom,
            'email'  => $email,
            'id'     => $id,
        ]);

        $this->json([
            'success'  => true,
            'message'  => 'Compte étudiant modifié avec succès.',
            'etudiant' => [
                'ID_Utilisateur' => $id,
                'Nom'            => $nom,
                'Prenom'         => $prenom,
                'Email'          => $email,
            ],
        ]);
    }

    // ── Supprimer ──────────────────────────────────────────────────────────
    private function supprimer(array $data): void
    {
        $id = (int) ($data['id'] ?? 0);

        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID manquant.']);
            return;
        }

        $stmt = $this->pdo->prepare("
            DELETE FROM Utilisateurs
            WHERE ID_Utilisateur = :id AND Role = 1
        ");
        $stmt->execute(['id' => $id]);

        $this->json(['success' => true, 'message' => 'Compte étudiant supprimé avec succès.']);
    }

    // ── Helper JSON ────────────────────────────────────────────────────────
    private function json(array $payload): void
    {
        echo json_encode($payload);
        exit;
    }
}