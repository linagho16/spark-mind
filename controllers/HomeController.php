<?php

class HomeController
{
    /**
     * Page publique : front office (page d'accueil)
     */
    public function front(): void
    {
        // Pas besoin de session ici
        include __DIR__ . '/../views/front/front.php';
    }

    /**
     * Page publique : étapes explicatives
     */
    public function step(): void
    {
        include __DIR__ . '/../views/front/step.php';
    }

    /**
     * Page principale une fois connecté
     */
    public function main(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Validation côté serveur : l'utilisateur doit être connecté
        $userId = $_SESSION['user_id'] ?? null;

        if (empty($userId) || !is_numeric($userId)) {
            // Protection contre manipulation de session
            session_unset();
            session_destroy();
            header("Location: index.php?page=login");
            exit;
        }

        include __DIR__ . '/../views/front/main.php';
    }
}
