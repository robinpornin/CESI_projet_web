<?php

class HomeController
{
    public function index()
    {
        global $twig;

        echo $twig->render('accueil.html.twig', [
            'platform_name' => 'Cesi Stages'
        ]);
    }
}