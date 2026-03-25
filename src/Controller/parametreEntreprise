<?php
declare(strict_types=1);

require_once __DIR__ . '/../../database.php';

class PageParametreEntreprise
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
        $id = (int) ($_GET['id'] ?? 0);

        $stmt = $this->pdo->prepare("
            SELECT Nom_entreprise, Secteur, Type_
            FROM Entreprises
            WHERE ID_Entreprise = :id
        ");
        $stmt->execute([':id' => $id]);
        $entreprise = $stmt->fetch();

        echo $this->twig->render('parametreEntreprise.html.twig', [
            'page'        => 'parametre_entreprise',
            'title'       => 'Paramètres de l\'entreprise',
            'entreprise'  => $entreprise,
        ]);
    }
}