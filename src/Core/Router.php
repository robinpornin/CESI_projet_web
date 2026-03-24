<?php

namespace App\Core;

use App\Controller\ControllerAccueil;
use App\Controller\ControllerConnexion;
use App\Controller\ControllerInvite;
use App\Controller\ControllerCreationOffre;


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