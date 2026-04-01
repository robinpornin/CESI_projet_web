<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

use App\Core\Middleware;

class PageCreationEntreprise
{
    private \Twig\Environment $twig;
    private \PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo = getPDO();
    }

    public function render(): void
    {
        $error   = null;
        $success = false;

        $form = [
            'nom_entreprise'         => '',
            'email_entreprise'       => '',
            'secteur'                => '',
            'type'                   => '',
            'nb_stagiaires'          => '',
            'telephone'              => '',
            'description_entreprise' => '',
        ];

        $jwtUser = Middleware::getUtilisateur();

        if ($jwtUser === null || empty($jwtUser->id)) {
            $error = "Vous devez être connecté pour créer une entreprise.";

            echo $this->twig->render('creationEntreprise.html.twig', [
                'page'          => 'creation_entreprise',
                'title'         => 'Création d\'une entreprise',
                'platform_name' => 'CESI-STAGES',
                'error'         => $error,
                'success'       => $success,
                'form'          => $form,
            ]);
            return;
        }

        $idUtilisateur = (int) $jwtUser->id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $form['email_entreprise']       = trim($_POST['email_entreprise'] ?? '');
            $form['nom_entreprise']         = trim($_POST['nom_entreprise'] ?? '');
            $form['secteur']                = trim($_POST['secteur'] ?? '');
            $form['type']                   = trim($_POST['type'] ?? '');
            $form['nb_stagiaires']          = trim($_POST['nb_stagiaires'] ?? '');
            $form['description_entreprise'] = trim($_POST['description_entreprise'] ?? '');
            $form['telephone']              = trim($_POST['telephone'] ?? '');

            $emailEntreprise       = $form['email_entreprise'];
            $nomEntreprise         = $form['nom_entreprise'];
            $secteur               = $form['secteur'];
            $type                  = $form['type'];
            $nbStagiaires          = (int) $form['nb_stagiaires'];
            $descriptionEntreprise = $form['description_entreprise'];
            $telephone             = $form['telephone'];

            if (
                $emailEntreprise === '' ||
                $nomEntreprise === '' ||
                $secteur === '' ||
                $type === '' ||
                $form['nb_stagiaires'] === '' ||
                $descriptionEntreprise === '' ||
                $telephone === ''
            ) {
                $error = "Veuillez remplir tous les champs obligatoires.";
            } elseif (!filter_var($emailEntreprise, FILTER_VALIDATE_EMAIL)) {
                $error = "L'adresse email n'est pas valide.";
            } elseif ($nbStagiaires < 0) {
                $error = "Le nombre de stagiaires ne peut pas être négatif.";
            } else {
                try {
                    $stmt = $this->pdo->prepare("
                        INSERT INTO Entreprises
                            (Email_entreprise, Nom_entreprise, Secteur, Type_, Nb_stagiaires,
                             Description_entreprise, Telephone, ID_Utilisateur)
                        VALUES
                            (:email_entreprise, :nom_entreprise, :secteur, :type, :nb_stagiaires,
                             :description_entreprise, :telephone, :id_utilisateur)
                    ");
                    $stmt->execute([
                        ':email_entreprise'       => $emailEntreprise,
                        ':nom_entreprise'         => $nomEntreprise,
                        ':secteur'                => $secteur,
                        ':type'                   => $type,
                        ':nb_stagiaires'          => $nbStagiaires,
                        ':description_entreprise' => $descriptionEntreprise,
                        ':telephone'              => $telephone,
                        ':id_utilisateur'         => $idUtilisateur,
                    ]);

                    $success = true;
                    $form = [
                        'nom_entreprise'         => '',
                        'email_entreprise'       => '',
                        'secteur'                => '',
                        'type'                   => '',
                        'nb_stagiaires'          => '',
                        'telephone'              => '',
                        'description_entreprise' => '',
                    ];
                } catch (\PDOException $e) {
                    $error = $e->getCode() === '23000'
                        ? "Une entreprise avec cet email existe déjà."
                        : "Erreur lors de la création de l'entreprise : " . $e->getMessage();
                }
            }
        }

        echo $this->twig->render('creationEntreprise.html.twig', [
            'page'          => 'creation_entreprise',
            'title'         => 'Création d\'une entreprise',
            'platform_name' => 'CESI-STAGES',
            'error'         => $error,
            'success'       => $success,
            'form'          => $form,
        ]);
    }
}
