<?php
class HomeController
{
    public function front()
    {
        include __DIR__ . '/../views/front/front.php';
    }

    public function step()
    {
        include __DIR__ . '/../views/front/step.php';
    }

    public function main()
    {
        session_start();
        if (empty($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }
        include __DIR__ . '/../views/front/main.php';
    }
}
