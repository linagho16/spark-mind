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

            // Validations
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

                    // Gestion de la photo (optionnelle)
                    $photoPath = null;

                    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

                        $uploadDirFs  = __DIR__ . '/../uploads/';
                        $uploadDirWeb = 'uploads/';

                        if (!is_dir($uploadDirFs)) {
                            mkdir($uploadDirFs, 0777, true);
                        }

                        $originalName = basename($_FILES['photo']['name']);
                        $extension    = pathinfo($originalName, PATHINFO_EXTENSION);

                        $allowedExt  = ['jpg','jpeg','png','gif'];
                        $allowedMime = ['image/jpeg','image/png','image/gif'];

                        $mimeType = mime_content_type($_FILES['photo']['tmp_name']);

                        if (in_array(strtolower($extension), $allowedExt) && in_array($mimeType, $allowedMime)) {

                            $fileName  = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                            $targetFs  = $uploadDirFs . $fileName;
                            $targetWeb = $uploadDirWeb . $fileName;

                            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFs)) {
                                $photoPath = $targetWeb;
                            } else {
                                $errors[] = "Erreur lors de l'upload de l'image.";
                            }
                        } else {
                            $errors[] = "Format d'image non supporté (jpg, jpeg, png, gif uniquement).";
                        }
                    }

                    $data['role']  = 'user';
                    $data['photo'] = $photoPath;

                    if (empty($errors)) {
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
        }

        include __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Mot de passe oublié : étape 1
     * Saisir email ou téléphone -> génération d'un code + stockage en session
     */
    public function forgotPassword()
    {
        session_start();
        $errors = [];
        $info   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier'] ?? '');

            if ($identifier === '') {
                $errors[] = "Veuillez saisir votre e-mail ou votre numéro de téléphone.";
            } else {
                $userModel = new User();
                $user      = $userModel->findByEmailOrTel($identifier);

                if (!$user) {
                    $errors[] = "Aucun compte trouvé avec ces informations.";
                } else {
                    // Générer un code à 6 chiffres
                    $code = (string) random_int(100000, 999999);

                    // Stocker en session (dans un vrai site on l'enverrait par mail/SMS)
                    $_SESSION['reset_user_id'] = $user['id'];
                    $_SESSION['reset_code']    = $code;
                    $_SESSION['reset_expires'] = time() + 15 * 60; // 15 minutes

                    // Pour ton projet : on affiche le code sur la page suivante
                    $_SESSION['reset_info'] = "Votre code de vérification (à envoyer par mail/SMS dans un vrai site) est : " . $code;

                    header("Location: index.php?page=reset_password");
                    exit;
                }
            }
        }

        include __DIR__ . '/../views/auth/forgot_password.php';
    }

    /**
     * Mot de passe oublié : étape 2
     * Page pour saisir le code + nouveau mot de passe
     */
    public function resetPassword()
    {
        session_start();
        $errors = [];
        $success = '';
        $info = $_SESSION['reset_info'] ?? '';

        // Vérifier qu'il y a bien une demande en cours
        if (empty($_SESSION['reset_user_id']) || empty($_SESSION['reset_code']) || empty($_SESSION['reset_expires'])) {
            $errors[] = "Aucune demande de réinitialisation en cours.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
            $code            = trim($_POST['code'] ?? '');
            $password        = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if ($code === '' || $password === '' || $passwordConfirm === '') {
                $errors[] = "Tous les champs sont obligatoires.";
            }

            if ($password !== $passwordConfirm) {
                $errors[] = "Les deux mots de passe ne correspondent pas.";
            }

            if (strlen($password) < 8) {
                $errors[] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
            }

            if (time() > ($_SESSION['reset_expires'] ?? 0)) {
                $errors[] = "Le code a expiré. Veuillez recommencer la procédure.";
            }

            if ($code !== ($_SESSION['reset_code'] ?? '')) {
                $errors[] = "Code de vérification incorrect.";
            }

            if (empty($errors)) {
                $userModel = new User();
                $userId    = (int) $_SESSION['reset_user_id'];

                $userModel->updatePassword($userId, $password);

                // Nettoyer la session de reset
                unset($_SESSION['reset_user_id'], $_SESSION['reset_code'], $_SESSION['reset_expires'], $_SESSION['reset_info']);

                $success = "Votre mot de passe a bien été modifié. Vous pouvez maintenant vous connecter.";
            }
        }

        include __DIR__ . '/../views/auth/reset_password.php';
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
