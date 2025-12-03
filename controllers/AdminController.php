<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/DonationType.php';

class AdminController {
    public function dashboard() {
        $postModel = new Post();
        $commentModel = new Comment();
        $donationTypeModel = new DonationType();
        $totalPosts = count($postModel->getAll());
        $totalComments = count($commentModel->getAll());
        $totalTypes = count($donationTypeModel->getAll());
        include __DIR__ . '/../views/admin/dashboard.php';
    }
    public function listPosts() {
        $postModel = new Post();
        $posts = $postModel->getAll();
        include __DIR__ . '/../views/admin/post_list.php';
    }
    public function deletePost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $postModel = new Post();
                $postModel->delete($id);
            }
        }
        header('Location: index.php?action=admin_posts');
        exit;
    }
     public function listComments() {
        $commentModel = new Comment();
        $comments = $commentModel->getAll();
        include __DIR__ . '/../views/admin/comments_list.php';
    }
    public function deleteCommentAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $commentModel = new Comment();
                $commentModel->delete($id);
            }
        }
        header('Location: index.php?action=admin_comments');
        exit;
    }
    public function listDonationTypes() {
        $donationTypeModel = new DonationType();
        $types = $donationTypeModel->getAll();
        include __DIR__ . '/../views/admin/types_list.php';
    }
}