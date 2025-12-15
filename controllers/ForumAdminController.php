<?php

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/DonationType.php';
require_once __DIR__ . '/../config/config.php';

class ForumAdminController
{
    public function dashboard(): void
    {
        global $pdo;

        $postModel         = new Post($pdo);
        $commentModel      = new Comment($pdo);
        $donationTypeModel = new DonationType($pdo);

        $totalPosts    = count($postModel->getAll());
        $totalComments = count($commentModel->getAll());
        $totalTypes    = count($donationTypeModel->getAll());

        include __DIR__ . '/../views/admin/dashboard.php';
    }

    public function listPosts(): void
    {
        global $pdo;
        $postModel = new Post($pdo);
        $posts     = $postModel->getAll();

        include __DIR__ . '/../views/admin/post_list.php';
    }

    public function listComments(): void
    {
        global $pdo;
        $commentModel = new Comment($pdo);
        $comments     = $commentModel->getAll();

        include __DIR__ . '/../views/admin/comments_list.php';
    }

    public function listDonationTypes(): void
    {
        global $pdo;
        $donationTypeModel = new DonationType($pdo);
        $types             = $donationTypeModel->getAll();

        include __DIR__ . '/../views/admin/types_list.php';
    }
    public function aiDashboard(): void
    {
        // temporaire: réutilise le dashboard classique
        $this->dashboard();
    }

}
