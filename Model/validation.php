<?php
class Validation {
    
    // Validate email
    public static function validateEmail($email) {
        if (empty($email)) {
            return "L'email est requis";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Format d'email invalide";
        }
        if (strlen($email) > 100) {
            return "L'email ne doit pas dépasser 100 caractères";
        }
        return true;
    }
    
    // Validate text
    public static function validateText($text, $fieldName, $min = 2, $max = 255) {
        if (empty($text)) {
            return "Le champ '$fieldName' est requis";
        }
        
        $text = trim($text);
        $length = strlen($text);
        
        if ($length < $min) {
            return "Le champ '$fieldName' doit contenir au moins $min caractères";
        }
        
        if ($length > $max) {
            return "Le champ '$fieldName' ne doit pas dépasser $max caractères";
        }
        
        // Check for only letters, spaces, and basic punctuation
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s\-\'.,!?()]+$/u', $text)) {
            return "Le champ '$fieldName' contient des caractères non autorisés";
        }
        
        return true;
    }
    
    // Validate phone number
    public static function validatePhone($phone) {
        if (empty($phone)) {
            return "Le téléphone est requis";
        }
        
        // Tunisian phone format: +216 XX XXX XXX or XX XXX XXX or XXXXXXX
        if (!preg_match('/^(\+216\s?[2-9][0-9]{7}|[2-9][0-9]{7}|0[2-9][0-9]{7})$/', $phone)) {
            return "Numéro de téléphone invalide. Format attendu: +216 XX XXX XXX ou 0X XXXXXX";
        }
        
        return true;
    }
    
    // Validate numeric value
    public static function validateNumber($number, $fieldName, $min = 0, $max = 999999) {
        if (empty($number) && $number !== '0') {
            return "Le champ '$fieldName' est requis";
        }
        
        if (!is_numeric($number)) {
            return "Le champ '$fieldName' doit être un nombre";
        }
        
        if ($number < $min) {
            return "Le champ '$fieldName' doit être supérieur ou égal à $min";
        }
        
        if ($number > $max) {
            return "Le champ '$fieldName' doit être inférieur ou égal à $max";
        }
        
        return true;
    }
    
    // Validate selection (dropdown)
    public static function validateSelection($value, $fieldName, $allowedValues = []) {
        if (empty($value)) {
            return "Le champ '$fieldName' est requis";
        }
        
        if (!empty($allowedValues) && !in_array($value, $allowedValues)) {
            return "Valeur invalide pour le champ '$fieldName'";
        }
        
        return true;
    }
    
    // Sanitize input
    public static function sanitize($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }
    
    // Validate file upload
    public static function validateFile($file, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'], $maxSize = 2097152) {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return true; // File not required
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Erreur lors du téléchargement du fichier";
        }
        
        if ($file['size'] > $maxSize) {
            return "Le fichier est trop volumineux (max: " . ($maxSize / 1024 / 1024) . "MB)";
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowedTypes)) {
            return "Type de fichier non autorisé. Types acceptés: " . implode(', ', $allowedTypes);
        }
        
        return true;
    }
}
?>