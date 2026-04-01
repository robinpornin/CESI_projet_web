<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageCreationOffre
{
    private \Twig\Environment $twig;
    private \PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo = getPDO();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function render(): void
    {
        $error = null;
        $success = false;

        $form = [
            'titre' => '',
            'id_entreprise' => '',
            'ville_cp' => '',
            'duree' => '',
            'remuneration' => '',
            'date_debut' => '',
            'description' => '',
            'competences' => [],
        ];

        if (
            !isset($_SESSION['utilisateur']) ||
            !isset($_SESSION['utilisateur']['id']) ||
            empty($_SESSION['utilisateur']['id'])
        ) {
            $error = "Vous devez être connecté pour créer une offre.";

            echo $this->twig->render('creationOffre.html.twig', [
                'page' => 'creation_offre',
                'title' => 'Création d\'une offre',
                'platform_name' => 'CESI-STAGES',
                'error' => $error,
                'success' => $success,
                'form' => $form,
                'competences' => [],
                'entreprises' => [],
            ]);
            return;
        }

        $idUtilisateur = (int) $_SESSION['utilisateur']['id'];

        $stmtCompetences = $this->pdo->query("
            SELECT ID_Competence, Nom_competence
            FROM Competences
            ORDER BY Nom_competence ASC
        ");
        $competences = $stmtCompetences->fetchAll();

        $stmtEntreprises = $this->pdo->prepare("
            SELECT ID_Entreprise, Nom_entreprise, Secteur
            FROM Entreprises
            WHERE ID_Utilisateur = :id_utilisateur
            ORDER BY Nom_entreprise ASC
        ");
        $stmtEntreprises->execute([
            ':id_utilisateur' => $idUtilisateur,
        ]);
        $entreprises = $stmtEntreprises->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $form['titre'] = trim($_POST['titre'] ?? '');
            $form['id_entreprise'] = trim($_POST['id_entreprise'] ?? '');
            $form['ville_cp'] = trim($_POST['ville_cp'] ?? '');
            $form['duree'] = trim($_POST['duree'] ?? '');
            $form['remuneration'] = trim($_POST['remuneration'] ?? '');
            $form['date_debut'] = trim($_POST['date_debut'] ?? '');
            $form['description'] = trim($_POST['description'] ?? '');
            $form['competences'] = $_POST['competences'] ?? [];

            $titre = $form['titre'];
            $idEntreprise = (int) $form['id_entreprise'];
            $villeCp = $form['ville_cp'];
            $duree = (int) $form['duree'];
            $remuneration = (float) $form['remuneration'];
            $dateDebut = $form['date_debut'];
            $description = $form['description'];
            $competencesSelectionnees = array_map('intval', $form['competences']);

            if (
                $titre === '' ||
                $form['id_entreprise'] === '' ||
                $villeCp === '' ||
                $form['duree'] === '' ||
                $form['remuneration'] === '' ||
                $dateDebut === '' ||
                $description === ''
            ) {
                $error = "Veuillez remplir tous les champs obligatoires.";
            } elseif ($duree < 1 || $duree > 24) {
                $error = "La durée doit être comprise entre 1 et 24 mois.";
            } elseif ($remuneration < 0) {
                $error = "La gratification ne peut pas être négative.";
            } else {
                $entrepriseValide = false;

                foreach ($entreprises as $entreprise) {
                    if ((int) $entreprise['ID_Entreprise'] === $idEntreprise) {
                        $entrepriseValide = true;

                        if ($villeCp === '') {
                            $villeCp = (string) $entreprise['Secteur'];
                        }

                        break;
                    }
                }

                if (!$entrepriseValide) {
                    $error = "L'entreprise sélectionnée est invalide.";
                } else {
                    try {
                        $this->pdo->beginTransaction();

                        $sqlOffre = "INSERT INTO Offres
                            (Titre, Remuneration, Date_, Description, Duree, Ville_CP, ID_Entreprise, ID_Utilisateur)
                            VALUES
                            (:titre, :remuneration, :date_debut, :description, :duree, :ville_cp, :id_entreprise, :id_utilisateur)";

                        $stmtOffre = $this->pdo->prepare($sqlOffre);
                        $stmtOffre->execute([
                            ':titre' => $titre,
                            ':remuneration' => $remuneration,
                            ':date_debut' => $dateDebut,
                            ':description' => $description,
                            ':duree' => $duree,
                            ':ville_cp' => $villeCp,
                            ':id_entreprise' => $idEntreprise,
                            ':id_utilisateur' => $idUtilisateur,
                        ]);

                        $idOffre = (int) $this->pdo->lastInsertId();

                        if (!empty($competencesSelectionnees)) {
                            $sqlCompetence = "INSERT INTO Requerir (ID_Offre, ID_Competence)
                                              VALUES (:id_offre, :id_competence)";
                            $stmtCompetence = $this->pdo->prepare($sqlCompetence);

                            foreach ($competencesSelectionnees as $idCompetence) {
                                $stmtCompetence->execute([
                                    ':id_offre' => $idOffre,
                                    ':id_competence' => $idCompetence,
                                ]);
                            }
                        }

                        $this->pdo->commit();

                        $success = true;

                        $form = [
                            'titre' => '',
                            'id_entreprise' => '',
                            'ville_cp' => '',
                            'duree' => '',
                            'remuneration' => '',
                            'date_debut' => '',
                            'description' => '',
                            'competences' => [],
                        ];
                    } catch (\PDOException $e) {
                        if ($this->pdo->inTransaction()) {
                            $this->pdo->rollBack();
                        }

                        $error = "Erreur lors de la création de l'offre : " . $e->getMessage();
                    }
                }
            }
        }

        echo $this->twig->render('creationOffre.html.twig', [
            'page' => 'creation_offre',
            'title' => 'Création d\'une offre',
            'platform_name' => 'CESI-STAGES',
            'error' => $error,
            'success' => $success,
            'form' => $form,
            'competences' => $competences,
            'entreprises' => $entreprises,
        ]);
    }
}
