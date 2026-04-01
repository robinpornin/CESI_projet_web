<?php

declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageFormulaire
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
        $message = null;
        $erreur  = null;

        $idUtilisateur = $_SESSION['utilisateur']['id'] ?? null;
        $idOffre       = (int) ($_GET['id'] ?? 0);

        if (!$idUtilisateur) {
            $erreur = "Vous devez être connecté pour postuler.";
        }

        if ($idOffre <= 0) {
            $erreur = "Offre introuvable.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$erreur) {

            // Vérification CSRF
            if (
                empty($_POST['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                http_response_code(403);
                die('Requête invalide.');
            }

            $cv     = $_FILES['cv'] ?? null;
            $lettre = $_FILES['lettre_motivation'] ?? null;

            if (!$cv || $cv['error'] !== UPLOAD_ERR_OK) {
                $erreur = "Le CV est obligatoire.";
            } elseif (!$lettre || $lettre['error'] !== UPLOAD_ERR_OK) {
                $erreur = "La lettre de motivation est obligatoire.";
            } else {
                $extensionCv     = strtolower(pathinfo($cv['name'], PATHINFO_EXTENSION));
                $extensionLettre = strtolower(pathinfo($lettre['name'], PATHINFO_EXTENSION));

                if ($extensionCv !== 'pdf') {
                    $erreur = "Le CV doit être au format PDF.";
                } elseif ($extensionLettre !== 'pdf') {
                    $erreur = "La lettre de motivation doit être au format PDF.";
                } else {
                    // Vérification doublon
                    $stmt = $this->pdo->prepare("
                        SELECT COUNT(*)
                        FROM Candidatures
                        WHERE ID_Utilisateur = :id_utilisateur
                          AND ID_Offre = :id_offre
                    ");
                    $stmt->execute([
                        ':id_utilisateur' => $idUtilisateur,
                        ':id_offre'       => $idOffre,
                    ]);

                    $dejaPostule = (int) $stmt->fetchColumn() > 0;

                    if ($dejaPostule) {
                        $erreur = "Vous avez déjà postulé à cette offre.";
                    } else {
                        // ✅ Chemin dynamique (plus de hardcode)
                        $dossierCv     = realpath(__DIR__ . '/../../uploads') . '/cv/';
                        $dossierLettre = realpath(__DIR__ . '/../../uploads') . '/lettres/';

                        if (!is_dir($dossierCv)) {
                            mkdir($dossierCv, 0755, true);
                        }
                        if (!is_dir($dossierLettre)) {
                            mkdir($dossierLettre, 0755, true);
                        }

                        $nomCv     = uniqid('cv_', true) . '.pdf';
                        $nomLettre = uniqid('lettre_', true) . '.pdf';

                        $cheminCv     = $dossierCv . $nomCv;
                        $cheminLettre = $dossierLettre . $nomLettre;

                        if (
                            move_uploaded_file($cv['tmp_name'], $cheminCv) &&
                            move_uploaded_file($lettre['tmp_name'], $cheminLettre)
                        ) {
                            $stmt = $this->pdo->prepare("
                                INSERT INTO Candidatures (Lettre_de_motivation, CV, ID_Offre, ID_Utilisateur)
                                VALUES (:lettre, :cv, :id_offre, :id_utilisateur)
                            ");
                            $stmt->execute([
                                ':lettre'         => $nomLettre,
                                ':cv'             => $nomCv,
                                ':id_offre'       => $idOffre,
                                ':id_utilisateur' => $idUtilisateur,
                            ]);

                            $message = "Votre candidature a bien été envoyée.";
                        } else {
                            $erreur = "Erreur lors de l'envoi des fichiers.";
                        }
                    }
                }
            }
        }

        echo $this->twig->render('formulaire.html.twig', [
            'message' => $message,
            'erreur'  => $erreur,
            'idOffre' => $idOffre,
        ]);
    }
}
