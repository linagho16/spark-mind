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

    public function delete()
    {
        session_start();
        if (empty($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        // On ne supprime QUE en POST (sécurité)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId    = (int)$_SESSION['user_id'];
            $userModel = new User();

            // 🔍 Si tu veux tester que la méthode existe, tu peux décommenter ça :
            /*
            var_dump(get_class($userModel));
            var_dump(get_class_methods($userModel));
            exit;
            */

            // Supprimer l'utilisateur
            $userModel->deleteById($userId);

            // Détruire la session
            session_unset();
            session_destroy();

            // Redirection après suppression (tu peux changer la page si tu veux)
            header("Location: index.php?page=front");
            exit;
        }

        // Si quelqu’un arrive ici en GET, on le renvoie au profil
        header("Location: index.php?page=profile");
        exit;
    }
}
