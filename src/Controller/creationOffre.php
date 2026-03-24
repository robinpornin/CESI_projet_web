<?php

declare(strict_types=1);

class PageInvite
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Récupère les données et affiche la page invité.
     */
    public function render(): void
    {
        echo $this->twig->render('creationOffre.html.twig', [
            'page'   => 'creation_offre',
            'title'  => 'Création d\'une offre',
            'platform_name' => 'CESI-STAGES',
        ]);
    }
}

public function create(CompetenceRepository $competenceRepository): Response
{
    $competences = $competenceRepository->findAll();

    return $this->render('offre/create.html.twig', [
        'competences' => $competences,
    ]);
}

$competenceIds = $request->request->all('competences'); // tableau d'IDs

foreach ($competenceIds as $id) {
    $competence = $competenceRepository->find($id);
    $offre->addCompetence($competence); // selon votre relation ManyToMany
}