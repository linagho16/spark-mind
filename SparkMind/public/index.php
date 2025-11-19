<?php
require_once __DIR__ . '/../controllers/PostController.php';

$action = $_GET['action'] ?? 'front';

$controller = new PostController();

switch ($action) {
    case 'front':
        $controller->indexFront();
        break;

    case 'store_front':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->storeFront();
        } else {
            $controller->indexFront();
        }
        break;

    default:
        $controller->indexFront();
        break;
}
