<?php
declare(strict_types=1);

class PageFile
{
    private \Twig\Environment $twig;

    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(): void
    {
        // Vérifier que l'utilisateur est connecté
        if (empty($_SESSION['utilisateur'])) {
            http_response_code(403);
            echo 'Accès refusé';
            exit;
        }

        $role      = $_SESSION['utilisateur']['role'] ?? 0;
        $basePath  = realpath(__DIR__ . '/../../uploads/');
        $rawPath   = rawurldecode($_GET['path'] ?? '');
        $rawPath   = str_replace("\0", '', $rawPath);
        $requested = realpath($basePath . '/' . $rawPath);


        // Anti path traversal
        if (!$requested || !str_starts_with($requested, $basePath)) {
            http_response_code(403);
            echo 'Accès refusé';
            exit;
        }

        if (!file_exists($requested)) {
            http_response_code(404);
            echo 'Fichier introuvable';
            exit;
        }

        // Vérifier le rôle
        if (!in_array((int)$role, [1, 2, 3])) {
            http_response_code(403);
            echo 'Accès refusé';
            exit;
        }

        // Servir le fichier
        $ext  = strtolower(pathinfo($requested, PATHINFO_EXTENSION));
        $mime = match($ext) {
            'pdf'        => 'application/pdf',
            'jpg','jpeg' => 'image/jpeg',
            'png'        => 'image/png',
            default      => 'application/octet-stream'
        };

        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($requested) . '"');
        header('Content-Length: ' . filesize($requested));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($requested);
        exit;
    }
}
