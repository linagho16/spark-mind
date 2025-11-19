<?php
require_once __DIR__ . '/../models/User.php';

class ProfileController
{
    public function show()
    {
        session_start();
        if (empty($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userModel = new User();
        $user      = $userModel->findById((int)$_SESSION['user_id']);

        if (!$user) {
            die("Utilisateur introuvable.");
        }

        include __DIR__ . '/../views/profile/show.php';
    }
}
