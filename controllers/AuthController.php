<?php
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public function login()
    {
        session_start();
        $errors = [];
        $emailValue = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = $_POST['email']    ?? '';
            $password = $_POST['password'] ?? '';
            $emailValue = $email;

            if (empty($email) || empty($password)) {
                $errors[] = "Veuillez saisir l’e-mail et le mot de passe.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Adresse e-mail invalide.";
            } else {
                $userModel = new User();
                $user      = $userModel->findByEmail($email);

                if (!$user || empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
                    $errors[] = "Email ou mot de passe incorrect.";
                } else {
                    $_SESSION['user_id']         = $user['id'];
                    $_SESSION['user_nom']        = $user['nom'];
                    $_SESSION['user_prenom']     = $user['prenom'];
                    $_SESSION['user_email']      = $user['email'];
                    $_SESSION['user_ville']      = $user['ville'];
                    $_SESSION['user_profession'] = $user['profession'];
                    $_SESSION['user_role']       = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: index.php?page=admin_home");
                    } else {
                        header("Location: index.php?page=main");
                    }
                    exit;
                }
            }
        }

        include __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        session_start();
        $errors = [];
        $data = [
            'nom'        => $_POST['nom']        ?? '',
            'prenom'     => $_POST['prenom']     ?? '',
            'naissance'  => $_POST['naissance']  ?? '',
            'tel'        => $_POST['tel']        ?? '',
            'adresse'    => $_POST['adresse']    ?? '',
            'ville'      => $_POST['ville']      ?? '',
            'profession' => $_POST['profession'] ?? '',
            'email'      => $_POST['email']      ?? '',
            'password'   => $_POST['password']   ?? '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($data['nom']) || empty($data['prenom']) || empty($data['email']) || empty($data['password'])) {
                $errors[] = "Tous les champs obligatoires ne sont pas remplis.";
            }

            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Adresse e-mail invalide.";
            }

            if (!empty($data['password']) && strlen($data['password']) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }

            if (empty($errors)) {
                $userModel = new User();
                $existing  = $userModel->findByEmail($data['email']);

                if ($existing) {
                    $errors[] = "Un compte avec cet e-mail existe déjà.";
                } else {
                    $ok = $userModel->create($data);

                    if ($ok) {
                        header("Location: index.php?page=login");
                        exit;
                    } else {
                        $errors[] = "Erreur lors de la création du compte.";
                    }
                }
            }
        }

        include __DIR__ . '/../views/auth/register.php';
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}
