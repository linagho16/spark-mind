<?php
require_once __DIR__ . '/../models/Post.php';

class PostController {

    // Affichage formulaire + liste des posts
    public function indexFront() {
        $postModel = new Post();
        $posts = $postModel->getAll();
        $errors = [];
        $success = "";
        include __DIR__ . '/../views/front/post_list.php';
    }

    // Traitement du formulaire
    public function storeFront() {
        $postModel = new Post();
        $errors = [];
        $success = "";

        $titre   = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');

        // Validation côté PHP
        if ($contenu === "") {
            $errors[] = "Le contenu du post ne peut pas être vide.";
        } elseif (strlen($contenu) < 5) {
            $errors[] = "Le message doit contenir au moins 5 caractères.";
        }

        if (empty($errors)) {
            $postModel->create($titre, $contenu);
            $success = "Post publié avec succès ✅";
        }

        $posts = $postModel->getAll();
        include __DIR__ . '/../views/front/post_list.php';
    }
}
