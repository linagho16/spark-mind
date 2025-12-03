<?php
// ============================================
// CONFIGURATION : Choisissez UNE des deux options
// ============================================

// OPTION A : Si tu as copié PHPMailer dans un dossier phpmailer/
require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';
require_once __DIR__ . '/../phpmailer/Exception.php';

// OPTION B : Si tu utilises Composer, commente les 3 lignes ci-dessus
// et décommente cette ligne :
// require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService {

    // 🔐 CONFIG SMTP (à adapter pour TON compte Gmail / SMTP)
    private static $smtpHost     = 'smtp.gmail.com';
    private static $smtpUsername = 'lanoulouna24@gmail.com';     // ton adresse d’envoi
    private static $smtpPassword = 'gerb iwji hjaa kfkd';         // ⚠ mets ici ton mot de passe d’application Gmail
    private static $fromEmail    = 'lanoulouna24@gmail.com';     // adresse "From"
    private static $fromName     = 'SPARKMIND';

    /**
     * Email de bienvenue (après inscription)
     */
    public static function sendWelcome(string $toEmail, string $userName): bool
    {
        $subject = "Bienvenue sur SPARKMIND ✨";

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style=\"
                background: linear-gradient(135deg, #1A464F, #0f2730);
                padding: 30px;
                text-align: center;\">
                <h1 style='color: white; margin: 0;'>Bienvenue sur SPARKMIND</h1>
                <p style='color:#FBEEDD;margin-top:8px;'>Quand la pensée devient espoir</p>
            </div>
            <div style='padding: 30px; background: #f9f3ea;'>
                <p>Bonjour <strong>$userName</strong>,</p>
                <p>Votre compte <strong>SPARKMIND</strong> a été créé avec succès 🌟.</p>
                <p>Vous pouvez maintenant :</p>
                <ul>
                    <li>Demander de l'aide ou proposer votre aide</li>
                    <li>Exprimer ce que vous ressentez dans un espace bienveillant</li>
                    <li>Rejoindre une communauté qui écoute et accompagne</li>
                </ul>
                <p style='text-align: center; margin-top: 30px;'>
                    <a href='http://localhost/sparkmind_mvc_100percent/index.php?page=login'
                       style=\"
                           background: #1A464F;
                           color: white;
                           padding: 12px 30px;
                           text-decoration: none;
                           border-radius: 5px;
                           display: inline-block;\">
                        Se connecter à SPARKMIND
                    </a>
                </p>
            </div>
            <div style='padding: 20px; text-align: center; color: #666; font-size: 12px; background:#FBEDD7;'>
                © 2024 SPARKMIND - Tous droits réservés
            </div>
        </div>";

        return self::send($toEmail, $subject, $body);
    }

    /**
     * Email de confirmation de changement de mot de passe
     */
    public static function sendPasswordChanged(string $toEmail, string $userName): bool
    {
        $subject = "Votre mot de passe SPARKMIND a été modifié";
        $date    = date('d/m/Y à H:i');

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style=\"
                background: linear-gradient(135deg, #1A464F, #0f2730);
                padding: 30px;
                text-align: center;\">
                <h1 style='color: white; margin: 0;'>Mot de passe modifié</h1>
            </div>
            <div style='padding: 30px; background: #f9f3ea;'>
                <p>Bonjour <strong>$userName</strong>,</p>
                <p>Votre mot de passe <strong>SPARKMIND</strong> a été modifié avec succès le <strong>$date</strong>.</p>
                <div style='background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;'>
                    <strong>Ce n'était pas vous ?</strong><br>
                    Si vous n'êtes pas à l'origine de cette modification, contactez-nous immédiatement.
                </div>
            </div>
            <div style='padding: 20px; text-align: center; color: #666; font-size: 12px; background:#FBEDD7;'>
                © 2024 SPARKMIND - Tous droits réservés
            </div>
        </div>";

        return self::send($toEmail, $subject, $body);
    }

    /**
     * Email de notification de compte bloqué
     */
    public static function sendAccountBlocked(string $toEmail, string $userName): bool
    {
        $subject = "Votre compte SPARKMIND a été suspendu";

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style=\"
                background: linear-gradient(135deg, #b3261e, #7f1410);
                padding: 30px;
                text-align: center;\">
                <h1 style='color: white; margin: 0;'>Compte suspendu</h1>
            </div>
            <div style='padding: 30px; background: #f9f3ea;'>
                <p>Bonjour <strong>$userName</strong>,</p>
                <p>Votre compte <strong>SPARKMIND</strong> a été <strong>temporairement suspendu</strong> par un administrateur.</p>
                <p>Cela peut être lié à une activité non conforme aux règles de la communauté ou à une vérification de sécurité.</p>
                <p>Si vous pensez qu'il s'agit d'une erreur, vous pouvez répondre à cet e-mail ou contacter l’équipe SPARKMIND.</p>
            </div>
            <div style='padding: 20px; text-align: center; color: #666; font-size: 12px; background:#FBEDD7;'>
                © 2024 SPARKMIND - Tous droits réservés
            </div>
        </div>";

        return self::send($toEmail, $subject, $body);
    }

    /**
     * Email de notification de compte débloqué / réactivé
     */
    public static function sendAccountUnblocked(string $toEmail, string $userName): bool
    {
        $subject = "Votre compte SPARKMIND a été réactivé";

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style=\"
                background: linear-gradient(135deg, #28a745, #1e7e34);
                padding: 30px;
                text-align: center;\">
                <h1 style='color: white; margin: 0;'>Compte réactivé 🎉</h1>
            </div>
            <div style='padding: 30px; background: #f9f3ea;'>
                <p>Bonjour <strong>$userName</strong>,</p>
                <p>Bonne nouvelle ! Votre compte <strong>SPARKMIND</strong> a été <strong>réactivé</strong>.</p>
                <p>Vous pouvez à nouveau vous connecter et utiliser tous les services de la plateforme.</p>
                <p style='text-align: center; margin-top: 30px;'>
                    <a href='http://localhost/sparkmind_mvc_100percent/index.php?page=login'
                       style=\"
                           background: #1A464F;
                           color: white;
                           padding: 12px 30px;
                           text-decoration: none;
                           border-radius: 5px;
                           display: inline-block;\">
                        Se connecter à SPARKMIND
                    </a>
                </p>
            </div>
            <div style='padding: 20px; text-align: center; color: #666; font-size: 12px; background:#FBEDD7;'>
                © 2024 SPARKMIND - Tous droits réservés
            </div>
        </div>";

        return self::send($toEmail, $subject, $body);
    }

    /**
     * Méthode principale d'envoi d'email (PHPMailer)
     */
    private static function send(string $toEmail, string $subject, string $body): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Config SMTP
            $mail->isSMTP();
            $mail->Host       = self::$smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = self::$smtpUsername;
            $mail->Password   = self::$smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Destinataires
            $mail->setFrom(self::$fromEmail, self::$fromName);
            $mail->addAddress($toEmail);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Erreur envoi email: " . $mail->ErrorInfo);
            return false;
        }
    }
}
