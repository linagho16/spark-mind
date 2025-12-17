<?php

namespace Utils;

use Exception;

/**
 * Générateur de QR Code Avancé
 * 
 * Version avec bibliothèque PHP QR Code native (sans dépendances externes)
 * Implémentation simplifiée de l'algorithme QR Code
 */
class QrGeneratorAdvanced
{
    private const SIZE = 300;
    private const MODULE_SIZE = 10; // Taille d'un module QR en pixels
    
    /**
     * Génère un QR code et retourne l'image en Base64
     * 
     * @param string $content Contenu à encoder (max 300 caractères recommandé)
     * @return string Image PNG encodée en Base64 avec préfixe data:image
     * @throws Exception Si GD n'est pas disponible
     */
    public function generateQrBase64(string $content): string
    {
        if (!extension_loaded('gd')) {
            throw new Exception("L'extension GD est requise pour générer des QR codes");
        }
        
        // Générer la matrice QR
        $matrix = $this->generateQrMatrix($content);
        
        // Créer l'image
        $imageData = $this->matrixToImage($matrix, self::SIZE);
        
        return 'data:image/png;base64,' . base64_encode($imageData);
    }
    
    /**
     * Génère une matrice QR simplifiée
     * 
     * @param string $content Contenu à encoder
     * @return array Matrice 2D (1 = noir, 0 = blanc)
     */
    private function generateQrMatrix(string $content): array
    {
        $size = 29; // Version 3 QR Code (29x29 modules)
        $matrix = array_fill(0, $size, array_fill(0, $size, 0));
        
        // 1. Patterns de positionnement (3 coins)
        $this->addFinderPattern($matrix, 0, 0);      // Coin haut-gauche
        $this->addFinderPattern($matrix, $size - 7, 0); // Coin haut-droit
        $this->addFinderPattern($matrix, 0, $size - 7); // Coin bas-gauche
        
        // 2. Séparateurs (lignes blanches autour des patterns)
        $this->addSeparators($matrix, $size);
        
        // 3. Timing patterns (lignes alternées)
        $this->addTimingPatterns($matrix, $size);
        
        // 4. Encoder les données (version simplifiée)
        $this->encodeData($matrix, $content, $size);
        
        return $matrix;
    }
    
    /**
     * Ajoute un pattern de positionnement (finder pattern)
     */
    private function addFinderPattern(array &$matrix, int $row, int $col): void
    {
        // Carré extérieur 7x7 noir
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 7; $j++) {
                if ($i == 0 || $i == 6 || $j == 0 || $j == 6) {
                    $matrix[$row + $i][$col + $j] = 1;
                }
            }
        }
        
        // Carré intérieur 3x3 noir
        for ($i = 2; $i < 5; $i++) {
            for ($j = 2; $j < 5; $j++) {
                $matrix[$row + $i][$col + $j] = 1;
            }
        }
    }
    
    /**
     * Ajoute les séparateurs (lignes blanches)
     */
    private function addSeparators(array &$matrix, int $size): void
    {
        // Séparateurs horizontaux et verticaux autour des patterns
        for ($i = 0; $i < 8; $i++) {
            $matrix[7][$i] = 0; // Haut-gauche horizontal
            $matrix[$i][7] = 0; // Haut-gauche vertical
            
            $matrix[7][$size - 8 + $i] = 0; // Haut-droit horizontal
            $matrix[$i][$size - 8] = 0;     // Haut-droit vertical
            
            $matrix[$size - 8][$i] = 0;     // Bas-gauche horizontal
            $matrix[$size - 8 + $i][7] = 0; // Bas-gauche vertical
        }
    }
    
    /**
     * Ajoute les timing patterns (lignes alternées)
     */
    private function addTimingPatterns(array &$matrix, int $size): void
    {
        for ($i = 8; $i < $size - 8; $i++) {
            $matrix[6][$i] = ($i % 2 == 0) ? 1 : 0; // Horizontal
            $matrix[$i][6] = ($i % 2 == 0) ? 1 : 0; // Vertical
        }
    }
    
    /**
     * Encode les données dans la matrice (version simplifiée)
     */
    private function encodeData(array &$matrix, string $content, int $size): void
    {
        // Convertir le contenu en binaire
        $binary = '';
        for ($i = 0; $i < strlen($content); $i++) {
            $binary .= str_pad(decbin(ord($content[$i])), 8, '0', STR_PAD_LEFT);
        }
        
        // Placer les bits dans la matrice (zigzag de bas en haut)
        $bitIndex = 0;
        $direction = -1; // -1 = vers le haut, 1 = vers le bas
        $col = $size - 1;
        
        while ($col > 0 && $bitIndex < strlen($binary)) {
            for ($row = ($direction == -1 ? $size - 1 : 0); 
                 $row >= 0 && $row < $size && $bitIndex < strlen($binary); 
                 $row += $direction) {
                
                // Placer le bit si la case est libre
                if ($matrix[$row][$col] === 0 && !$this->isReserved($row, $col, $size)) {
                    $matrix[$row][$col] = (int)$binary[$bitIndex];
                    $bitIndex++;
                }
                
                if ($bitIndex < strlen($binary) && $col > 0) {
                    if ($matrix[$row][$col - 1] === 0 && !$this->isReserved($row, $col - 1, $size)) {
                        $matrix[$row][$col - 1] = (int)$binary[$bitIndex];
                        $bitIndex++;
                    }
                }
            }
            
            $direction *= -1;
            $col -= 2;
            
            // Sauter la colonne du timing pattern
            if ($col == 6) $col = 5;
        }
    }
    
    /**
     * Vérifie si une position est réservée (patterns)
     */
    private function isReserved(int $row, int $col, int $size): bool
    {
        // Patterns de positionnement
        if (($row < 9 && $col < 9) || 
            ($row < 9 && $col >= $size - 8) || 
            ($row >= $size - 8 && $col < 9)) {
            return true;
        }
        
        // Timing patterns
        if ($row == 6 || $col == 6) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Convertit la matrice en image PNG
     */
    private function matrixToImage(array $matrix, int $targetSize): string
    {
        $matrixSize = count($matrix);
        $moduleSize = (int)($targetSize / $matrixSize);
        $actualSize = $matrixSize * $moduleSize;
        
        // Créer l'image
        $image = imagecreate($actualSize, $actualSize);
        
        // Couleurs
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Fond blanc
        imagefill($image, 0, 0, $white);
        
        // Dessiner les modules
        for ($row = 0; $row < $matrixSize; $row++) {
            for ($col = 0; $col < $matrixSize; $col++) {
                if ($matrix[$row][$col] == 1) {
                    imagefilledrectangle(
                        $image,
                        $col * $moduleSize,
                        $row * $moduleSize,
                        ($col + 1) * $moduleSize - 1,
                        ($row + 1) * $moduleSize - 1,
                        $black
                    );
                }
            }
        }
        
        // Capturer l'image en buffer
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        
        return $imageData;
    }
}
