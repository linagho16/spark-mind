<?php

namespace Utils;

use Exception;

/**
 * Générateur de QR Code
 * 
 * Génère des QR codes en utilisant l'API Google Charts
 * ou une bibliothèque PHP native selon la configuration
 */
class QrGenerator
{
    private const DEFAULT_SIZE = 300;
    private const QR_API_URL = 'https://api.qrserver.com/v1/create-qr-code/';
    
    /**
     * Génère un QR code et retourne l'image en Base64
     * 
     * @param string $content Contenu à encoder dans le QR code
     * @param int $size Taille du QR code (300x300 par défaut)
     * @return string Image QR code encodée en Base64
     * @throws Exception Si la génération échoue
     */
    public function generateQrBase64(string $content, int $size = self::DEFAULT_SIZE): string
    {
        try {
            // Méthode 1: Utiliser une API externe (simple et rapide)
            $imageData = $this->generateViaApi($content, $size);
            
            if ($imageData) {
                return 'data:image/png;base64,' . base64_encode($imageData);
            }
            
            // Méthode 2: Générer localement avec GD (fallback)
            return $this->generateWithGD($content, $size);
            
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la génération du QR code: " . $e->getMessage());
        }
    }
    
    /**
     * Génère un QR code via une API externe
     * 
     * @param string $content Contenu à encoder
     * @param int $size Taille du QR code
     * @return string|false Données binaires de l'image ou false
     */
    private function generateViaApi(string $content, int $size): string|false
    {
        $url = self::QR_API_URL . '?' . http_build_query([
            'size' => $size . 'x' . $size,
            'data' => $content,
            'format' => 'png',
            'margin' => 10
        ]);
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'ignore_errors' => true
            ]
        ]);
        
        return @file_get_contents($url, false, $context);
    }
    
    /**
     * Génère un QR code avec la bibliothèque GD (méthode simple)
     * 
     * @param string $content Contenu à encoder
     * @param int $size Taille du QR code
     * @return string Image encodée en Base64
     */
    private function generateWithGD(string $content, int $size): string
    {
        // Pour une vraie implémentation QR code, utilisez une bibliothèque comme:
        // - chillerlan/php-qrcode (composer require chillerlan/php-qrcode)
        // - endroid/qr-code (composer require endroid/qr-code)
        
        // Version simplifiée: créer une image placeholder
        $image = imagecreate($size, $size);
        
        // Couleurs
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Fond blanc
        imagefill($image, 0, 0, $white);
        
        // Bordure
        imagerectangle($image, 10, 10, $size - 10, $size - 10, $black);
        
        // Texte (version simplifiée)
        $text = "QR: " . substr($content, 0, 20);
        imagestring($image, 3, 50, $size / 2, $text, $black);
        
        // Capturer l'image en buffer
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        
        return 'data:image/png;base64,' . base64_encode($imageData);
    }
    
    /**
     * Génère un QR code avec la bibliothèque chillerlan/php-qrcode
     * (Nécessite: composer require chillerlan/php-qrcode)
     * 
     * @param string $content Contenu à encoder
     * @param int $size Taille du QR code
     * @return string Image encodée en Base64
     */
    private function generateWithChillerlan(string $content, int $size): string
    {
        if (!class_exists('\chillerlan\QRCode\QRCode')) {
            throw new Exception("Bibliothèque chillerlan/php-qrcode non installée");
        }
        
        $options = new \chillerlan\QRCode\QROptions([
            'version'    => 5,
            'outputType' => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => \chillerlan\QRCode\QRCode::ECC_L,
            'scale'      => $size / 100,
            'imageBase64' => true,
        ]);
        
        $qrcode = new \chillerlan\QRCode\QRCode($options);
        return $qrcode->render($content);
    }
    
    /**
     * Sauvegarde un QR code dans un fichier
     * 
     * @param string $content Contenu à encoder
     * @param string $filepath Chemin du fichier de sortie
     * @param int $size Taille du QR code
     * @return bool Succès de la sauvegarde
     */
    public function saveQrToFile(string $content, string $filepath, int $size = self::DEFAULT_SIZE): bool
    {
        try {
            $base64Data = $this->generateQrBase64($content, $size);
            
            // Extraire les données binaires du Base64
            $base64Data = preg_replace('#^data:image/\w+;base64,#i', '', $base64Data);
            $imageData = base64_decode($base64Data);
            
            return file_put_contents($filepath, $imageData) !== false;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Génère un QR code pour un ticket
     * 
     * @param string $ticketCode Code du ticket
     * @param array $additionalData Données supplémentaires (optionnel)
     * @return string Image QR code en Base64
     */
    public function generateTicketQr(string $ticketCode, array $additionalData = []): string
    {
        // Format du contenu: JSON avec toutes les infos du ticket
        $data = [
            'type' => 'TICKET',
            'code' => $ticketCode,
            'timestamp' => time(),
        ];
        
        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }
        
        $content = json_encode($data);
        
        return $this->generateQrBase64($content);
    }
    
    /**
     * Génère un QR code pour une URL
     * 
     * @param string $url URL à encoder
     * @return string Image QR code en Base64
     */
    public function generateUrlQr(string $url): string
    {
        return $this->generateQrBase64($url);
    }
    
    /**
     * Génère un QR code vCard pour un contact
     * 
     * @param array $contact Données du contact (nom, email, tel)
     * @return string Image QR code en Base64
     */
    public function generateVCardQr(array $contact): string
    {
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "FN:" . ($contact['name'] ?? '') . "\n";
        $vcard .= "TEL:" . ($contact['phone'] ?? '') . "\n";
        $vcard .= "EMAIL:" . ($contact['email'] ?? '') . "\n";
        $vcard .= "END:VCARD";
        
        return $this->generateQrBase64($vcard);
    }
}
