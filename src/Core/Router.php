<?php

namespace App\Core;

use App\Controller\ControllerAccueil;
use App\Controller\ControllerInvite;

class Router
{
    public function handle($url)
    {
        switch ($url) {

            case '/':
                (new ControllerAccueil())->index();
                break;

            case '/connexion':
                echo "Page de connexion (à faire)";
                break;

            case '/invite':
                (new ControllerInvite())->index();
                ;
                break;

            default:
                http_response_code(404);
                echo "404 - Page non trouvée";
        }
    }
}