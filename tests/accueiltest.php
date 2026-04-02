<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Controller/accueil.php';

final class PageAccueilTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testRenderSansUtilisateurEnSession(): void
    {
        $twig = $this->createMock(\Twig\Environment::class);

        $twig->expects($this->once())
            ->method('render')
            ->with(
                'accueil.html.twig',
                [
                    'page' => 'accueil',
                    'title' => 'Accueil',
                    'platform_name' => 'CESI-STAGES',
                    'utilisateur' => null,
                ]
            )
            ->willReturn('<h1>Accueil</h1>');

        $controller = new PageAccueil($twig);

        ob_start();
        $controller->render();
        $output = ob_get_clean();

        $this->assertSame('<h1>Accueil</h1>', $output);
    }

    public function testRenderAvecUtilisateurEnSession(): void
    {
        $utilisateur = new stdClass();
        $utilisateur->id = 1;
        $utilisateur->nom = 'Dupont';
        $utilisateur->prenom = 'Alice';
        $utilisateur->email = 'alice@example.com';

        $_SESSION['utilisateur'] = $utilisateur;

        $twig = $this->createMock(\Twig\Environment::class);

        $twig->expects($this->once())
            ->method('render')
            ->with(
                'accueil.html.twig',
                [
                    'page' => 'accueil',
                    'title' => 'Accueil',
                    'platform_name' => 'CESI-STAGES',
                    'utilisateur' => $utilisateur,
                ]
            )
            ->willReturn('<h1>Bienvenue Alice</h1>');

        $controller = new PageAccueil($twig);

        ob_start();
        $controller->render();
        $output = ob_get_clean();

        $this->assertSame('<h1>Bienvenue Alice</h1>', $output);
    }
}
