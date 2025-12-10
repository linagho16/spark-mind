<?php
// config/stripe_config.php

class StripeConfig {
    // ⚠️ CORRIGEZ LA PUBLIC_KEY ! Elle doit commencer par pk_test_
    const SECRET_KEY = 'sk_test_51ScAlJCrQE16oHSTSBxUe4SXbgSOAg0jeYnFC7DH7m3lpJkZD2Lsifg00QGwNWaTUwcQSJSP5JpxLnMj2j7UBa6A00SrbH8ssL';
    const PUBLIC_KEY = 'pk_test_51ScAlJCrQE16oHST8ZpcnpYvk7AGAhM8zzVhayhVcCfgGcXUPELhQt6WvATAnDpLMHois1TUcXwApnIvWgObPJKC00Zf8oayN6';
    
    public static function getSecretKey() {
        return self::SECRET_KEY;
    }
    
    public static function getPublicKey() {
        return self::PUBLIC_KEY;
    }
    
    public static function getSuccessUrl($paiementId) {
        $base_url = 'http://' . $_SERVER['HTTP_HOST'];
        return $base_url . '/aide_solitaire/controller/donC.php?action=paiement_success&paiement_id=' . $paiementId;
    }
    
    public static function getCancelUrl($paiementId) {
        $base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
        return $base_url . '/aide_solitaire/controller/donC.php?action=paiement_cancel&paiement_id=' . $paiementId;
    }
}
?>