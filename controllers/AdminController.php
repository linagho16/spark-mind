<?php
require_once __DIR__ . '/../models/User.php';

class AdminController
{
    private function requireAdmin()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo "Accès refusé. Cette page est réservée à l'administrateur.";
            exit();
        }
    }

    public function home()
    {
        $this->requireAdmin();
        include __DIR__ . '/../views/admin/home.php';
    }

    public function users()
    {
        $this->requireAdmin();

        $userModel = new User();
        $users     = $userModel->findAll();

        include __DIR__ . '/../views/admin/users.php';
    }
}
