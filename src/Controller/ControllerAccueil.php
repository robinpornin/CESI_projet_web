<?php

namespace App\Controller;

class ControllerAccueil
{
    public function index()
    {
        global $twig;

        echo $twig->render('accueil.html.twig', [
            'platform_name' => 'notre plateforme d\'offres de stage'
        ]);
    }
}