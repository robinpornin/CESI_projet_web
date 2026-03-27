<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageModificationEntreprise
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
        $id     = (int) ($_GET['id'] ?? 0);
        $succes = false;
        $erreur = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id'] ?? 0);

            $nom          = trim($_POST['nom']          ?? '');
            $secteur      = trim($_POST['secteur']      ?? '');
            $type         = trim($_POST['type']         ?? '');
            $email        = trim($_POST['email']        ?? '');
            $telephone    = trim($_POST['telephone']    ?? '');
            $description  = trim($_POST['description']  ?? '');
            $nbStagiaires = (int) ($_POST['nb_stagiaires'] ?? 0);

            if ($nom === '' || $secteur === '' || $email === '') {
                $erreur = 'Les champs Nom, Secteur et Email sont obligatoires.';
            } else {
                $stmt = $this->pdo->prepare("
                    UPDATE Entreprises
                    SET
                        Nom_entreprise         = :nom,
                        Secteur                = :secteur,
                        Type_                  = :type,
                        Email_entreprise       = :email,
                        Telephone              = :telephone,
                        Description_entreprise = :description,
                        Nb_stagiaires          = :nb
                    WHERE ID_Entreprise = :id
                ");
                $stmt->execute([
                    ':nom'         => $nom,
                    ':secteur'     => $secteur,
                    ':type'        => $type,
                    ':email'       => $email,
                    ':telephone'   => $telephone,
                    ':description' => $description,
                    ':nb'          => $nbStagiaires,
                    ':id'          => $id,
                ]);
                $succes = true;
            }
        }

        $stmt = $this->pdo->prepare("
            SELECT * FROM Entreprises WHERE ID_Entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $entreprise = $stmt->fetch();

        echo $this->twig->render('modificationEntreprise.html.twig', [
            'page'       => 'modification_entreprise',
            'title'      => 'Modifier l\'entreprise',
            'entreprise' => $entreprise,
            'succes'     => $succes,
            'erreur'     => $erreur,
        ]);
    }
}