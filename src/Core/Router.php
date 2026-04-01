<?php

namespace App\Core;

use App\Controller\{
    PageAccueil,
    PageConnexion,
    PageContactAdmin,
    PageInvite,
    PageAdmin,
    PageFicheEntreprise,
    PageCreationCompte,
    PageCreationEleve,
    PageCreationEntreprise,
    PageCreationOffre,
    PageEleve,
    PageEspaceEleve,
    PageFormulaire,
    PageGestionCompte,
    PageGestionEntreprise,
    PageGestionOffre,
    PageGestionCompteEleveAdmin,
    PageGestionComptePiloteAdmin,
    PageOffre,
    PageParametreEleve,
    PageParametreEntreprise,
    PageParametreOffre,
    PagePilote,
    PageRechercheEntreprise,
    PageRechercheOffre,
    PageWishlist,
    PageListeCandidatures,
    PageMentionsLegales,
    PageModificationEntreprise,
    PageGestionCompteElevePilote,
};

class Router
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(string $url): void
    {
        \App\Core\Middleware::check($url);

        switch ($url) {

            // --- Accueil ---
            case 'home':
            case '':
                (new \PageAccueil($this->twig))->render();
                break;

            // --- Authentification ---
            case 'connexion':
                (new \PageConnexion($this->twig))->render();
                break;

            case 'contactAdmin':
                (new \PageContactAdmin($this->twig))->render();
                break;

            case 'deconnexion':
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                session_unset();
                session_destroy();
                header('Location: /');
                exit;

            case 'suppressionCompte':

                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    http_response_code(405);
                    exit;
                }

                if (
                    empty($_POST['csrf_token']) ||
                    !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
                ) {
                    http_response_code(403);
                    die('Requête invalide.');
                }

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $idUtilisateur = (int) ($_SESSION['utilisateur']['id'] ?? 0);

                if ($idUtilisateur <= 0) {
                    header('Location: /gestionCompte');
                    exit;
                }

                require_once __DIR__ . '/../../database.php';
                $pdo = getPDO();

                try {
                    $pdo->beginTransaction();

                    $stmt = $pdo->prepare('DELETE FROM Requerir WHERE ID_Offre IN (
                        SELECT ID_Offre FROM Offres WHERE ID_Entreprise IN (
                            SELECT ID_Entreprise FROM Entreprises WHERE ID_Utilisateur = :id
                        )
                    )');
                    $stmt->execute(['id' => $idUtilisateur]);


                    $stmt = $pdo->prepare('DELETE FROM Contenir 
                        WHERE ID_Wishlist IN (
                            SELECT ID_Wishlist
                            FROM Wishlists
                            WHERE ID_Utilisateur = :id
                        )');
                    $stmt->execute(['id' => $idUtilisateur]);

                    $stmt = $pdo->prepare('DELETE FROM Candidatures WHERE ID_Utilisateur = :id');
                    $stmt->execute(['id' => $idUtilisateur]);

                    $stmt = $pdo->prepare('DELETE FROM Evaluations WHERE ID_Utilisateur = :id');
                    $stmt->execute(['id' => $idUtilisateur]);

                    $stmt = $pdo->prepare('DELETE FROM Wishlists WHERE ID_Utilisateur = :id');
                    $stmt->execute(['id' => $idUtilisateur]);

                    $stmt = $pdo->prepare('DELETE FROM Entreprises WHERE ID_Utilisateur = :id');
                    $stmt->execute(['id' => $idUtilisateur]);

                    $stmt = $pdo->prepare('DELETE FROM Utilisateurs WHERE ID_Utilisateur = :id');
                    $stmt->execute(['id' => $idUtilisateur]);

                    $pdo->commit();

                    session_unset();
                    session_destroy();

                    header('Location: /');
                    exit;

                } catch (\Throwable $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }

                    echo '<pre>';
                    echo 'Erreur suppression compte :' . "\n";
                    echo $e->getMessage() . "\n\n";
                    print_r($e);
                    echo '</pre>';
                    exit;
                }


            case 'creationCompte':
                (new \PageCreationCompte($this->twig))->render();
                break;

            // --- Invité ---
            case 'invite':
                (new \PageInvite($this->twig))->render();
                break;

            // --- Espaces utilisateurs ---
            case 'eleve':
                (new \PageEleve($this->twig))->render();
                break;

            case 'pilote':
                (new \PagePilote($this->twig))->render();
                break;

            case 'admin':
                (new \PageAdmin($this->twig))->render();
                break;

            // --- Gestion compte ---
            case 'gestionCompte':
                (new \PageGestionCompte($this->twig))->render();
                break;

            case 'gestionCompteEleveAdmin':
                (new \PageGestionCompteEleveAdmin($this->twig))->render();
                break;
            
            case 'gestionCompteElevePilote':
                (new \PageGestionCompteElevePilote($this->twig))->render();
                break;

            case 'gestionComptePiloteAdmin':
                (new \PageGestionComptePiloteAdmin($this->twig))->render();
                break;

            // --- Espace élève ---
            case 'espaceEleve':
                (new \PageEspaceEleve($this->twig))->render();
                break;

            case 'creationEleve':
                (new \PageCreationEleve($this->twig))->render();
                break;

            case 'parametreEleve':
                (new \PageParametreEleve($this->twig))->render();
                break;

            case 'listeCandidatures':
                (new \PageListeCandidatures($this->twig))->render();
                break;
            
            case 'listeCandidaturesPilote':
                (new \PageListeCandidaturesPilote($this->twig))->render();
                break;
            
            case 'file':
                (new \PageFile($this->twig))->render();
                break;

            // --- Entreprises ---
            case 'gestionEntreprise':
                (new \PageGestionEntreprise($this->twig))->render();
                break;

            case 'creationEntreprise':
                (new \PageCreationEntreprise($this->twig))->render();
                break;

            case 'ficheEntreprise':
                (new \PageFicheEntreprise($this->twig))->render();
                break;

            case 'parametreEntreprise':
                (new \PageParametreEntreprise($this->twig))->render();
                break;

            case 'rechercheEntreprise':
                (new \PageRechercheEntreprise($this->twig))->render();
                break;

            case 'modificationEntreprise':
                (new \PageModificationEntreprise($this->twig))->render();
                break;

            // --- Offres ---
            case 'gestionOffre':
                (new \PageGestionOffre($this->twig))->render();
                break;

            case 'creationOffre':
                (new \PageCreationOffre($this->twig))->render();
                break;

            case 'offre':
                (new \PageOffre($this->twig))->render();
                break;

            case 'parametreOffre':
                (new \PageParametreOffre($this->twig))->render();
                break;

            case 'rechercheOffre':
                (new \PageRechercheOffre($this->twig))->render();
                break;

            // --- Wishlist & Formulaire ---
            // --- Wishlist & Formulaire ---
            case 'wishlist':
            (new \PageWishlist($this->twig))->render();
            break;

            case 'wishlist/ajouter':
            (new \PageWishlist($this->twig))->ajouter();
            break;

            case 'wishlist/supprimer':
            (new \PageWishlist($this->twig))->supprimer();
            break;


            case 'formulaire':
                (new \PageFormulaire($this->twig))->render();
                break;

            // --- Mentions Légales ---
            case 'mentionsLegales':
                (new \PageMentionsLegales($this->twig))->render();
                break;

            // --- 404 ---
            default:
                http_response_code(404);
                echo '404 - Page non trouvée : ' . htmlspecialchars($url);
                break;
        }
    }
}