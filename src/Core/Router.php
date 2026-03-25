<?php

namespace App\Core;

use App\Controller\ControllerAccueil;
use App\Controller\ControllerConnexion;
use App\Controller\ControllerInvite;
use App\Controller\ControllerAdmin;
use App\Controller\ControllerFicheEntreprise;
use App\Controller\ControllerCreationCompte;
use App\Controller\ControllerCreationEleve;
use App\Controller\ControllerCreationEntreprise;

use App\Controller\ControllerCreationOffre;
use App\Controller\ControllerEleve;
use App\Controller\ControllerEspaceEleve;
use App\Controller\ControllerFormulaire;



class Router
{
    public function handle($url)
    {
        switch ($url) {

            case '/':
                (new ControllerAccueil())->index();
                break;

            case '/connexion':
                (new ControllerConnexion())->index();
                break;

            case '/invite':
                (new ControllerInvite())->index();
                ;
                break;

            case '/creationOffre':
                (new ControllerCreationOffre())->index();
                ;
                break;

            default:
                http_response_code(404);
                echo "404 - Page non trouvée";
        }
    }
}