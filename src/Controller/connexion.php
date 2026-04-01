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
    }

    public function render(): void
    {
        $erreur = null;
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                http_response_code(403);
                die('Requête invalide.');
            }

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
                $stmt->execute(['email' => $email]);
                $utilisateur = $stmt->fetch();

                if (!$utilisateur) {
                    $erreur = 'Aucun compte trouvé avec cet email.';
                } elseif (!password_verify($motDePasse, $utilisateur['Mdp'])) {
                    $erreur = 'Mot de passe incorrect.';
                } else {
                    // session_regenerate_id(true); // commenté temporairement

                    $_SESSION['utilisateur'] = [
                        'id'     => $utilisateur['ID_Utilisateur'],
                        'nom'    => $utilisateur['Nom'],
                        'prenom' => $utilisateur['Prenom'],
                        'email'  => $utilisateur['Email'],
                        'role'   => $utilisateur['Role'],
                    ];

                    switch ((int) $utilisateur['Role']) {
                        case 1:
                            header('Location: /eleve');
                            break;
                        case 2:
                            header('Location: /pilote');
                            break;
                        case 3:
                            header('Location: /admin');
                            break;
                        default:
                            header('Location: /');
                            break;
                    }
                    exit;
                }
            }
        }

        echo $this->twig->render('connexion.html.twig', [
            'page'          => 'Connexion',
            'title'         => 'Connexion',
            'platform_name' => 'CESI-STAGES',
            'erreur'        => $erreur,
            'message'       => $message,
        ]);
    }
}