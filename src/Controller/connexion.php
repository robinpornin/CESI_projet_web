<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageConnexion
{
    private \Twig\Environment $twig;
    private PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo = getPDO();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Récupère les données et affiche la page de connexion.
     */
    public function render(): void
    {
        $erreur = null;
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            if ($email === '' || $motDePasse === '') {
                $erreur = 'Veuillez remplir tous les champs.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreur = 'Adresse email invalide.';
            } else {
                $sql = "SELECT ID_Utilisateur, Nom, Prenom, Email, Mdp, Role
                        FROM Utilisateurs
                        WHERE Email = :email";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'email' => $email,
                ]);

                $utilisateur = $stmt->fetch();

                if (!$utilisateur) {
                    $erreur = 'Aucun compte trouvé avec cet email.';
                } elseif (!password_verify($motDePasse, $utilisateur['Mdp'])) {
                    $erreur = 'Mot de passe incorrect.';
                } else {
                    $_SESSION['utilisateur'] = [
                        'id' => $utilisateur['ID_Utilisateur'],
                        'nom' => $utilisateur['Nom'],
                        'prenom' => $utilisateur['Prenom'],
                        'email' => $utilisateur['Email'],
                        'role' => $utilisateur['Role'],
                    ];

                    $message = 'Connexion réussie.';

                    // Exemple de redirection :
                    // header('Location: /dashboard');
                    // exit;
                }
            }
        }

        echo $this->twig->render('connexion.html.twig', [
            'page' => 'Connexion',
            'title' => 'Connexion',
            'platform_name' => 'CESI-STAGES',
            'erreur' => $erreur,
            'message' => $message,
        ]);
    }
}

