<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageCreationOffre
{
    private \Twig\Environment $twig;
    private PDO $pdo;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
        $this->pdo  = getPDO();
    }

    public function render(): void
    {
        $stmt = $this->pdo->query("
            SELECT ID_Competence, Nom_competence FROM Competences ORDER BY Nom_competence ASC
        ");
        $competences = $stmt->fetchAll();

        echo $this->twig->render('creationOffre.html.twig', [
            'page'        => 'creation_offre',
            'title'       => 'Création d\'une offre',
            'competences' => $competences,
        ]);
    }
}