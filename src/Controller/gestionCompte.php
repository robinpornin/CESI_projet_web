<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageGestionCompte
{
    private \Twig\Environment $twig;
    private PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo  = getPDO();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function render(): void
    {
        $idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;

        if (!$idUtilisateur) {
            die('Utilisateur non connecté.');
        }

        $message = null;
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                http_response_code(403);
                die('Requête invalide.');
            }
            
            $newUsername = trim($_POST['new_username'] ?? '');
            $mdpActuel   = trim($_POST['mdp_actuel'] ?? '');
            $newMdp      = trim($_POST['new_mdp'] ?? '');
            $confirmMdp  = trim($_POST['confirm_mdp'] ?? '');

            $stmt = $this->pdo->prepare("
                SELECT Nom, Prenom, Email, Mdp
                FROM Utilisateurs
                WHERE ID_Utilisateur = :id
            ");
            $stmt->execute([':id' => $idUtilisateur]);
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$utilisateur) {
                $erreur = "Utilisateur introuvable.";
            } else {
                if ($newUsername !== '') {
                    $parties = explode(' ', $newUsername, 2);
                    $prenom = $parties[0] ?? '';
                    $nom = $parties[1] ?? '';

                    if ($prenom !== '' && $nom !== '') {
                        $updateName = $this->pdo->prepare("
                            UPDATE Utilisateurs
                            SET Prenom = :prenom, Nom = :nom
                            WHERE ID_Utilisateur = :id
                        ");
                        $updateName->execute([
                            ':prenom' => $prenom,
                            ':nom' => $nom,
                            ':id' => $idUtilisateur
                        ]);
                        $message = "Nom d'utilisateur mis à jour.";
                    } else {
                        $erreur = "Veuillez entrer prénom et nom.";
                    }
                }

                if ($mdpActuel !== '' || $newMdp !== '' || $confirmMdp !== '') {
                    if (!password_verify($mdpActuel, $utilisateur['Mdp'])) {
                        $erreur = "Mot de passe actuel incorrect.";
                    } elseif ($newMdp !== $confirmMdp) {
                        $erreur = "La confirmation du nouveau mot de passe ne correspond pas.";
                    } else {
                        $newMdpHash = password_hash($newMdp, PASSWORD_DEFAULT);

                        $updateMdp = $this->pdo->prepare("
                            UPDATE Utilisateurs
                            SET Mdp = :mdp
                            WHERE ID_Utilisateur = :id
                        ");
                        $updateMdp->execute([
                            ':mdp' => $newMdpHash,
                            ':id' => $idUtilisateur
                        ]);
                        $message = "Mot de passe mis à jour avec succès.";
                    }
                }
            }
        }

        $stmt = $this->pdo->prepare("
            SELECT Nom, Prenom, Email
            FROM Utilisateurs
            WHERE ID_Utilisateur = :id
        ");
        $stmt->execute([':id' => $idUtilisateur]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        echo $this->twig->render('gestionCompte.html.twig', [
            'page'        => 'gestion_compte',
            'title'       => 'Gestion de votre compte',
            'utilisateur' => $utilisateur,
            'message'     => $message,
            'erreur'      => $erreur,
            'app_user'    => AppUser::fromSession(),
        ]);
    }
}
