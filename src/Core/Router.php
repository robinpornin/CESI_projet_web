<?php

namespace App\Core;

use App\Controller\{
    PageAccueil,
    PageConnexion,
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
    PageWishlist
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
        switch ($url) {

            // --- Accueil ---
            case 'home':
            default :
                (new \PageAccueil($this->twig))->render();
                break;

            // --- Authentification ---
            case 'connexion':
                (new \ControllerConnexion($this->twig))->render();
                break;

            case 'deconnexion':
                session_destroy();
                header('Location: /');
                exit;

            case 'creationCompte':
                (new \ControllerCreationCompte($this->twig))->render();
                break;

            // --- Invité ---
            case 'invite':
                (new \ControllerInvite($this->twig))->render();
                break;

            // --- Espaces utilisateurs ---
            case 'eleve':
                (new \ControllerEleve($this->twig))->render();
                break;

            case 'pilote':
                (new \ControllerPilote($this->twig))->render();
                break;

            case 'admin':
                (new \ControllerAdmin($this->twig))->render();
                break;

            // --- Gestion compte ---
            case 'gestionCompte':
                (new \ControllerGestionCompte($this->twig))->render();
                break;

            case 'gestionCompteEleveAdmin':
                (new \ControllerGestionCompteEleveAdmin($this->twig))->render();
                break;

            case 'gestionComptePiloteAdmin':
                (new \ControllerGestionComptePiloteAdmin($this->twig))->render();
                break;

            // --- Espace élève ---
            case 'espaceEleve':
                (new \ControllerEspaceEleve($this->twig))->render();
                break;

            case 'creationEleve':
                (new \ControllerCreationEleve($this->twig))->render();
                break;

            case 'parametreEleve':
                (new \ControllerParametreEleve($this->twig))->render();
                break;

            // --- Entreprises ---
            case 'gestionEntreprise':
                (new \ControllerGestionEntreprise($this->twig))->render();
                break;

            case 'creationEntreprise':
                (new \ControllerCreationEntreprise($this->twig))->render();
                break;

            case 'ficheEntreprise':
                (new \ControllerFicheEntreprise($this->twig))->render();
                break;

            case 'parametreEntreprise':
                (new \ControllerParametreEntreprise($this->twig))->render();
                break;

            case 'rechercheEntreprise':
                (new \ControllerRechercheEntreprise($this->twig))->render();
                break;

            // --- Offres ---
            case 'gestionOffre':
                (new \ControllerGestionOffre($this->twig))->render();
                break;

            case 'creationOffre':
                (new \ControllerCreationOffre($this->twig))->render();
                break;

            case 'offre':
                (new \ControllerOffre($this->twig))->render();
                break;

            case 'parametreOffre':
                (new \ControllerParametreOffre($this->twig))->render();
                break;

            case 'rechercheOffre':
                (new \ControllerRechercheOffre($this->twig))->render();
                break;

            // --- Wishlist & Formulaire ---
            case 'wishlist':
                (new \ControllerWishlist($this->twig))->render();
                break;

            case 'formulaire':
                (new \ControllerFormulaire($this->twig))->render();
                break;

            // --- 404 ---
            // default:
                //http_response_code(404);
                //echo "404 - Page non trouvée : " . htmlspecialchars($url);
                //break;
        }
    }
}