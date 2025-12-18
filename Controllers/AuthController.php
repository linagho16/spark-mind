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

        $errors          = [];
        $email           = '';
        $remember        = false;
        $captchaQuestion = '';
        $captchaChoices  = [];

        $userModel = new User();

        // 👉 GET : afficher le formulaire
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            // "Se souvenir de moi" : pré-remplir email si cookie
            if (!empty($_COOKIE['remember_email'])) {
                $email    = $_COOKIE['remember_email'];
                $remember = true;
            }

            // 🔹 Génération aléatoire du type de captcha (1, 2 ou 3)
            $captchaType = random_int(1, 3);
            $_SESSION['captcha_type'] = $captchaType;

            switch ($captchaType) {
                case 1:
                    // 1) Identifier le logo SPARKMIND
                    $captchaQuestion = "Sélectionnez le logo SPARKMIND parmi ces images :";
                    $captchaChoices = [
                        [
                            'value' => 'logo',
                            'label' => '',
                            'image' => 'images/logo.jpg'
                        ],
                        [
                            'value' => 'img1',
                            'label' => '',
                            'image' => 'images/log2.jpg'
                        ],
                        [
                            'value' => 'img2',
                            'label' => '',
                            'image' => 'images/log3.png'
                        ],
                    ];
                    $_SESSION['captcha_answer'] = 'logo';
                    break;

                case 2:
                    // 2) Objectif de SPARKMIND
                    $captchaQuestion = "Quel objectif correspond le mieux à SPARKMIND ?";
                    $captchaChoices = [
                        [
                            'value' => 'a',
                            'label' => "Vendre des produits de divertissement",
                            'image' => ''
                        ],
                        [
                            'value' => 'b',
                            'label' => "Offrir un espace d’écoute, de soutien et d’espoir",
                            'image' => ''
                        ],
                        [
                            'value' => 'c',
                            'label' => "Partager des recettes de cuisine",
                            'image' => ''
                        ],
                    ];
                    $_SESSION['captcha_answer'] = 'b';
                    break;

                case 3:
                default:
                    // 3) Distinguer un animal
                    $captchaQuestion = "Lequel de ces animaux est un chat ?";
                    $captchaChoices = [
                        [
                            'value' => 'dog',
                            'label' => "🐶 ",
                            'image' => ''
                        ],
                        [
                            'value' => 'cat',
                            'label' => "🐱 ",
                            'image' => ''
                        ],
                        [
                            'value' => 'bird',
                            'label' => "🐦 ",
                            'image' => ''
                        ],
                    ];
                    $_SESSION['captcha_answer'] = 'cat';
                    break;
            }

            include __DIR__ . '/../views/auth/login.php';
            return;
        }

        // 👉 POST : tentative de connexion
        $emailRaw      = $_POST['email']    ?? '';
        $passwordRaw   = $_POST['password'] ?? '';
        $captchaCheck  = isset($_POST['captcha_check']);
        $captchaChoice = $_POST['captcha_choice'] ?? null;
        $remember      = isset($_POST['remember']);

        $email    = is_string($emailRaw)    ? trim($emailRaw)    : '';
        $password = is_string($passwordRaw) ? $passwordRaw       : '';

        // 🔎 VALIDATION CÔTÉ SERVEUR
        if ($email === '' || $password === '') {
            $errors[] = "Veuillez saisir l’e-mail et le mot de passe.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Adresse e-mail invalide.";
        }

        // Vérifier la case "Je ne suis pas un robot"
        if (!$captchaCheck) {
            $errors[] = "Veuillez cocher la case « Je ne suis pas un robot ».";
        }

        // Vérifier le choix du captcha
        $expectedAnswer = $_SESSION['captcha_answer'] ?? null;
        if ($captchaChoice === null || $captchaChoice === '') {
            $errors[] = "Veuillez répondre au test de vérification.";
        } elseif ($expectedAnswer === null || $captchaChoice !== $expectedAnswer) {
            $errors[] = "Le test de vérification est incorrect.";
        }

        // Si pas encore d'erreurs → vérification des identifiants
        if (empty($errors)) {
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

                    // 🔹 "Se souvenir de moi"
                    if ($remember) {
                        setcookie('remember_email', $email, time() + 60 * 60 * 24 * 30, "/");
                    } else {
                        setcookie('remember_email', '', time() - 3600, "/");
                    }

                    // On peut supprimer la réponse captcha utilisée
                    unset($_SESSION['captcha_answer'], $_SESSION['captcha_type']);

                    $_SESSION['user_id']         = $user['id'];
                    $_SESSION['user_nom']        = $user['nom'];
                    $_SESSION['user_prenom']     = $user['prenom'];
                    $_SESSION['user_email']      = $user['email'];
                    $_SESSION['user_ville']      = $user['ville'];
                    $_SESSION['user_profession'] = $user['profession'];
                    $_SESSION['user_role']       = $user['role'];

                    // Redirection selon rôle
                    if ($user['role'] === 'admin') {
                        header("Location: index.php?page=admin_home");
                    } else {
                        header("Location: index.php?page=main");
                    }
                    exit;
                }
            }
        }

        // ❌ En cas d'erreurs, on régénère un nouveau captcha aléatoire
        $captchaType = random_int(1, 3);
        $_SESSION['captcha_type'] = $captchaType;

        switch ($captchaType) {
            case 1:
                $captchaQuestion = "Sélectionnez le logo SPARKMIND parmi ces images :";
                $captchaChoices = [
                    [
                        'value' => 'logo',
                        'label' => '',
                        'image' => 'images/logo.jpg'
                    ],
                    [
                        'value' => 'img1',
                        'label' => '',
                        'image' => 'images/captcha1.jpg'
                    ],
                    [
                        'value' => 'img2',
                        'label' => '',
                        'image' => 'images/captcha2.jpg'
                    ],
                ];
                $_SESSION['captcha_answer'] = 'logo';
                break;

            case 2:
                $captchaQuestion = "Quel objectif correspond le mieux à SPARKMIND ?";
                $captchaChoices = [
                    [
                        'value' => 'a',
                        'label' => "Vendre des produits de divertissement",
                        'image' => ''
                    ],
                    [
                        'value' => 'b',
                        'label' => "Offrir un espace d’écoute, de soutien et d’espoir",
                        'image' => ''
                    ],
                    [
                        'value' => 'c',
                        'label' => "Partager des recettes de cuisine",
                        'image' => ''
                    ],
                ];
                $_SESSION['captcha_answer'] = 'b';
                break;

            case 3:
            default:
                $captchaQuestion = "Lequel de ces animaux est un chat ?";
                $captchaChoices = [
                    [
                        'value' => 'dog',
                        'label' => "🐶 Chien",
                        'image' => ''
                    ],
                    [
                        'value' => 'cat',
                        'label' => "🐱 Chat",
                        'image' => ''
                    ],
                    [
                        'value' => 'bird',
                        'label' => "🐦 Oiseau",
                        'image' => ''
                    ],
                ];
                $_SESSION['captcha_answer'] = 'cat';
                break;
        }

        include __DIR__ . '/../views/auth/login.php';
    }

    public function register(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors          = [];
        $captchaQuestion = '';
        $captchaChoices  = [];

        // Petite fonction interne pour générer un captcha aléatoire pour l'inscription
        $generateCaptcha = function () use (&$captchaQuestion, &$captchaChoices) {
            $type = random_int(1, 3);
            $_SESSION['reg_captcha_type'] = $type;

            switch ($type) {
                case 1:
                    // 1) Identifier le logo SPARKMIND
                    $captchaQuestion = "Sélectionnez le logo SPARKMIND pour confirmer votre inscription :";
                    $captchaChoices = [
                        [
                            'value' => 'logo',
                            'label' => '',
                            'image' => 'images/logo.jpg',
                        ],
                        [
                            'value' => 'img1',
                            'label' => '',
                            'image' => 'images/log2.jpg',
                        ],
                        [
                            'value' => 'img2',
                            'label' => '',
                            'image' => 'images/log3.png',
                        ],
                    ];
                    $_SESSION['reg_captcha_answer'] = 'logo';
                    break;

                case 2:
                    // 2) Objectif / esprit du site, version plus "fun"
                    $captchaQuestion = "Quel message ressemble le plus à l’esprit de SPARKMIND ?";
                    $captchaChoices = [
                        [
                            'value' => 'a',
                            'label' => "Gagner le plus d'abonnés possible",
                            'image' => '',
                        ],
                        [
                            'value' => 'b',
                            'label' => "Donner de l’espoir, de l’écoute et du soutien",
                            'image' => '',
                        ],
                        [
                            'value' => 'c',
                            'label' => "Parler uniquement de technologie",
                            'image' => '',
                        ],
                    ];
                    $_SESSION['reg_captcha_answer'] = 'b';
                    break;

                case 3:
                default:
                    // 3) Petit test "good vibes"
                    $captchaQuestion = "Quel emoji représente le mieux la bonne humeur que nous voulons partager ici ?";
                    $captchaChoices = [
                        [
                            'value' => 'sad',
                            'label' => "😢 Triste",
                            'image' => '',
                        ],
                        [
                            'value' => 'angry',
                            'label' => "😡 En colère",
                            'image' => '',
                        ],
                        [
                            'value' => 'happy',
                            'label' => "😊 Souriant",
                            'image' => '',
                        ],
                    ];
                    $_SESSION['reg_captcha_answer'] = 'happy';
                    break;
            }
        };

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
            // 🔹 rôle sur le site
            'site_role'  => isset($_POST['site_role'])  && is_string($_POST['site_role'])  ? trim($_POST['site_role'])  : '',
        ];

        // 👉 GET : afficher le formulaire avec un captcha
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $generateCaptcha();
            include __DIR__ . '/../views/auth/register.php';
            return;
        }

        // 👉 POST : validations + captcha
        // VALIDATIONS classiques
        if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '' || $data['password'] === '') {
            $errors[] = "Tous les champs obligatoires ne sont pas remplis.";
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Adresse e-mail invalide.";
        }

        // 🔐 Vérification complexité mot de passe (serveur)
        if ($data['password'] !== '') {
            $password = $data['password'];
            $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/';
            if (!preg_match($pattern, $password)) {
                $errors[] = "Le mot de passe doit contenir au minimum 8 caractères, avec au moins une majuscule, une minuscule, un chiffre et un symbole.";
            }
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

        // 🔹 VALIDATION du site_role
        $allowedSiteRoles = ['seeker', 'helper', 'both', 'speaker'];
        if ($data['site_role'] === '' || !in_array($data['site_role'], $allowedSiteRoles, true)) {
            $errors[] = "Veuillez choisir votre rôle sur SPARKMIND.";
        }

        // 🔹 VALIDATION CAPTCHA inscription
        $captchaCheck  = isset($_POST['captcha_check']);
        $captchaChoice = isset($_POST['captcha_choice']) ? $_POST['captcha_choice'] : null;
        $expected      = $_SESSION['reg_captcha_answer'] ?? null;

        if (!$captchaCheck) {
            $errors[] = "Veuillez cocher la case « Je ne suis pas un robot ».";
        }

        if ($captchaChoice === null || $captchaChoice === '') {
            $errors[] = "Veuillez répondre au test de vérification.";
        } elseif ($expected === null || $captchaChoice !== $expected) {
            $errors[] = "Le test de vérification est incorrect.";
        }

        // Si erreurs → on régénère un captcha et on réaffiche le formulaire
        if (!empty($errors)) {
            $generateCaptcha();
            include __DIR__ . '/../views/auth/register.php';
            return;
        }

        // Aucune erreur → on peut créer le compte
        $userModel = new User();
        $existing  = $userModel->findByEmail($data['email']);

        if ($existing) {
            $errors[] = "Un compte avec cet e-mail existe déjà.";
            $generateCaptcha();
            include __DIR__ . '/../views/auth/register.php';
            return;
        }

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

        if (!empty($errors)) {
            $generateCaptcha();
            include __DIR__ . '/../views/auth/register.php';
            return;
        }

        $data['role']  = 'user';
        $data['photo'] = $photoPath;

        $ok = $userModel->create($data);

        if ($ok) {
            $fullName = trim($data['prenom'] . ' ' . $data['nom']);
            // ✉️ Email de bienvenue
            MailService::sendWelcome($data['email'], $fullName);

            // On peut aussi supprimer les infos captcha
            unset($_SESSION['reg_captcha_answer'], $_SESSION['reg_captcha_type']);

            header("Location: index.php?page=login");
            exit;
        } else {
            $errors[] = "Erreur lors de la création du compte.";
            $generateCaptcha();
            include __DIR__ . '/../views/auth/register.php';
            return;
        }
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
                    // 👉 Génération d’un code à 6 chiffres
                    $code = (string) random_int(100000, 999999);

                    // Stockage en session
                    $_SESSION['reset_user_id'] = $user['id'];
                    $_SESSION['reset_code']    = $code;
                    $_SESSION['reset_expires'] = time() + 15 * 60; // 15 minutes

                    // Envoi du mail avec le code
                    $fullName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
                    MailService::sendPasswordResetCode($user['email'], $fullName, $code);

                    // Message d’info affiché sur la page suivante
                    $_SESSION['reset_info'] = "Un code de vérification vient d’être envoyé à votre adresse e-mail.";

                    // Redirection vers la page où l’utilisateur saisit le code
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

            // 🔐 Vérification complexité du nouveau mot de passe
            if ($password !== '') {
                $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/';
                if (!preg_match($pattern, $password)) {
                    $errors[] = "Le nouveau mot de passe doit contenir au minimum 8 caractères, avec au moins une majuscule, une minuscule, un chiffre et un symbole.";
                }
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
