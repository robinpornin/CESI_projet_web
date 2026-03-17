<?php

class Router
{
    public function handle($url)
    {
        switch ($url) {

            case '/':
                (new ControllerAccueil())->index();
                break;

            case '/connexion':
                echo "Page connexion (à faire)";
                break;

            case '/invite':
                echo "Page invité (à faire)";
                break;

            default:
                http_response_code(404);
                echo "404 - Page non trouvée";
        }
    }
}