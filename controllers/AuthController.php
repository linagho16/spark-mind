<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/MailService.php';

class AuthController
{
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors   = [];
        $email    = '';
        $remember = false;

        $userModel = new User();

        // 👉 Si on arrive en GET : afficher le formulaire
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            // Si un cookie existe, on pré-remplit l'email et on coche la case
            if (!empty($_COOKIE['remember_email'])) {
                $email    = $_COOKIE['remember_email'];
                $remember = true;
            }

            include __DIR__ . '/../views/auth/login.php';
            return;
        }

        // 👉 Ici : POST → tentative de connexion
        $emailRaw    = $_POST['email']    ?? '';
        $passwordRaw = $_POST['password'] ?? '';

        $email    = is_string($emailRaw)    ? trim($emailRaw)    : '';
        $password = is_string($passwordRaw) ? $passwordRaw       : '';
        $remember = isset($_POST['remember']); // case cochée ou non

        // 🔎 VALIDATION CÔTÉ SERVEUR
        if ($email === '' || $password === '') {
            $errors[] = "Veuillez saisir l’e-mail et le mot de passe.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Adresse e-mail invalide.";
        } else {
            $user = $userModel->findByEmail($email);

            if (
                !$user
                || empty($user['password_hash'])
                || !password_verify($password, $user['password_hash'])
            ) {
                $errors[] = "Email ou mot de passe incorrect.";
            } else {
                // ✅ Vérifier si le compte est bloqué
                if (isset($user['status']) && $user['status'] === 'blocked') {
                    $errors[] = "Votre compte a été suspendu par l’administrateur.";
                } else {
                    // Connexion OK

                    // 🔹 Gestion du "Se souvenir de moi"
                    if ($remember) {
                        // Cookie valable 30 jours
                        setcookie('remember_email', $email, time() + 60 * 60 * 24 * 30, "/");
                    } else {
                        // Si décoché, on supprime le cookie
                        setcookie('remember_email', '', time() - 3600, "/");
                    }

                    $_SESSION['user_id']         = $user['id'];
                    $_SESSION['user_nom']        = $user['nom'];
                    $_SESSION['user_prenom']     = $user['prenom'];
                    $_SESSION['user_email']      = $user['email'];
                    $_SESSION['user_ville']      = $user['ville'];
                    $_SESSION['user_profession'] = $user['profession'];
                    $_SESSION['user_role']       = $user['role'];

                    // Redirection selon rôle technique
                    if ($user['role'] === 'admin') {
                        header("Location: index.php?page=admin_home");
                    } else {
                        header("Location: index.php?page=main");
                    }
                    exit;
                }
            }
        }

        // S'il y a des erreurs → on réaffiche le formulaire
        include __DIR__ . '/../views/auth/login.php';
    }

    public function register(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];

        // Récupération sécurisée des données
        $data = [
            'nom'        => isset($_POST['nom'])        && is_string($_POST['nom'])        ? trim($_POST['nom'])        : '',
            'prenom'     => isset($_POST['prenom'])     && is_string($_POST['prenom'])     ? trim($_POST['prenom'])     : '',
            'naissance'  => isset($_POST['naissance'])  && is_string($_POST['naissance'])  ? trim($_POST['naissance'])  : '',
            'tel'        => isset($_POST['tel'])        && is_string($_POST['tel'])        ? trim($_POST['tel'])        : '',
            'adresse'    => isset($_POST['adresse'])    && is_string($_POST['adresse'])    ? trim($_POST['adresse'])    : '',
            'ville'      => isset($_POST['ville'])      && is_string($_POST['ville'])      ? trim($_POST['ville'])      : '',
            'profession' => isset($_POST['profession']) && is_string($_POST['profession']) ? trim($_POST['profession']) : '',
            'email'      => isset($_POST['email'])      && is_string($_POST['email'])      ? trim($_POST['email'])      : '',
            'password'   => isset($_POST['password'])   && is_string($_POST['password'])   ? $_POST['password']         : '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 🔎 VALIDATIONS CÔTÉ SERVEUR
            if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '' || $data['password'] === '') {
                $errors[] = "Tous les champs obligatoires ne sont pas remplis.";
            }

            if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Adresse e-mail invalide.";
            }

            if ($data['password'] !== '' && strlen($data['password']) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }

            // Téléphone : contrôle façon regex
            if ($data['tel'] !== '') {
                $telNormalized = preg_replace('/\s+/', '', $data['tel']);
                if (!preg_match('/^(?:\+216)?[24579]\d{7}$/', $telNormalized)) {
                    $errors[] = "Le numéro de téléphone est invalide (format tunisien attendu).";
                }
            }

            // Date de naissance
            if ($data['naissance'] !== '') {
                $d = DateTime::createFromFormat('Y-m-d', $data['naissance']);
                $isValidDate = $d && $d->format('Y-m-d') === $data['naissance'];
                if (!$isValidDate) {
                    $errors[] = "La date de naissance est invalide.";
                }
            }

            if ($data['adresse'] !== '' && strlen($data['adresse']) < 5) {
                $errors[] = "L’adresse semble trop courte.";
            }

            if ($data['ville'] === '') {
                $errors[] = "Veuillez choisir une ville.";
            }

            if ($data['profession'] === '') {
                $errors[] = "Veuillez sélectionner une profession.";
            }

            if (empty($errors)) {
                $userModel = new User();
                $existing  = $userModel->findByEmail($data['email']);

                if ($existing) {
                    $errors[] = "Un compte avec cet e-mail existe déjà.";
                } else {
                    // Gestion de la photo (optionnelle)
                    $photoPath = null;

                    if (
                        isset($_FILES['photo'])
                        && is_array($_FILES['photo'])
                        && !empty($_FILES['photo']['name'])
                        && $_FILES['photo']['error'] === UPLOAD_ERR_OK
                    ) {
                        $uploadDirFs  = __DIR__ . '/../uploads/';
                        $uploadDirWeb = 'uploads/';

                        if (!is_dir($uploadDirFs)) {
                            mkdir($uploadDirFs, 0777, true);
                        }

                        $originalName = basename($_FILES['photo']['name']);
                        $extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                        $allowedExt  = ['jpg','jpeg','png','gif'];
                        $allowedMime = ['image/jpeg','image/png','image/gif'];

                        $mimeType = mime_content_type($_FILES['photo']['tmp_name']);

                        if (in_array($extension, $allowedExt, true) && in_array($mimeType, $allowedMime, true)) {
                            $safeName  = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                            $fileName  = time() . '_' . $safeName;
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

                    $data['role']      = 'user';
                    $data['photo']     = $photoPath;
                    // éventuellement : $data['site_role'] = 'seeker'; // par défaut

                    if (empty($errors)) {
                        $ok = $userModel->create($data);

                        if ($ok) {
                            // 🔥 Envoi d’un email de bienvenue
                            $fullName = trim($data['prenom'] . ' ' . $data['nom']);
                            MailService::sendWelcome($data['email'], $fullName);

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
     * Saisir email ou téléphone -> génération d'un code
     */
    public function forgotPassword(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];
        $info   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifierRaw = $_POST['identifier'] ?? '';
            $identifier    = is_string($identifierRaw) ? trim($identifierRaw) : '';

            if ($identifier === '') {
                $errors[] = "Veuillez saisir votre e-mail ou votre numéro de téléphone.";
            } else {
                $userModel = new User();
                $user      = $userModel->findByEmailOrTel($identifier);

                if (!$user) {
                    $errors[] = "Aucun compte trouvé avec ces informations.";
                } else {
                    $code = (string) random_int(100000, 999999);

                    $_SESSION['reset_user_id'] = $user['id'];
                    $_SESSION['reset_code']    = $code;
                    $_SESSION['reset_expires'] = time() + 15 * 60;

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
    public function resetPassword(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors  = [];
        $success = '';
        $info    = $_SESSION['reset_info'] ?? '';

        if (
            empty($_SESSION['reset_user_id'])
            || empty($_SESSION['reset_code'])
            || empty($_SESSION['reset_expires'])
        ) {
            $errors[] = "Aucune demande de réinitialisation en cours.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
            $codeRaw            = $_POST['code']             ?? '';
            $passwordRaw        = $_POST['password']         ?? '';
            $passwordConfirmRaw = $_POST['password_confirm'] ?? '';

            $code            = is_string($codeRaw)            ? trim($codeRaw)            : '';
            $password        = is_string($passwordRaw)        ? $passwordRaw              : '';
            $passwordConfirm = is_string($passwordConfirmRaw) ? $passwordConfirmRaw       : '';

            if ($code === '' || $password === '' || $passwordConfirm === '') {
                $errors[] = "Tous les champs sont obligatoires.";
            }

            if ($password !== '' && $password !== $passwordConfirm) {
                $errors[] = "Les deux mots de passe ne correspondent pas.";
            }

            if ($password !== '' && strlen($password) < 8) {
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

                // Mise à jour du mot de passe
                $userModel->updatePassword($userId, $password);

                // Envoi mail de confirmation
                $user = $userModel->findById($userId);
                if ($user) {
                    $fullName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
                    MailService::sendPasswordChanged($user['email'], $fullName);
                }

                // Nettoyer la session de reset
                unset(
                    $_SESSION['reset_user_id'],
                    $_SESSION['reset_code'],
                    $_SESSION['reset_expires'],
                    $_SESSION['reset_info']
                );

                $success = "Votre mot de passe a bien été modifié. Vous pouvez maintenant vous connecter.";
            }
        }

        include __DIR__ . '/../views/auth/reset_password.php';
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}
