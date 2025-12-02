<?php
require_once __DIR__ . '/../models/User.php';

class ProfileController
{
    /**
     * Démarre la session si besoin + vérifie que l'utilisateur est connecté.
     * Retourne l'ID utilisateur (int) ou redirige vers login.
     */
    private function requireLogin(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;

        if (empty($userId) || !is_numeric($userId)) {
            session_unset();
            session_destroy();
            header("Location: index.php?page=login");
            exit;
        }

        return (int)$userId;
    }

    /**
     * Affichage du profil
     */
    public function show(): void
    {
        $userId = $this->requireLogin();

        $userModel = new User();
        $user      = $userModel->findById($userId);

        if (!$user) {
            // Si le compte n'existe plus : on déconnecte proprement
            session_unset();
            session_destroy();
            header("Location: index.php?page=login");
            exit;
        }

        // Récupération d'éventuels messages d'erreur/succès stockés en session
        $errors  = $_SESSION['profile_errors']  ?? [];
        $success = $_SESSION['profile_success'] ?? '';
        unset($_SESSION['profile_errors'], $_SESSION['profile_success']);

        include __DIR__ . '/../views/profile/show.php';
    }

    /**
     * Affichage + traitement du formulaire d'édition du profil
     */
    public function edit(): void
    {
        $userId = $this->requireLogin();

        $userModel = new User();
        $user      = $userModel->findById($userId);

        if (!$user) {
            session_unset();
            session_destroy();
            header("Location: index.php?page=login");
            exit;
        }

        $errors = [];

        // Si l'utilisateur revient d'un upload photo en erreur
        if (!empty($_SESSION['profile_errors'])) {
            $errors = $_SESSION['profile_errors'];
            unset($_SESSION['profile_errors']);
        }

        // Pré-remplissage du formulaire : par défaut avec la base
        $data = [
            'nom'        => $user['nom']        ?? '',
            'prenom'     => $user['prenom']     ?? '',
            'naissance'  => $user['naissance']  ?? '',
            'tel'        => $user['tel']        ?? '',
            'adresse'    => $user['adresse']    ?? '',
            'ville'      => $user['ville']      ?? '',
            'profession' => $user['profession'] ?? '',
            'email'      => $user['email']      ?? '',
        ];

        // Si on arrive en POST → tentative de mise à jour
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // On remplit $data avec les valeurs envoyées (pour les réafficher si erreurs)
            $data = [
                'nom'        => isset($_POST['nom'])        && is_string($_POST['nom'])        ? trim($_POST['nom'])        : '',
                'prenom'     => isset($_POST['prenom'])     && is_string($_POST['prenom'])     ? trim($_POST['prenom'])     : '',
                'naissance'  => isset($_POST['naissance'])  && is_string($_POST['naissance'])  ? trim($_POST['naissance'])  : '',
                'tel'        => isset($_POST['tel'])        && is_string($_POST['tel'])        ? trim($_POST['tel'])        : '',
                'adresse'    => isset($_POST['adresse'])    && is_string($_POST['adresse'])    ? trim($_POST['adresse'])    : '',
                'ville'      => isset($_POST['ville'])      && is_string($_POST['ville'])      ? trim($_POST['ville'])      : '',
                'profession' => isset($_POST['profession']) && is_string($_POST['profession']) ? trim($_POST['profession']) : '',
                'email'      => isset($_POST['email'])      && is_string($_POST['email'])      ? trim($_POST['email'])      : '',
            ];

            // 🔎 VALIDATION CÔTÉ SERVEUR (pas HTML5)
            if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '') {
                $errors[] = "Nom, prénom et e-mail sont obligatoires.";
            }

            if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Adresse e-mail invalide.";
            }

            if ($data['tel'] !== '') {
                $telNormalized = preg_replace('/\s+/', '', $data['tel']);
                if (!preg_match('/^(?:\+216)?[24579]\d{7}$/', $telNormalized)) {
                    $errors[] = "Le numéro de téléphone est invalide (format tunisien attendu).";
                }
            }

            if ($data['naissance'] !== '') {
                $d = \DateTime::createFromFormat('Y-m-d', $data['naissance']);
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
                $errors[] = "Veuillez choisir une profession.";
            }

            // Si aucune erreur → mise à jour en BDD
            if (empty($errors)) {
                $ok = $userModel->updateProfile($userId, $data);

                if ($ok) {
                    // Mettre à jour quelques infos en session
                    $_SESSION['user_nom']        = $data['nom'];
                    $_SESSION['user_prenom']     = $data['prenom'];
                    $_SESSION['user_email']      = $data['email'];
                    $_SESSION['user_ville']      = $data['ville'];
                    $_SESSION['user_profession'] = $data['profession'];

                    $_SESSION['profile_success'] = "Votre profil a bien été mis à jour.";
                    header("Location: index.php?page=profile");
                    exit;
                } else {
                    $errors[] = "Erreur lors de la mise à jour du profil.";
                }
            }
        }

        // Si GET ou si erreurs en POST, on affiche le formulaire d'édition
        include __DIR__ . '/../views/profile/edit.php';
    }

    /**
     * Suppression du compte utilisateur
     */
    public function delete(): void
    {
        $userId = $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=profile");
            exit;
        }

        $userModel = new User();
        $userModel->deleteById($userId);

        session_unset();
        session_destroy();

        header("Location: index.php?page=front");
        exit;
    }

    /**
     * Upload / modification de la photo de profil
     */
    public function uploadPhoto(): void
    {
        $userId = $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=profile_edit");
            exit;
        }

        $errors = [];

        if (
            empty($_FILES['photo'])
            || !is_array($_FILES['photo'])
            || $_FILES['photo']['error'] !== UPLOAD_ERR_OK
        ) {
            $errors[] = "Aucun fichier valide n'a été envoyé.";
        }

        if (!empty($errors)) {
            $_SESSION['profile_errors'] = $errors;
            header("Location: index.php?page=profile_edit");
            exit;
        }

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

        if (!in_array($extension, $allowedExt, true) || !in_array($mimeType, $allowedMime, true)) {
            $errors[] = "Format de fichier non supporté (jpg, jpeg, png, gif uniquement).";
        }

        if (!empty($errors)) {
            $_SESSION['profile_errors'] = $errors;
            header("Location: index.php?page=profile_edit");
            exit;
        }

        // Nom de fichier sécurisé
        $safeName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
        $fileName  = time() . '_' . $safeName;
        $targetFs  = $uploadDirFs . $fileName;
        $targetWeb = $uploadDirWeb . $fileName;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFs)) {
            $_SESSION['profile_errors'] = ["Erreur lors de l'upload du fichier."];
            header("Location: index.php?page=profile_edit");
            exit;
        }

        // Mise à jour en BDD
        $userModel = new User();
        $userModel->updatePhoto($userId, $targetWeb);

        $_SESSION['profile_success'] = "Votre photo de profil a été mise à jour.";
        header("Location: index.php?page=profile");
        exit;
    }
}
