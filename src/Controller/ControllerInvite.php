<?php

namespace App\Controller;

class ControllerInvite
{
    public function index()
    {
        global $twig;

        echo $twig->render('invite.html.twig', [
            'platform_name' => 'notre plateforme d\'offres de stage',
            'mode' => 'Mode Invité'
        ]);
    }
}