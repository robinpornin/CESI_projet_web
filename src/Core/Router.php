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
    PageMentionsLegales
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
            case '' :
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
                session_destroy();
                header('Location: /');
                exit;
            
            case 'suppressionCompte':
                session_destroy();
                header('Location: /');
                exit;


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
            case 'wishlist':
                (new \PageWishlist($this->twig))->render();
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
                echo "404 - Page non trouvée : " . htmlspecialchars($url);
                break;
        }
    }
}