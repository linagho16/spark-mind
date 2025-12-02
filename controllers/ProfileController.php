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

    public function edit()
    {
        session_start();
        if (empty($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userModel = new User();
        $userId    = (int)$_SESSION['user_id'];
        $user      = $userModel->findById($userId);

        if (!$user) {
            die("Utilisateur introuvable.");
        }

        // Si on arrive en POST → on enregistre les modifications
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'        => $_POST['nom']        ?? '',
                'prenom'     => $_POST['prenom']     ?? '',
                'naissance'  => $_POST['naissance']  ?? '',
                'tel'        => $_POST['tel']        ?? '',
                'adresse'    => $_POST['adresse']    ?? '',
                'ville'      => $_POST['ville']      ?? '',
                'profession' => $_POST['profession'] ?? '',
                'email'      => $_POST['email']      ?? '',
            ];

            $userModel->updateProfile($userId, $data);

            // Après mise à jour, retour au profil
            header("Location: index.php?page=profile");
            exit;
        }

        // Si on arrive en GET → on affiche le formulaire d'édition
        include __DIR__ . '/../views/profile/edit.php';
    }

    public function delete()
    {
        session_start();
        if (empty($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId    = (int)$_SESSION['user_id'];
            $userModel = new User();

            $userModel->deleteById($userId);

            session_unset();
            session_destroy();

            header("Location: index.php?page=front");
            exit;
        }

        header("Location: index.php?page=profile");
        exit;
    }
    public function uploadPhoto()
    {
        session_start();
        if (empty($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        // Aucun fichier envoyé
        if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            // Pour débug : 
            // var_dump($_FILES);
            // exit;
            header("Location: index.php?page=profile_edit");
            exit;
        }

        $userId = (int)$_SESSION['user_id'];

        // Dossier où on stocke les images sur le serveur (réel)
        $uploadDirFs = __DIR__ . '/../uploads/';   // ex : C:\xampp\htdocs\sparkmind_mvc_100percent\uploads\
        // Chemin qui sera utilisé dans le src="" du HTML
        $uploadDirWeb = 'uploads/';               // vu depuis index.php

        if (!is_dir($uploadDirFs)) {
            mkdir($uploadDirFs, 0777, true);
        }

        // Sécuriser un peu le nom du fichier
        $originalName = basename($_FILES['photo']['name']);
        $extension    = pathinfo($originalName, PATHINFO_EXTENSION);

        // Types autorisés
        $allowedExt = ['jpg','jpeg','png','gif'];
        $allowedMime = ['image/jpeg','image/png','image/gif'];

        $mimeType = mime_content_type($_FILES['photo']['tmp_name']);

        if (!in_array(strtolower($extension), $allowedExt) || !in_array($mimeType, $allowedMime)) {
            die("Format de fichier non supporté (jpg, jpeg, png, gif uniquement)");
        }

        // Nom unique
        $fileName   = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
        $targetFs   = $uploadDirFs . $fileName;      // chemin disque
        $targetWeb  = $uploadDirWeb . $fileName;     // chemin à stocker dans la BDD et utilisé dans <img>

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFs)) {
            die("Erreur lors de l'upload du fichier");
        }

        // Enregistrer dans la BDD
        $userModel = new User();
        $userModel->updatePhoto($userId, $targetWeb);

        // Retour au profil
        header("Location: index.php?page=profile");
        exit;
    }


}
